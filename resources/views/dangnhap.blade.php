@extends('layouts.auth_layout')

@section('title', 'Đăng Nhập - Kingsman')

@section('content')
<div class="auth-card">
    <div class="auth-card-header">
        <i class="bi bi-person-circle me-2"></i> ĐĂNG NHẬP
    </div>
    <div class="auth-card-body">

        @if(session('success'))
        <div class="alert alert-success small mb-4">
            <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger small mb-4">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ url('/dang-nhap') }}" method="POST" autocomplete="on">
            @csrf

            <div class="mb-4">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                    required autocomplete="email"
                    value="{{ old('email') }}"
                    placeholder="example@email.com">
            </div>

            <div class="mb-4">
                <label class="form-label">Mật khẩu</label>
                <input type="password" name="password" class="form-control"
                    required autocomplete="current-password"
                    placeholder="••••••••">
            </div>

            <button type="submit" class="btn-gold mb-4">
                <i class="bi bi-box-arrow-in-right me-2"></i> ĐĂNG NHẬP
            </button>

            <div class="text-center small" style="color:#888;">
                Chưa có tài khoản?
                <a href="{{ url('/dang-ky') }}" class="link-gold">Đăng ký ngay</a>
            </div>
        </form>

    </div>
</div>
@endsection