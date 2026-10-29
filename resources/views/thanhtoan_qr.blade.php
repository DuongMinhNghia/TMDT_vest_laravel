@extends('layouts.default_layout')

@section('title', 'Thanh Toán Đơn ' . $donHang->ma_don_hang . ' - Kingsman Vietnam')

@section('content')
<style>
    .payment-wrap {
        max-width: 960px;
        margin: 0 auto;
        padding: 30px 15px 60px;
        width: 100%;
    }
    .payment-card {
        background: #141720;
        border: 1px solid rgba(212, 178, 111, 0.32);
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 16px 42px rgba(0, 0, 0, 0.45);
        width: 100%;
    }
    .payment-heading {
        padding: 24px 28px;
        background: linear-gradient(135deg, rgba(212, 178, 111, 0.18), rgba(20, 23, 32, 0.95));
        border-bottom: 1px solid rgba(212, 178, 111, 0.2);
    }
    .payment-heading h1 {
        color: #e8ca8c;
        font-size: 1.55rem;
        font-family: 'Playfair Display', Georgia, serif;
        font-weight: 700;
        margin: 0 0 6px;
        display: flex;
        align-items: center;
        gap: 10px;
        line-height: 1.3;
    }
    .payment-heading p {
        margin: 0;
        color: #cbd5e1;
        font-size: 0.9rem;
    }
    .payment-body {
        padding: 28px;
    }
    .payment-qr-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
    }
    .payment-qr {
        width: 100%;
        max-width: 270px;
        height: auto;
        aspect-ratio: 1/1;
        object-fit: contain;
        background: #ffffff;
        border-radius: 14px;
        padding: 10px;
        display: block;
        margin: 0 auto;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    .payment-qr-tip {
        display: block;
        margin-top: 12px;
        color: #94a3b8;
        font-size: 0.8rem;
        line-height: 1.4;
        max-width: 280px;
    }
    .payment-detail {
        background: #181b24;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 12px;
        padding: 14px 18px;
        margin-bottom: 12px;
        transition: border-color 0.2s ease;
    }
    .payment-detail:last-child {
        margin-bottom: 0;
    }
    .payment-detail:hover {
        border-color: rgba(212, 178, 111, 0.25);
    }
    .payment-label {
        color: #94a3b8;
        font-size: 0.74rem;
        letter-spacing: 0.6px;
        text-transform: uppercase;
        margin-bottom: 4px;
        font-weight: 600;
    }
    .payment-value {
        color: #f1f5f9;
        font-weight: 700;
        font-size: 0.98rem;
        word-break: break-word;
        overflow-wrap: anywhere;
    }
    .payment-amount {
        color: #e8ca8c;
        font-size: 1.55rem;
        font-weight: 800;
        font-variant-numeric: tabular-nums;
        line-height: 1.2;
    }
    .payment-copy-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 8px;
        min-width: 0;
    }
    .payment-copy-text {
        font-family: 'Plus Jakarta Sans', monospace, sans-serif;
        font-weight: 700;
        min-width: 0;
        word-break: break-all;
        overflow-wrap: anywhere;
    }
    .copy-button {
        border: 1px solid rgba(212, 178, 111, 0.52);
        color: #e8ca8c;
        background: rgba(212, 178, 111, 0.06);
        border-radius: 6px;
        padding: 4px 11px;
        font-size: 0.74rem;
        font-weight: 600;
        white-space: nowrap;
        flex-shrink: 0;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .copy-button:hover,
    .copy-button:active {
        background: #d4b26f;
        color: #111317;
        box-shadow: 0 2px 8px rgba(212, 178, 111, 0.3);
    }
    .payment-note {
        color: #fbbf24;
        background: rgba(245, 158, 11, 0.08);
        border: 1px solid rgba(245, 158, 11, 0.22);
        border-radius: 10px;
        padding: 14px 16px;
        font-size: 0.85rem;
        line-height: 1.55;
    }
    .payment-actions-wrap {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
        margin-top: 24px;
    }
    .btn-action-back {
        border: 1px solid rgba(255, 255, 255, 0.22);
        color: #cbd5e1;
        font-weight: 600;
        font-size: 0.85rem;
        padding: 9px 18px;
        border-radius: 8px;
        text-decoration: none;
        transition: all 0.2s ease;
    }
    .btn-action-back:hover {
        background: rgba(255, 255, 255, 0.08);
        color: #ffffff;
        border-color: rgba(255, 255, 255, 0.4);
    }
    .btn-action-submit {
        background: linear-gradient(135deg, #d4b26f, #c19b2f);
        color: #111317;
        font-weight: 700;
        font-size: 0.85rem;
        padding: 9px 20px;
        border-radius: 8px;
        border: none;
        text-decoration: none;
        box-shadow: 0 3px 12px rgba(212, 178, 111, 0.25);
        transition: all 0.2s ease;
    }
    .btn-action-submit:hover {
        background: #e8ca8c;
        color: #0b0c10;
        box-shadow: 0 4px 16px rgba(212, 178, 111, 0.35);
    }

    /* RESPONSIVE CHO ĐIỆN THOẠI & TABLET */
    @media (max-width: 767.98px) {
        .payment-wrap {
            padding: 12px 6px 45px;
        }
        .payment-card {
            border-radius: 12px;
        }
        .payment-heading {
            padding: 16px 14px;
            text-align: center;
        }
        .payment-heading h1 {
            font-size: 1.22rem;
            justify-content: center;
        }
        .payment-heading p {
            font-size: 0.82rem;
        }
        .payment-body {
            padding: 16px 12px;
        }
        .payment-qr {
            max-width: 220px;
            padding: 8px;
            border-radius: 10px;
        }
        .payment-qr-tip {
            font-size: 0.75rem;
            margin-top: 8px;
            margin-bottom: 12px;
        }
        .payment-detail {
            padding: 11px 13px;
            border-radius: 8px;
            margin-bottom: 8px;
        }
        .payment-label {
            font-size: 0.68rem;
        }
        .payment-value {
            font-size: 0.9rem;
        }
        .payment-amount {
            font-size: 1.28rem;
        }
        .copy-button {
            padding: 3px 8px;
            font-size: 0.7rem;
        }
        .payment-note {
            padding: 11px 12px;
            font-size: 0.78rem;
            margin-top: 14px !important;
        }
        .payment-actions-wrap {
            flex-direction: column-reverse;
            gap: 9px;
            margin-top: 18px;
        }
        .payment-actions-wrap .btn {
            width: 100%;
            text-align: center;
            justify-content: center;
            display: inline-flex;
            padding: 10px 14px;
            font-size: 0.88rem;
        }
    }
</style>

<div class="payment-wrap">
    <div class="payment-card">
        <div class="payment-heading">
            <h1><i class="bi bi-qr-code-scan"></i> Thanh Toán Chuyển Khoản</h1>
            <p>Đơn hàng <strong style="color:#e8ca8c; font-variant-numeric:tabular-nums;">{{ $donHang->ma_don_hang }}</strong> đang chờ Quý khách thanh toán.</p>
        </div>

        <div class="payment-body">
            @if($paymentConfigValid && $qrUrl)
            <div class="row g-3 g-md-4 align-items-center">
                <div class="col-12 col-md-5">
                    <div class="payment-qr-container">
                        <img src="{{ $qrUrl }}" class="payment-qr" alt="Mã QR thanh toán {{ $donHang->ma_don_hang }}">
                        <span class="payment-qr-tip">Quét QR bằng ứng dụng ngân hàng hoặc ví điện tử hỗ trợ VietQR.</span>
                    </div>
                </div>
                <div class="col-12 col-md-7">
                    <div class="payment-detail">
                        <div class="payment-label">Số tiền cần chuyển {{ $heSoDemo > 1 ? '(demo)' : '' }}</div>
                        <div class="payment-value payment-amount">{{ number_format($soTien, 0, ',', '.') }} VNĐ</div>
                        @if($heSoDemo > 1)
                        <small style="color:#fbbf24; font-size: 0.76rem; font-weight:600;" class="d-block mt-1">SỐ TIỀN DEMO — không phải tổng giá trị hóa đơn</small>
                        @endif
                        <small style="color:#94a3b8; font-size: 0.76rem;" class="d-block mt-1">Tổng giá trị hóa đơn: {{ number_format($soTienDon, 0, ',', '.') }} VNĐ{{ $heSoDemo > 1 ? ' · Chế độ demo 1/' . number_format($heSoDemo, 0, ',', '.') : '' }}</small>
                    </div>
                    <div class="payment-detail">
                        <div class="payment-label">Ngân hàng</div>
                        <div class="payment-value">{{ $bankName }}</div>
                    </div>
                    <div class="payment-detail">
                        <div class="payment-label">Chủ tài khoản</div>
                        <div class="payment-value">{{ $accountName }}</div>
                    </div>
                    <div class="payment-detail">
                        <div class="payment-label">Số tài khoản</div>
                        <div class="payment-copy-row">
                            <span class="payment-copy-text">{{ $accountNumber }}</span>
                            <button type="button" class="copy-button" data-copy="{{ $accountNumber }}">
                                <i class="bi bi-clipboard me-1"></i>Sao chép
                            </button>
                        </div>
                    </div>
                    <div class="payment-detail">
                        <div class="payment-label">Nội dung chuyển khoản <span style="color:#fbbf24;">(bắt buộc)</span></div>
                        <div class="payment-copy-row">
                            <span class="payment-copy-text">{{ $donHang->ma_don_hang }}</span>
                            <button type="button" class="copy-button" data-copy="{{ $donHang->ma_don_hang }}">
                                <i class="bi bi-clipboard me-1"></i>Sao chép
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="payment-note mt-3 mt-md-4">
                <i class="bi bi-info-circle-fill me-1"></i> Vui lòng chuyển <strong>đúng số tiền hiển thị</strong> và ghi <strong>đúng mã đơn hàng</strong> làm nội dung. Hệ thống sẽ xác nhận thanh toán sau khi nhận được tiền.
            </div>
            @else
            <div class="payment-note">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> Cấu hình thanh toán chưa đầy đủ hoặc không hợp lệ. Vui lòng liên hệ quản trị viên.
            </div>
            @endif

            <div class="payment-actions-wrap">
                <a class="btn btn-action-back" href="{{ route('donhang.chitiet', $donHang->id) }}">
                    <i class="bi bi-receipt me-1"></i> Xem chi tiết đơn
                </a>
                <a class="btn btn-action-submit" href="{{ route('donhang.lichsu') }}">
                    Xem đơn hàng của tôi <i class="bi bi-arrow-right ms-1"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
document.querySelectorAll('[data-copy]').forEach(function (button) {
    button.addEventListener('click', function () {
        const textToCopy = this.dataset.copy;
        const buttonEl = this;
        const setCopied = () => {
            const originalHtml = buttonEl.innerHTML;
            buttonEl.innerHTML = '<i class="bi bi-check-lg me-1"></i>Đã chép';
            buttonEl.style.borderColor = '#10b981';
            buttonEl.style.color = '#34d399';
            setTimeout(() => {
                buttonEl.innerHTML = originalHtml;
                buttonEl.style.borderColor = '';
                buttonEl.style.color = '';
            }, 1800);
        };

        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(textToCopy).then(setCopied).catch(() => {
                fallbackCopy(textToCopy, setCopied);
            });
        } else {
            fallbackCopy(textToCopy, setCopied);
        }
    });
});

function fallbackCopy(text, callback) {
    const tempInput = document.createElement('input');
    tempInput.value = text;
    document.body.appendChild(tempInput);
    tempInput.select();
    try {
        document.execCommand('copy');
        callback();
    } catch (e) {
        console.error('Không thể sao chép', e);
    }
    document.body.removeChild(tempInput);
}
</script>
@endsection
