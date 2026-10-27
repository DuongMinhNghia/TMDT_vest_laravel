@extends('layouts.auth_layout')

@section('title', 'Quên Mật Khẩu - Kingsman Vietnam')

@section('content')
<div class="auth-card">
    <div class="auth-card-header"><i class="bi bi-shield-lock me-2"></i> KHÔI PHỤC MẬT KHẨU</div>
    <div class="auth-card-body">
        <p class="small mb-4" style="color: var(--auth-text-muted);">Nhập email tài khoản để nhận mã xác thực OTP gồm 6 chữ số.</p>
        <form action="{{ route('matkhau.quen.gui') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="form-label">Địa Chỉ Email</label>
                <input type="email" name="email" class="form-control" required autocomplete="email" value="{{ old('email') }}">
            </div>
            <button type="submit" class="btn-gold mb-4"><i class="bi bi-send me-2"></i> GỬI MÃ XÁC THỰC</button>
        </form>
        <div class="text-center small"><a href="{{ route('login') }}" class="link-gold"><i class="bi bi-arrow-left"></i> Quay lại đăng nhập</a></div>
    </div>
</div>
@endsection
