@extends('layouts.auth_layout')

@section('title', 'Đăng Nhập Tài Khoản - Kingsman Vietnam')

@section('content')
<div class="auth-card">
    <div class="auth-card-header">
        <i class="bi bi-person-circle me-2"></i> ĐĂNG NHẬP HỆ THỐNG
    </div>
    <div class="auth-card-body">

        <form action="{{ url('/dang-nhap') }}" method="POST" autocomplete="on">
            @csrf

            <div class="mb-3">
                <label class="form-label">Email Đăng Nhập</label>
                <input type="email" name="email" class="form-control"
                    required autocomplete="email"
                    value="{{ old('email') }}"
                    placeholder="name@example.com">
            </div>

            <div class="mb-4">
                <label class="form-label">Mật Khẩu</label>
                <div class="password-field">
                    <input id="login-password" type="password" name="password" class="form-control"
                        required autocomplete="current-password"
                        placeholder="••••••••">
                    <button type="button" class="password-toggle" data-password-toggle="login-password"
                        aria-label="Hiển thị mật khẩu" title="Hiển thị mật khẩu">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-gold mb-4">
                <i class="bi bi-box-arrow-in-right me-2"></i> ĐĂNG NHẬP
            </button>

            <div class="mb-3" style="display: flex; justify-content: flex-end;">
                <a href="{{ route('matkhau.quen.form') }}" class="link-gold small">Quên mật khẩu?</a>
            </div>

            <div class="text-center small" style="color: var(--auth-text-muted);">
                Chưa có tài khoản Kingsman?
                <a href="{{ url('/dang-ky') }}" class="link-gold">Đăng ký ngay</a>
            </div>
        </form>

    </div>
</div>
@endsection
