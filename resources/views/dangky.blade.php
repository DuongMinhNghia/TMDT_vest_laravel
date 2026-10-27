@extends('layouts.auth_layout')

@section('title', 'Đăng Ký Tài Khoản - Kingsman Vietnam')

@section('content')
<div class="auth-card">
    <div class="auth-card-header">
        <i class="bi bi-person-plus me-2"></i> ĐĂNG KÝ THÀNH VIÊN
    </div>
    <div class="auth-card-body">

        <form action="{{ url('/dang-ky') }}" method="POST" autocomplete="off">
            @csrf

            <div class="mb-3">
                <label class="form-label">Họ và Tên</label>
                <input type="text" name="name" class="form-control"
                    required value="{{ old('name') }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Địa Chỉ Email</label>
                <input type="email" name="email" class="form-control"
                    required autocomplete="off"
                    value="{{ old('email') }}">
            </div>

            <div class="mb-4">
                <label class="form-label">Mật Khẩu</label>
                <div class="password-field">
                    <input id="register-password" type="password" name="password" class="form-control"
                        required autocomplete="new-password">
                    <button type="button" class="password-toggle" data-password-toggle="register-password"
                        aria-label="Hiển thị mật khẩu" title="Hiển thị mật khẩu">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
                <div class="small mt-2" style="color: var(--auth-text-muted);">Ít nhất 8 ký tự, gồm chữ cái, chữ số và ký tự đặc biệt.</div>
            </div>

            <div class="mb-4">
                <label class="form-label">Nhập Lại Mật Khẩu</label>
                <div class="password-field">
                    <input id="register-password-confirmation" type="password" name="password_confirmation" class="form-control"
                        required autocomplete="new-password">
                    <button type="button" class="password-toggle" data-password-toggle="register-password-confirmation"
                        aria-label="Hiển thị mật khẩu" title="Hiển thị mật khẩu">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="btn-gold mb-4">
                <i class="bi bi-person-check me-2"></i> TẠO TÀI KHOẢN MỚI
            </button>

            <div class="text-center small" style="color: var(--auth-text-muted);">
                Đã có tài khoản?
                <a href="{{ url('/dang-nhap') }}" class="link-gold">Đăng nhập</a>
            </div>
        </form>

    </div>
</div>
@endsection
