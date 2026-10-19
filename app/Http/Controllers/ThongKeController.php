<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HoaDon;
use App\Models\User;
use App\Models\SanPham;
use Illuminate\Support\Facades\DB;
class ThongKeController extends Controller
{
    public function doanhThu(Request $request)
    {
        $nam = $request->nam ?? date('Y');

        $doanhThuThang = [];
        for ($i = 1; $i <= 12; $i++) {
            $thangFormat = str_pad($i, 2, '0', STR_PAD_LEFT); // "01", "02",...
            $doanhThuThang[$i] = HoaDon::where('trang_thai', 'Hoàn Thành')
                ->whereRaw('strftime("%Y", ngay_ban) = ?', [(string)$nam])
                ->whereRaw('strftime("%m", ngay_ban) = ?', [$thangFormat])
                ->sum('thanh_toan');
        }

        $tongDoanhThu    = HoaDon::where('trang_thai', 'Hoàn Thành')->sum('thanh_toan');
        $tongDonHoan     = HoaDon::where('trang_thai', 'Hoàn Thành')->count();
        $tongDonDangGiao = HoaDon::where('trang_thai', 'Đang giao')->count();
        $tongDonCho      = HoaDon::where('trang_thai', 'Chờ xử lý')->count();
        $tongKhachHang   = User::where('quyen', 'Khách hàng')->count();
        $tongSanPham     = SanPham::count();

        $topSanPham = \DB::table('chi_tiet_hoa_dons')
            ->join('san_phams', 'chi_tiet_hoa_dons.san_pham_id', '=', 'san_phams.id')
            ->join('hoa_dons', 'chi_tiet_hoa_dons.hoa_don_id', '=', 'hoa_dons.id')
            ->where('hoa_dons.trang_thai', 'Hoàn Thành')
            ->select('san_phams.ten_sp', 'san_phams.hinh_anh',
                     \DB::raw('SUM(chi_tiet_hoa_dons.so_luong) as tong_ban'),
                     \DB::raw('SUM(chi_tiet_hoa_dons.tong_tien) as tong_tien'))
            ->groupBy('san_phams.id', 'san_phams.ten_sp', 'san_phams.hinh_anh')
            ->orderByDesc('tong_ban')
            ->limit(5)
            ->get();

        $danhSachNam = HoaDon::selectRaw('strftime("%Y", ngay_ban) as nam')
        ->groupByRaw('strftime("%Y", ngay_ban)')
        ->orderByDesc('nam')
        ->pluck('nam');

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
        $trangThai = $request->trang_thai ?? '';
        $tuNgay    = $request->tu_ngay ?? '';
        $denNgay   = $request->den_ngay ?? '';

        $donHangs = HoaDon::with('user')
            ->when($trangThai, fn($q) => $q->where('trang_thai', $trangThai))
            ->when($tuNgay, fn($q) => $q->whereDate('ngay_ban', '>=', $tuNgay))
            ->when($denNgay, fn($q) => $q->whereDate('ngay_ban', '<=', $denNgay))
            ->latest('ngay_ban')
            ->get();

        $tongHoanThanh = HoaDon::where('trang_thai', 'Hoàn Thành')->count();
        $tongDangGiao  = HoaDon::where('trang_thai', 'Đang giao')->count();
        $tongChoXuLy   = HoaDon::where('trang_thai', 'Chờ xử lý')->count();

        return view('thongke_donhang', compact(
            'donHangs', 'trangThai', 'tuNgay', 'denNgay',
            'tongHoanThanh', 'tongDangGiao', 'tongChoXuLy'
        ));
    }

    public function capNhatTrangThai(Request $request, $id)
    {
        $donHang = HoaDon::findOrFail($id);
        $request->validate([
            'trang_thai' => 'required|in:Chờ xử lý,Đang giao,Hoàn Thành,Đã hủy',
        ]);
        $donHang->update(['trang_thai' => $request->trang_thai]);
        return redirect()->route('thongke.donhang')->with('success', 'Cập nhật trạng thái thành công!');
    }
}