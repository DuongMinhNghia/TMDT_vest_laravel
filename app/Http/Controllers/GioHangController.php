<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\SanPham;

class GioHangController extends Controller
{
    private function sessionKey()
    {
        return 'gio_hang_' . Auth::id();
    }

    public function index()
    {
        $gioHang  = session()->get($this->sessionKey(), []);
        $tongTien = collect($gioHang)->sum(fn($item) => $item['gia'] * $item['so_luong']);
        if (session('thanhtoan')) {
            $gioHang  = session('gioHang_thanhtoan', []);
            $tongTien = session('tongTien_thanhtoan', 0);
        }

        return view('giohang', compact('gioHang', 'tongTien'));
    }

    public function addToCart(Request $request)
    {
        $id      = $request->id;
        $size    = $request->size;
        $sanPham = SanPham::findOrFail($id);

        $gioHang = session()->get($this->sessionKey(), []);
        $key     = $id . '_' . $size;

        if (isset($gioHang[$key])) {
            $gioHang[$key]['so_luong']++;
        } else {
            $gioHang[$key] = [
                'id'       => $sanPham->id,
                'ten_sp'   => $sanPham->ten_sp,
                'gia'      => $sanPham->gia,
                'hinh_anh' => $sanPham->hinh_anh,
                'size'     => $size,
                'so_luong' => 1,
            ];
        }

        session()->put($this->sessionKey(), $gioHang);

        $backUrl = $request->url ?: route('sanpham.index');
        return redirect($backUrl)->with('success', 'Đã thêm vào giỏ hàng!');
    }

    public function updateSL(Request $request)
    {
        $key     = $request->key;
        $type    = $request->type;
        $gioHang = session()->get($this->sessionKey(), []);

        if (isset($gioHang[$key])) {
            $gioHang[$key]['so_luong'] += $type;
            if ($gioHang[$key]['so_luong'] <= 0) {
                unset($gioHang[$key]);
            }
        }

        session()->put($this->sessionKey(), $gioHang);
        return redirect()->route('giohang.index');
    }

    public function remove(Request $request)
    {
        $key     = $request->key;
        $gioHang = session()->get($this->sessionKey(), []);
        unset($gioHang[$key]);
        session()->put($this->sessionKey(), $gioHang);
        return redirect()->route('giohang.index');
    }

    public function checkout(Request $request)
    {
        $gioHang = session()->get($this->sessionKey(), []);

        if (empty($gioHang)) {
            return redirect()->route('giohang.index')->with('error', 'Giỏ hàng trống!');
        }

        $tongTien = collect($gioHang)->sum(fn($item) => $item['gia'] * $item['so_luong']);

        // Tạo hóa đơn
        $hoaDon = \App\Models\HoaDon::create([
            'user_id'    => Auth::id(),
            'ngay_ban'   => now(),
            'trang_thai' => 'Chờ xử lý',
            'thanh_toan' => $tongTien,
            'dia_chi'    => $request->dia_chi ?? 'Chưa cập nhật',
        ]);

        // Lưu chi tiết từng sản phẩm
        foreach ($gioHang as $item) {
            \App\Models\ChiTietHoaDon::create([
                'hoa_don_id'  => $hoaDon->id,
                'san_pham_id' => $item['id'],
                'so_luong'    => $item['so_luong'],
                'don_gia'     => $item['gia'],
                'tong_tien'   => $item['gia'] * $item['so_luong'],
                'size'        => $item['size'] ?? '',  // ← thêm dòng này
            ]);
        }

        // Xóa giỏ hàng
        session()->forget($this->sessionKey());

        return redirect()->route('sanpham.index')->with('success', 'Đặt hàng thành công! Chờ xác nhận từ cửa hàng.');
    }
}
