@extends('layouts.default_layout')

@section('title', $sanPham->ten_sp . ' - Kingsman Vietnam')

@section('content')
<style>
    .product-detail-container {
        background: #111317;
        min-height: 600px;
        padding: 50px 0 80px;
        color: #F1F5F9;
    }

    .product-image-wrapper {
        position: relative;
        overflow: hidden;
        border-radius: 16px;
        box-shadow: 0 16px 45px rgba(0, 0, 0, 0.45);
        border: 1px solid rgba(255, 255, 255, 0.08);
        background: #141720;
    }

    .product-image-wrapper img {
        width: 100%;
        height: 520px;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .product-image-wrapper:hover img {
        transform: scale(1.04);
    }

    .product-info-section {
        padding: 36px 42px;
        background: #141720;
        border-radius: 16px;
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.4);
    }

    .product-title {
        font-family: 'Playfair Display', Georgia, serif;
        color: #D4B26F;
        font-size: 2.1rem;
        font-weight: 700;
        margin-bottom: 20px;
        letter-spacing: 0.5px;
        position: relative;
        padding-bottom: 14px;
    }

    .product-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 60px;
        height: 2px;
        background: linear-gradient(90deg, #D4B26F, transparent);
    }

    .product-price-box {
        font-family: 'Plus Jakarta Sans', Arial, sans-serif;
        font-size: 1.8rem;
        color: #D4B26F;
        font-weight: 700;
        margin: 20px 0 25px;
        padding: 14px 22px;
        background: #181B24;
        border: 1px solid rgba(212, 178, 111, 0.25);
        border-radius: 10px;
        display: flex;
        align-items: center;
        gap: 16px;
        flex-wrap: wrap;
        font-variant-numeric: tabular-nums;
        line-height: 1.25;
    }

    .flash-sale-badge {
        font-size: 0.8rem;
        color: #EF4444;
        background: rgba(239, 68, 68, 0.12);
        border: 1px solid rgba(239, 68, 68, 0.35);
        padding: 4px 10px;
        border-radius: 6px;
        font-weight: 700;
    }

    .product-detail-item {
        margin: 14px 0;
        padding: 12px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        display: flex;
        align-items: flex-start;
    }

    .product-detail-item:last-of-type {
        border-bottom: none;
    }

    .detail-label {
        color: #D4B26F;
        font-size: 0.92rem;
        font-weight: 600;
        min-width: 120px;
        margin-right: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .detail-value {
        color: #CBD5E1;
        font-size: 0.92rem;
        line-height: 1.6;
        flex: 1;
    }

    .size-selection {
        margin: 26px 0;
        padding-top: 20px;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
    }

    .size-label {
        color: #D4B26F;
        font-size: 1.05rem;
        font-weight: 600;
        margin: 0;
    }

    .size-options {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        margin-top: 14px;
        margin-bottom: 20px;
    }

    .size-option {
        position: relative;
    }

    .size-option input[type="radio"] {
        display: none;
    }

    .size-option label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        width: 64px;
        height: 64px;
        background: #181B24;
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 10px;
        color: #CBD5E1;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.25s ease;
        position: relative;
    }

    .size-option label:hover {
        border-color: #D4B26F;
        background: rgba(212, 178, 111, 0.08);
        transform: translateY(-2px);
    }

    .size-option input[type="radio"]:checked+label {
        background: linear-gradient(135deg, #D4B26F, #B89246);
        border-color: #D4B26F;
        color: #0c0e12;
        font-weight: 700;
        box-shadow: 0 6px 18px rgba(212, 178, 111, 0.35);
        transform: translateY(-2px);
    }

    .size-option input[type="radio"]:checked+label span {
        color: #0c0e12 !important;
    }

    .custom-size-container {
        display: none;
        margin-top: 18px;
        padding: 22px;
        background: #181B24;
        border: 1px solid rgba(212, 178, 111, 0.4);
        border-radius: 12px;
        animation: fadeIn 0.3s ease;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .custom-size-container.active {
        display: block;
    }

    .custom-measurements {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 14px;
    }

    .measurement-input label {
        display: block;
        color: #CBD5E1;
        font-size: 0.82rem;
        margin-bottom: 6px;
        font-weight: 600;
    }

    .measurement-input input {
        width: 100%;
        padding: 10px 14px;
        background: #111317;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        color: #F1F5F9;
        font-size: 0.92rem;
        transition: all 0.25s;
    }

    .measurement-input input:focus {
        outline: none;
        border-color: #D4B26F;
        box-shadow: 0 0 0 3px rgba(212, 178, 111, 0.2);
    }

    .measurement-guide {
        margin-top: 14px;
        padding: 12px 16px;
        background: rgba(212, 178, 111, 0.08);
        border-radius: 8px;
        border-left: 3px solid #D4B26F;
    }

    .measurement-guide p {
        color: #94A3B8;
        font-size: 0.82rem;
        margin: 4px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .action-buttons {
        display: flex;
        gap: 12px;
        margin-top: 30px;
        flex-wrap: wrap;
    }

    .btn-luxury {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 13px 26px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.92rem;
        letter-spacing: 0.3px;
        text-decoration: none;
        transition: all 0.25s ease;
        cursor: pointer;
        border: none;
    }

    .btn-add-cart {
        background: linear-gradient(135deg, #D4B26F, #B89246);
        color: #0c0e12;
    }

    .btn-add-cart:hover {
        background: linear-gradient(135deg, #E8CA8C, #C9A458);
        color: #000;
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(212, 178, 111, 0.35);
    }

    .btn-appointment {
        background: #181B24;
        border: 1px solid rgba(212, 178, 111, 0.4);
        color: #D4B26F;
    }

    .btn-appointment:hover {
        background: rgba(212, 178, 111, 0.15);
        color: #E8CA8C;
        border-color: #D4B26F;
        transform: translateY(-2px);
    }

    .btn-back {
        background: transparent;
        color: #94A3B8;
        border: 1px solid rgba(255, 255, 255, 0.12);
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.06);
        color: #F1F5F9;
        border-color: rgba(255, 255, 255, 0.2);
    }

    .related-products {
        padding: 60px 0;
        background: #111317;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
    }

    .related-products h4 {
        font-family: 'Playfair Display', Georgia, serif;
        color: #D4B26F;
        font-size: 1.8rem;
        font-weight: 700;
        margin-bottom: 35px;
        text-align: center;
        letter-spacing: 1px;
        position: relative;
        padding-bottom: 16px;
    }

    .related-products h4::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 2px;
        background: linear-gradient(90deg, transparent, #D4B26F, transparent);
    }

    @media (max-width: 991.98px) {
        .product-detail-container {
            padding: 20px 0 50px;
        }

        .product-image-wrapper img {
            height: 380px;
            object-fit: cover;
        }

        .product-info-section {
            margin-top: 18px;
            padding: 24px 20px;
        }

        .product-title {
            font-size: 1.6rem;
            margin-bottom: 14px;
        }

        .product-price-box {
            font-size: 1.4rem;
            padding: 12px 18px;
            margin: 15px 0 20px;
            gap: 12px;
        }

        .custom-measurements {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
            gap: 10px;
            margin-top: 20px;
        }

        .btn-luxury {
            width: 100%;
            justify-content: center;
            padding: 12px 20px;
        }
    }

    @media (max-width: 575.98px) {
        .product-detail-container {
            padding: 12px 0 35px;
        }

        .product-image-wrapper {
            border-radius: 12px;
        }

        .product-image-wrapper img {
            height: 270px;
            object-fit: cover;
        }

        .product-info-section {
            padding: 18px 14px;
            border-radius: 12px;
            margin-top: 14px;
        }

        .product-title {
            font-size: 1.35rem;
            margin-bottom: 10px;
            padding-bottom: 8px;
        }

        .product-price-box {
            font-size: 1.2rem;
            padding: 10px 12px;
            flex-direction: column;
            align-items: flex-start;
            gap: 6px;
        }

        .detail-label {
            min-width: 90px;
            font-size: 0.82rem;
        }

        .detail-value {
            font-size: 0.82rem;
        }

        .size-selection {
            margin: 16px 0;
            padding-top: 12px;
        }

        .size-options {
            gap: 8px;
            margin-top: 10px;
            margin-bottom: 14px;
        }

        .size-option label {
            padding: 7px 14px;
            font-size: 0.82rem;
        }

        .measurement-input input {
            padding: 8px 10px;
            font-size: 0.85rem;
        }

        .btn-luxury {
            font-size: 0.85rem;
            padding: 11px 16px;
        }
    }
</style>

<div class="product-detail-container">
    <div class="container">
        <div class="row g-4 align-items-start">

            {{-- ẢNH SẢN PHẨM --}}
            <div class="col-lg-5">
                <div class="product-image-wrapper">
                    <img src="{{ asset('images/' . $sanPham->hinh_anh) }}" alt="{{ $sanPham->ten_sp }}" />
                </div>
            </div>

            {{-- THÔNG TIN SẢN PHẨM --}}
            <div class="col-lg-7">
                <div class="product-info-section">
                    <h1 class="product-title">{{ $sanPham->ten_sp }}</h1>

                    <div class="product-price-box">
                        <div>
                            <i class="bi bi-tag-fill me-1"></i>
                            <span id="vnd_price" data-val="{{ $sanPham->gia_khuyen_mai }}">
                                {{ number_format($sanPham->gia_khuyen_mai, 0, ',', '.') }}
                            </span> VNĐ
                        </div>

                        @if($sanPham->phan_tram_giam > 0)
                        <div class="flash-sale-badge">
                            -{{ $sanPham->phan_tram_giam }}% FLASH SALE
                        </div>
                        <div style="font-size: 1.05rem; color: #64748B; text-decoration: line-through; font-weight: 500;">
                            {{ number_format($sanPham->gia_ban, 0, ',', '.') }} VNĐ
                        </div>
                        @endif

                        <div style="font-size: 1.05rem; color: #94A3B8; font-weight: 500; border-left: 1px solid rgba(255,255,255,0.1); padding-left: 14px; display: flex; align-items: center; gap: 5px;">
                            <i class="bi bi-arrow-left-right" style="font-size: 0.9rem;"></i>
                            <span id="usd_price">Đang tính...</span> USD
                        </div>
                    </div>

                    @php
                        $activeSale = $sanPham->giamGias()->dangChay()->first() ?? \App\Models\GiamGia::dangChay()->where('ap_dung_tat_ca', true)->first();
                    @endphp
                    @if($activeSale)
                    <div class="flash-sale-timer-box" style="margin: 15px 0 25px; padding: 14px 20px; background: #181B24; border: 1px solid rgba(239, 68, 68, 0.4); border-radius: 10px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                        <div style="display: flex; align-items: center; gap: 8px; color: #EF4444; font-weight: 700; font-size: 0.92rem;">
                            <i class="bi bi-fire" style="font-size: 1.25rem;"></i>
                            <span>{{ $activeSale->tieu_de }}:</span>
                        </div>
                        <div id="flash-sale-countdown" data-end="{{ $activeSale->thoi_gian_ket_thuc ? $activeSale->thoi_gian_ket_thuc->timestamp : 0 }}" style="display: flex; gap: 6px; color: #fff; font-size: 0.85rem; font-weight: 700;">
                            <span style="background: #EF4444; padding: 4px 8px; border-radius: 6px;" id="fs-days">00</span> <span style="color: #94A3B8; align-self: center;">ngày</span>
                            <span style="background: #EF4444; padding: 4px 8px; border-radius: 6px;" id="fs-hours">00</span> <span style="color: #94A3B8; align-self: center;">giờ</span>
                            <span style="background: #EF4444; padding: 4px 8px; border-radius: 6px;" id="fs-mins">00</span> <span style="color: #94A3B8; align-self: center;">phút</span>
                            <span style="background: #EF4444; padding: 4px 8px; border-radius: 6px;" id="fs-secs">00</span> <span style="color: #94A3B8; align-self: center;">giây</span>
                        </div>
                    </div>
                    @endif

                    <div class="product-detail-item">
                        <span class="detail-label"><i class="bi bi-globe"></i> Xuất xứ:</span>
                        <span class="detail-value">{{ $sanPham->xuat_xu ?? 'Chính hãng Kingsman' }}</span>
                    </div>

                    <div class="product-detail-item">
                        <span class="detail-label"><i class="bi bi-stars"></i> Chất liệu:</span>
                        <span class="detail-value">{{ $sanPham->chat_lieu ?? 'Vải len cừu Ý cao cấp' }}</span>
                    </div>

                    <div class="product-detail-item">
                        <span class="detail-label"><i class="bi bi-person-badge"></i> Form dáng:</span>
                        <span class="detail-value">{{ $sanPham->form_dang ?? 'Slim-fit chuẩn quý ông' }}</span>
                    </div>

                    <div class="product-detail-item">
                        <span class="detail-label"><i class="bi bi-card-text"></i> Mô tả:</span>
                        <span class="detail-value">{{ $sanPham->mo_ta }}</span>
                    </div>

                    {{-- CHỌN SIZE --}}
                    <div class="size-selection">
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px; margin-bottom: 8px;">
                            <label class="size-label">
                                <i class="bi bi-rulers me-1"></i> Chọn Kích Thước (Tổng kho: <strong style="color:#D4B26F;">{{ $sanPham->tongTonKho() }}</strong> chiếc)
                            </label>
                            <button type="button" onclick="openSizeGuideModal()" style="background: transparent; border: none; color: #D4B26F; font-size: 0.84rem; font-weight: 600; cursor: pointer; text-decoration: underline; display: inline-flex; align-items: center; gap: 5px;">
                                <i class="bi bi-info-circle-fill"></i> Bảng gợi ý chọn size
                            </button>
                        </div>
                        <div class="size-options">
                            @php $firstAvailable = false; @endphp
                            @forelse($sanPham->sizes as $s)
                                @php
                                    $isAvail = $s->so_luong_ton > 0;
                                    $checkAttr = '';
                                    if ($isAvail && !$firstAvailable) {
                                        $checkAttr = 'checked';
                                        $firstAvailable = true;
                                    }
                                @endphp
                                <div class="size-option" style="{{ !$isAvail ? 'opacity: 0.4;' : '' }}">
                                    <input type="radio" name="size" id="size-{{ strtolower($s->ten_size) }}" value="{{ $s->ten_size }}" {{ $checkAttr }} {{ !$isAvail ? 'disabled' : '' }} />
                                    <label for="size-{{ strtolower($s->ten_size) }}" title="{{ $s->chieu_cao_goi_y }} | {{ $s->can_nang_goi_y }}" style="{{ !$isAvail ? 'cursor: not-allowed; text-decoration: line-through;' : '' }}">
                                        {{ $s->ten_size }}
                                        @if(!$isAvail)
                                            <span style="font-size: 0.65rem; display: block; color: #EF4444;">Hết</span>
                                        @else
                                            <span style="font-size: 0.65rem; display: block; color: #94A3B8;">({{ $s->so_luong_ton }})</span>
                                        @endif
                                    </label>
                                </div>
                            @empty
                                <div class="size-option">
                                    <input type="radio" name="size" id="size-s" value="S" checked />
                                    <label for="size-s">S</label>
                                </div>
                                <div class="size-option">
                                    <input type="radio" name="size" id="size-m" value="M" />
                                    <label for="size-m">M</label>
                                </div>
                                <div class="size-option">
                                    <input type="radio" name="size" id="size-l" value="L" />
                                    <label for="size-l">L</label>
                                </div>
                            @endforelse

                            <div class="size-option">
                                <input type="radio" name="size" id="size-custom" value="Custom" />
                                <label for="size-custom" title="Đặt may đo theo số đo riêng"><i class="bi bi-pencil-square"></i><span style="font-size:0.65rem; color:#94A3B8;">Đo riêng</span></label>
                            </div>
                        </div>

                        {{-- CUSTOM SIZE --}}
                        @php
                            $userSoDo = Auth::check() ? optional(Auth::user()->khachHang)->soDoMacDinh() : null;
                        @endphp
                        <div class="custom-size-container" id="customSizeContainer">
                            @if($userSoDo)
                            <div class="mb-3 p-2 rounded" style="background: rgba(212,178,111,0.12); border: 1px dashed #D4B26F; font-size: 0.84rem; color: #D4B26F;">
                                <i class="bi bi-magic me-1"></i> Đã tự động điền thông số từ hồ sơ: <strong>{{ $userSoDo->ten_ho_so }}</strong> của bạn.
                            </div>
                            @endif
                            <div class="custom-measurements">
                                <div class="measurement-input">
                                    <label><i class="bi bi-arrows-expand"></i> Rộng vai (cm)</label>
                                    <input type="number" id="shoulder" placeholder="VD: 45" min="0" step="0.5" value="{{ $userSoDo?->rong_vai }}" />
                                </div>
                                <div class="measurement-input">
                                    <label><i class="bi bi-arrows-expand"></i> Vòng ngực (cm)</label>
                                    <input type="number" id="chest" placeholder="VD: 96" min="0" step="0.5" value="{{ $userSoDo?->vong_nguc }}" />
                                </div>
                                <div class="measurement-input">
                                    <label><i class="bi bi-arrows-expand"></i> Vòng eo (cm)</label>
                                    <input type="number" id="waist" placeholder="VD: 84" min="0" step="0.5" value="{{ $userSoDo?->vong_eo }}" />
                                </div>
                                <div class="measurement-input">
                                    <label><i class="bi bi-arrows-vertical"></i> Dài áo (cm)</label>
                                    <input type="number" id="length" placeholder="VD: 75" min="0" step="0.5" value="{{ $userSoDo?->dai_ao_vest }}" />
                                </div>
                            </div>
                            <div class="measurement-guide">
                                <p><i class="bi bi-info-circle-fill" style="color:#D4B26F;"></i> Số đo riêng được xác nhận trong buổi tư vấn; sản phẩm may đo không được thêm trực tiếp vào giỏ hàng.</p>
                                @if(Auth::check() && Auth::user()->isCustomer())
                                <p><i class="bi bi-rulers" style="color:#D4B26F;"></i> Quản lý số đo tại <a href="{{ route('khachhang.dashboard') }}#sodo" style="color:#D4B26F; text-decoration:underline;">Hồ Sơ & Thẻ VIP</a> hoặc đặt lịch tại showroom.</p>
                                @else
                                <p><i class="bi bi-person-lock" style="color:#D4B26F;"></i> Vui lòng <a href="{{ route('login') }}" style="color:#D4B26F; text-decoration:underline;">đăng nhập</a> để lưu số đo và đặt lịch tư vấn.</p>
                                @endif
                            </div>
                            <div id="customSizeNotice" class="mt-3 d-none" role="status" style="color:#D4B26F; font-size:0.86rem; border-left:2px solid #D4B26F; padding-left:12px;">
                                May đo riêng cần được chuyên viên xác nhận. Vui lòng dùng một trong các lựa chọn tư vấn bên dưới.
                            </div>
                        </div>
                    </div>

                    {{-- ACTION BUTTONS --}}
                    <div class="action-buttons">
                        @if(Auth::check() && Auth::user()->isCustomer())
                        <form method="POST" action="{{ route('giohang.add') }}" id="addToCartForm" class="d-inline">
                            @csrf
                            <input type="hidden" name="id" value="{{ $sanPham->id }}">
                            <input type="hidden" name="size" id="cartSize">
                            <input type="hidden" name="url" value="{{ url()->current() }}">
                            <button type="submit" class="btn-luxury btn-add-cart" id="addToCartBtn">
                            <i class="bi bi-cart-plus-fill"></i> Thêm Vào Giỏ Hàng
                            </button>
                        </form>
                        @elseif(!Auth::check())
                        <a href="{{ route('login') }}" class="btn-luxury btn-add-cart">
                            <i class="bi bi-person-lock"></i> Đăng Nhập Để Thêm Giỏ Hàng
                        </a>
                        @endif
                        @if(Auth::check() && !Auth::user()->isCustomer())
                            <a href="{{ route('lichhen.quanly') }}" class="btn-luxury btn-appointment">
                                <i class="bi bi-calendar2-check-fill"></i> Quản Lý Lịch Hẹn
                            </a>
                        @else
                            <a href="{{ route('lichhen.create', ['san_pham_id' => $sanPham->id]) }}" class="btn-luxury btn-appointment">
                                <i class="bi bi-calendar2-check-fill"></i> Đặt Lịch Thử Tại Showroom
                            </a>
                        @endif
                        <a href="{{ route('sanpham.index') }}" class="btn-luxury btn-back">
                            <i class="bi bi-arrow-left-circle-fill"></i> Quay Lại
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- ĐÁNH GIÁ SẢN PHẨM --}}
@include('partials.danhgia', ['sanPham' => $sanPham])

{{-- SẢN PHẨM LIÊN QUAN --}}
<div class="related-products">
    <div class="container">
        <h4><i class="bi bi-grid-3x3-gap-fill me-2"></i>Sản Phẩm Cùng Phong Cách</h4>
        <div class="row g-4">
            @foreach($sanPhamLienQuan as $sp)
            <div class="col-6 col-md-4 col-lg-3">
                <a href="{{ route('sanpham.chitiet', $sp->id) }}" style="text-decoration:none;">
                    <div style="background:#141720; border:1px solid rgba(255,255,255,0.08); border-radius:12px; overflow:hidden; transition:all 0.3s;"
                        onmouseover="this.style.borderColor='#D4B26F'; this.style.transform='translateY(-4px)'"
                        onmouseout="this.style.borderColor='rgba(255,255,255,0.08)'; this.style.transform='none'">
                        <img src="{{ asset('images/' . $sp->hinh_anh) }}"
                            style="width:100%; height:220px; object-fit:cover;" />
                        <div style="padding:14px 16px;">
                            <p style="color:#F1F5F9; font-weight:600; margin-bottom:6px; font-size:0.9rem; white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">{{ $sp->ten_sp }}</p>
                            <p style="color:#D4B26F; font-weight:700; margin:0; font-size:0.95rem;">{{ number_format($sp->gia, 0, ',', '.') }} VNĐ</p>
                        </div>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sizeRadios = document.querySelectorAll('input[name="size"]');
        const customSizeContainer = document.getElementById('customSizeContainer');
        const addToCartForm = document.getElementById('addToCartForm');
        const cartSize = document.getElementById('cartSize');
        const customSizeNotice = document.getElementById('customSizeNotice');

        // Toggle custom size container
        sizeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'Custom') {
                    customSizeContainer.classList.add('active');
                } else {
                    customSizeContainer.classList.remove('active');
                    customSizeNotice.classList.add('d-none');
                }
            });
        });

        // ===== GỌI API QUY ĐỔI TỶ GIÁ VNĐ -> USD =====
        const rawVndPrice = parseFloat(document.getElementById('vnd_price').getAttribute('data-val'));
        const usdPriceDisplay = document.getElementById('usd_price');

        fetch('https://api.exchangerate-api.com/v4/latest/USD')
            .then(response => response.json())
            .then(data => {
                const tyGia = data.rates.VND;
                const usdValue = (rawVndPrice / tyGia).toFixed(2);
                usdPriceDisplay.innerText = "~ " + usdValue;
            })
            .catch(error => {
                usdPriceDisplay.innerText = "Lỗi";
            });
        // =============================================

        // Thêm vào giỏ
        if (addToCartForm) addToCartForm.addEventListener('submit', function(e) {

            const selectedSize = document.querySelector('input[name="size"]:checked');

            if (!selectedSize) {
                e.preventDefault();
                alert('Vui lòng chọn kích thước trước khi thêm vào giỏ hàng!');
                return;
            }

            let sizeInfo = selectedSize.value;

            if (selectedSize.value === 'Custom') {
                e.preventDefault();
                customSizeNotice.classList.remove('d-none');
                customSizeNotice.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                return;
            }

            cartSize.value = sizeInfo;
        });
    });

    // Modal Size Guide
    function openSizeGuideModal() {
        document.getElementById('modal-size-guide').style.display = 'flex';
        document.body.style.overflow = 'hidden';
    }

    function closeSizeGuideModal() {
        document.getElementById('modal-size-guide').style.display = 'none';
        document.body.style.overflow = '';
    }

    // Flash Sale Countdown Logic
    const timerEl = document.getElementById('flash-sale-countdown');
    if (timerEl) {
        const endTime = parseInt(timerEl.getAttribute('data-end')) * 1000;
        function updateCountdown() {
            const now = new Date().getTime();
            const distance = endTime - now;

            if (distance < 0) {
                document.getElementById('fs-days').innerText = '00';
                document.getElementById('fs-hours').innerText = '00';
                document.getElementById('fs-mins').innerText = '00';
                document.getElementById('fs-secs').innerText = '00';
                return;
            }

            const days = Math.floor(distance / (1000 * 60 * 60 * 24));
            const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((distance % (1000 * 60)) / (1000 * 60));
            const seconds = Math.floor((distance % (1000 * 60)) / 1000);

            document.getElementById('fs-days').innerText = String(days).padStart(2, '0');
            document.getElementById('fs-hours').innerText = String(hours).padStart(2, '0');
            document.getElementById('fs-mins').innerText = String(minutes).padStart(2, '0');
            document.getElementById('fs-secs').innerText = String(seconds).padStart(2, '0');
        }
        updateCountdown();
        setInterval(updateCountdown, 1000);
    }
</script>

{{-- MODAL BẢNG GỢI Ý SIZE MAY ĐO --}}
<div class="sp-modal-overlay" id="modal-size-guide" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.85); z-index:1060; align-items:center; justify-content:center; padding:20px; backdrop-filter:blur(6px);">
    <div style="background:#141720; border:1px solid rgba(212,178,111,0.3); border-radius:16px; width:100%; max-width:640px; padding:28px; position:relative; box-shadow:0 20px 50px rgba(0,0,0,0.8);">
        <button onclick="closeSizeGuideModal()" style="position:absolute; top:18px; right:20px; background:none; border:none; color:#94A3B8; font-size:1.6rem; cursor:pointer;">&times;</button>
        <h4 style="font-family:'Playfair Display', serif; color:#D4B26F; margin-bottom:12px; font-weight:700; display:flex; align-items:center; gap:8px;">
            <i class="bi bi-rulers"></i> Bảng Thông Số Thể Hình Vest Nam
        </h4>
        <p style="color:#94A3B8; font-size:0.86rem; line-height:1.5;">Thông số chuẩn may đo cho phom áo vest Kingsman. Nếu chiều cao và cân nặng nằm giữa 2 size, quý khách nên chọn size lớn hơn để có độ cử động thoải mái nhất.</p>
        
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; color:#F1F5F9; font-size:0.86rem; margin-top:14px;">
                <thead>
                    <tr style="background:#181B24; color:#D4B26F; text-align:left;">
                        <th style="padding:10px 14px; border:1px solid rgba(255,255,255,0.08);">Size</th>
                        <th style="padding:10px 14px; border:1px solid rgba(255,255,255,0.08);">Chiều cao</th>
                        <th style="padding:10px 14px; border:1px solid rgba(255,255,255,0.08);">Cân nặng</th>
                        <th style="padding:10px 14px; border:1px solid rgba(255,255,255,0.08);">Vòng ngực</th>
                        <th style="padding:10px 14px; border:1px solid rgba(255,255,255,0.08);">Dài áo</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding:9px 14px; border:1px solid rgba(255,255,255,0.06); font-weight:700; color:#D4B26F;">Size S</td>
                        <td style="padding:9px 14px; border:1px solid rgba(255,255,255,0.06);">1m60 - 1m68</td>
                        <td style="padding:9px 14px; border:1px solid rgba(255,255,255,0.06);">52kg - 60kg</td>
                        <td style="padding:9px 14px; border:1px solid rgba(255,255,255,0.06);">88 - 92 cm</td>
                        <td style="padding:9px 14px; border:1px solid rgba(255,255,255,0.06);">68 cm</td>
                    </tr>
                    <tr style="background:rgba(255,255,255,0.02);">
                        <td style="padding:9px 14px; border:1px solid rgba(255,255,255,0.06); font-weight:700; color:#D4B26F;">Size M</td>
                        <td style="padding:9px 14px; border:1px solid rgba(255,255,255,0.06);">1m68 - 1m74</td>
                        <td style="padding:9px 14px; border:1px solid rgba(255,255,255,0.06);">60kg - 68kg</td>
                        <td style="padding:9px 14px; border:1px solid rgba(255,255,255,0.06);">92 - 96 cm</td>
                        <td style="padding:9px 14px; border:1px solid rgba(255,255,255,0.06);">70 cm</td>
                    </tr>
                    <tr>
                        <td style="padding:9px 14px; border:1px solid rgba(255,255,255,0.06); font-weight:700; color:#D4B26F;">Size L</td>
                        <td style="padding:9px 14px; border:1px solid rgba(255,255,255,0.06);">1m74 - 1m78</td>
                        <td style="padding:9px 14px; border:1px solid rgba(255,255,255,0.06);">68kg - 75kg</td>
                        <td style="padding:9px 14px; border:1px solid rgba(255,255,255,0.06);">96 - 100 cm</td>
                        <td style="padding:9px 14px; border:1px solid rgba(255,255,255,0.06);">72 cm</td>
                    </tr>
                    <tr style="background:rgba(255,255,255,0.02);">
                        <td style="padding:9px 14px; border:1px solid rgba(255,255,255,0.06); font-weight:700; color:#D4B26F;">Size XL</td>
                        <td style="padding:9px 14px; border:1px solid rgba(255,255,255,0.06);">1m78 - 1m83</td>
                        <td style="padding:9px 14px; border:1px solid rgba(255,255,255,0.06);">75kg - 82kg</td>
                        <td style="padding:9px 14px; border:1px solid rgba(255,255,255,0.06);">100 - 104 cm</td>
                        <td style="padding:9px 14px; border:1px solid rgba(255,255,255,0.06);">74 cm</td>
                    </tr>
                    <tr>
                        <td style="padding:9px 14px; border:1px solid rgba(255,255,255,0.06); font-weight:700; color:#D4B26F;">Size XXL</td>
                        <td style="padding:9px 14px; border:1px solid rgba(255,255,255,0.06);">1m80 - 1m88</td>
                        <td style="padding:9px 14px; border:1px solid rgba(255,255,255,0.06);">82kg - 90kg</td>
                        <td style="padding:9px 14px; border:1px solid rgba(255,255,255,0.06);">104 - 110 cm</td>
                        <td style="padding:9px 14px; border:1px solid rgba(255,255,255,0.06);">76 cm</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div style="margin-top:16px; padding:12px 16px; background:#181B24; border:1px solid rgba(212,178,111,0.3); border-radius:8px; color:#D4B26F; font-size:0.82rem;">
            <i class="bi bi-lightbulb-fill me-1"></i> <strong>Tư vấn may đo:</strong> Quý khách cũng có thể chọn mục <strong>[Đo riêng]</strong> hoặc đặt lịch hẹn để nghệ nhân may đo của Kingsman trực tiếp hỗ trợ lấy số đo chuẩn xác nhất.
        </div>
    </div>
</div>
@endsection
