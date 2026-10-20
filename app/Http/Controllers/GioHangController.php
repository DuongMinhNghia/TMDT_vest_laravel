<?php

namespace App\Http\Controllers;

use App\Models\ChiTietGioHang;
use App\Models\GioHang;
use App\Models\SanPham;
use App\Models\Size;
use App\Services\DonHangService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

/**
 * Task 3.1 (giỏ hàng lưu Database) + Task 3.2 (checkout, trừ kho, chiết khấu hạng)
 */
class GioHangController extends Controller
{
    public function __construct(private DonHangService $donHangService)
    {
    }

    private function khachHang()
    {
        return Auth::user()->khachHang; // null nếu là admin/nhân viên
    }

    private function gioHangCuaToi(): GioHang
    {
        return GioHang::firstOrCreate(['khach_hang_id' => $this->khachHang()->id]);
    }

    private function chanNeuKhongPhaiKhach()
    {
        if (!$this->khachHang()) {
            return redirect()->route('sanpham.index')
                ->with('error', 'Chỉ tài khoản khách hàng mới sử dụng được giỏ hàng.');
        }
        return null;
    }

    public function index()
    {
        if ($chan = $this->chanNeuKhongPhaiKhach()) {
            return $chan;
        }

        $khachHang = $this->khachHang();
        $chiTiets  = $this->gioHangCuaToi()->chiTiets()->with(['sanPham', 'size'])->get();

        $gioHang = [];
        foreach ($chiTiets as $ct) {
            $gioHang[$ct->id] = [
                'id'       => $ct->san_pham_id,
                'ten_sp'   => $ct->sanPham->ten_sp,
                'gia'      => $ct->sanPham->gia_khuyen_mai,
                'gia_goc'  => $ct->sanPham->gia_ban,
                'sale'     => $ct->sanPham->phan_tram_giam,
                'hinh_anh' => $ct->sanPham->hinh_anh,
                'size'     => $ct->size->ten_size,
                'so_luong' => $ct->so_luong,
                'ton'      => $ct->size->so_luong_ton,
            ];
        }

        $tien           = $this->donHangService->tinhTien($chiTiets, $khachHang);
        $tongTien       = $tien['tam_tinh'];
        $diaChiMacDinh  = $khachHang->dia_chi;
        $tenHang        = optional($khachHang->hangThanhVien)->ten_hang;

        return view('giohang', compact('gioHang', 'tongTien', 'tien', 'diaChiMacDinh', 'tenHang'));
    }

    public function addToCart(Request $request)
    {
        if ($chan = $this->chanNeuKhongPhaiKhach()) {
            return $chan;
        }

        $sanPham = SanPham::findOrFail($request->id);
        $size    = Size::where('san_pham_id', $sanPham->id)->where('ten_size', $request->size)->first();

        if (!$size) {
            return back()->with('error', 'Size "' . $request->size . '" chưa hỗ trợ đặt trực tiếp. Với size may đo, vui lòng liên hệ cửa hàng.');
        }

        $chiTiet  = ChiTietGioHang::firstOrNew([
            'gio_hang_id' => $this->gioHangCuaToi()->id,
            'san_pham_id' => $sanPham->id,
            'size_id'     => $size->id,
        ]);
        $soLuongMoi = ((int) $chiTiet->so_luong) + 1;

        // TV1: kiểm tra tồn kho trước khi cho vào giỏ
        if (!$sanPham->kiemTraTonKhoSize($size->ten_size, $soLuongMoi)) {
            return back()->with('error', 'Size ' . $size->ten_size . ' hiện không đủ số lượng trong kho!');
        }

        $chiTiet->so_luong = $soLuongMoi;
        $chiTiet->save();

        return redirect($this->diaChiQuayLai($request))->with('success', 'Đã thêm vào giỏ hàng!');
    }

    public function updateSL(Request $request)
    {
        if ($chan = $this->chanNeuKhongPhaiKhach()) {
            return $chan;
        }

        $chiTiet = ChiTietGioHang::where('gio_hang_id', $this->gioHangCuaToi()->id)
            ->with(['sanPham', 'size'])
            ->findOrFail($request->key);

        $soLuongMoi = $chiTiet->so_luong + (int) $request->type;

        if ($soLuongMoi <= 0) {
            $chiTiet->delete();
        } elseif (!$chiTiet->sanPham->kiemTraTonKhoSize($chiTiet->size->ten_size, $soLuongMoi)) {
            return redirect()->route('giohang.index')
                ->with('error', 'Size ' . $chiTiet->size->ten_size . ' chỉ còn ' . $chiTiet->size->so_luong_ton . ' sản phẩm!');
        } else {
            $chiTiet->update(['so_luong' => $soLuongMoi]);
        }

        return redirect()->route('giohang.index');
    }

    public function remove(Request $request)
    {
        if ($chan = $this->chanNeuKhongPhaiKhach()) {
            return $chan;
        }

        ChiTietGioHang::where('gio_hang_id', $this->gioHangCuaToi()->id)
            ->where('id', $request->key)
            ->delete();

        return redirect()->route('giohang.index');
    }

    public function checkout(Request $request)
    {
        if ($chan = $this->chanNeuKhongPhaiKhach()) {
            return $chan;
        }

        $data = $request->validate([
            'dia_chi'     => 'required|string|max:500',
            'phuong_thuc' => 'required|in:COD,Chuyển khoản',
            'ghi_chu'     => 'nullable|string|max:500',
        ], [
            'dia_chi.required' => 'Vui lòng nhập địa chỉ giao hàng.',
        ]);

        try {
            $hoaDon = $this->donHangService->taoDonTuGioHang($this->khachHang(), $this->gioHangCuaToi(), $data);
        } catch (RuntimeException $e) {
            return redirect()->route('giohang.index')->with('error', $e->getMessage());
        }

        return redirect()->route('donhang.chitiet', $hoaDon->id)
            ->with('success', 'Đặt hàng thành công! Mã đơn: ' . $hoaDon->ma_don_hang . '. Chờ xác nhận từ cửa hàng.');
    }

    /** Chỉ cho quay lại trang cùng domain, tránh open-redirect */
    private function diaChiQuayLai(Request $request): string
    {
        $url = $request->url;
        if ($url && parse_url($url, PHP_URL_HOST) === $request->getHost()) {
            return $url;
        }
        return route('sanpham.index');
    }
}
