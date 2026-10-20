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

    @if(session('error'))
    <div class="alert" style="background:rgba(255,68,68,0.12); border:1px solid #ff4444; color:#ff6b6b; border-radius:10px; padding:12px 20px; margin-bottom:20px;">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
    </div>
    @endif
    @if($errors->any())
    <div class="alert" style="background:rgba(255,68,68,0.12); border:1px solid #ff4444; color:#ff6b6b; border-radius:10px; padding:12px 20px; margin-bottom:20px;">
        @foreach($errors->all() as $e)<div>{{ $e }}</div>@endforeach
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
                                @if($item['sale'] > 0)
                                <div style="color:#777; text-decoration:line-through; font-size:0.8rem; font-weight:400;">{{ number_format($item['gia_goc'], 0, ',', '.') }} VNĐ</div>
                                <span style="color:#e74c3c; font-size:0.75rem;">-{{ $item['sale'] }}%</span>
                                @endif
                                {{ number_format($item['gia'], 0, ',', '.') }} VNĐ
                                @if($item['so_luong'] > $item['ton'])
                                <div style="color:#ff4444; font-size:0.75rem; font-weight:400;">Chỉ còn {{ $item['ton'] }} sản phẩm</div>
                                @endif
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
                        <span>{{ number_format($tien['phi_ship'], 0, ',', '.') }} VNĐ</span>
                    </div>
                    @if($tien['phan_tram_hang'] > 0)
                    <div class="summary-row">
                        <span>Ưu đãi hạng {{ $tenHang }} (-{{ $tien['phan_tram_hang'] }}%):</span>
                        <span style="color:#2ecc71;">- {{ number_format($tien['giam_hang'], 0, ',', '.') }} VNĐ</span>
                    </div>
                    @endif
                    <div class="summary-total">
                        <span>Thanh toán:</span>
                        <span>{{ number_format($tien['thanh_toan'], 0, ',', '.') }} VNĐ</span>
                    </div>

                    <form method="POST" action="{{ route('giohang.checkout') }}" style="margin-top:20px;">
                        @csrf
                        <label style="color:#aaa; font-size:0.85rem;">Địa chỉ giao hàng</label>
                        <textarea name="dia_chi" rows="2" required
                            style="width:100%; background:#111; border:1px solid #333; color:#e0e0e0; border-radius:8px; padding:8px 10px; margin:6px 0 12px;">{{ old('dia_chi', $diaChiMacDinh) }}</textarea>

                        <label style="color:#aaa; font-size:0.85rem;">Phương thức thanh toán</label>
                        <select name="phuong_thuc"
                            style="width:100%; background:#111; border:1px solid #333; color:#e0e0e0; border-radius:8px; padding:8px 10px; margin:6px 0 12px;">
                            <option value="COD">Thanh toán khi nhận hàng (COD)</option>
                            <option value="Chuyển khoản" {{ old('phuong_thuc') == 'Chuyển khoản' ? 'selected' : '' }}>Chuyển khoản</option>
                        </select>

                        <label style="color:#aaa; font-size:0.85rem;">Ghi chú (không bắt buộc)</label>
                        <input type="text" name="ghi_chu" value="{{ old('ghi_chu') }}" maxlength="500"
                            style="width:100%; background:#111; border:1px solid #333; color:#e0e0e0; border-radius:8px; padding:8px 10px; margin:6px 0 0;">

                        <button type="submit" class="btn-checkout">
                            <i class="bi bi-bag-check-fill me-2"></i> Đặt Hàng
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection
