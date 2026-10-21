<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LichHen;
use App\Models\SanPham;
use App\Models\NhanVien;
use App\Models\KhachHang;
use App\Models\HangThanhVien;
use Carbon\Carbon;

class LichHenController extends Controller
{
    /**
     * Lấy hoặc tạo hồ sơ khách hàng của người dùng hiện tại
     */
    protected function getKhachHangHienTai(): KhachHang
    {
        $user = Auth::user();
        $khachHang = $user->khachHang;

        if (!$khachHang) {
            $hangDong = HangThanhVien::orderBy('diem_toi_thieu', 'asc')->first();
            $khachHang = KhachHang::create([
                'tai_khoan_id'       => $user->id,
                'hang_thanh_vien_id' => $hangDong?->id ?? 1,
                'ma_kh'              => 'KH' . str_pad($user->id, 4, '0', STR_PAD_LEFT),
                'ho_ten'             => explode('@', $user->email)[0],
                'diem_tich_luy'      => 0,
            ]);
        }

        return $khachHang;
    }

    /**
     * Trang Đặt lịch hẹn dành cho Khách hàng
     */
    public function create(Request $request)
    {
        $khachHang = $this->getKhachHangHienTai();
        $sanPham = null;

        if ($request->filled('san_pham_id')) {
            $sanPham = SanPham::find($request->san_pham_id);
        }

        $danhSachSanPham = SanPham::select('id', 'ten_sp', 'ma_sp', 'hinh_anh', 'gia_ban')->get();

        return view('datlich', compact('khachHang', 'sanPham', 'danhSachSanPham'));
    }

    /**
     * Lưu lịch hẹn mới từ khách
     */
    public function store(Request $request)
    {
        $khachHang = $this->getKhachHangHienTai();

        $request->validate([
            'thoi_gian_hen'        => 'required|date|after:now',
            'loai_dich_vu'         => 'required|string|max:50',
            'so_nguoi_di_cung'     => 'required|integer|min:1|max:10',
            'san_pham_quan_tam_id' => 'nullable|exists:san_phams,id',
            'ghi_chu'              => 'nullable|string|max:1000',
            'so_dien_thoai'        => 'nullable|string|max:15',
        ], [
            'thoi_gian_hen.required' => 'Vui lòng chọn ngày và giờ hẹn.',
            'thoi_gian_hen.after'    => 'Thời gian hẹn phải sau thời điểm hiện tại.',
        ]);

        // Cập nhật SĐT khách hàng nếu chưa có
        if ($request->filled('so_dien_thoai') && !$khachHang->so_dien_thoai) {
            $khachHang->update(['so_dien_thoai' => $request->so_dien_thoai]);
        }

        $maLichHen = 'LH' . Carbon::now()->format('ymd') . rand(1000, 9999);

        $lichHen = LichHen::create([
            'khach_hang_id'        => $khachHang->id,
            'san_pham_quan_tam_id' => $request->san_pham_quan_tam_id ?: null,
            'ma_lich_hen'          => $maLichHen,
            'thoi_gian_hen'        => $request->thoi_gian_hen,
            'loai_dich_vu'         => $request->loai_dich_vu,
            'so_nguoi_di_cung'     => $request->so_nguoi_di_cung,
            'trang_thai'           => LichHen::CHO_XAC_NHAN,
            'ghi_chu'              => $request->ghi_chu,
        ]);

        return redirect()->route('khachhang.dashboard', ['#lichhen'])->with(
            'success',
            "Đặt lịch hẹn thành công! Mã lịch hẹn của bạn là: {$lichHen->ma_lich_hen}. Showroom Kingsman sẽ liên hệ xác nhận sớm nhất."
        );
    }

    /**
     * Khách hàng tự hủy lịch hẹn (khi còn 'Chờ xác nhận')
     */
    public function huy($id)
    {
        $khachHang = $this->getKhachHangHienTai();
        $lichHen = LichHen::where('khach_hang_id', $khachHang->id)->findOrFail($id);

        if ($lichHen->trang_thai !== LichHen::CHO_XAC_NHAN) {
            return back()->with('error', 'Chỉ có thể hủy những lịch hẹn đang ở trạng thái "Chờ xác nhận".');
        }

        $lichHen->update(['trang_thai' => LichHen::DA_HUY]);

        return back()->with('success', 'Đã hủy lịch hẹn thành công.');
    }

    /**
     * Trang Quản trị Lịch Hẹn dành cho Admin & Nhân viên
     */
    public function quanLy(Request $request)
    {
        $query = LichHen::with(['khachHang.taiKhoan', 'nhanVienTiepDon', 'sanPhamQuanTam']);

        // Bộ lọc trạng thái
        if ($request->filled('trang_thai')) {
            $query->where('trang_thai', $request->trang_thai);
        }

        // Bộ lọc ngày hẹn
        if ($request->filled('ngay')) {
            $query->whereDate('thoi_gian_hen', $request->ngay);
        }

        // Tìm kiếm theo tên khách, SĐT hoặc mã lịch hẹn
        if ($request->filled('tim_kiem')) {
            $search = $request->tim_kiem;
            $query->where(function ($q) use ($search) {
                $q->where('ma_lich_hen', 'like', "%{$search}%")
                  ->orWhereHas('khachHang', function ($kh) use ($search) {
                      $kh->where('ho_ten', 'like', "%{$search}%")
                         ->orWhere('so_dien_thoai', 'like', "%{$search}%");
                  });
            });
        }

        $lichHens = $query->orderBy('thoi_gian_hen', 'desc')->paginate(12);

        // Thống kê nhanh
        $thongKe = [
            'tong'         => LichHen::count(),
            'cho_xac_nhan' => LichHen::where('trang_thai', LichHen::CHO_XAC_NHAN)->count(),
            'da_xac_nhan'  => LichHen::where('trang_thai', LichHen::DA_XAC_NHAN)->count(),
            'dang_tiep_don'=> LichHen::where('trang_thai', LichHen::DANG_TIEP_DON)->count(),
            'hoan_thanh'   => LichHen::where('trang_thai', LichHen::HOAN_THANH)->count(),
            'da_huy'       => LichHen::where('trang_thai', LichHen::DA_HUY)->count(),
        ];

        $nhanViens = NhanVien::where('trang_thai', 'Đang làm việc')->get();

        return view('quanlylichhen', compact('lichHens', 'thongKe', 'nhanViens'));
    }

    /**
     * Admin / Nhân viên cập nhật trạng thái lịch hẹn và phân công nhân sự đón tiếp
     */
    public function capNhat(Request $request, $id)
    {
        $lichHen = LichHen::findOrFail($id);

        $request->validate([
            'trang_thai'            => 'required|in:' . implode(',', [
                LichHen::CHO_XAC_NHAN,
                LichHen::DA_XAC_NHAN,
                LichHen::DANG_TIEP_DON,
                LichHen::HOAN_THANH,
                LichHen::DA_HUY,
            ]),
            'nhan_vien_tiep_don_id' => 'nullable|exists:nhan_viens,id',
            'ghi_chu'               => 'nullable|string|max:1000',
        ]);

        $lichHen->update([
            'trang_thai'            => $request->trang_thai,
            'nhan_vien_tiep_don_id' => $request->nhan_vien_tiep_don_id ?: null,
            'ghi_chu'               => $request->ghi_chu ?? $lichHen->ghi_chu,
        ]);

        return redirect()->route('lichhen.quanly')->with('success', "Cập nhật lịch hẹn {$lichHen->ma_lich_hen} thành công!");
    }

    /**
     * Xóa lịch hẹn (Admin)
     */
    public function xoa($id)
    {
        $lichHen = LichHen::findOrFail($id);
        $ma = $lichHen->ma_lich_hen;
        $lichHen->delete();

        return redirect()->route('lichhen.quanly')->with('success', "Đã xóa lịch hẹn {$ma}.");
    }
}

