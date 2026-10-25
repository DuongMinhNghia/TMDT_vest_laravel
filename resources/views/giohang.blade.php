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
        padding: 22px;
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

    @media (max-width: 768px) {
        .cart-table thead {
            display: none;
        }
        .cart-table, .cart-table tbody, .cart-table tr, .cart-table td {
            display: block;
            width: 100%;
        }
        .cart-table tbody tr {
            padding: 14px 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .cart-table tbody td {
            padding: 6px 10px;
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

    @if(session('success'))
    <div class="alert mb-4" style="background:rgba(16,185,129,0.12); border:1px solid rgba(16,185,129,0.3); color:#6EE7B7; border-radius:10px; padding:12px 20px;">
        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="alert mb-4" style="background:rgba(239,68,68,0.12); border:1px solid rgba(239,68,68,0.3); color:#FCA5A5; border-radius:10px; padding:12px 20px;">
        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
    </div>
    @endif
    @if($errors->any())
    <div class="alert mb-4" style="background:rgba(239,68,68,0.12); border:1px solid rgba(239,68,68,0.3); color:#FCA5A5; border-radius:10px; padding:12px 20px;">
        @foreach($errors->all() as $e)<div><i class="bi bi-x-circle me-1"></i> {{ $e }}</div>@endforeach
    </div>
    @endif

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
                                <div style="color:#64748B; text-decoration:line-through; font-size:0.78rem; font-weight:400;">{{ number_format($item['gia_goc'], 0, ',', '.') }} VNĐ</div>
                                <span style="color:#EF4444; font-size:0.75rem; font-weight:600;">-{{ $item['sale'] }}%</span>
                                @endif
                                <div>{{ number_format($item['gia'], 0, ',', '.') }} VNĐ</div>
                                @if($item['so_luong'] > $item['ton'])
                                <div style="color:#EF4444; font-size:0.75rem; font-weight:500;">Chỉ còn {{ $item['ton'] }} sản phẩm</div>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="qty-control">
                                    <a href="{{ route('giohang.update', ['key' => $key, 'type' => -1]) }}"
                                        class="qty-btn" title="Giảm">−</a>
                                    <span class="qty-num">{{ $item['so_luong'] }}</span>
                                    <a href="{{ route('giohang.update', ['key' => $key, 'type' => 1]) }}"
                                        class="qty-btn" title="Tăng">+</a>
                                </div>
                            </td>
                            <td class="text-end price-col">
                                {{ number_format($item['gia'] * $item['so_luong'], 0, ',', '.') }} VNĐ
                            </td>
                            <td class="text-center">
                                <a href="{{ route('giohang.remove', ['key' => $key]) }}"
                                    class="remove-btn"
                                    onclick="return confirm('Xác nhận xóa sản phẩm này khỏi giỏ hàng?')"
                                    title="Xóa">
                                    <i class="bi bi-trash3"></i>
                                </a>
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

                    <form method="POST" action="{{ route('giohang.checkout') }}" style="margin-top:16px;">
                        @csrf
                        <label class="form-label-summary">Địa chỉ giao hàng *</label>
                        <textarea name="dia_chi" rows="2" required class="form-input-summary" placeholder="Số nhà, đường, phường/xã, quận/huyện...">{{ old('dia_chi', $diaChiMacDinh) }}</textarea>

                        <label class="form-label-summary">Phương thức thanh toán *</label>
                        <select name="phuong_thuc" class="form-input-summary">
                            <option value="COD">Thanh toán khi nhận hàng (COD)</option>
                            <option value="Chuyển khoản" {{ old('phuong_thuc') == 'Chuyển khoản' ? 'selected' : '' }}>Chuyển khoản ngân hàng</option>
                        </select>

                        <label class="form-label-summary">Ghi chú giao hàng (nếu có)</label>
                        <input type="text" name="ghi_chu" value="{{ old('ghi_chu') }}" maxlength="500" class="form-input-summary" placeholder="VD: Giao giờ hành chính, gọi trước...">

                        <button type="submit" class="btn-checkout">
                            <i class="bi bi-bag-check-fill me-2"></i> Hoàn Tất Đặt Hàng
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
