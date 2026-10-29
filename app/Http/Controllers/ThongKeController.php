<?php

namespace App\Http\Controllers;

use App\Models\HoaDon;
use App\Models\KhachHang;
use App\Models\SanPham;
use App\Services\DonHangService;
use App\Services\PaymentStatusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * Task 3.3 - phía quản trị: thống kê, duyệt đơn, cập nhật trạng thái.
 * Đã chuyển sang schema mới (hoa_dons.tong_tien/ngay_dat/khach_hang_id) và dùng truy vấn chạy được trên cả MySQL lẫn SQLite.
 */
class ThongKeController extends Controller
{
    public function __construct(
        private DonHangService $donHangService,
        private PaymentStatusService $paymentStatusService,
    ) {}

    private function chiChoQuanTri(): void
    {
        abort_unless(in_array(Auth::user()->vai_tro, ['admin', 'nhan_vien'], true), 403, 'Bạn không có quyền truy cập.');
    }

    public function doanhThu(Request $request)
    {
        $this->chiChoQuanTri();

        $nam = (int) ($request->nam ?? date('Y'));

        $doanhThuThang = [];
        for ($i = 1; $i <= 12; $i++) {
            $doanhThuThang[$i] = HoaDon::where('trang_thai', HoaDon::HOAN_THANH)
                ->whereYear('ngay_dat', $nam)
                ->whereMonth('ngay_dat', $i)
                ->sum('tong_tien');
        }

        $tongDoanhThu    = HoaDon::where('trang_thai', HoaDon::HOAN_THANH)->sum('tong_tien');
        $tongDonHoan     = HoaDon::where('trang_thai', HoaDon::HOAN_THANH)->count();
        $tongDonDangGiao = HoaDon::where('trang_thai', HoaDon::DANG_GIAO)->count();
        $tongDonCho      = HoaDon::where('trang_thai', HoaDon::CHO_XU_LY)->count();
        $tongKhachHang   = KhachHang::count();
        $tongSanPham     = SanPham::count();

        $topSanPham = DB::table('chi_tiet_hoa_dons')
            ->join('san_phams', 'chi_tiet_hoa_dons.san_pham_id', '=', 'san_phams.id')
            ->join('hoa_dons', 'chi_tiet_hoa_dons.hoa_don_id', '=', 'hoa_dons.id')
            ->where('hoa_dons.trang_thai', HoaDon::HOAN_THANH)
            ->select(
                'san_phams.ten_sp',
                'san_phams.hinh_anh',
                DB::raw('SUM(chi_tiet_hoa_dons.so_luong) as tong_ban'),
                DB::raw('SUM(chi_tiet_hoa_dons.thanh_tien) as tong_tien')
            )
            ->groupBy('san_phams.id', 'san_phams.ten_sp', 'san_phams.hinh_anh')
            ->orderByDesc('tong_ban')
            ->limit(5)
            ->get();

        // Lấy danh sách năm bằng PHP để không phụ thuộc hàm SQL riêng của từng loại CSDL
        $danhSachNam = HoaDon::pluck('ngay_dat')
            ->map(fn($d) => \Carbon\Carbon::parse($d)->format('Y'))
            ->unique()
            ->sortDesc()
            ->values();

        if ($danhSachNam->isEmpty()) {
            $danhSachNam = collect([date('Y')]);
        }

        return view('thongke_doanhthu', compact(
            'doanhThuThang', 'tongDoanhThu', 'tongDonHoan',
            'tongDonDangGiao', 'tongDonCho', 'tongKhachHang',
            'tongSanPham', 'topSanPham', 'nam', 'danhSachNam'
        ));
    }

    public function donHang(Request $request)
    {
        $this->chiChoQuanTri();

        $trangThai = $request->trang_thai ?? '';
        $tuNgay    = $request->tu_ngay ?? '';
        $denNgay   = $request->den_ngay ?? '';

        $donHangs = HoaDon::with(['khachHang.taiKhoan', 'nhanVienDuyet', 'nguoiXacNhanThanhToan'])
            ->when($trangThai, fn($q) => $q->where('trang_thai', $trangThai))
            ->when($tuNgay, fn($q) => $q->whereDate('ngay_dat', '>=', $tuNgay))
            ->when($denNgay, fn($q) => $q->whereDate('ngay_dat', '<=', $denNgay))
            ->latest('ngay_dat')
            ->get();

        $tongHoanThanh = HoaDon::where('trang_thai', HoaDon::HOAN_THANH)->count();
        $tongDangGiao  = HoaDon::where('trang_thai', HoaDon::DANG_GIAO)->count();
        $tongChoXuLy   = HoaDon::where('trang_thai', HoaDon::CHO_XU_LY)->count();
        $luongTrangThai = HoaDon::LUONG_TRANG_THAI;

        return view('thongke_donhang', compact(
            'donHangs', 'trangThai', 'tuNgay', 'denNgay',
            'tongHoanThanh', 'tongDangGiao', 'tongChoXuLy', 'luongTrangThai'
        ));
    }

    public function capNhatTrangThai(Request $request, $id)
    {
        $this->chiChoQuanTri();

        $request->validate([
            'trang_thai' => 'required|in:' . implode(',', array_keys(HoaDon::LUONG_TRANG_THAI)),
        ]);

        try {
            $this->donHangService->capNhatTrangThai((int) $id, $request->trang_thai, Auth::user()->nhanVien);
        } catch (RuntimeException $e) {
            return redirect()->route('thongke.donhang')->with('error', $e->getMessage());
        }

        return redirect()->route('thongke.donhang')->with('success', 'Cập nhật trạng thái thành công!');
    }

    public function xacNhanThanhToan($id)
    {
        abort_unless(Auth::user()->isAdmin(), 403);
        try {
            $this->paymentStatusService->confirm((int) $id, Auth::user());
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Đã xác nhận thanh toán cho đơn hàng thành công.');
    }

    public function tuChoiThanhToan(Request $request, $id)
    {
        abort_unless(Auth::user()->isAdmin(), 403);
        $data = $request->validate(['ly_do' => ['nullable', 'string', 'max:500']]);
        try {
            $this->paymentStatusService->reject((int) $id, Auth::user(), $data['ly_do'] ?? null);
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Đã từ chối yêu cầu xác nhận thanh toán.');
    }
}
