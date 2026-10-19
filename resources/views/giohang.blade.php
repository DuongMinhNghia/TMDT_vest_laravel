@extends('layouts.default_layout')

@section('title', 'Giỏ Hàng - Kingsman Vietnam')

@section('content')
<style>
    .cart-container {
        padding: 40px 0;
    }

    .cart-title {
        font-family: 'Playfair Display', serif;
        color: #C5A059;
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 30px;
        text-transform: uppercase;
        letter-spacing: 2px;
        position: relative;
        padding-bottom: 15px;
    }

    .cart-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 80px;
        height: 3px;
        background: linear-gradient(90deg, #C5A059, transparent);
    }

    .cart-table-wrapper {
        background: #1a1a1a;
        border: 1px solid #333;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    .cart-table {
        width: 100%;
        margin: 0;
        color: #e0e0e0;
    }

    .cart-table thead tr {
        background: linear-gradient(135deg, #C5A059, #a08045);
        color: #000;
    }

    .cart-table thead th {
        padding: 15px 20px;
        font-weight: 700;
        font-size: 0.95rem;
        letter-spacing: 0.5px;
        border: none;
    }

    .cart-table tbody tr {
        border-bottom: 1px solid #2a2a2a;
        transition: background 0.2s;
    }

    .cart-table tbody tr:hover {
        background: rgba(197, 160, 89, 0.05);
    }

    .cart-table tbody tr:last-child {
        border-bottom: none;
    }

    .cart-table tbody td {
        padding: 18px 20px;
        vertical-align: middle;
        border: none;
        color: #e0e0e0;
    }

    .product-img {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border-radius: 10px;
        border: 2px solid #333;
    }

    .product-name {
        font-weight: 600;
        color: #e0e0e0;
        font-size: 0.95rem;
    }

    .size-badge {
        display: inline-block;
        padding: 3px 10px;
        background: rgba(197, 160, 89, 0.15);
        border: 1px solid #C5A059;
        border-radius: 20px;
        color: #C5A059;
        font-size: 0.8rem;
        font-weight: 600;
        margin-top: 5px;
    }

    .qty-control {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .qty-btn {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        border: 2px solid #C5A059;
        background: transparent;
        color: #C5A059;
        font-size: 1.1rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        text-decoration: none;
        transition: all 0.2s;
        line-height: 1;
    }

    .qty-btn:hover {
        background: #C5A059;
        color: #000;
    }

    .qty-num {
        font-weight: 700;
        font-size: 1.1rem;
        color: #e0e0e0;
        min-width: 20px;
        text-align: center;
    }

    .price-col {
        color: #C5A059;
        font-weight: 700;
        font-size: 1rem;
    }

    .remove-btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        border: 2px solid #ff4444;
        color: #ff4444;
        background: transparent;
        text-decoration: none;
        transition: all 0.2s;
        font-size: 1rem;
    }

    .remove-btn:hover {
        background: #ff4444;
        color: #fff;
    }

    .cart-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 20px;
        border-top: 1px solid #2a2a2a;
        background: #1a1a1a;
    }

    .btn-continue {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 24px;
        border-radius: 25px;
        border: 2px solid #C5A059;
        color: #C5A059;
        background: transparent;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        transition: all 0.3s;
    }

    .btn-continue:hover {
        background: #C5A059;
        color: #000;
    }

    /* SUMMARY CARD */
    .summary-card {
        background: #1a1a1a;
        border: 1px solid #333;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
        position: sticky;
        top: 20px;
    }

    .summary-header {
        background: linear-gradient(135deg, #C5A059, #a08045);
        color: #000;
        padding: 18px 25px;
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        font-size: 1.1rem;
        letter-spacing: 1px;
        text-transform: uppercase;
    }

    .summary-body {
        padding: 25px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #2a2a2a;
        color: #aaa;
        font-size: 0.95rem;
    }

    .summary-row:last-of-type {
        border-bottom: none;
    }

    .summary-row span:last-child {
        font-weight: 700;
        color: #e0e0e0;
    }

    .summary-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 18px 0 0;
        margin-top: 10px;
        border-top: 2px solid #C5A059;
    }

    .summary-total span:first-child {
        color: #C5A059;
        font-weight: 700;
        font-size: 1.1rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .summary-total span:last-child {
        color: #C5A059;
        font-weight: 700;
        font-size: 1.3rem;
    }

    .btn-checkout {
        display: block;
        width: 100%;
        padding: 15px;
        margin-top: 20px;
        background: linear-gradient(135deg, #C5A059, #a08045);
        color: #000;
        border: none;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1rem;
        letter-spacing: 1px;
        text-decoration: none;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 8px 25px rgba(197, 160, 89, 0.4);
    }

    .btn-checkout:hover {
        background: linear-gradient(135deg, #d4b36a, #C5A059);
        transform: translateY(-2px);
        box-shadow: 0 12px 35px rgba(197, 160, 89, 0.6);
        color: #000;
    }

    .empty-cart {
        text-align: center;
        padding: 80px 20px;
        color: #555;
    }

    .empty-cart i {
        font-size: 5rem;
        color: #333;
        margin-bottom: 20px;
        display: block;
    }

    .empty-cart p {
        font-size: 1.2rem;
        margin-bottom: 25px;
    }
</style>

<div class="cart-container container">
    <h2 class="cart-title"><i class="bi bi-cart3"></i> Giỏ Hàng</h2>

    @if(session('success'))
    <div class="alert" style="background:rgba(197,160,89,0.15); border:1px solid #C5A059; color:#C5A059; border-radius:10px; padding:12px 20px; margin-bottom:20px;">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
    </div>
    @endif

    @if(empty($gioHang))
    <div class="empty-cart">
        <i class="bi bi-cart-x"></i>
        <p>Giỏ hàng của bạn đang trống.</p>
        <a href="{{ route('sanpham.index') }}" class="btn-continue">
            <i class="bi bi-arrow-left"></i> Tiếp tục mua sắm
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
                            <th class="text-center">Giá</th>
                            <th class="text-center">Số lượng</th>
                            <th class="text-end">Thành tiền</th>
                            <th class="text-center">Xóa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($gioHang as $key => $item)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ asset('images/' . $item['hinh_anh']) }}"
                                        class="product-img" alt="{{ $item['ten_sp'] }}" />
                                    <div>
                                        <div class="product-name">{{ $item['ten_sp'] }}</div>
                                        <span class="size-badge">{{ $item['size'] }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center price-col">
                                {{ number_format($item['gia'], 0, ',', '.') }} VNĐ
                            </td>
                            <td class="text-center">
                                <div class="qty-control">
                                    <a href="{{ route('giohang.update', ['key' => $key, 'type' => -1]) }}"
                                        class="qty-btn">−</a>
                                    <span class="qty-num">{{ $item['so_luong'] }}</span>
                                    <a href="{{ route('giohang.update', ['key' => $key, 'type' => 1]) }}"
                                        class="qty-btn">+</a>
                                </div>
                            </td>
                            <td class="text-end price-col">
                                {{ number_format($item['gia'] * $item['so_luong'], 0, ',', '.') }} VNĐ
                            </td>
                            <td class="text-center">
                                <a href="{{ route('giohang.remove', ['key' => $key]) }}"
                                    class="remove-btn"
                                    onclick="return confirm('Xóa sản phẩm này?')">
                                    <i class="bi bi-trash3"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="cart-footer">
                    <a href="{{ route('sanpham.index') }}" class="btn-continue">
                        <i class="bi bi-arrow-left"></i> Tiếp tục mua sắm
                    </a>
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
                        <span>30.000 VNĐ</span>
                    </div>
                    <div class="summary-total">
                        <span>Thanh toán:</span>
                        <span>{{ number_format($tongTien + 30000, 0, ',', '.') }} VNĐ</span>
                    </div>
                    <a href="#" class="btn-checkout" id="btnThanhToan">
                        <i class="bi bi-bag-check-fill me-2"></i> Tiến Hành Thanh Toán
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- MODAL THANH TOÁN THÀNH CÔNG --}}
<div class="modal fade" id="modalThanhToan" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background:#1a1a1a; border:2px solid #C5A059; border-radius:16px;">
            <div class="modal-body text-center" style="padding:40px;">
                <i class="bi bi-check-circle-fill" style="font-size:4rem; color:#C5A059; display:block; margin-bottom:20px;"></i>
                <h4 style="color:#C5A059; font-family:'Playfair Display',serif; margin-bottom:15px;">Thanh Toán Thành Công!</h4>
                <p style="color:#aaa; margin-bottom:25px;">Cảm ơn bạn đã mua hàng tại Kingsman. Chúng tôi sẽ liên hệ xác nhận đơn hàng sớm nhất.</p>

                <div style="background:#111; border-radius:10px; padding:20px; margin-bottom:25px; text-align:left;">
                    @foreach($gioHang as $item)
                    <div style="display:flex; align-items:center; gap:12px; padding:10px 0; border-bottom:1px solid #2a2a2a;">
                        <img src="{{ asset('images/' . $item['hinh_anh']) }}"
                            style="width:50px; height:50px; object-fit:cover; border-radius:8px;" />
                        <div style="flex:1;">
                            <div style="color:#e0e0e0; font-weight:600; font-size:0.9rem;">{{ $item['ten_sp'] }}</div>
                            <div style="color:#888; font-size:0.8rem;">Size: {{ $item['size'] }} × {{ $item['so_luong'] }}</div>
                        </div>
                        <div style="color:#C5A059; font-weight:700; font-size:0.9rem;">
                            {{ number_format($item['gia'] * $item['so_luong'], 0, ',', '.') }} VNĐ
                        </div>
                    </div>
                    @endforeach
                    <div style="display:flex; justify-content:space-between; padding-top:15px;">
                        <span style="color:#aaa;">Tổng cộng:</span>
                        <span style="color:#C5A059; font-weight:700; font-size:1.1rem;">
                            {{ number_format($tongTien + 30000, 0, ',', '.') }} VNĐ
                        </span>
                    </div>
                </div>

                <a href="{{ route('sanpham.index') }}" class="btn-checkout" style="display:inline-block; width:auto; padding:12px 35px;">
                    <i class="bi bi-bag me-2"></i> Tiếp tục mua sắm
                </a>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('modalThanhToan');
        if (modal && modal.dataset.autoShow === 'true') {
            new bootstrap.Modal(modal).show();
        }
    });
    document.getElementById('btnThanhToan').addEventListener('click', function() {
        alert('Thanh toán thành công! Cảm ơn bạn đã mua hàng tại Kingsman.');
        window.location.href = "{{ route('giohang.checkout') }}";
    });
</script>
@endsection