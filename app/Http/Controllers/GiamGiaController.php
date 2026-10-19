<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GiamGia;
use App\Models\SanPham;

class GiamGiaController extends Controller
{
    /**
     * Danh sách tất cả các chiến dịch giảm giá (Admin)
     */
    public function danhSach()
    {
        $giamGias = GiamGia::with(['sanPhams'])->withCount('sanPhams')->latest('id')->get();
        $sanPhams = SanPham::with('danhMuc')->orderBy('ten_sp')->get();

        // Thống kê nhanh
        $now = now();
        $thongKe = [
            'tong'        => $giamGias->count(),
            'dang_chay'   => $giamGias->filter(fn($g) => $g->trang_thai && $g->thoi_gian_bat_dau <= $now && $g->thoi_gian_ket_thuc >= $now)->count(),
            'sap_dien_ra' => $giamGias->filter(fn($g) => $g->trang_thai && $g->thoi_gian_bat_dau > $now)->count(),
            'ket_thuc'    => $giamGias->filter(fn($g) => !$g->trang_thai || $g->thoi_gian_ket_thuc < $now)->count(),
        ];

        return view('quanlygiamgia', compact('giamGias', 'sanPhams', 'thongKe'));
    }

    /**
     * Lưu thêm mới Chiến dịch giảm giá
     */
    public function luuThem(Request $request)
    {
        $request->validate([
            'tieu_de'            => 'required|string|max:100',
            'phan_tram_giam'     => 'required|integer|min:1|max:99',
            'thoi_gian_bat_dau'  => 'required|date',
            'thoi_gian_ket_thuc' => 'required|date|after_or_equal:thoi_gian_bat_dau',
            'san_pham_ids'       => 'nullable|array',
            'san_pham_ids.*'     => 'exists:san_phams,id',
        ], [
            'tieu_de.required'                  => 'Vui lòng nhập tên chương trình giảm giá.',
            'tieu_de.max'                       => 'Tên chương trình không vượt quá 100 ký tự.',
            'phan_tram_giam.required'           => 'Vui lòng nhập mức giảm giá (%).',
            'phan_tram_giam.min'                => 'Mức giảm giá tối thiểu là 1%.',
            'phan_tram_giam.max'                => 'Mức giảm giá tối đa là 99%.',
            'thoi_gian_bat_dau.required'        => 'Vui lòng chọn thời gian bắt đầu.',
            'thoi_gian_ket_thuc.required'       => 'Vui lòng chọn thời gian kết thúc.',
            'thoi_gian_ket_thuc.after_or_equal' => 'Thời gian kết thúc phải sau hoặc bằng thời gian bắt đầu.',
        ]);

        $apDungTatCa = $request->input('pham_vi') === 'all';

        $giamGia = GiamGia::create([
            'tieu_de'            => $request->tieu_de,
            'phan_tram_giam'     => (int)$request->phan_tram_giam,
            'ap_dung_tat_ca'     => $apDungTatCa,
            'thoi_gian_bat_dau'  => $request->thoi_gian_bat_dau,
            'thoi_gian_ket_thuc' => $request->thoi_gian_ket_thuc,
            'trang_thai'         => true,
        ]);

        // Nếu không áp dụng toàn shop thì gắn các sản phẩm được chọn
        if (!$apDungTatCa && $request->filled('san_pham_ids')) {
            $giamGia->sanPhams()->sync($request->san_pham_ids);
        }

        return redirect()->route('giamgia.danhsach')->with('success', 'Tạo chiến dịch giảm giá "' . $giamGia->tieu_de . '" thành công!');
    }

    /**
     * Cập nhật thông tin Chiến dịch giảm giá
     */
    public function luuSua(Request $request, $id)
    {
        $giamGia = GiamGia::findOrFail($id);

        $request->validate([
            'tieu_de'            => 'required|string|max:100',
            'phan_tram_giam'     => 'required|integer|min:1|max:99',
            'thoi_gian_bat_dau'  => 'required|date',
            'thoi_gian_ket_thuc' => 'required|date|after_or_equal:thoi_gian_bat_dau',
            'san_pham_ids'       => 'nullable|array',
            'san_pham_ids.*'     => 'exists:san_phams,id',
        ], [
            'tieu_de.required'                  => 'Vui lòng nhập tên chương trình giảm giá.',
            'tieu_de.max'                       => 'Tên chương trình không vượt quá 100 ký tự.',
            'phan_tram_giam.required'           => 'Vui lòng nhập mức giảm giá (%).',
            'phan_tram_giam.min'                => 'Mức giảm giá tối thiểu là 1%.',
            'phan_tram_giam.max'                => 'Mức giảm giá tối đa là 99%.',
            'thoi_gian_bat_dau.required'        => 'Vui lòng chọn thời gian bắt đầu.',
            'thoi_gian_ket_thuc.required'       => 'Vui lòng chọn thời gian kết thúc.',
            'thoi_gian_ket_thuc.after_or_equal' => 'Thời gian kết thúc phải sau hoặc bằng thời gian bắt đầu.',
        ]);

        $apDungTatCa = $request->input('pham_vi') === 'all';

        $giamGia->update([
            'tieu_de'            => $request->tieu_de,
            'phan_tram_giam'     => (int)$request->phan_tram_giam,
            'ap_dung_tat_ca'     => $apDungTatCa,
            'thoi_gian_bat_dau'  => $request->thoi_gian_bat_dau,
            'thoi_gian_ket_thuc' => $request->thoi_gian_ket_thuc,
        ]);

        if ($apDungTatCa) {
            // Nếu chuyển sang toàn shop thì gỡ danh sách sản phẩm lẻ
            $giamGia->sanPhams()->detach();
        } else {
            $giamGia->sanPhams()->sync($request->input('san_pham_ids', []));
        }

        return redirect()->route('giamgia.danhsach')->with('success', 'Cập nhật chiến dịch "' . $giamGia->tieu_de . '" thành công!');
    }

    /**
     * Bật / Tắt nhanh trạng thái chiến dịch
     */
    public function doiTrangThai($id)
    {
        $giamGia = GiamGia::findOrFail($id);
        $giamGia->trang_thai = !$giamGia->trang_thai;
        $giamGia->save();

        $msg = $giamGia->trang_thai ? 'Đã kích hoạt chiến dịch' : 'Đã tạm dừng chiến dịch';
        return redirect()->route('giamgia.danhsach')->with('success', $msg . ' "' . $giamGia->tieu_de . '"!');
    }

    /**
     * Xóa Chiến dịch giảm giá
     */
    public function xoa($id)
    {
        $giamGia = GiamGia::findOrFail($id);
        $tieuDe = $giamGia->tieu_de;

        $giamGia->sanPhams()->detach();
        $giamGia->delete();

        return redirect()->route('giamgia.danhsach')->with('success', 'Đã xóa chiến dịch giảm giá "' . $tieuDe . '"!');
    }
}
