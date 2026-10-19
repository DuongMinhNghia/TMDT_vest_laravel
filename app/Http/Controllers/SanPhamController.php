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
        $sanPham = SanPham::with(['danhMuc', 'sizes'])->findOrFail($id);
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
        $sanPhams = SanPham::with(['danhMuc', 'sizes'])->latest()->get();
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
            'ma_sp'       => 'nullable|string|max:50|unique:san_phams,ma_sp',
            'gia'         => 'required|numeric|min:0',
            'danh_muc_id' => 'required|exists:danh_mucs,id',
            'form_dang'   => 'nullable|string|max:50',
            'xuat_xu'     => 'nullable|string|max:100',
            'chat_lieu'   => 'nullable|string|max:100',
            'mo_ta'       => 'nullable|string',
            'hinh_anh'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = [
            'ten_sp'      => $request->ten_sp,
            'ma_sp'       => $request->ma_sp ?: 'VEST-' . strtoupper(uniqid()),
            'gia_ban'     => $request->gia,
            'danh_muc_id' => $request->danh_muc_id,
            'form_dang'   => $request->form_dang ?? 'Slim-fit',
            'xuat_xu'     => $request->xuat_xu,
            'chat_lieu'   => $request->chat_lieu,
            'mo_ta'       => $request->mo_ta,
        ];

        if ($request->hasFile('hinh_anh')) {
            $file    = $request->file('hinh_anh');
            $tenFile = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $tenFile);
            $data['hinh_anh'] = $tenFile;
        }

        $sanPham = SanPham::create($data);

        // Lưu tồn kho theo từng Size (S, M, L, XL, XXL)
        $standardSizes = [
            'S'   => ['chieu_cao' => '1m60 - 1m68', 'can_nang' => '52kg - 60kg'],
            'M'   => ['chieu_cao' => '1m68 - 1m74', 'can_nang' => '60kg - 68kg'],
            'L'   => ['chieu_cao' => '1m74 - 1m78', 'can_nang' => '68kg - 75kg'],
            'XL'  => ['chieu_cao' => '1m78 - 1m83', 'can_nang' => '75kg - 82kg'],
            'XXL' => ['chieu_cao' => '1m80 - 1m88', 'can_nang' => '82kg - 90kg'],
        ];

        $inputSizes = $request->input('sizes', []);
        foreach ($standardSizes as $tenSize => $goiY) {
            $soLuong = isset($inputSizes[$tenSize]) ? (int)$inputSizes[$tenSize] : 0;
            \App\Models\Size::create([
                'san_pham_id'     => $sanPham->id,
                'ten_size'        => $tenSize,
                'so_luong_ton'    => max(0, $soLuong),
                'chieu_cao_goi_y' => $goiY['chieu_cao'],
                'can_nang_goi_y'  => $goiY['can_nang'],
            ]);
        }

        return redirect()->route('sanpham.danhsach')->with('success', 'Thêm sản phẩm và tạo size tồn kho thành công!');
    }

    // ---------------------------------------------------------------
    // ADMIN — Lưu sửa
    // ---------------------------------------------------------------

    public function luuSua(Request $request, $id)
    {
        $sanPham = SanPham::findOrFail($id);

        $request->validateWithBag('edit', [
            'ten_sp'      => 'required|string|max:255',
            'ma_sp'       => 'nullable|string|max:50|unique:san_phams,ma_sp,' . $sanPham->id,
            'gia'         => 'required|numeric|min:0',
            'danh_muc_id' => 'required|exists:danh_mucs,id',
            'form_dang'   => 'nullable|string|max:50',
            'xuat_xu'     => 'nullable|string|max:100',
            'chat_lieu'   => 'nullable|string|max:100',
            'mo_ta'       => 'nullable|string',
            'hinh_anh'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = [
            'ten_sp'      => $request->ten_sp,
            'ma_sp'       => $request->ma_sp ?: $sanPham->ma_sp,
            'gia_ban'     => $request->gia,
            'danh_muc_id' => $request->danh_muc_id,
            'form_dang'   => $request->form_dang ?? $sanPham->form_dang,
            'xuat_xu'     => $request->xuat_xu,
            'chat_lieu'   => $request->chat_lieu,
            'mo_ta'       => $request->mo_ta,
        ];

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

        // Cập nhật số lượng tồn kho của từng Size
        $inputSizes = $request->input('sizes', []);
        $standardSizes = [
            'S'   => ['chieu_cao' => '1m60 - 1m68', 'can_nang' => '52kg - 60kg'],
            'M'   => ['chieu_cao' => '1m68 - 1m74', 'can_nang' => '60kg - 68kg'],
            'L'   => ['chieu_cao' => '1m74 - 1m78', 'can_nang' => '68kg - 75kg'],
            'XL'  => ['chieu_cao' => '1m78 - 1m83', 'can_nang' => '75kg - 82kg'],
            'XXL' => ['chieu_cao' => '1m80 - 1m88', 'can_nang' => '82kg - 90kg'],
        ];

        foreach ($standardSizes as $tenSize => $goiY) {
            $soLuong = isset($inputSizes[$tenSize]) ? (int)$inputSizes[$tenSize] : 0;
            \App\Models\Size::updateOrCreate(
                ['san_pham_id' => $sanPham->id, 'ten_size' => $tenSize],
                [
                    'so_luong_ton'    => max(0, $soLuong),
                    'chieu_cao_goi_y' => $goiY['chieu_cao'],
                    'can_nang_goi_y'  => $goiY['can_nang'],
                ]
            );
        }

        return redirect()->route('sanpham.danhsach')->with('success', 'Cập nhật sản phẩm và tồn kho size thành công!');
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
