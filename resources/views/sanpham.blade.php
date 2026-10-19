@extends('layouts.default_layout')

@section('title', 'Sản Phẩm - Kingsman Vietnam')

@section('sidebar_danhmuc')
<ul class="list-unstyled mb-0">
    <li>
        <a href="{{ url('/san-pham') }}"
            class="d-block px-2 py-2 text-decoration-none fw-semibold border-bottom"
            style="color:#d4af37;">
            <i class="bi bi-grid-fill me-1"></i> Tất cả
        </a>
    </li>
    @foreach($danhMucs as $dm)
    <li>
        <a href="{{ url('/san-pham?danh_muc=' . $dm->id) }}"
            class="d-block px-2 py-2 text-decoration-none border-bottom sidebar-link"
            style="color:#ccc; font-size:0.9rem;">
            <i class="bi bi-chevron-right me-1"></i>{{ $dm->ten_danh_muc }}
        </a>
    </li>
    @endforeach
</ul>
@endsection

@section('content')
<style>
    .products-section {
        padding: 40px 0;
        background: linear-gradient(to bottom, #0a0a0a, #000);
        min-height: 100vh;
    }

    .section-header {
        text-align: center;
        margin-bottom: 40px;
    }

    .section-header h2 {
        font-family: 'Playfair Display', serif;
        color: #d4af37;
        font-size: 2.2rem;
        font-weight: 700;
        letter-spacing: 3px;
        text-transform: uppercase;
    }

    .section-header p {
        color: #a0a0a0;
        font-size: 1rem;
    }

    .product-card {
        background: linear-gradient(135deg, #1a1a1a, #2a2a2a);
        border: 1px solid #333;
        border-radius: 15px;
        overflow: hidden;
        transition: all 0.4s ease;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .product-card:hover {
        transform: translateY(-15px);
        box-shadow: 0 20px 50px rgba(212, 175, 55, 0.4);
        border-color: #d4af37;
    }

    .product-img-wrap {
        position: relative;
        overflow: hidden;
        height: 350px;
        background: #0f0f0f;
    }

    .product-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.6s ease;
    }

    .product-card:hover .product-img-wrap img {
        transform: scale(1.15);
    }

    .badge-hot {
        position: absolute;
        top: 15px;
        right: 15px;
        background: linear-gradient(135deg, #ff6b6b, #ee5a52);
        color: #fff;
        padding: 6px 16px;
        border-radius: 25px;
        font-weight: 700;
        font-size: 0.8rem;
        letter-spacing: 2px;
        box-shadow: 0 5px 15px rgba(255, 107, 107, 0.4);
        animation: pulse 2s infinite;
        z-index: 2;
    }

    @keyframes pulse {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.05);
        }
    }

    .product-info {
        padding: 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        background: linear-gradient(to bottom, #1a1a1a, #0f0f0f);
    }

    .product-info h3 {
        color: #f0f0f0;
        font-size: 1.05rem;
        font-weight: 600;
        margin-bottom: 12px;
        min-height: 45px;
        line-height: 1.4;
        transition: color 0.3s;
    }

    .product-card:hover .product-info h3 {
        color: #d4af37;
    }

    .product-meta {
        display: flex;
        gap: 12px;
        margin-bottom: 12px;
        padding-bottom: 12px;
        border-bottom: 1px solid #333;
        flex-wrap: wrap;
    }

    .meta-item {
        display: flex;
        align-items: center;
        gap: 5px;
        color: #888;
        font-size: 0.82rem;
    }

    .meta-item i {
        color: #d4af37;
    }

    .price {
        margin-bottom: 18px;
    }

    .price .current {
        color: #d4af37;
        font-size: 1.3rem;
        font-weight: 700;
        font-family: 'Playfair Display', serif;
    }

    .product-actions {
        display: flex;
        gap: 10px;
        margin-top: auto;
    }

    .btn-detail {
        flex: 1;
        background: transparent;
        border: 2px solid #d4af37;
        color: #d4af37;
        padding: 10px 15px;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: all 0.3s;
        text-align: center;
        font-size: 0.85rem;
        letter-spacing: 1px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .btn-detail:hover {
        background: #d4af37;
        color: #000;
        transform: translateY(-2px);
    }

    .btn-cart {
        background: linear-gradient(135deg, #d4af37, #c19b2f);
        border: none;
        color: #000;
        padding: 10px 15px;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        text-decoration: none;
    }

    .btn-cart:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(212, 175, 55, 0.5);
        color: #000;
    }

    .empty-state {
        text-align: center;
        padding: 80px 20px;
    }

    .empty-state i {
        font-size: 4rem;
        color: #d4af37;
        opacity: 0.5;
        margin-bottom: 15px;
        display: block;
    }

    .empty-state h3 {
        color: #f0f0f0;
        margin-bottom: 10px;
    }

    .empty-state p {
        color: #888;
    }

    /* Sidebar dark */
    .card {
        background: #1a1a1a !important;
        border-color: #333 !important;
    }

    .card-header.bg-dark {
        border-bottom: 1px solid #d4af37 !important;
        color: #d4af37 !important;
        letter-spacing: 2px;
    }

    .sidebar-link:hover {
        color: #d4af37 !important;
    }

        /* Phân trang */
    .pagination {
        gap: 6px;
    }
    .pagination .page-link {
        background: #1a1a1a !important;
        border: 1px solid #444 !important;
        color: #d4af37 !important;
        border-radius: 8px !important;
        padding: 10px 18px;
        font-size: 1rem;
        font-weight: 600;
        transition: all 0.3s;
    }
    .pagination .page-link:hover {
        background: #d4af37 !important;
        color: #000 !important;
        border-color: #d4af37 !important;
        transform: translateY(-2px);
    }
    .pagination .page-item.active .page-link {
        background: #d4af37 !important;
        color: #000 !important;
        border-color: #d4af37 !important;
        font-weight: 700;
    }
    .pagination .page-item.disabled .page-link {
        background: #111 !important;
        border-color: #333 !important;
        color: #555 !important;
    }

    .pagination-info,
    nav[role="navigation"] > div:first-child {
        display: none !important;
    }
    nav[role="navigation"] {
        display: flex;
        justify-content: center;
    }
</style>

{{-- THANH TÌM KIẾM --}}
<div style="background:#0a0a0a; padding: 25px 0 20px; border-bottom: 1px solid #222;">
    <div class="container">
        <form method="GET" action="{{ url('/san-pham') }}" class="d-flex gap-4 justify-content-center align-items-center flex-wrap">
            {{-- Giữ filter danh mục nếu đang lọc --}}
            @if(request('danh_muc'))
            <input type="hidden" name="danh_muc" value="{{ request('danh_muc') }}">
            @endif

            <input type="text" name="tim_kiem"
                value="{{ request('tim_kiem') }}"
                placeholder="Tìm theo tên, chất liệu, xuất xứ..."
                style="background:#1a1a1a; border: 1px solid #444; border-radius:25px;
                          color:#fff; padding: 10px 20px; width: 300px; outline:none; margin-right: 10px;
                          transition: border-color 0.3s;"
                onfocus="this.style.borderColor='#d4af37'"
                onblur="this.style.borderColor='#444'">


          {{-- Lọc theo giá (Dropdown) --}}
            @php
            $giaRanges = [
                ['label' => 'Tất cả mức giá', 'min' => '',        'max' => ''],
                ['label' => 'Dưới 1 triệu',   'min' => '',        'max' => '1000000'],
                ['label' => '1tr – 3tr',       'min' => '1000000', 'max' => '3000000'],
                ['label' => '3tr – 5tr',       'min' => '3000000', 'max' => '5000000'],
                ['label' => 'Trên 5 triệu',    'min' => '5000000', 'max' => ''],
            ];

            $currentLabel = 'Lọc theo giá';
            foreach ($giaRanges as $range) {
                if ($range['min'] == request('gia_min') && $range['max'] == request('gia_max') && ($range['min'] || $range['max'])) {
                    $currentLabel = $range['label'];
                    break;
                }
            }
            @endphp

            <div class="position-relative" id="gia-dropdown-wrap">
                {{-- Nút trigger --}}
                <button type="button"
                    onclick="document.getElementById('gia-dropdown').classList.toggle('d-none')"
                    style="
                        background: {{ request('gia_min') || request('gia_max') ? '#d4af37' : 'transparent' }};
                        border: 1px solid {{ request('gia_min') || request('gia_max') ? '#d4af37' : '#444' }};
                        color: {{ request('gia_min') || request('gia_max') ? '#000' : '#ccc' }};
                        border-radius: 25px;
                        padding: 10px 20px;
                        font-size: 0.85rem;
                        font-weight: {{ request('gia_min') || request('gia_max') ? '700' : '400' }};
                        cursor: pointer;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                        transition: all 0.3s;
                        white-space: nowrap;
                         margin-right: 10px;
                    ">
                    <i class="bi bi-funnel-fill"></i>
                    {{ $currentLabel }}
                    <i class="bi bi-chevron-down" style="font-size:0.75rem;"></i>
                </button>

                {{-- Dropdown list --}}
                <div id="gia-dropdown"
                    class="d-none position-absolute"
                    style="
                        top: calc(100% + 8px);
                        left: 0;
                        background: #1a1a1a;
                        border: 1px solid #444;
                        border-radius: 12px;
                        overflow: hidden;
                        min-width: 180px;
                        z-index: 999;
                        box-shadow: 0 8px 24px rgba(0,0,0,0.5);
                    ">
                    @foreach($giaRanges as $range)
                        @php
                            $isActive = request('gia_min') == $range['min'] && request('gia_max') == $range['max'];
                        @endphp
                        <a href="{{ url('/san-pham') }}?{{ http_build_query(array_filter([
                                'danh_muc' => request('danh_muc'),
                                'tim_kiem' => request('tim_kiem'),
                                'gia_min'  => $range['min'],
                                'gia_max'  => $range['max'],
                            ])) }}"
                            style="
                                display: flex;
                                align-items: center;
                                gap: 10px;
                                padding: 12px 18px;
                                color: {{ $isActive ? '#d4af37' : '#ccc' }};
                                background: {{ $isActive ? 'rgba(212,175,55,0.1)' : 'transparent' }};
                                text-decoration: none;
                                font-size: 0.9rem;
                                font-weight: {{ $isActive ? '700' : '400' }};
                                border-bottom: 1px solid #2a2a2a;
                                transition: background 0.2s, color 0.2s;
                            "
                            onmouseover="this.style.background='rgba(212,175,55,0.15)'; this.style.color='#d4af37';"
                            onmouseout="this.style.background='{{ $isActive ? 'rgba(212,175,55,0.1)' : 'transparent' }}'; this.style.color='{{ $isActive ? '#d4af37' : '#ccc' }}';">
                            @if($isActive)
                                <i class="bi bi-check-circle-fill" style="color:#d4af37;"></i>
                            @else
                                <i class="bi bi-circle" style="color:#555;"></i>
                            @endif
                            {{ $range['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            {{-- Đóng dropdown khi click ra ngoài --}}
            <script>
            document.addEventListener('click', function(e) {
                const wrap = document.getElementById('gia-dropdown-wrap');
                if (wrap && !wrap.contains(e.target)) {
                    document.getElementById('gia-dropdown').classList.add('d-none');
                }
            });
            </script>

            <button type="submit"
                style="background: linear-gradient(135deg, #d4af37, #c19b2f);
                           color:#000; border:none; border-radius:25px;
                           padding: 10px 25px; font-weight:700; cursor:pointer;
                           transition: all 0.3s;"
                onmouseover="this.style.transform='translateY(-2px)'"
                onmouseout="this.style.transform='translateY(0)'">
                <i class="bi bi-search me-1"></i> Tìm kiếm
            </button>

            @if(request('tim_kiem'))
            <a href="{{ url('/san-pham') . (request('danh_muc') ? '?danh_muc='.request('danh_muc') : '') }}"
                style="background:transparent; border: 1px solid #666; border-radius:25px;
                      color:#aaa; padding: 10px 20px; text-decoration:none;
                      transition: all 0.3s; display:flex; align-items:center; gap:6px;"
                onmouseover="this.style.borderColor='#d4af37'; this.style.color='#d4af37'"
                onmouseout="this.style.borderColor='#666'; this.style.color='#aaa'">
                <i class="bi bi-x-circle"></i> Xoá
            </a>
            @endif
        </form>

        {{-- Hiển thị kết quả tìm kiếm --}}
        @if(request('tim_kiem'))
        <div class="text-center mt-3" style="color:#a0a0a0; font-size:0.9rem;">
            Kết quả cho "<span style="color:#d4af37;">{{ request('tim_kiem') }}</span>"
            — tìm thấy <span style="color:#d4af37;">{{ $sanPhams->count() }}</span> sản phẩm
        </div>
        @endif

        {{-- Hiển thị kết quả lọc giá--}}
        @if(request('gia_min') || request('gia_max'))
        <div class="text-center mt-2" style="color:#a0a0a0; font-size:0.9rem;">
            @php
                $label = collect($giaRanges)->first(fn($r) =>
                    $r['min'] == request('gia_min') && $r['max'] == request('gia_max')
                );
            @endphp
            Đang lọc: <span style="color:#d4af37; font-weight:600;">
                {{ $label ? $label['label'] : 'Khoảng giá tuỳ chỉnh' }}
            </span>
            — tìm thấy <span style="color:#d4af37;">{{ $sanPhams->count() }}</span> sản phẩm
        </div>
        @endif
    </div>
</div>

<section class="products-section">
    <div class="container-fluid px-0">

        <div class="section-header">
            <h2><i class="bi bi-grid-3x3-gap-fill"></i>
                {{ request('danh_muc') ? ($danhMucs->find(request('danh_muc'))->ten_danh_muc ?? 'Sản Phẩm') : 'Tất Cả Sản Phẩm' }}
            </h2>
            <p>Khám phá bộ sưu tập vest cao cấp — {{ $sanPhams->count() }} sản phẩm</p>
        </div>

        @if($sanPhams->count() > 0)
        <div class="row g-4">
            @foreach($sanPhams as $sp)
            <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                <div class="product-card">
                    <div class="product-img-wrap">
                        @if($sp->hinh_anh)
                        <img src="{{ asset('images/' . $sp->hinh_anh) }}" alt="{{ $sp->ten_sp }}">
                        @else
                        <div class="d-flex align-items-center justify-content-center bg-dark" style="height:350px;">
                            <i class="bi bi-image text-secondary" style="font-size:3rem;"></i>
                        </div>
                        @endif

                        @if($sp->gia > 3000000)
                        <span class="badge-hot">🔥 HOT</span>
                        @endif
                    </div>

                    <div class="product-info">
                        <h3>{{ $sp->ten_sp }}</h3>

                        @if($sp->xuat_xu || $sp->chat_lieu)
                        <div class="product-meta">
                            @if($sp->xuat_xu)
                            <div class="meta-item">
                                <i class="bi bi-geo-alt-fill"></i>
                                <span>{{ $sp->xuat_xu }}</span>
                            </div>
                            @endif
                            @if($sp->chat_lieu)
                            <div class="meta-item">
                                <i class="bi bi-stars"></i>
                                <span>{{ $sp->chat_lieu }}</span>
                            </div>
                            @endif
                        </div>
                        @endif

                        <div class="price">
                            <span class="current">{{ number_format($sp->gia, 0, ',', '.') }} ₫</span>
                        </div>

                        <div class="product-actions">
                            <a href="{{ url('/san-pham/' . $sp->id) }}" class="btn-detail" style="flex:1;">
                                <i class="bi bi-info-circle-fill"></i> Chi Tiết
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        {{-- PHÂN TRANG --}}
        <div class="d-flex justify-content-center mt-4 mb-4">
            <nav>
                {{ $sanPhams->onEachSide(1)->links('pagination::bootstrap-5') }}
            </nav>
        </div>

        @else
        <div class="empty-state">
            <i class="bi bi-inbox"></i>
            <h3>Không tìm thấy sản phẩm</h3>
            <p>Vui lòng thử danh mục khác hoặc xem tất cả sản phẩm</p>
            <a href="{{ url('/san-pham') }}" class="btn-detail mt-3" style="display:inline-flex;">
                <i class="bi bi-arrow-left"></i> Xem Tất Cả
            </a>
        </div>
        @endif

    </div>
</section>
@endsection