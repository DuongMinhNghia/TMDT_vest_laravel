@extends('layouts.auth_layout')

@section('title', 'Đăng Ký - Kingsman')

@section('content')
<div class="auth-card">
    <div class="auth-card-header">
        <i class="bi bi-person-plus me-2"></i> ĐĂNG KÝ TÀI KHOẢN
    </div>
    <div class="auth-card-body">

        @if($errors->any())
        <div class="alert alert-danger small mb-4">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ url('/dang-ky') }}" method="POST" autocomplete="off">
            @csrf

            <div class="mb-3">
                <label class="form-label">Họ tên</label>
                <input type="text" name="name" class="form-control"
                    required value="{{ old('name') }}"
                    placeholder="Nguyễn Văn A">
            </div>

            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control"
                    required autocomplete="off"
                    value="{{ old('email') }}"
                    placeholder="example@email.com">
            </div>

            <div class="mb-4">
                <label class="form-label">Mật khẩu</label>
                <input type="password" name="password" class="form-control"
                    required autocomplete="new-password"
                    placeholder="Ít nhất 6 ký tự">
            </div>

            <button type="submit" class="btn-gold mb-4">
                <i class="bi bi-person-check me-2"></i> TẠO TÀI KHOẢN
            </button>

            <div class="text-center small" style="color:#888;">
                Đã có tài khoản?
                <a href="{{ url('/dang-nhap') }}" class="link-gold">Đăng nhập</a>
            </div>
        </form>

    </div>
</div>
@endsection