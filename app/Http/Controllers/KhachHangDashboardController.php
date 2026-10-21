<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\KhachHang;
use App\Models\HangThanhVien;
use App\Models\SoDoKhachHang;

class KhachHangDashboardController extends Controller
{
    /**
     * Lấy hoặc tạo hồ sơ khách hàng gắn liền với tài khoản đang đăng nhập
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
     * Dashboard tập trung: Thẻ VIP, Số đo, Lịch hẹn, Lịch sử mua
     */
    public function index()
    {
        $khachHang = $this->getKhachHangHienTai()->load([
            'hangThanhVien',
            'soDos' => fn($q) => $q->latest(),
            'lichHens' => fn($q) => $q->with(['sanPhamQuanTam', 'nhanVienTiepDon'])->latest(),
            'hoaDons' => fn($q) => $q->latest()->take(5),
        ]);

        $tienDo = $khachHang->tienDoThangHang();
        $cacHang = HangThanhVien::orderBy('diem_toi_thieu', 'asc')->get();

        return view('khachhang_dashboard', compact('khachHang', 'tienDo', 'cacHang'));
    }

    /**
     * Cập nhật thông tin cá nhân
     */
    public function capNhatThongTin(Request $request)
    {
        $khachHang = $this->getKhachHangHienTai();

        $request->validate([
            'ho_ten'        => 'required|string|max:100',
            'so_dien_thoai' => 'nullable|string|max:15',
            'dia_chi'       => 'nullable|string|max:255',
            'ngay_sinh'     => 'nullable|date',
            'gioi_tinh'     => 'nullable|in:Nam,Nu,Khac',
        ]);

        $khachHang->update($request->only([
            'ho_ten', 'so_dien_thoai', 'dia_chi', 'ngay_sinh', 'gioi_tinh'
        ]));

        return back()->with('success', 'Cập nhật thông tin cá nhân thành công!');
    }

    /**
     * Lưu hoặc tạo mới bộ số đo cơ thể
     */
    public function luuSoDo(Request $request)
    {
        $khachHang = $this->getKhachHangHienTai();

        $request->validate([
            'id'          => 'nullable|exists:so_do_khach_hangs,id',
            'ten_ho_so'   => 'required|string|max:50',
            'vong_co'     => 'nullable|numeric|min:20|max:60',
            'rong_vai'    => 'nullable|numeric|min:30|max:70',
            'vong_nguc'   => 'nullable|numeric|min:50|max:160',
            'vong_eo'     => 'nullable|numeric|min:40|max:150',
            'vong_mong'   => 'nullable|numeric|min:50|max:160',
            'dai_tay_ao'  => 'nullable|numeric|min:40|max:90',
            'dai_ao_vest' => 'nullable|numeric|min:50|max:110',
            'dai_quan'    => 'nullable|numeric|min:60|max:130',
            'vong_dui'    => 'nullable|numeric|min:30|max:90',
            'ghi_chu'     => 'nullable|string|max:500',
        ]);

        $data = $request->only([
            'ten_ho_so', 'vong_co', 'rong_vai', 'vong_nguc', 'vong_eo',
            'vong_mong', 'dai_tay_ao', 'dai_ao_vest', 'dai_quan', 'vong_dui', 'ghi_chu'
        ]);

        if ($request->filled('id')) {
            $soDo = SoDoKhachHang::where('khach_hang_id', $khachHang->id)->findOrFail($request->id);
            $soDo->update($data);
            $msg = 'Cập nhật bộ số đo may vest thành công!';
        } else {
            $data['khach_hang_id'] = $khachHang->id;
            SoDoKhachHang::create($data);
            $msg = 'Thêm bộ số đo may vest mới thành công!';
        }

        return redirect()->route('khachhang.dashboard', ['#sodo'])->with('success', $msg);
    }

    /**
     * Xóa một bộ số đo
     */
    public function xoaSoDo($id)
    {
        $khachHang = $this->getKhachHangHienTai();
        $soDo = SoDoKhachHang::where('khach_hang_id', $khachHang->id)->findOrFail($id);
        $soDo->delete();

        return redirect()->route('khachhang.dashboard', ['#sodo'])->with('success', 'Đã xóa bộ số đo.');
    }
}

