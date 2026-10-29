<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\KhachHang;
use App\Models\HangThanhVien;
use App\Models\SoDoKhachHang;
use App\Rules\StrongPassword;
use App\Services\VietnamAddressService;
use Illuminate\Support\Facades\Validator;
use RuntimeException;

class KhachHangDashboardController extends Controller
{
    /**
     * Lấy hoặc tạo hồ sơ khách hàng gắn liền với tài khoản đang đăng nhập
     */
    protected function getKhachHangHienTai(): KhachHang
    {
        $user = Auth::user();
        abort_unless($user?->isCustomer(), 403);
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
    public function index(VietnamAddressService $addresses)
    {
        $khachHang = $this->getKhachHangHienTai()->load([
            'hangThanhVien',
            'soDos' => fn($q) => $q->latest(),
            'lichHens' => fn($q) => $q->with(['sanPhamQuanTam', 'nhanVienTiepDon'])->latest(),
            'hoaDons' => fn($q) => $q->latest()->take(5),
        ]);

        $tienDo = $khachHang->tienDoThangHang();
        $cacHang = HangThanhVien::orderBy('diem_toi_thieu', 'asc')->get();
        try {
            $tinhThanhs = $addresses->provinces();
            $loiDuLieuDiaChi = null;
        } catch (RuntimeException $e) {
            $tinhThanhs = [];
            $loiDuLieuDiaChi = $e->getMessage();
        }

        return view('khachhang_dashboard', compact(
            'khachHang', 'tienDo', 'cacHang', 'tinhThanhs', 'loiDuLieuDiaChi'
        ));
    }

    /**
     * Cập nhật thông tin cá nhân
     */
    public function capNhatThongTin(Request $request, VietnamAddressService $addresses)
    {
        $khachHang = $this->getKhachHangHienTai();
        $doiMatKhau = $request->filled('current_password')
            || $request->filled('password')
            || $request->filled('password_confirmation');

        $request->merge([
            'ho_ten' => preg_replace('/\s+/u', ' ', trim((string) $request->ho_ten)),
            'so_dien_thoai' => preg_replace('/[\s\.\-\(\)]+/', '', trim((string) $request->so_dien_thoai)),
            'tinh_thanh_code' => trim((string) $request->tinh_thanh_code),
            'phuong_xa_code' => trim((string) $request->phuong_xa_code),
            'ten_duong' => preg_replace('/\s+/u', ' ', trim((string) $request->ten_duong)),
            'dia_chi_chi_tiet' => preg_replace('/\s+/u', ' ', trim((string) $request->dia_chi_chi_tiet)),
        ]);

        $validator = Validator::make($request->all(), [
            'ho_ten'        => 'required|string|max:100',
            'so_dien_thoai' => ['nullable', 'regex:/^0(?:3|5|7|8|9)\d{8}$/'],
            'tinh_thanh_code' => ['nullable', 'required_with:phuong_xa_code,ten_duong,dia_chi_chi_tiet', 'string', 'max:10'],
            'phuong_xa_code' => ['nullable', 'required_with:tinh_thanh_code,ten_duong,dia_chi_chi_tiet', 'string', 'max:10'],
            'ten_duong' => ['nullable', 'required_with:tinh_thanh_code,phuong_xa_code,dia_chi_chi_tiet', 'string', 'min:2', 'max:150'],
            'google_place_id' => ['nullable', 'string', 'max:255'],
            'dia_chi_chi_tiet' => ['nullable', 'required_with:tinh_thanh_code,phuong_xa_code,ten_duong', 'string', 'min:2', 'max:200'],
        ], [
            'so_dien_thoai.regex' => 'Số điện thoại không hợp lệ.',
            'tinh_thanh_code.required_with' => 'Vui lòng chọn Tỉnh/Thành phố.',
            'phuong_xa_code.required_with' => 'Vui lòng chọn Phường/Xã.',
            'ten_duong.required_with' => 'Vui lòng chọn hoặc nhập tên đường.',
            'dia_chi_chi_tiet.required_with' => 'Vui lòng nhập số nhà, tòa nhà, thôn hoặc ấp.',
        ]);

        $resolved = null;
        $validator->after(function ($validator) use ($request, $addresses, &$resolved) {
            if (!$request->filled('tinh_thanh_code') || !$request->filled('phuong_xa_code')) {
                return;
            }
            $resolved = $addresses->resolve($request->tinh_thanh_code, $request->phuong_xa_code);
            if (!$resolved) {
                $validator->errors()->add('phuong_xa_code', 'Phường/Xã không thuộc Tỉnh/Thành phố đã chọn.');
            }
        });
        $validator->validate();

        if ($doiMatKhau) {
            $request->validate([
                'current_password' => 'required|string',
                'password' => ['required', 'string', 'confirmed', new StrongPassword],
                'password_confirmation' => 'required|string',
            ]);

            if (!Hash::check($request->current_password, Auth::user()->password)) {
                return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không chính xác.'])
                    ->withInput($request->except(['current_password', 'password', 'password_confirmation']));
            }

            if (Hash::check($request->password, Auth::user()->password)) {
                return back()->withErrors(['password' => 'Mật khẩu mới phải khác mật khẩu hiện tại.'])
                    ->withInput($request->except(['current_password', 'password', 'password_confirmation']));
            }

            Auth::user()->update(['password' => $request->password]);
        }

        $profileData = $request->only([
            'ho_ten', 'so_dien_thoai', 'tinh_thanh_code', 'phuong_xa_code',
            'ten_duong', 'google_place_id', 'dia_chi_chi_tiet',
        ]);
        if ($resolved) {
            $profileData['dia_chi'] = implode(', ', [
                $request->dia_chi_chi_tiet,
                $request->ten_duong,
                $resolved['ward']['name'],
                $resolved['province']['name'],
            ]);
        } elseif (!$request->filled('tinh_thanh_code')) {
            unset(
                $profileData['tinh_thanh_code'],
                $profileData['phuong_xa_code'],
                $profileData['ten_duong'],
                $profileData['google_place_id'],
                $profileData['dia_chi_chi_tiet'],
            );
        }
        $khachHang->update($profileData);

        return back()->with('success', $doiMatKhau
            ? 'Cập nhật thông tin và mật khẩu thành công!'
            : 'Cập nhật thông tin cá nhân thành công!');
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

