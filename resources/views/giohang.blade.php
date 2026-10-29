@extends('layouts.default_layout')

@section('title', 'Giỏ Hàng May Đo - Kingsman Vietnam')

@section('content')
<style>
    .cart-container {
        padding: 40px 0 80px;
        min-height: calc(100vh - 200px);
    }

    .cart-title {
        font-family: 'Playfair Display', Georgia, serif;
        color: #D4B26F;
        font-size: 1.85rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }

    /* NÚT QUAY LẠI TRÊN TOP */
    .btn-back-store {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 10px 22px;
        border-radius: 8px;
        border: 1px solid rgba(212, 178, 111, 0.4);
        background: #141720;
        color: #D4B26F;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.88rem;
        letter-spacing: 0.3px;
        transition: all 0.25s cubic-bezier(0.165, 0.84, 0.44, 1);
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
    }

    .btn-back-store i {
        font-size: 1.1rem;
        transition: transform 0.25s ease;
    }

    .btn-back-store:hover {
        background: rgba(212, 178, 111, 0.15);
        border-color: #D4B26F;
        color: #E8CA8C;
        transform: translateX(-4px);
        box-shadow: 0 6px 20px rgba(212, 178, 111, 0.2);
    }

    .btn-back-store:hover i {
        transform: translateX(-3px);
    }

    .cart-table-wrapper {
        background: #141720;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    .cart-table {
        width: 100%;
        margin: 0;
        color: #F1F5F9;
        border-collapse: collapse;
    }

    .cart-table thead tr {
        background: #181B24;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .cart-table thead th {
        padding: 16px 20px;
        font-weight: 600;
        font-size: 0.84rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        color: #94A3B8;
        border: none;
    }

    .cart-table tbody tr {
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        transition: background 0.2s ease;
    }

    .cart-table tbody tr:hover {
        background: rgba(212, 178, 111, 0.03);
    }

    .cart-table tbody tr:last-child {
        border-bottom: none;
    }

    .cart-table tbody td {
        padding: 18px 20px;
        vertical-align: middle;
        border: none;
        color: #F1F5F9;
    }

    .product-img {
        width: 72px;
        height: 72px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .product-name {
        font-weight: 600;
        color: #F1F5F9;
        font-size: 0.95rem;
    }

    .size-badge {
        display: inline-block;
        padding: 2px 9px;
        background: rgba(212, 178, 111, 0.12);
        border: 1px solid rgba(212, 178, 111, 0.35);
        border-radius: 20px;
        color: #D4B26F;
        font-size: 0.76rem;
        font-weight: 600;
        margin-top: 5px;
    }

    .qty-control {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        background: #181B24;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 3px 6px;
        display: inline-flex;
    }

    .qty-btn {
        width: 26px;
        height: 26px;
        border-radius: 50%;
        border: none;
        background: rgba(255, 255, 255, 0.06);
        color: #D4B26F;
        font-size: 1rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s ease;
        line-height: 1;
    }

    .qty-btn:hover {
        background: #D4B26F;
        color: #0c0e12;
    }

    .qty-num {
        font-weight: 700;
        font-size: 0.95rem;
        color: #F1F5F9;
        min-width: 24px;
        text-align: center;
    }

    .price-col {
        color: #D4B26F;
        font-weight: 700;
        font-size: 0.96rem;
    }

    .remove-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #EF4444;
        background: rgba(239, 68, 68, 0.08);
        text-decoration: none;
        transition: all 0.2s ease;
        font-size: 0.9rem;
    }

    .remove-btn:hover {
        background: #EF4444;
        color: #fff;
    }

    /* FOOTER BẢNG GIỎ HÀNG */
    .cart-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 24px;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
        background: #181B24;
        flex-wrap: wrap;
        gap: 12px;
    }

    .btn-continue {
        display: inline-flex;
        align-items: center;
        gap: 9px;
        padding: 10px 22px;
        border-radius: 8px;
        border: 1px solid rgba(212, 178, 111, 0.45);
        color: #D4B26F;
        background: rgba(212, 178, 111, 0.08);
        text-decoration: none;
        font-weight: 600;
        font-size: 0.88rem;
        letter-spacing: 0.3px;
        transition: all 0.25s ease;
    }

    .btn-continue i {
        font-size: 1rem;
        transition: transform 0.2s ease;
    }

    .btn-continue:hover {
        background: linear-gradient(135deg, #D4B26F, #B89246);
        border-color: #D4B26F;
        color: #0c0e12;
        box-shadow: 0 4px 16px rgba(212, 178, 111, 0.3);
        transform: translateX(-3px);
    }

    .btn-continue:hover i {
        transform: translateX(-3px);
    }

    /* SUMMARY CARD */
    .summary-card {
        background: #141720;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        position: sticky;
        top: 24px;
    }

    .summary-header {
        background: #181B24;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        color: #D4B26F;
        padding: 16px 22px;
        font-family: 'Playfair Display', Georgia, serif;
        font-weight: 700;
        font-size: 1.1rem;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .summary-body {
        padding: 22px 22px 14px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        color: #94A3B8;
        font-size: 0.9rem;
    }

    .summary-row:last-of-type {
        border-bottom: none;
    }

    .summary-row span:last-child {
        font-weight: 600;
        color: #F1F5F9;
    }

    .summary-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 0 0;
        margin-top: 8px;
        border-top: 1px solid rgba(212, 178, 111, 0.3);
    }

    .summary-total span:first-child {
        color: #D4B26F;
        font-weight: 700;
        font-size: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .summary-total span:last-child {
        color: #D4B26F;
        font-weight: 700;
        font-size: 1.25rem;
    }

    .form-label-summary {
        color: #CBD5E1;
        font-size: 0.82rem;
        font-weight: 600;
        margin-top: 14px;
        margin-bottom: 6px;
        display: block;
    }

    .form-input-summary {
        width: 100%;
        background: #181B24;
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #F1F5F9;
        border-radius: 8px;
        padding: 9px 12px;
        font-size: 0.88rem;
        transition: all 0.25s ease;
    }

    .form-input-summary:focus {
        outline: none;
        border-color: #D4B26F;
        box-shadow: 0 0 0 3px rgba(212, 178, 111, 0.2);
    }

    .btn-checkout {
        display: block;
        width: 100%;
        padding: 14px;
        margin-top: 22px;
        background: linear-gradient(135deg, #D4B26F, #B89246);
        color: #0c0e12;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.95rem;
        letter-spacing: 0.5px;
        text-decoration: none;
        text-align: center;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .btn-checkout:hover {
        background: linear-gradient(135deg, #E8CA8C, #C9A458);
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(212, 178, 111, 0.35);
        color: #000;
    }

    .btn-checkout:disabled {
        opacity: 0.48;
        cursor: not-allowed;
        transform: none;
        box-shadow: none;
    }

    .checkout-dialog {
        width: min(720px, calc(100vw - 28px));
        max-height: min(90vh, 860px);
        padding: 0;
        overflow-y: auto;
        background: #141720;
        color: #F1F5F9;
        border: 1px solid rgba(212, 178, 111, 0.38);
        border-radius: 16px;
        box-shadow: 0 28px 80px rgba(0, 0, 0, 0.72);
    }

    .checkout-dialog:not([open]) {
        display: none;
    }

    .checkout-dialog[open] {
        position: fixed;
        inset: 50% auto auto 50%;
        margin: 0;
        transform: translate(-50%, -50%);
    }

    .checkout-dialog::backdrop {
        background: rgba(3, 5, 9, 0.78);
        backdrop-filter: blur(4px);
    }

    .checkout-dialog-header {
        position: sticky;
        top: 0;
        z-index: 30;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        padding: 18px 22px;
        background: #181B24;
        border-bottom: 1px solid rgba(255, 255, 255, 0.09);
    }

    .checkout-dialog-title {
        margin: 0;
        color: #D4B26F;
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 1.2rem;
        font-weight: 700;
    }

    .checkout-dialog-close {
        flex: 0 0 auto;
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        border: 1px solid rgba(255, 255, 255, 0.12);
        background: transparent;
        color: #CBD5E1;
        font-size: 1.25rem;
        cursor: pointer;
    }

    .checkout-dialog-close:hover {
        color: #D4B26F;
        border-color: #D4B26F;
        background: rgba(212, 178, 111, 0.08);
    }

    .checkout-dialog-form {
        padding: 4px 24px 24px;
    }

    .profile-address-card {
        display: grid;
        grid-template-columns: 46px minmax(0, 1fr);
        gap: 14px;
        margin: 18px 0 6px;
        padding: 16px;
        background: linear-gradient(135deg, rgba(212, 178, 111, 0.11), rgba(24, 27, 36, 0.92));
        border: 1px solid rgba(212, 178, 111, 0.38);
        border-radius: 12px;
        box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.04);
    }

    .profile-address-icon {
        width: 46px;
        height: 46px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 12px;
        background: rgba(212, 178, 111, 0.16);
        border: 1px solid rgba(212, 178, 111, 0.32);
        color: #D4B26F;
        font-size: 1.25rem;
    }

    .profile-address-heading {
        display: flex;
        align-items: flex-start;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 7px;
    }

    .profile-address-label {
        color: #D4B26F;
        font-size: 0.88rem;
        font-weight: 700;
    }

    .profile-address-status {
        flex: 0 0 auto;
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 8px;
        border-radius: 999px;
        background: rgba(16, 185, 129, 0.12);
        border: 1px solid rgba(16, 185, 129, 0.3);
        color: #34D399;
        font-size: 0.7rem;
        font-weight: 700;
    }

    .profile-address-text {
        margin: 0;
        color: #F1F5F9;
        font-size: 0.9rem;
        line-height: 1.55;
        font-style: normal;
    }

    .profile-address-recipient {
        display: flex;
        flex-wrap: wrap;
        gap: 6px 12px;
        margin-top: 9px;
        padding-top: 9px;
        border-top: 1px solid rgba(255, 255, 255, 0.07);
        color: #94A3B8;
        font-size: 0.76rem;
    }

    .profile-address-recipient i {
        color: #D4B26F;
        margin-right: 4px;
    }

    body:has(.checkout-dialog[open]) {
        overflow: hidden;
    }

    @media (max-width: 575.98px) {
        .checkout-dialog {
            width: calc(100vw - 16px);
            max-height: calc(100vh - 16px);
            border-radius: 12px;
        }

        .checkout-dialog-header {
            padding: 15px 16px;
        }

        .checkout-dialog-form {
            padding: 2px 16px 18px;
        }

        .profile-address-card {
            grid-template-columns: 38px minmax(0, 1fr);
            gap: 11px;
            padding: 13px;
        }

        .profile-address-icon {
            width: 38px;
            height: 38px;
            font-size: 1rem;
        }

        .profile-address-heading {
            display: block;
        }

        .profile-address-status {
            margin-top: 6px;
        }
    }

    /* TRẠNG THÁI GIỎ HÀNG TRỐNG */
    .empty-cart {
        text-align: center;
        padding: 80px 24px;
        color: #94A3B8;
        background: #141720;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    .empty-cart-icon-wrap {
        width: 90px;
        height: 90px;
        border-radius: 50%;
        background: rgba(212, 178, 111, 0.1);
        border: 1px solid rgba(212, 178, 111, 0.3);
        color: #D4B26F;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2.8rem;
        margin: 0 auto 20px;
    }

    .empty-cart h4 {
        font-family: 'Playfair Display', Georgia, serif;
        color: #F1F5F9;
        font-size: 1.45rem;
        font-weight: 700;
        margin-bottom: 8px;
    }

    .empty-cart p {
        font-size: 0.95rem;
        margin-bottom: 26px;
        color: #94A3B8;
        max-width: 440px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.6;
    }

    .btn-empty-shop {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 13px 32px;
        border-radius: 8px;
        background: linear-gradient(135deg, #D4B26F 0%, #B89246 100%);
        color: #0c0e12;
        font-weight: 700;
        font-size: 0.92rem;
        letter-spacing: 0.5px;
        text-decoration: none;
        transition: all 0.25s ease;
        box-shadow: 0 6px 20px rgba(212, 178, 111, 0.3);
    }

    .btn-empty-shop:hover {
        background: linear-gradient(135deg, #E8CA8C 0%, #C9A458 100%);
        color: #000;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(212, 178, 111, 0.45);
    }

    @media (max-width: 991.98px) {
        .summary-card {
            position: static;
            margin-top: 24px;
        }
    }

    @media (max-width: 768px) {
        .cart-container {
            padding: 16px 0 50px;
        }
        .cart-title {
            font-size: 1.4rem;
        }
        .btn-back-store {
            padding: 7px 14px;
            font-size: 0.8rem;
        }
        .cart-table thead {
            display: none;
        }
        .cart-table, .cart-table tbody, .cart-table tr, .cart-table td {
            display: block;
            width: 100%;
        }
        .cart-table tbody tr {
            padding: 14px 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .cart-table tbody td {
            padding: 5px 8px;
            text-align: left !important;
        }
    }
</style>

<div class="cart-container container">

    {{-- HEADER GIỎ HÀNG VỚI NÚT QUAY LẠI CỬA HÀNG --}}
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <div>
            <h2 class="cart-title"><i class="bi bi-cart3 me-2"></i>Giỏ Hàng May Đo</h2>
            <p class="text-muted mb-0" style="font-size:0.86rem; color:#94A3B8;">Các tác phẩm vest may đo và phụ kiện Quý khách đã tuyển chọn</p>
        </div>
        <a href="{{ route('sanpham.index') }}" class="btn-back-store" id="btnBackToStore">
            <i class="bi bi-arrow-left-circle-fill"></i>
            <span>Tiếp Tục Chọn Đồ</span>
        </a>
    </div>

    @if(empty($gioHang))
    <div class="empty-cart">
        <div class="empty-cart-icon-wrap">
            <i class="bi bi-bag-x"></i>
        </div>
        <h4>Giỏ hàng của bạn đang trống</h4>
        <p>Quý khách chưa chọn mẫu vest nào. Hãy khám phá bộ sưu tập phong cách Ý và Anh quốc độc quyền tại Kingsman Bespoke.</p>
        <a href="{{ route('sanpham.index') }}" class="btn-empty-shop">
            <i class="bi bi-arrow-left-circle-fill"></i> Khám Phá Bộ Sưu Tập Vest
        </a>
    </div>
    @else
    <div class="row g-4">
        {{-- BẢNG GIỎ HÀNG --}}
        <div class="col-lg-8">
            <div class="cart-table-wrapper">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Sản phẩm</th>
                            <th class="text-center">Đơn giá</th>
                            <th class="text-center">Số lượng</th>
                            <th class="text-end">Thành tiền</th>
                            <th class="text-center">Xóa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($gioHang as $key => $item)
                        <tr class="{{ $item['kha_dung'] ? '' : 'opacity-75' }}">
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    @if($item['hinh_anh'])
                                    <img src="{{ asset('images/' . $item['hinh_anh']) }}"
                                        class="product-img" alt="{{ $item['ten_sp'] }}" />
                                    @else
                                    <div class="product-img d-flex align-items-center justify-content-center" style="background:#232733;color:#94A3B8;">
                                        <i class="bi bi-image"></i>
                                    </div>
                                    @endif
                                    <div>
                                        <div class="product-name">{{ $item['ten_sp'] }}</div>
                                        <span class="size-badge">{{ $item['size'] }}</span>
                                        @unless($item['kha_dung'])
                                        <div style="color:#EF4444;font-size:0.78rem;font-weight:600;margin-top:7px;max-width:360px;">
                                            Sản phẩm này hiện không còn kinh doanh. Vui lòng xóa khỏi giỏ hàng.
                                        </div>
                                        @endunless
                                    </div>
                                </div>
                            </td>
                            <td class="text-center price-col">
                                @if($item['kha_dung'])
                                @if($item['sale'] > 0)
                                <div style="color:#64748B; text-decoration:line-through; font-size:0.78rem; font-weight:400;">{{ number_format($item['gia_goc'], 0, ',', '.') }} VNĐ</div>
                                <span style="color:#EF4444; font-size:0.75rem; font-weight:600;">-{{ $item['sale'] }}%</span>
                                @endif
                                <div>{{ number_format($item['gia'], 0, ',', '.') }} VNĐ</div>
                                @if($item['so_luong'] > $item['ton'])
                                <div style="color:#EF4444; font-size:0.75rem; font-weight:500;">Chỉ còn {{ $item['ton'] }} sản phẩm</div>
                                @endif
                                @else
                                <span style="color:#94A3B8;font-size:0.82rem;">Không khả dụng</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($item['kha_dung'])
                                <div class="qty-control">
                                    <form method="POST" action="{{ route('giohang.update') }}" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="key" value="{{ $key }}">
                                        <input type="hidden" name="type" value="-1">
                                        <button type="submit" class="qty-btn" title="Giảm">−</button>
                                    </form>
                                    <span class="qty-num">{{ $item['so_luong'] }}</span>
                                    <form method="POST" action="{{ route('giohang.update') }}" class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="key" value="{{ $key }}">
                                        <input type="hidden" name="type" value="1">
                                        <button type="submit" class="qty-btn" title="Tăng">+</button>
                                    </form>
                                </div>
                                @else
                                <span class="qty-num">{{ $item['so_luong'] }}</span>
                                @endif
                            </td>
                            <td class="text-end price-col">
                                @if($item['kha_dung'])
                                {{ number_format($item['gia'] * $item['so_luong'], 0, ',', '.') }} VNĐ
                                @else
                                &mdash;
                                @endif
                            </td>
                            <td class="text-center">
                                <form method="POST" action="{{ route('giohang.remove') }}" class="d-inline" onsubmit="return confirm('Xác nhận xóa sản phẩm này khỏi giỏ hàng?')">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" name="key" value="{{ $key }}">
                                    <button type="submit" class="remove-btn" title="Xóa">
                                        <i class="bi bi-trash3"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                {{-- CHÂN BẢNG GIỎ HÀNG VỚI NÚT QUAY LẠI TIẾP TỤC CHỌN ĐỒ --}}
                <div class="cart-footer">
                    <a href="{{ route('sanpham.index') }}" class="btn-continue">
                        <i class="bi bi-arrow-left"></i> Tiếp Tục Tuyển Chọn Vest
                    </a>
                    <span style="font-size:0.84rem; color:#94A3B8;">
                        Đang có <strong style="color:#D4B26F;">{{ count($gioHang) }}</strong> mẫu thiết kế trong giỏ
                    </span>
                </div>
            </div>
        </div>

        {{-- TÓM TẮT ĐƠN HÀNG --}}
        <div class="col-lg-4">
            <div class="summary-card">
                <div class="summary-header">
                    <i class="bi bi-receipt"></i> Tóm Tắt Đơn Hàng
                </div>
                <div class="summary-body">
                    <div class="summary-row">
                        <span>Tổng tiền hàng:</span>
                        <span>{{ number_format($tongTien, 0, ',', '.') }} VNĐ</span>
                    </div>
                    <div class="summary-row">
                        <span>Phí vận chuyển:</span>
                        <span>{{ number_format($tien['phi_ship'], 0, ',', '.') }} VNĐ</span>
                    </div>
                    @if($tien['phan_tram_hang'] > 0)
                    <div class="summary-row">
                        <span style="color:#D4B26F;">Ưu đãi hạng {{ $tenHang }} (-{{ $tien['phan_tram_hang'] }}%):</span>
                        <span style="color:#10B981;">- {{ number_format($tien['giam_hang'], 0, ',', '.') }} VNĐ</span>
                    </div>
                    @endif
                    <div class="summary-total">
                        <span>Tổng thanh toán:</span>
                        <span>{{ number_format($tien['thanh_toan'], 0, ',', '.') }} VNĐ</span>
                    </div>

                    @if($coSanPhamKhongKhaDung)
                    <div class="alert alert-danger" style="font-size:0.84rem;">
                        Giỏ hàng có sản phẩm không còn kinh doanh. Vui lòng xóa sản phẩm đó trước khi thanh toán.
                    </div>
                    @endif
                    @if($loiDuLieuDiaChi)
                    <div class="alert alert-danger" style="font-size:0.84rem;">
                        {{ $loiDuLieuDiaChi }}
                    </div>
                    @endif

                    <button
                        type="button"
                        id="open-checkout-dialog"
                        class="btn-checkout"
                        {{ ($coSanPhamKhongKhaDung || $loiDuLieuDiaChi) ? 'disabled' : '' }}
                    >
                        <i class="bi bi-bag-check-fill me-2"></i> Xác Nhận Đặt Hàng
                    </button>

                    <dialog id="checkout-address-dialog" class="checkout-dialog" aria-labelledby="checkout-dialog-title">
                        <div class="checkout-dialog-header">
                            <div>
                                <h2 id="checkout-dialog-title" class="checkout-dialog-title">Thông Tin Nhận Hàng</h2>
                                <small style="color:#94A3B8;">Kiểm tra địa chỉ trước khi hoàn tất đơn hàng</small>
                            </div>
                            <button type="button" id="close-checkout-dialog" class="checkout-dialog-close" aria-label="Đóng form đặt hàng">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>

                    <form method="POST" action="{{ route('giohang.checkout') }}" class="checkout-dialog-form">
                        @csrf
                        @if($khachHang->tinh_thanh_code && $khachHang->phuong_xa_code && $khachHang->dia_chi_chi_tiet)
                        <section class="profile-address-card" aria-label="Địa chỉ giao hàng mặc định">
                            <div class="profile-address-icon" aria-hidden="true">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <div>
                                <div class="profile-address-heading">
                                    <div>
                                        <div class="profile-address-label">Địa chỉ mặc định trong hồ sơ</div>
                                        <small style="color:#94A3B8;">Đã tự động dùng địa chỉ trong hồ sơ</small>
                                    </div>
                                    <span class="profile-address-status"><i class="bi bi-check-circle-fill"></i> Đang sử dụng</span>
                                </div>
                                <address class="profile-address-text">{{ $khachHang->dia_chi }}</address>
                                <div class="profile-address-recipient">
                                    <span><i class="bi bi-person-fill"></i>{{ $khachHang->ho_ten }}</span>
                                    @if($khachHang->so_dien_thoai)
                                    <span><i class="bi bi-telephone-fill"></i>{{ $khachHang->so_dien_thoai }}</span>
                                    @endif
                                </div>
                            </div>
                        </section>
                        @endif
                        <label class="form-label-summary" for="ten_nguoi_nhan">Tên người nhận *</label>
                        <input id="ten_nguoi_nhan" type="text" name="ten_nguoi_nhan" required minlength="2" maxlength="100"
                            class="form-input-summary @error('ten_nguoi_nhan') is-invalid @enderror"
                            value="{{ old('ten_nguoi_nhan', $khachHang->ho_ten) }}" autocomplete="name">
                        @error('ten_nguoi_nhan')<div class="text-danger small mb-2">{{ $message }}</div>@enderror

                        <label class="form-label-summary" for="so_dien_thoai_nhan">Số điện thoại người nhận *</label>
                        <input id="so_dien_thoai_nhan" type="tel" name="so_dien_thoai_nhan" required maxlength="20"
                            class="form-input-summary @error('so_dien_thoai_nhan') is-invalid @enderror"
                            value="{{ old('so_dien_thoai_nhan', $khachHang->so_dien_thoai) }}" autocomplete="tel" inputmode="tel" placeholder="VD: 0912 345 678">
                        @error('so_dien_thoai_nhan')<div class="text-danger small mb-2">{{ $message }}</div>@enderror

                        <label class="form-label-summary" for="tinh_thanh_code">Tỉnh / Thành phố *</label>
                        <input id="province-combobox" type="text" list="province-options" required class="form-input-summary @error('tinh_thanh_code') is-invalid @enderror" placeholder="Chọn hoặc gõ Tỉnh / Thành phố" autocomplete="off">
                        <datalist id="province-options">
                            @foreach($tinhThanhs as $tinhThanh)<option value="{{ $tinhThanh['name'] }}"></option>@endforeach
                        </datalist>
                        <select id="tinh_thanh_code" name="tinh_thanh_code" class="d-none" aria-hidden="true" tabindex="-1">
                            <option value="">Chọn Tỉnh / Thành phố</option>
                            @foreach($tinhThanhs as $tinhThanh)
                            <option value="{{ $tinhThanh['code'] }}" {{ (string) old('tinh_thanh_code', $khachHang->tinh_thanh_code) === (string) $tinhThanh['code'] ? 'selected' : '' }}>{{ $tinhThanh['name'] }}</option>
                            @endforeach
                        </select>
                        @error('tinh_thanh_code')<div class="text-danger small mb-2">{{ $message }}</div>@enderror

                        <label class="form-label-summary" for="phuong_xa_code">Phường / Xã *</label>
                        <input id="ward-combobox" type="text" list="ward-options" required class="form-input-summary @error('phuong_xa_code') is-invalid @enderror" placeholder="Chọn hoặc gõ Phường / Xã" autocomplete="off" disabled>
                        <datalist id="ward-options"></datalist>
                        <select id="phuong_xa_code" name="phuong_xa_code" disabled class="d-none" aria-hidden="true" tabindex="-1" data-selected="{{ old('phuong_xa_code', $khachHang->phuong_xa_code) }}">
                            <option value="">Chọn Phường / Xã</option>
                        </select>
                        <div id="ward-loading" class="small mb-2" style="display:none;color:#94A3B8;">Đang tải danh sách Phường/Xã...</div>
                        <div id="ward-error" class="text-danger small mb-2" style="display:none;"></div>
                        @error('phuong_xa_code')<div class="text-danger small mb-2">{{ $message }}</div>@enderror

                        <label class="form-label-summary" for="dia_chi_chi_tiet">Địa chỉ chi tiết *</label>
                        <textarea id="dia_chi_chi_tiet" name="dia_chi_chi_tiet" rows="3" required minlength="2" maxlength="200"
                            class="form-input-summary @error('dia_chi_chi_tiet') is-invalid @enderror"
                            placeholder="VD: 306/6 Đặng Thùy Trâm, tòa nhà A..." autocomplete="street-address">{{ old('dia_chi_chi_tiet', $khachHang->dia_chi_chi_tiet) }}</textarea>
                        <small class="d-block mt-1" style="color:#94A3B8;font-size:.72rem;">Nhập số nhà, tên đường, tòa nhà, thôn hoặc ấp cụ thể.</small>
                        @error('dia_chi_chi_tiet')<div class="text-danger small mb-2">{{ $message }}</div>@enderror

                        <label class="form-label-summary">Phương thức thanh toán *</label>
                        <select name="phuong_thuc" class="form-input-summary">
                            <option value="COD">Thanh toán khi nhận hàng (COD)</option>
                            <option value="Chuyển khoản QR" {{ old('phuong_thuc') == 'Chuyển khoản QR' ? 'selected' : '' }}>Chuyển khoản QR (MBBank / VietQR)</option>
                        </select>

                        <label class="form-label-summary">Ghi chú giao hàng (nếu có)</label>
                        <input type="text" name="ghi_chu" value="{{ old('ghi_chu') }}" maxlength="500" class="form-input-summary" placeholder="VD: Giao giờ hành chính, gọi trước...">

                        <button type="submit" class="btn-checkout" {{ ($coSanPhamKhongKhaDung || $loiDuLieuDiaChi) ? 'disabled' : '' }}>
                            <i class="bi bi-bag-check-fill me-2"></i> Hoàn Tất Đặt Hàng
                        </button>
                    </form>
                    </dialog>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const checkoutDialog = document.getElementById('checkout-address-dialog');
    const openCheckoutDialog = document.getElementById('open-checkout-dialog');
    const closeCheckoutDialog = document.getElementById('close-checkout-dialog');

    function showCheckoutDialog() {
        if (!checkoutDialog || checkoutDialog.open) return;
        checkoutDialog.showModal();
    }

    openCheckoutDialog?.addEventListener('click', showCheckoutDialog);
    closeCheckoutDialog?.addEventListener('click', function () {
        checkoutDialog?.close();
    });
    checkoutDialog?.addEventListener('click', function (event) {
        if (event.target === checkoutDialog) checkoutDialog.close();
    });

    @if($errors->any())
    showCheckoutDialog();
    @endif

    const province = document.getElementById('tinh_thanh_code');
    const ward = document.getElementById('phuong_xa_code');
    if (!province || !ward) return;

    const loading = document.getElementById('ward-loading');
    const error = document.getElementById('ward-error');
    const provinceCombobox = document.getElementById('province-combobox');
    const wardCombobox = document.getElementById('ward-combobox');
    const wardDatalist = document.getElementById('ward-options');
    const selectedWard = ward.dataset.selected || '';
    const endpoint = @json(route('giohang.phuongxa'));
    const provinceOptions = Array.from(province.options).slice(1).map(option => ({ value: option.value, text: option.textContent }));
    let wardOptions = [];

    function normalizeSearch(value) {
        return value.normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/đ/g, 'd').replace(/Đ/g, 'D').toLowerCase();
    }

    provinceCombobox.addEventListener('input', function () {
        const match = provinceOptions.find(item => normalizeSearch(item.text) === normalizeSearch(provinceCombobox.value.trim()));
        const nextValue = match?.value || '';
        if (province.value !== nextValue) {
            province.value = nextValue;
            province.dispatchEvent(new Event('change'));
        }
    });
    wardCombobox.addEventListener('input', function () {
        const match = wardOptions.find(item => normalizeSearch(item.text) === normalizeSearch(wardCombobox.value.trim()));
        ward.value = match?.value || '';
        ward.dispatchEvent(new Event('change'));
    });

    async function loadWards(restoreSelection = false) {
        ward.innerHTML = '<option value="">Chọn Phường / Xã</option>';
        ward.disabled = true;
        wardCombobox.disabled = true;
        wardCombobox.value = '';
        wardDatalist.innerHTML = '';
        error.style.display = 'none';
        if (!province.value) return;

        loading.style.display = 'block';
        try {
            const response = await fetch(endpoint + '?tinh_thanh_code=' + encodeURIComponent(province.value), {
                headers: { 'Accept': 'application/json' }
            });
            const payload = await response.json();
            if (!response.ok) throw new Error(payload.message || 'Không thể tải danh sách địa chỉ.');

            payload.data.forEach(function (item) {
                const option = document.createElement('option');
                option.value = item.code;
                option.textContent = item.name;
                if (restoreSelection && String(item.code) === String(selectedWard)) option.selected = true;
                ward.appendChild(option);
            });
            wardOptions = payload.data.map(item => ({ value: String(item.code), text: item.name }));
            wardOptions.forEach(item => wardDatalist.appendChild(new Option(item.text)));
            ward.disabled = payload.data.length === 0;
            wardCombobox.disabled = payload.data.length === 0;
            const selected = wardOptions.find(item => String(item.value) === String(ward.value));
            if (selected) wardCombobox.value = selected.text;
            if (payload.data.length === 0) {
                error.textContent = 'Không tìm thấy Phường/Xã thuộc Tỉnh/Thành phố đã chọn.';
                error.style.display = 'block';
            }
        } catch (exception) {
            error.textContent = exception.message || 'Không thể tải danh sách địa chỉ. Vui lòng thử lại sau.';
            error.style.display = 'block';
        } finally {
            loading.style.display = 'none';
        }
    }

    province.addEventListener('change', function () { loadWards(false); });
    const initialProvince = provinceOptions.find(item => String(item.value) === String(province.value));
    if (initialProvince) provinceCombobox.value = initialProvince.text;
    if (province.value) loadWards(true);

});
</script>
@endsection
