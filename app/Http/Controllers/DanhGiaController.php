<?php

namespace App\Http\Controllers;

use App\Models\ChiTietHoaDon;
use App\Models\DanhGia;
use App\Models\HoaDon;
use App\Models\SanPham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Task 3.4 - Đánh giá sản phẩm: chỉ khách đã mua (đơn Hoàn Thành) mới được đánh giá, mỗi sản phẩm 1 đánh giá/khách.
 */
class DanhGiaController extends Controller
{
    public function luu(Request $request, $sanPhamId)
    {
        $sanPham   = SanPham::findOrFail($sanPhamId);
        $khachHang = Auth::user()->khachHang;

        if (!$khachHang) {
            return back()->with('error', 'Chỉ tài khoản khách hàng mới được đánh giá.');
        }

        $daMua = ChiTietHoaDon::where('san_pham_id', $sanPham->id)
            ->whereHas('hoaDon', fn($q) => $q
                ->where('khach_hang_id', $khachHang->id)
                ->where('trang_thai', HoaDon::HOAN_THANH))
            ->exists();

        if (!$daMua) {
            return back()->with('error', 'Bạn chỉ có thể đánh giá sản phẩm sau khi đơn hàng đã hoàn thành.');
        }

        $data = $request->validate([
            'so_sao'   => 'required|integer|between:1,5',
            'noi_dung' => 'nullable|string|max:1000',
            'hinh_anh' => 'nullable|image|max:2048',
        ], [
            'so_sao.required' => 'Vui lòng chọn số sao.',
            'hinh_anh.image'  => 'File tải lên phải là hình ảnh.',
            'hinh_anh.max'    => 'Ảnh tối đa 2MB.',
        ]);

        $danhGia = DanhGia::firstOrNew([
            'san_pham_id'   => $sanPham->id,
            'khach_hang_id' => $khachHang->id,
        ]);
        $danhGia->so_sao   = $data['so_sao'];
        $danhGia->noi_dung = $data['noi_dung'] ?? null;

        if ($request->hasFile('hinh_anh')) {
            $file = $request->file('hinh_anh');
            $ten  = 'dg_' . $khachHang->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images/danhgia'), $ten);
            $danhGia->hinh_anh = 'danhgia/' . $ten; // đường dẫn tính từ public/images/
        }

        $danhGia->save();

        return redirect()->route('sanpham.chitiet', $sanPham->id)->with('success', 'Cảm ơn bạn đã đánh giá sản phẩm!');
    }
}
