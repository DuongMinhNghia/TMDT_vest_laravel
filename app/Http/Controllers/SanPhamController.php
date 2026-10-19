<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SanPham;
use App\Models\DanhMuc;

class SanPhamController extends Controller
{
    // ---------------------------------------------------------------
    // FRONTEND
    // ---------------------------------------------------------------

    public function index(Request $request)
    {
        $danhMucs = DanhMuc::all();

        $sanPhams = SanPham::with('danhMuc')
            ->when($request->danh_muc, function ($query) use ($request) {
                $query->where('danh_muc_id', $request->danh_muc);
            })
            ->when($request->tim_kiem, function ($query) use ($request) {
                $query->where('ten_sp', 'like', '%' . $request->tim_kiem . '%')
                    ->orWhere('xuat_xu', 'like', '%' . $request->tim_kiem . '%')
                    ->orWhere('chat_lieu', 'like', '%' . $request->tim_kiem . '%');
            })
            ->when($request->gia_min, function ($query) use ($request) {
                $query->where('gia', '>=', $request->gia_min);
            })
            ->when($request->gia_max, function ($query) use ($request) {
                $query->where('gia', '<=', $request->gia_max);
            })
            ->paginate(8);

        return view('sanpham', compact('sanPhams', 'danhMucs'));
    }

    public function chiTiet($id)
    {
        $sanPham = SanPham::with('danhMuc')->findOrFail($id);
        $sanPhamLienQuan = SanPham::where('danh_muc_id', $sanPham->danh_muc_id)
            ->where('id', '!=', $id)
            ->limit(8)
            ->get();

        return view('chitietsanpham', compact('sanPham', 'sanPhamLienQuan'));
    }

    // ---------------------------------------------------------------
    // ADMIN — Danh sách (view duy nhất, chứa cả 3 modal)
    // ---------------------------------------------------------------

    public function danhSach()
    {
        $sanPhams = SanPham::with('danhMuc')->latest()->get();
        $danhMucs = DanhMuc::all();
        return view('quanlysanpham', compact('sanPhams', 'danhMucs'));
    }

    // ---------------------------------------------------------------
    // ADMIN — Lưu thêm mới
    // ---------------------------------------------------------------

    public function luuThem(Request $request)
    {
        $request->validateWithBag('add', [
            'ten_sp'      => 'required|string|max:255',
            'gia'         => 'required|numeric|min:0',
            'danh_muc_id' => 'required|exists:danh_mucs,id',
            'xuat_xu'     => 'nullable|string|max:100',
            'chat_lieu'   => 'nullable|string|max:100',
            'mo_ta'       => 'nullable|string',
            'hinh_anh'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only(['ten_sp', 'xuat_xu', 'chat_lieu', 'gia', 'mo_ta', 'danh_muc_id']);

        if ($request->hasFile('hinh_anh')) {
            $file    = $request->file('hinh_anh');
            $tenFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $tenFile);
            $data['hinh_anh'] = $tenFile;
        }

        SanPham::create($data);

        return redirect()->route('sanpham.danhsach')->with('success', 'Thêm sản phẩm thành công!');
    }

    // ---------------------------------------------------------------
    // ADMIN — Lưu sửa
    // ---------------------------------------------------------------

    public function luuSua(Request $request, $id)
    {
        $sanPham = SanPham::findOrFail($id);

        $request->validateWithBag('edit', [
            'ten_sp'      => 'required|string|max:255',
            'gia'         => 'required|numeric|min:0',
            'danh_muc_id' => 'required|exists:danh_mucs,id',
            'xuat_xu'     => 'nullable|string|max:100',
            'chat_lieu'   => 'nullable|string|max:100',
            'mo_ta'       => 'nullable|string',
            'hinh_anh'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = $request->only(['ten_sp', 'xuat_xu', 'chat_lieu', 'gia', 'mo_ta', 'danh_muc_id']);

        if ($request->hasFile('hinh_anh')) {
            // Xóa ảnh cũ nếu tồn tại
            if ($sanPham->hinh_anh && file_exists(public_path('images/' . $sanPham->hinh_anh))) {
                unlink(public_path('images/' . $sanPham->hinh_anh));
            }
            $file    = $request->file('hinh_anh');
            $tenFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $tenFile);
            $data['hinh_anh'] = $tenFile;
        }

        $sanPham->update($data);

        return redirect()->route('sanpham.danhsach')->with('success', 'Cập nhật sản phẩm thành công!');
    }

    public function xoa($id)
    {
        $sanPham = SanPham::findOrFail($id);

        if ($sanPham->hinh_anh && file_exists(public_path('images/' . $sanPham->hinh_anh))) {
            unlink(public_path('images/' . $sanPham->hinh_anh));
        }

        $sanPham->delete();

        return redirect()->route('sanpham.danhsach')->with('success', 'Xóa sản phẩm thành công!');
    }
}
