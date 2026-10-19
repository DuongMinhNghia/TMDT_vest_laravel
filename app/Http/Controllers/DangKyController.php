<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaiKhoan;
use Illuminate\Support\Facades\Hash; // Thư viện mã hóa mật khẩu

class DangKyController extends Controller
{
    public function index()
    {
        return view('dangky');
    }

    public function register(Request $request)
    {
        // 1. Kiểm tra dữ liệu đầu vào (Validation)
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:tai_khoans,email',
            'password' => 'required|min:6'
        ], [
            'email.unique' => 'Email này đã được sử dụng!',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.'
        ]);

        // 2. Lưu vào database
        $taiKhoan = TaiKhoan::create([
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'vai_tro' => 'khach_hang'
        ]);

        // Tạo hồ sơ khách hàng tương ứng
        \App\Models\KhachHang::create([
            'tai_khoan_id' => $taiKhoan->id,
            'ho_ten' => $request->name,
            'so_dien_thoai' => $request->phone ?? null,
            'dia_chi' => $request->address ?? null,
        ]);

        return redirect('/dang-nhap')->with('success', 'Đăng ký thành công! Vui lòng đăng nhập.');
    }
}
