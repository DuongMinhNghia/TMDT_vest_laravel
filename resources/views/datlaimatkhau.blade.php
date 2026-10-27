@extends('layouts.auth_layout')

@section('title', 'Đặt Lại Mật Khẩu - Kingsman Vietnam')

@section('content')
<div class="auth-card">
    <div class="auth-card-header"><i class="bi bi-key me-2"></i> ĐẶT LẠI MẬT KHẨU</div>
    <div class="auth-card-body">
        <form action="{{ route('matkhau.datlai') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Mật Khẩu Mới</label>
                <div class="password-field">
                    <input id="reset-password" type="password" name="password" class="form-control" required autocomplete="new-password">
                    <button type="button" class="password-toggle" data-password-toggle="reset-password" aria-label="Hiển thị mật khẩu" title="Hiển thị mật khẩu"><i class="bi bi-eye"></i></button>
                </div>
                <div class="small mt-2" style="color: var(--auth-text-muted);">Ít nhất 8 ký tự, gồm chữ cái, chữ số và ký tự đặc biệt; không trùng mật khẩu cũ.</div>
            </div>
            <div class="mb-4">
                <label class="form-label">Nhập Lại Mật Khẩu Mới</label>
                <div class="password-field">
                    <input id="reset-password-confirmation" type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
                    <button type="button" class="password-toggle" data-password-toggle="reset-password-confirmation" aria-label="Hiển thị mật khẩu" title="Hiển thị mật khẩu"><i class="bi bi-eye"></i></button>
                </div>
            </div>
            <button type="submit" class="btn-gold"><i class="bi bi-check2-circle me-2"></i> ĐẶT LẠI MẬT KHẨU</button>
        </form>
    </div>
</div>
@endsection
