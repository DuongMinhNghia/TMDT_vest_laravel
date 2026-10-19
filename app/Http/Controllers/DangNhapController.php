<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Thư viện xử lý Đăng nhập của Laravel

class DangNhapController extends Controller
{
    public function index()
    {
        return view('dangnhap');
    }

    // Xử lý logic đăng nhập
    public function login(Request $request)
    {
        // 1. Kiểm tra dữ liệu nhập
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'password.required' => 'Vui lòng nhập mật khẩu.'
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect('/');
        }

        return back()->withErrors([
            'email' => 'Email hoặc mật khẩu không chính xác.',
        ])->onlyInput('email');
    }
}
