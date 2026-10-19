<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BoSuuTap;
use App\Models\SanPham;

class BoSuuTapController extends Controller
{
    /**
     * Danh sách tất cả bộ sưu tập (Admin)
     */
    public function danhSach()
    {
        $boSuuTaps = BoSuuTap::with(['sanPhams'])->withCount('sanPhams')->latest()->get();
        $sanPhams  = SanPham::with('danhMuc')->orderBy('ten_sp')->get();

        return view('quanlybosuutap', compact('boSuuTaps', 'sanPhams'));
    }

    /**
     * Lưu thêm mới Bộ sưu tập
     */
    public function luuThem(Request $request)
    {
        $request->validate([
            'ten_bo_suu_tap' => 'required|string|max:100',
            'mo_ta'          => 'nullable|string',
            'hinh_banner'    => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'san_pham_ids'   => 'nullable|array',
            'san_pham_ids.*' => 'exists:san_phams,id',
        ], [
            'ten_bo_suu_tap.required' => 'Vui lòng nhập tên bộ sưu tập.',
            'ten_bo_suu_tap.max'      => 'Tên bộ sưu tập không quá 100 ký tự.',
            'hinh_banner.image'       => 'File tải lên phải là hình ảnh.',
            'hinh_banner.max'         => 'Ảnh banner dung lượng tối đa 5MB.',
        ]);

        $data = [
            'ten_bo_suu_tap' => $request->ten_bo_suu_tap,
            'mo_ta'          => $request->mo_ta,
        ];

        // Xử lý upload ảnh banner
        if ($request->hasFile('hinh_banner')) {
            $file = $request->file('hinh_banner');
            $tenFile = 'bst_' . time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $tenFile);
            $data['hinh_banner'] = $tenFile;
        }

        $boSuuTap = BoSuuTap::create($data);

        // Gán sản phẩm vào BST nếu có chọn
        if ($request->filled('san_pham_ids')) {
            $boSuuTap->sanPhams()->sync($request->san_pham_ids);
        }

        return redirect()->route('bosuutap.danhsach')->with('success', 'Thêm bộ sưu tập thành công!');
    }

    /**
     * Cập nhật thông tin Bộ sưu tập & danh sách sản phẩm đính kèm
     */
    public function luuSua(Request $request, $id)
    {
        $boSuuTap = BoSuuTap::findOrFail($id);

        $request->validate([
            'ten_bo_suu_tap' => 'required|string|max:100',
            'mo_ta'          => 'nullable|string',
            'hinh_banner'    => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
            'san_pham_ids'   => 'nullable|array',
            'san_pham_ids.*' => 'exists:san_phams,id',
        ], [
            'ten_bo_suu_tap.required' => 'Vui lòng nhập tên bộ sưu tập.',
            'ten_bo_suu_tap.max'      => 'Tên bộ sưu tập không quá 100 ký tự.',
            'hinh_banner.image'       => 'File tải lên phải là hình ảnh.',
            'hinh_banner.max'         => 'Ảnh banner dung lượng tối đa 5MB.',
        ]);

        $data = [
            'ten_bo_suu_tap' => $request->ten_bo_suu_tap,
            'mo_ta'          => $request->mo_ta,
        ];

        if ($request->hasFile('hinh_banner')) {
            $file = $request->file('hinh_banner');
            $tenFile = 'bst_' . time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('images'), $tenFile);
            $data['hinh_banner'] = $tenFile;
        }

        $boSuuTap->update($data);

        // Đồng bộ sản phẩm thuộc BST
        $sanPhamIds = $request->input('san_pham_ids', []);
        $boSuuTap->sanPhams()->sync($sanPhamIds);

        return redirect()->route('bosuutap.danhsach')->with('success', 'Cập nhật bộ sưu tập "' . $boSuuTap->ten_bo_suu_tap . '" thành công!');
    }

    /**
     * Xóa Bộ sưu tập
     */
    public function xoa($id)
    {
        $boSuuTap = BoSuuTap::findOrFail($id);
        $tenBst = $boSuuTap->ten_bo_suu_tap;

        // Xóa liên kết trong bảng pivot
        $boSuuTap->sanPhams()->detach();
        $boSuuTap->delete();

        return redirect()->route('bosuutap.danhsach')->with('success', 'Đã xóa bộ sưu tập "' . $tenBst . '"!');
    }

    /**
     * Xem chi tiết Bộ sưu tập ngoài Frontend (Khách hàng)
     */
    public function chiTiet($id)
    {
        $boSuuTap = BoSuuTap::with(['sanPhams' => function ($q) {
            $q->with(['sizes', 'danhMuc']);
        }])->findOrFail($id);

        return view('bosuutap_chitiet', compact('boSuuTap'));
    }
}
