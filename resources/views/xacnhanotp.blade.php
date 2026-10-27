@extends('layouts.auth_layout')

@section('title', 'Xác Nhận OTP - Kingsman Vietnam')

@section('content')
<div class="auth-card">
    <div class="auth-card-header"><i class="bi bi-envelope-check me-2"></i> XÁC NHẬN MÃ OTP</div>
    <div class="auth-card-body">
        <p class="small mb-4" style="color: var(--auth-text-muted);">Nhập mã 6 chữ số được gửi đến email của bạn. Mã có hiệu lực trong 10 phút.</p>
        <form action="{{ route('matkhau.xacnhan') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="form-label">Mã Xác Thực</label>
                <input type="text" name="otp" class="form-control text-center" required inputmode="numeric" pattern="[0-9]{6}" maxlength="6" autocomplete="one-time-code" style="font-size: 1.2rem; letter-spacing: 0.5rem;">
            </div>
            <button type="submit" class="btn-gold"><i class="bi bi-check2-circle me-2"></i> XÁC NHẬN MÃ</button>
        </form>
    </div>
</div>
@endsection
