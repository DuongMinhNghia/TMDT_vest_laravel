@extends('layouts.default_layout')

@section('title', $sanPham->ten_sp . ' - Kingsman Vietnam')

@section('content')
<style>
    .product-detail-container {
        background: linear-gradient(135deg, #0a0a0a 0%, #1a1a1a 100%);
        min-height: 600px;
        padding: 60px 0;
    }

    .product-image-wrapper {
        position: relative;
        overflow: hidden;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
        border: 2px solid #333;
        background: #1a1a1a;
    }

    .product-image-wrapper::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(197, 160, 89, 0.1), transparent);
        transition: left 0.7s;
    }

    .product-image-wrapper:hover::before {
        left: 100%;
    }

    .product-image-wrapper img {
        width: 100%;
        height: 500px;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .product-image-wrapper:hover img {
        transform: scale(1.05);
    }

    .product-info-section {
        padding: 40px 50px;
        background: linear-gradient(135deg, #1a1a1a 0%, #252525 100%);
        border-radius: 20px;
        border: 1px solid #333;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
    }

    .product-title {
        font-family: 'Playfair Display', serif;
        color: #C5A059;
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 25px;
        letter-spacing: 1px;
        text-transform: uppercase;
        position: relative;
        padding-bottom: 15px;
    }

    .product-title::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 80px;
        height: 3px;
        background: linear-gradient(90deg, #C5A059, transparent);
    }

    .product-price {
        font-size: 2rem;
        color: #C5A059;
        font-weight: 700;
        margin: 25px 0;
        padding: 15px 25px;
        background: rgba(197, 160, 89, 0.1);
        border-left: 4px solid #C5A059;
        border-radius: 8px;
        display: inline-block;
    }

    .product-detail-item {
        margin: 20px 0;
        padding: 15px 0;
        border-bottom: 1px solid #333;
        display: flex;
        align-items: flex-start;
    }

    .product-detail-item:last-child {
        border-bottom: none;
    }

    .detail-label {
        color: #C5A059;
        font-size: 1.1rem;
        font-weight: 600;
        min-width: 120px;
        margin-right: 15px;
    }

    .detail-value {
        color: #e0e0e0;
        font-size: 1rem;
        line-height: 1.6;
        flex: 1;
    }

    .size-selection {
        margin: 30px 0;
    }

    .size-label {
        color: #C5A059;
        font-size: 1.2rem;
        font-weight: 600;
        margin-bottom: 15px;
        display: block;
    }

    .size-options {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
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
        align-items: center;
        justify-content: center;
        width: 70px;
        height: 70px;
        background: #2a2a2a;
        border: 2px solid #444;
        border-radius: 12px;
        color: #e0e0e0;
        font-weight: 600;
        font-size: 1.1rem;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
    }

    .size-option label:hover {
        border-color: #C5A059;
        background: rgba(197, 160, 89, 0.05);
        transform: translateY(-3px);
        box-shadow: 0 5px 20px rgba(197, 160, 89, 0.3);
    }

    .size-option input[type="radio"]:checked+label {
        background: linear-gradient(135deg, #C5A059, #a08045);
        border-color: #C5A059;
        color: #000;
        font-weight: 700;
        box-shadow: 0 8px 25px rgba(197, 160, 89, 0.5);
        transform: scale(1.05);
    }

    .size-option input[type="radio"]:checked+label::after {
        content: '✓';
        position: absolute;
        top: -5px;
        right: -5px;
        background: #000;
        color: #C5A059;
        width: 24px;
        height: 24px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        font-weight: 700;
        border: 2px solid #C5A059;
    }

    .custom-size-container {
        display: none;
        margin-top: 20px;
        padding: 25px;
        background: #2a2a2a;
        border: 2px solid #C5A059;
        border-radius: 15px;
        animation: slideDown 0.4s ease;
    }

    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .custom-size-container.active {
        display: block;
    }

    .custom-size-title {
        color: #C5A059;
        font-weight: 600;
        margin-bottom: 20px;
        font-size: 1.1rem;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .custom-measurements {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }

    .measurement-input {
        position: relative;
    }

    .measurement-input label {
        display: block;
        color: #e0e0e0;
        font-size: 0.9rem;
        margin-bottom: 8px;
        font-weight: 500;
    }

    .measurement-input input[type="number"],
    .measurement-input input[type="date"] {
        width: 100%;
        padding: 12px 15px;
        background: #1a1a1a;
        border: 2px solid #444;
        border-radius: 8px;
        color: #e0e0e0;
        font-size: 1rem;
        transition: all 0.3s;
    }

    .measurement-input input:focus {
        outline: none;
        border-color: #C5A059;
        background: #222;
        box-shadow: 0 0 15px rgba(197, 160, 89, 0.2);
    }

    .measurement-input input::placeholder {
        color: #555;
    }

    .measurement-guide {
        margin-top: 15px;
        padding: 15px;
        background: rgba(197, 160, 89, 0.05);
        border-radius: 8px;
        border-left: 3px solid #C5A059;
    }

    .measurement-guide p {
        color: #aaa;
        font-size: 0.85rem;
        margin: 5px 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .measurement-guide p i {
        color: #C5A059;
    }

    .action-buttons {
        display: flex;
        gap: 15px;
        margin-top: 30px;
        flex-wrap: wrap;
    }

    .btn-luxury {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 15px 35px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1rem;
        letter-spacing: 1px;
        text-decoration: none;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        cursor: pointer;
        border: none;
    }

    .btn-add-cart {
        background: linear-gradient(135deg, #C5A059, #a08045);
        color: #000;
        box-shadow: 0 10px 30px rgba(197, 160, 89, 0.4);
    }

    .btn-add-cart:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 40px rgba(197, 160, 89, 0.6);
        background: linear-gradient(135deg, #d4b36a, #C5A059);
        color: #000;
    }

    .btn-back {
        background: transparent;
        color: #C5A059;
        border: 2px solid #C5A059;
    }

    .btn-back:hover {
        background: #C5A059;
        color: #000;
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(197, 160, 89, 0.4);
    }

    .related-products {
        margin-top: 80px;
        padding: 50px 0;
        background: #0a0a0a;
        border-top: 1px solid #333;
    }

    .related-products h4 {
        font-family: 'Playfair Display', serif;
        color: #C5A059;
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 40px;
        text-align: center;
        text-transform: uppercase;
        letter-spacing: 2px;
        position: relative;
        padding-bottom: 20px;
    }

    .related-products h4::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 100px;
        height: 3px;
        background: linear-gradient(90deg, transparent, #C5A059, transparent);
    }

    @media (max-width: 992px) {
        .product-info-section {
            margin-top: 30px;
            padding: 30px 25px;
        }

        .product-title {
            font-size: 2rem;
        }

        .custom-measurements {
            grid-template-columns: 1fr;
        }

        .action-buttons {
            flex-direction: column;
        }

        .btn-luxury {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="product-detail-container">
    <div class="container">
        <div class="row">

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

                    <div class="product-price" style="display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                        <div>
                            <i class="bi bi-tag-fill"></i>
                            <span id="vnd_price" data-val="{{ $sanPham->gia_khuyen_mai }}">
                                {{ number_format($sanPham->gia_khuyen_mai, 0, ',', '.') }}
                            </span> VNĐ
                        </div>

                        @if($sanPham->phan_tram_giam > 0)
                        <div style="font-size: 0.95rem; color: #ff4444; background: rgba(255, 68, 68, 0.15); border: 1px solid rgba(255, 68, 68, 0.4); padding: 4px 10px; border-radius: 6px; font-weight: 700;">
                            -{{ $sanPham->phan_tram_giam }}% FLASH SALE
                        </div>
                        <div style="font-size: 1.1rem; color: #777; text-decoration: line-through;">
                            {{ number_format($sanPham->gia_ban, 0, ',', '.') }} VNĐ
                        </div>
                        @endif

                        <div style="font-size: 1.2rem; color: #a0a0a0; font-weight: 500; border-left: 1px solid #555; padding-left: 15px; display: flex; align-items: center; gap: 5px;">
                            <i class="bi bi-arrow-left-right" style="font-size: 1rem;"></i>
                            <span id="usd_price">Đang tính...</span> USD
                        </div>
                    </div>
                    @php
                        $activeSale = $sanPham->giamGias()->dangChay()->first() ?? \App\Models\GiamGia::dangChay()->where('ap_dung_tat_ca', true)->first();
                    @endphp
                    @if($activeSale)
                    <div class="flash-sale-timer-box" style="margin: 15px 0 25px; padding: 14px 20px; background: linear-gradient(135deg, #241010 0%, #151515 100%); border: 1px solid rgba(231, 76, 60, 0.45); border-radius: 12px; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 12px;">
                        <div style="display: flex; align-items: center; gap: 10px; color: #e74c3c; font-weight: 700; font-size: 0.95rem;">
                            <i class="bi bi-fire" style="font-size: 1.3rem;"></i>
                            <span>{{ $activeSale->tieu_de }}:</span>
                        </div>
                        <div id="flash-sale-countdown" data-end="{{ $activeSale->thoi_gian_ket_thuc ? $activeSale->thoi_gian_ket_thuc->timestamp : 0 }}" style="display: flex; gap: 6px; color: #fff; font-size: 0.85rem; font-weight: 700;">
                            <span style="background: #e74c3c; padding: 4px 8px; border-radius: 6px;" id="fs-days">00</span> <span style="color: #aaa; align-self: center;">ngày</span>
                            <span style="background: #e74c3c; padding: 4px 8px; border-radius: 6px;" id="fs-hours">00</span> <span style="color: #aaa; align-self: center;">giờ</span>
                            <span style="background: #e74c3c; padding: 4px 8px; border-radius: 6px;" id="fs-mins">00</span> <span style="color: #aaa; align-self: center;">phút</span>
                            <span style="background: #e74c3c; padding: 4px 8px; border-radius: 6px;" id="fs-secs">00</span> <span style="color: #aaa; align-self: center;">giây</span>
                        </div>
                    </div>
                    @endif

                    <div class="product-detail-item">
                        <span class="detail-label"><i class="bi bi-globe"></i> Xuất xứ:</span>
                        <span class="detail-value">{{ $sanPham->xuat_xu ?? 'Chính hãng' }}</span>
                    </div>

                    <div class="product-detail-item">
                        <span class="detail-label"><i class="bi bi-stars"></i> Chất liệu:</span>
                        <span class="detail-value">{{ $sanPham->chat_lieu ?? 'Vải cao cấp' }}</span>
                    </div>

                    <div class="product-detail-item">
                        <span class="detail-label"><i class="bi bi-person-badge"></i> Form dáng:</span>
                        <span class="detail-value">{{ $sanPham->form_dang ?? 'Slim-fit' }}</span>
                    </div>

                    <div class="product-detail-item">
                        <span class="detail-label"><i class="bi bi-card-text"></i> Mô tả:</span>
                        <span class="detail-value">{{ $sanPham->mo_ta }}</span>
                    </div>

                    {{-- CHỌN SIZE --}}
                    <div class="size-selection">
                        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 8px; margin-bottom: 10px;">
                            <label class="size-label" style="margin: 0;">
                                <i class="bi bi-rulers"></i> Chọn Kích Thước (Tổng kho: <strong style="color:#C5A059;">{{ $sanPham->tongTonKho() }}</strong> chiếc)
                            </label>
                            <button type="button" onclick="openSizeGuideModal()" style="background: transparent; border: none; color: #C5A059; font-size: 0.85rem; font-weight: 600; cursor: pointer; text-decoration: underline; display: inline-flex; align-items: center; gap: 5px;">
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
                                <div class="size-option" style="{{ !$isAvail ? 'opacity: 0.45;' : '' }}">
                                    <input type="radio" name="size" id="size-{{ strtolower($s->ten_size) }}" value="{{ $s->ten_size }}" {{ $checkAttr }} {{ !$isAvail ? 'disabled' : '' }} />
                                    <label for="size-{{ strtolower($s->ten_size) }}" title="{{ $s->chieu_cao_goi_y }} | {{ $s->can_nang_goi_y }}" style="{{ !$isAvail ? 'cursor: not-allowed; text-decoration: line-through;' : '' }}">
                                        {{ $s->ten_size }}
                                        @if(!$isAvail)
                                            <span style="font-size: 0.65rem; display: block; color: #ff5555;">Hết</span>
                                        @else
                                            <span style="font-size: 0.65rem; display: block; color: #aaa;">({{ $s->so_luong_ton }})</span>
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
                                <label for="size-custom" title="Đặt may đo theo số đo riêng"><i class="bi bi-pencil-square"></i></label>
                            </div>
                        </div>
                        {{-- CUSTOM SIZE --}}
                        @php
                            $userSoDo = Auth::check() ? optional(Auth::user()->khachHang)->soDoMacDinh() : null;
                        @endphp
                        <div class="custom-size-container" id="customSizeContainer">
                            @if($userSoDo)
                            <div class="mb-2 p-2 rounded" style="background: rgba(212,175,55,0.1); border: 1px dashed #d4af37; font-size: 0.8rem; color: #d4af37;">
                                <i class="bi bi-magic me-1"></i> Đã tự động điền thông số từ hồ sơ: <strong>{{ $userSoDo->ten_ho_so }}</strong> của bạn.
                            </div>
                            @endif
                            <div class="custom-measurements">
                                <div class="measurement-input">
                                    <label><i class="bi bi-arrows-expand"></i> Vai (cm)</label>
                                    <input type="number" id="shoulder" placeholder="VD: 45" min="0" step="0.5" value="{{ $userSoDo?->rong_vai }}" />
                                </div>
                                <div class="measurement-input">
                                    <label><i class="bi bi-arrows-expand"></i> Ngực (cm)</label>
                                    <input type="number" id="chest" placeholder="VD: 96" min="0" step="0.5" value="{{ $userSoDo?->vong_nguc }}" />
                                </div>
                                <div class="measurement-input">
                                    <label><i class="bi bi-arrows-expand"></i> Eo (cm)</label>
                                    <input type="number" id="waist" placeholder="VD: 84" min="0" step="0.5" value="{{ $userSoDo?->vong_eo }}" />
                                </div>
                                <div class="measurement-input">
                                    <label><i class="bi bi-arrows-vertical"></i> Dài áo (cm)</label>
                                    <input type="number" id="length" placeholder="VD: 75" min="0" step="0.5" value="{{ $userSoDo?->dai_ao_vest }}" />
                                </div>
                            </div>
                            <div class="measurement-guide">
                                <p><i class="bi bi-info-circle-fill"></i> Vui lòng nhập số đo hoặc quản lý tại trang <a href="{{ route('khachhang.dashboard', ['#sodo']) }}" style="color:#d4af37; text-decoration:underline;">Hồ Sơ & VIP</a>.</p>
                                <p><i class="bi bi-telephone-fill"></i> Liên hệ hotline <strong style="color:#C5A059;">1900-xxxx</strong> để được hướng dẫn đo size chi tiết.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ACTION BUTTONS --}}
                <div class="action-buttons" style="display: flex; flex-wrap: wrap; gap: 10px;">
                    <a href="#" class="btn-luxury btn-add-cart" id="addToCartBtn">
                        <i class="bi bi-cart-plus-fill"></i> Thêm vào giỏ
                    </a>
                    <a href="{{ route('lichhen.create', ['san_pham_id' => $sanPham->id]) }}" class="btn-luxury" style="background: linear-gradient(135deg, #1f4e79, #15324e); border: 1px solid #3270a6; color: #fff;">
                        <i class="bi bi-calendar2-check-fill"></i> Đặt lịch thử tại tiệm
                    </a>
                    <a href="{{ route('sanpham.index') }}" class="btn-luxury btn-back">
                        <i class="bi bi-arrow-left-circle-fill"></i> Quay lại
                    </a>
                </div>

            </div>
        </div>
    </div>
</div>

{{-- ĐÁNH GIÁ SẢN PHẨM (Task 3.4 - TV3) --}}
@include('partials.danhgia', ['sanPham' => $sanPham])

{{-- SẢN PHẨM LIÊN QUAN --}}
<div class="related-products">
    <div class="container">
        <h4><i class="bi bi-grid-3x3-gap-fill"></i> Sản Phẩm Liên Quan</h4>
        <div class="row g-4">
            @foreach($sanPhamLienQuan as $sp)
            <div class="col-6 col-md-4 col-lg-3">
                <a href="{{ route('sanpham.chitiet', $sp->id) }}" style="text-decoration:none;">
                    <div style="background:#1a1a1a; border:1px solid #333; border-radius:12px; overflow:hidden; transition:all 0.3s;"
                        onmouseover="this.style.borderColor='#C5A059'; this.style.transform='translateY(-5px)'"
                        onmouseout="this.style.borderColor='#333'; this.style.transform='translateY(0)'">
                        <img src="{{ asset('images/' . $sp->hinh_anh) }}"
                            style="width:100%; height:200px; object-fit:cover;" />
                        <div style="padding:15px;">
                            <p style="color:#e0e0e0; font-weight:600; margin-bottom:8px; font-size:0.9rem;">{{ $sp->ten_sp }}</p>
                            <p style="color:#C5A059; font-weight:700; margin:0;">{{ number_format($sp->gia, 0, ',', '.') }} VNĐ</p>
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
        const addToCartBtn = document.getElementById('addToCartBtn');

        const addCartUrl = "{{ url('/gio-hang/them') }}?id={{ $sanPham->id }}";
        const currentUrl = window.location.href;

        // Toggle custom size container
        sizeRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                if (this.value === 'Custom') {
                    customSizeContainer.classList.add('active');
                } else {
                    customSizeContainer.classList.remove('active');
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
        addToCartBtn.addEventListener('click', function(e) {
            e.preventDefault();

            const selectedSize = document.querySelector('input[name="size"]:checked');

            if (!selectedSize) {
                alert('Vui lòng chọn kích thước trước khi thêm vào giỏ hàng!');
                return;
            }

            let sizeInfo = selectedSize.value;

            if (selectedSize.value === 'Custom') {
                const shoulder = document.getElementById('shoulder').value;
                const chest = document.getElementById('chest').value;
                const waist = document.getElementById('waist').value;
                const length = document.getElementById('length').value;

                if (!shoulder || !chest || !waist || !length) {
                    alert('Vui lòng nhập đầy đủ số đo custom!');
                    return;
                }

                sizeInfo = `Custom (Vai:${shoulder} - Ngực:${chest} - Eo:${waist} - Dài:${length})`;
            }

            window.location.href = addCartUrl +
                '&size=' + encodeURIComponent(sizeInfo) +
                '&url=' + encodeURIComponent(currentUrl);
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
            const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
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
<div class="sp-modal-overlay" id="modal-size-guide" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.85); z-index:1060; align-items:center; justify-content:center; padding:20px; backdrop-filter:blur(5px);">
    <div style="background:#181818; border:1px solid #333; border-radius:16px; width:100%; max-width:620px; padding:28px; position:relative; box-shadow:0 15px 40px rgba(0,0,0,0.8);">
        <button onclick="closeSizeGuideModal()" style="position:absolute; top:18px; right:20px; background:none; border:none; color:#888; font-size:1.6rem; cursor:pointer;">&times;</button>
        <h4 style="font-family:'Playfair Display', serif; color:#C5A059; margin-bottom:14px; display:flex; align-items:center; gap:8px;">
            <i class="bi bi-rulers"></i> Bảng Thông Số & Gợi Ý Thể Hình Vest Nam
        </h4>
        <p style="color:#aaa; font-size:0.86rem; line-height:1.5;">Thông số chuẩn may đo cho phom áo vest Kingsman. Nếu chiều cao và cân nặng nằm giữa 2 size, quý khách nên chọn size lớn hơn để mặc thoải mái.</p>
        
        <div style="overflow-x:auto;">
            <table style="width:100%; border-collapse:collapse; color:#ddd; font-size:0.86rem; margin-top:14px;">
                <thead>
                    <tr style="background:#222; color:#C5A059; text-align:left;">
                        <th style="padding:10px 14px; border:1px solid #333;">Size</th>
                        <th style="padding:10px 14px; border:1px solid #333;">Chiều cao</th>
                        <th style="padding:10px 14px; border:1px solid #333;">Cân nặng</th>
                        <th style="padding:10px 14px; border:1px solid #333;">Vòng ngực</th>
                        <th style="padding:10px 14px; border:1px solid #333;">Dài áo</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td style="padding:9px 14px; border:1px solid #282828; font-weight:700; color:#fff;">Size S</td>
                        <td style="padding:9px 14px; border:1px solid #282828;">1m60 - 1m68</td>
                        <td style="padding:9px 14px; border:1px solid #282828;">52kg - 60kg</td>
                        <td style="padding:9px 14px; border:1px solid #282828;">88 - 92 cm</td>
                        <td style="padding:9px 14px; border:1px solid #282828;">68 cm</td>
                    </tr>
                    <tr style="background:rgba(255,255,255,0.02);">
                        <td style="padding:9px 14px; border:1px solid #282828; font-weight:700; color:#fff;">Size M</td>
                        <td style="padding:9px 14px; border:1px solid #282828;">1m68 - 1m74</td>
                        <td style="padding:9px 14px; border:1px solid #282828;">60kg - 68kg</td>
                        <td style="padding:9px 14px; border:1px solid #282828;">92 - 96 cm</td>
                        <td style="padding:9px 14px; border:1px solid #282828;">70 cm</td>
                    </tr>
                    <tr>
                        <td style="padding:9px 14px; border:1px solid #282828; font-weight:700; color:#fff;">Size L</td>
                        <td style="padding:9px 14px; border:1px solid #282828;">1m74 - 1m78</td>
                        <td style="padding:9px 14px; border:1px solid #282828;">68kg - 75kg</td>
                        <td style="padding:9px 14px; border:1px solid #282828;">96 - 100 cm</td>
                        <td style="padding:9px 14px; border:1px solid #282828;">72 cm</td>
                    </tr>
                    <tr style="background:rgba(255,255,255,0.02);">
                        <td style="padding:9px 14px; border:1px solid #282828; font-weight:700; color:#fff;">Size XL</td>
                        <td style="padding:9px 14px; border:1px solid #282828;">1m78 - 1m83</td>
                        <td style="padding:9px 14px; border:1px solid #282828;">75kg - 82kg</td>
                        <td style="padding:9px 14px; border:1px solid #282828;">100 - 104 cm</td>
                        <td style="padding:9px 14px; border:1px solid #282828;">74 cm</td>
                    </tr>
                    <tr>
                        <td style="padding:9px 14px; border:1px solid #282828; font-weight:700; color:#fff;">Size XXL</td>
                        <td style="padding:9px 14px; border:1px solid #282828;">1m80 - 1m88</td>
                        <td style="padding:9px 14px; border:1px solid #282828;">82kg - 90kg</td>
                        <td style="padding:9px 14px; border:1px solid #282828;">104 - 110 cm</td>
                        <td style="padding:9px 14px; border:1px solid #282828;">76 cm</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div style="margin-top:18px; padding:12px; background:#1f190e; border:1px solid #C5A059; border-radius:8px; color:#C5A059; font-size:0.82rem;">
            <i class="bi bi-lightbulb-fill"></i> <strong>Tư vấn may đo:</strong> Quý khách cũng có thể chọn mục <strong>[May đo riêng]</strong> hoặc đặt lịch hẹn để nghệ nhân may đo của Kingsman hỗ trợ lấy số đo chuẩn xác nhất.
        </div>
    </div>
</div>
@endsection