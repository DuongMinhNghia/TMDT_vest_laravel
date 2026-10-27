@extends('layouts.default_layout')

@section('title', 'Thanh Toán Đơn ' . $donHang->ma_don_hang . ' - Kingsman Vietnam')

@section('content')
@php
    $bankId = config('services.manual_transfer.bank_id', 'VCB');
    $accountNumber = config('services.manual_transfer.account_number');
    $accountName = config('services.manual_transfer.account_name');
    $soTienDon = (int) $donHang->tong_tien;
    $heSoDemo = config('services.manual_transfer.demo_divisor', 1);
    $soTien = (int) ceil($soTienDon / $heSoDemo);
    $qrUrl = $accountNumber
        ? 'https://img.vietqr.io/image/' . rawurlencode($bankId) . '-' . rawurlencode($accountNumber) . '-compact2.png?amount=' . $soTien . '&addInfo=' . rawurlencode($donHang->ma_don_hang) . '&accountName=' . rawurlencode($accountName ?? '')
        : null;
@endphp

<style>
    .payment-wrap { max-width: 980px; margin: 0 auto; padding: 38px 15px 70px; }
    .payment-card { background:#141720; border:1px solid rgba(212,178,111,.32); border-radius:16px; overflow:hidden; box-shadow:0 16px 42px rgba(0,0,0,.38); }
    .payment-heading { padding:26px 30px; background:linear-gradient(135deg, rgba(212,178,111,.18), rgba(20,23,32,.92)); border-bottom:1px solid rgba(212,178,111,.2); }
    .payment-heading h1 { color:#e8ca8c; font-size:1.65rem; margin:0 0 6px; }
    .payment-heading p { margin:0; color:#cbd5e1; }
    .payment-body { padding:30px; }
    .payment-qr { width:min(100%, 330px); background:#fff; border-radius:14px; padding:12px; display:block; margin:0 auto; }
    .payment-detail { background:#181b24; border:1px solid rgba(255,255,255,.09); border-radius:12px; padding:18px; }
    .payment-label { color:#94a3b8; font-size:.76rem; letter-spacing:.6px; text-transform:uppercase; margin-bottom:5px; }
    .payment-value { color:#f1f5f9; font-weight:700; font-size:1rem; overflow-wrap:anywhere; }
    .payment-amount { color:#e8ca8c; font-size:1.65rem; font-variant-numeric:tabular-nums; }
    .payment-note { color:#fbbf24; background:rgba(245,158,11,.09); border:1px solid rgba(245,158,11,.25); border-radius:10px; padding:14px 16px; font-size:.88rem; }
    .copy-button { border:1px solid rgba(212,178,111,.52); color:#e8ca8c; background:transparent; border-radius:7px; padding:4px 9px; font-size:.76rem; }
    .copy-button:hover { background:rgba(212,178,111,.13); }
</style>

<div class="payment-wrap">
    <div class="payment-card">
        <div class="payment-heading">
            <h1><i class="bi bi-qr-code-scan me-2"></i>Thanh Toán Chuyển Khoản</h1>
            <p>Đơn hàng <strong style="color:#e8ca8c; font-variant-numeric:tabular-nums;">{{ $donHang->ma_don_hang }}</strong> đang chờ Quý khách thanh toán.</p>
        </div>

        <div class="payment-body">
            @if($qrUrl)
            <div class="row g-4 align-items-center">
                <div class="col-md-5 text-center">
                    <img src="{{ $qrUrl }}" class="payment-qr" alt="Mã QR thanh toán {{ $donHang->ma_don_hang }}">
                    <small class="d-block mt-3" style="color:#94a3b8;">Quét QR bằng ứng dụng ngân hàng hoặc MoMo hỗ trợ VietQR.</small>
                </div>
                <div class="col-md-7">
                    <div class="payment-detail mb-3">
                        <div class="payment-label">Số tiền cần chuyển {{ $heSoDemo > 1 ? '(demo)' : '' }}</div>
                        <div class="payment-value payment-amount">{{ number_format($soTien, 0, ',', '.') }} VNĐ</div>
                        @if($heSoDemo > 1)
                        <small style="color:#94a3b8;">Giá trị đơn: {{ number_format($soTienDon, 0, ',', '.') }} VNĐ · Chế độ demo 1/{{ number_format($heSoDemo, 0, ',', '.') }}</small>
                        @endif
                    </div>
                    <div class="payment-detail mb-3">
                        <div class="payment-label">Ngân hàng</div>
                        <div class="payment-value">Vietcombank</div>
                    </div>
                    <div class="payment-detail mb-3">
                        <div class="payment-label">Chủ tài khoản</div>
                        <div class="payment-value">{{ $accountName }}</div>
                    </div>
                    <div class="payment-detail mb-3">
                        <div class="payment-label">Số tài khoản</div>
                        <div class="payment-value d-flex justify-content-between align-items-center gap-2"><span>{{ $accountNumber }}</span><button type="button" class="copy-button" data-copy="{{ $accountNumber }}">Sao chép</button></div>
                    </div>
                    <div class="payment-detail">
                        <div class="payment-label">Nội dung chuyển khoản <span style="color:#fbbf24;">(bắt buộc)</span></div>
                        <div class="payment-value d-flex justify-content-between align-items-center gap-2"><span>{{ $donHang->ma_don_hang }}</span><button type="button" class="copy-button" data-copy="{{ $donHang->ma_don_hang }}">Sao chép</button></div>
                    </div>
                </div>
            </div>
            <div class="payment-note mt-4"><i class="bi bi-info-circle-fill me-2"></i>Vui lòng chuyển <strong>đúng số tiền hiển thị</strong> và ghi <strong>đúng mã đơn hàng</strong> làm nội dung. Đơn hiện được xử lý theo hình thức xác nhận chuyển khoản thủ công.</div>
            @else
            <div class="payment-note"><i class="bi bi-exclamation-triangle-fill me-2"></i>Chưa có cấu hình tài khoản nhận tiền. Vui lòng liên hệ quản trị viên.</div>
            @endif

            <div class="d-flex justify-content-between flex-wrap gap-3 mt-4">
                <a class="btn btn-outline-light" href="{{ route('donhang.chitiet', $donHang->id) }}"><i class="bi bi-receipt me-1"></i> Xem chi tiết đơn</a>
                <a class="btn" style="background:#d4b26f; color:#111317; font-weight:700;" href="{{ route('donhang.lichsu') }}">Xem đơn hàng của tôi</a>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('[data-copy]').forEach(function (button) {
    button.addEventListener('click', function () {
        navigator.clipboard.writeText(this.dataset.copy).then(() => {
            const original = this.textContent;
            this.textContent = 'Đã sao chép';
            setTimeout(() => this.textContent = original, 1600);
        });
    });
});
</script>
@endsection
