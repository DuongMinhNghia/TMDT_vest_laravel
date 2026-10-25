@extends('layouts.default_layout')

@section('title', 'Bộ Sưu Tập Vest Nam - Kingsman Vietnam')

@section('sidebar_danhmuc')
<ul class="list-unstyled mb-0 custom-sidebar-nav">
    <li>
        <a href="{{ url('/san-pham') }}"
            class="d-flex align-items-center justify-content-between px-3 py-2 text-decoration-none sidebar-nav-item {{ !request('danh_muc') ? 'active' : '' }}">
            <span><i class="bi bi-grid-fill me-2"></i> Tất cả trang phục</span>
            @if(!request('danh_muc'))
            <i class="bi bi-chevron-right fs-xs"></i>
            @endif
        </a>
    </li>
    @foreach($danhMucs as $dm)
    @php $isCatActive = request('danh_muc') == $dm->id; @endphp
    <li>
        <a href="{{ url('/san-pham?danh_muc=' . $dm->id) }}"
            class="d-flex align-items-center justify-content-between px-3 py-2 text-decoration-none sidebar-nav-item {{ $isCatActive ? 'active' : '' }}">
            <span><i class="bi bi-tag me-2"></i> {{ $dm->ten_danh_muc }}</span>
            @if($isCatActive)
            <i class="bi bi-check2"></i>
            @endif
        </a>
    </li>
    @endforeach
</ul>
@endsection

@section('content')
<style>
    /* NHẬP FONT CHUẨN THỜI TRANG CAO CẤP HỖ TRỢ TIẾNG VIỆT 100% */
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,400;1,600&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap');

    :root {
        --km-bg-deep: #111317;
        --km-bg-surface: #161922;
        --km-bg-card: #181B24;
        --km-border: rgba(255, 255, 255, 0.08);
        --km-border-focus: rgba(212, 178, 111, 0.5);
        --km-gold: #D4B26F;
        --km-gold-light: #E8CA8C;
        --km-gold-glow: rgba(212, 178, 111, 0.15);
        --km-text-main: #F1F5F9;
        --km-text-muted: #94A3B8;
        --km-text-dim: #64748B;
        --km-font-heading: 'Playfair Display', Georgia, serif;
        --km-font-body: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
    }

    body {
        background-color: var(--km-bg-deep) !important;
        font-family: var(--km-font-body) !important;
        color: var(--km-text-main) !important;
    }

    /* ĐỒNG BỘ SIDEBAR DANH MỤC */
    .sidebar {
        background: var(--km-bg-surface) !important;
        border: 1px solid var(--km-border) !important;
        border-radius: 12px !important;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25) !important;
    }

    .side-heading {
        background: #141720 !important;
        color: var(--km-gold) !important;
        font-family: var(--km-font-heading) !important;
        font-size: 0.95rem !important;
        letter-spacing: 2px !important;
        padding: 16px 20px !important;
        border-bottom: 1px solid var(--km-border) !important;
        text-align: left !important;
        font-weight: 600 !important;
    }

    .custom-sidebar-nav .sidebar-nav-item {
        color: var(--km-text-muted);
        font-size: 0.88rem;
        font-weight: 500;
        border-bottom: 1px solid rgba(255, 255, 255, 0.03);
        transition: all 0.25s ease;
    }

    .custom-sidebar-nav .sidebar-nav-item:hover {
        color: var(--km-gold);
        background: var(--km-gold-glow);
        padding-left: 20px !important;
    }

    .custom-sidebar-nav .sidebar-nav-item.active {
        color: var(--km-gold);
        background: rgba(212, 178, 111, 0.12);
        font-weight: 600;
        border-left: 3px solid var(--km-gold);
    }

    /* THANH TÌM KIẾM & BỘ LỌC (FILTER BAR) */
    .filter-wrapper {
        background: var(--km-bg-surface);
        border: 1px solid var(--km-border);
        border-radius: 14px;
        padding: 18px 24px;
        margin-bottom: 35px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.2);
    }

    .search-input-group {
        position: relative;
        min-width: 280px;
    }

    .search-input-group i {
        position: absolute;
        left: 16px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--km-text-dim);
        font-size: 0.95rem;
    }

    .search-input-field {
        width: 100%;
        background: var(--km-bg-deep);
        border: 1px solid var(--km-border);
        border-radius: 8px;
        color: var(--km-text-main);
        padding: 10px 16px 10px 42px;
        font-size: 0.88rem;
        outline: none;
        transition: all 0.25s ease;
    }

    .search-input-field:focus {
        border-color: var(--km-gold);
        box-shadow: 0 0 0 3px var(--km-gold-glow);
    }

    .search-input-field::placeholder {
        color: var(--km-text-dim);
    }

    /* NÚT LỌC GIÁ */
    .btn-filter-trigger {
        background: var(--km-bg-deep);
        border: 1px solid var(--km-border);
        color: var(--km-text-muted);
        border-radius: 8px;
        padding: 10px 18px;
        font-size: 0.88rem;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.25s ease;
        white-space: nowrap;
    }

    .btn-filter-trigger:hover,
    .btn-filter-trigger.active {
        border-color: var(--km-gold);
        color: var(--km-gold);
        background: var(--km-gold-glow);
    }

    /* DROPDOWN MENU */
    .price-dropdown-box {
        position: absolute;
        top: calc(100% + 8px);
        left: 0;
        background: #141720;
        border: 1px solid var(--km-border);
        border-radius: 10px;
        overflow: hidden;
        min-width: 210px;
        z-index: 1000;
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.45);
    }

    .price-dropdown-link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 11px 18px;
        color: var(--km-text-muted);
        text-decoration: none;
        font-size: 0.85rem;
        border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        transition: all 0.2s ease;
    }

    .price-dropdown-link:hover,
    .price-dropdown-link.active {
        color: var(--km-gold);
        background: rgba(212, 178, 111, 0.1);
    }

    /* NÚT TÌM KIẾM */
    .btn-submit-search {
        background: var(--km-gold);
        color: #111317;
        font-weight: 600;
        font-size: 0.88rem;
        letter-spacing: 0.5px;
        border: none;
        border-radius: 8px;
        padding: 10px 22px;
        cursor: pointer;
        transition: all 0.25s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-submit-search:hover {
        background: var(--km-gold-light);
        box-shadow: 0 4px 15px rgba(212, 178, 111, 0.35);
        transform: translateY(-1px);
    }

    .btn-reset-filter {
        background: transparent;
        border: 1px solid var(--km-border);
        color: var(--km-text-dim);
        border-radius: 8px;
        padding: 10px 16px;
        font-size: 0.88rem;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }

    .btn-reset-filter:hover {
        color: #ef4444;
        border-color: rgba(239, 68, 68, 0.4);
        background: rgba(239, 68, 68, 0.08);
    }

    /* PHẦN DANH SÁCH SẢN PHẨM */
    .products-section {
        padding: 10px 0 60px;
    }

    .section-header {
        margin-bottom: 30px;
        text-align: left;
        border-bottom: 1px solid var(--km-border);
        padding-bottom: 20px;
    }

    .section-header h2 {
        font-family: var(--km-font-heading);
        color: var(--km-text-main);
        font-size: 1.65rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
    }

    .section-header h2 span {
        color: var(--km-gold);
    }

    .section-header p {
        color: var(--km-text-muted);
        font-size: 0.9rem;
        margin-bottom: 0;
    }

    /* THẺ SẢN PHẨM (PRODUCT CARD) */
    .product-card {
        background: var(--km-bg-card);
        border: 1px solid var(--km-border);
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.35s cubic-bezier(0.165, 0.84, 0.44, 1);
        height: 100%;
        display: flex;
        flex-direction: column;
        position: relative;
    }

    .product-card:hover {
        transform: translateY(-6px);
        border-color: rgba(212, 178, 111, 0.4);
        box-shadow: 0 16px 36px rgba(0, 0, 0, 0.35);
    }

    .product-img-wrap {
        position: relative;
        overflow: hidden;
        height: 340px;
        background: #111317;
    }

    .product-img-wrap img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: top center;
        transition: transform 0.6s ease;
    }

    .product-card:hover .product-img-wrap img {
        transform: scale(1.05);
    }

    /* BADGE TAG TINH TẾ */
    .badge-luxury {
        position: absolute;
        top: 14px;
        left: 14px;
        padding: 5px 12px;
        border-radius: 4px;
        font-size: 0.72rem;
        font-weight: 700;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        z-index: 2;
    }

    .badge-sale {
        background: rgba(220, 38, 38, 0.9);
        color: #fff;
        backdrop-filter: blur(4px);
    }

    .badge-hot {
        background: rgba(212, 178, 111, 0.95);
        color: #111317;
        backdrop-filter: blur(4px);
    }

    /* THÔNG TIN SẢN PHẨM */
    .product-info {
        padding: 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        background: var(--km-bg-card);
    }

    .product-title {
        color: var(--km-text-main);
        font-size: 1.02rem;
        font-weight: 600;
        line-height: 1.45;
        margin-bottom: 12px;
        min-height: 44px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: color 0.25s ease;
    }

    .product-card:hover .product-title {
        color: var(--km-gold);
    }

    .product-meta {
        display: flex;
        gap: 12px;
        margin-bottom: 14px;
        padding-bottom: 12px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        flex-wrap: wrap;
    }

    .meta-tag {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        color: var(--km-text-muted);
        font-size: 0.78rem;
    }

    .meta-tag i {
        color: var(--km-gold);
        font-size: 0.8rem;
    }

    /* KHU VỰC GIÁ TIỀN */
    .product-price-box {
        margin-bottom: 18px;
        display: flex;
        align-items: baseline;
        gap: 8px;
    }

    .price-current {
        color: var(--km-gold);
        font-size: 1.25rem;
        font-weight: 700;
        font-family: var(--km-font-body);
        letter-spacing: -0.3px;
    }

    .price-sale-current {
        color: #ef4444;
        font-size: 1.25rem;
        font-weight: 700;
        letter-spacing: -0.3px;
    }

    .price-old {
        text-decoration: line-through;
        color: var(--km-text-dim);
        font-size: 0.85rem;
    }

    /* NÚT THAO TÁC */
    .btn-view-suit {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
        padding: 10px 16px;
        background: transparent;
        border: 1px solid rgba(212, 178, 111, 0.6);
        color: var(--km-gold);
        border-radius: 6px;
        font-size: 0.82rem;
        font-weight: 600;
        letter-spacing: 1px;
        text-transform: uppercase;
        text-decoration: none;
        transition: all 0.25s ease;
        margin-top: auto;
    }

    .btn-view-suit:hover {
        background: var(--km-gold);
        color: #111317;
        border-color: var(--km-gold);
        box-shadow: 0 4px 14px rgba(212, 178, 111, 0.25);
    }

    /* TRẠNG THÁI TRỐNG (EMPTY STATE) */
    .empty-state-box {
        text-align: center;
        padding: 70px 20px;
        background: var(--km-bg-surface);
        border: 1px dashed var(--km-border);
        border-radius: 14px;
        margin-top: 20px;
    }

    .empty-state-box i {
        font-size: 3.5rem;
        color: var(--km-gold);
        opacity: 0.4;
        margin-bottom: 15px;
        display: block;
    }

    .empty-state-box h3 {
        font-family: var(--km-font-heading);
        color: var(--km-text-main);
        font-size: 1.35rem;
        margin-bottom: 8px;
    }

    .empty-state-box p {
        color: var(--km-text-muted);
        font-size: 0.9rem;
    }

    /* PHÂN TRANG (PAGINATION) */
    .pagination {
        gap: 8px;
        margin-top: 40px;
        justify-content: center;
    }

    .pagination .page-link {
        background: var(--km-bg-card) !important;
        border: 1px solid var(--km-border) !important;
        color: var(--km-text-muted) !important;
        border-radius: 8px !important;
        padding: 8px 16px;
        font-size: 0.9rem;
        font-weight: 500;
        transition: all 0.2s ease;
    }

    .pagination .page-link:hover {
        background: var(--km-gold-glow) !important;
        color: var(--km-gold) !important;
        border-color: var(--km-gold) !important;
    }

    .pagination .page-item.active .page-link {
        background: var(--km-gold) !important;
        color: #111317 !important;
        border-color: var(--km-gold) !important;
        font-weight: 700;
        box-shadow: 0 4px 12px rgba(212, 178, 111, 0.3);
    }

    .pagination .page-item.disabled .page-link {
        background: var(--km-bg-deep) !important;
        border-color: rgba(255, 255, 255, 0.04) !important;
        color: var(--km-text-dim) !important;
    }

    .pagination-info,
    nav[role="navigation"] > div:first-child {
        display: none !important;
    }
</style>

{{-- THANH TÌM KIẾM & BỘ LỌC STUDIO --}}
<div class="filter-wrapper">
    <form method="GET" action="{{ url('/san-pham') }}" class="d-flex gap-3 justify-content-between align-items-center flex-wrap">
        {{-- Giữ filter danh mục nếu đang lọc --}}
        @if(request('danh_muc'))
        <input type="hidden" name="danh_muc" value="{{ request('danh_muc') }}">
        @endif

        <div class="d-flex gap-3 align-items-center flex-wrap flex-grow-1">
            {{-- Ô tìm kiếm --}}
            <div class="search-input-group">
                <i class="bi bi-search"></i>
                <input type="text" name="tim_kiem"
                    value="{{ request('tim_kiem') }}"
                    placeholder="Tìm tên trang phục, chất liệu, xuất xứ..."
                    class="search-input-field">
            </div>

            {{-- Lọc theo mức giá --}}
            @php
            $giaRanges = [
                ['label' => 'Tất cả mức giá', 'min' => '',        'max' => ''],
                ['label' => 'Dưới 1 triệu',   'min' => '',        'max' => '1000000'],
                ['label' => '1tr – 3tr',       'min' => '1000000', 'max' => '3000000'],
                ['label' => '3tr – 5tr',       'min' => '3000000', 'max' => '5000000'],
                ['label' => 'Trên 5 triệu',    'min' => '5000000', 'max' => ''],
            ];

            $currentLabel = 'Khoảng giá';
            foreach ($giaRanges as $range) {
                if ($range['min'] == request('gia_min') && $range['max'] == request('gia_max') && ($range['min'] || $range['max'])) {
                    $currentLabel = $range['label'];
                    break;
                }
            }
            $isPriceFiltered = request('gia_min') || request('gia_max');
            @endphp

            <div class="position-relative" id="gia-dropdown-wrap">
                <button type="button"
                    onclick="document.getElementById('gia-dropdown').classList.toggle('d-none')"
                    class="btn-filter-trigger {{ $isPriceFiltered ? 'active' : '' }}">
                    <i class="bi bi-sliders2"></i>
                    <span>{{ $currentLabel }}</span>
                    <i class="bi bi-chevron-down" style="font-size:0.75rem;"></i>
                </button>

                <div id="gia-dropdown" class="d-none price-dropdown-box">
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
                            class="price-dropdown-link {{ $isActive ? 'active' : '' }}">
                            @if($isActive)
                                <i class="bi bi-check-circle-fill text-warning"></i>
                            @else
                                <i class="bi bi-circle opacity-50"></i>
                            @endif
                            {{ $range['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="btn-submit-search">
                <i class="bi bi-funnel-fill"></i> Lọc kết quả
            </button>

            @if(request('tim_kiem') || request('gia_min') || request('gia_max'))
            <a href="{{ url('/san-pham') . (request('danh_muc') ? '?danh_muc='.request('danh_muc') : '') }}"
                class="btn-reset-filter">
                <i class="bi bi-x-circle"></i> Đặt lại
            </a>
            @endif
        </div>

        {{-- Bộ đếm kết quả nhỏ góc phải --}}
        <div class="text-end d-none d-md-block" style="color: var(--km-text-muted); font-size: 0.85rem;">
            Hiển thị <span style="color: var(--km-gold); font-weight: 600;">{{ $sanPhams->count() }}</span> mẫu trang phục
        </div>
    </form>
</div>

{{-- ĐÓNG DROPDOWN KHI CLICK NGOÀI --}}
<script>
document.addEventListener('click', function(e) {
    const wrap = document.getElementById('gia-dropdown-wrap');
    if (wrap && !wrap.contains(e.target)) {
        document.getElementById('gia-dropdown').classList.add('d-none');
    }
});
</script>

{{-- KHU VỰC DANH SÁCH SẢN PHẨM --}}
<section class="products-section">
    <div class="section-header d-flex justify-content-between align-items-end flex-wrap gap-2">
        <div>
            <h2>
                @if(request('danh_muc'))
                    {{ $danhMucs->find(request('danh_muc'))->ten_danh_muc ?? 'Bộ Sưu Tập' }}
                @else
                    Bộ Sưu Tập <span>Vest Cao Cấp</span>
                @endif
            </h2>
            <p>Trang phục may đo tiêu chuẩn Savile Row — Tuyển chọn chất liệu thượng hạng</p>
        </div>
        <div class="d-md-none" style="color: var(--km-text-muted); font-size: 0.82rem;">
            {{ $sanPhams->count() }} sản phẩm
        </div>
    </div>

    @if($sanPhams->count() > 0)
    <div class="row g-4">
        @foreach($sanPhams as $sp)
        <div class="col-xl-3 col-lg-4 col-md-6 col-12">
            <div class="product-card">
                <div class="product-img-wrap">
                    @if($sp->hinh_anh)
                    <img src="{{ asset('images/' . $sp->hinh_anh) }}" alt="{{ $sp->ten_sp }}" loading="lazy">
                    @else
                    <div class="d-flex align-items-center justify-content-center h-100 bg-black">
                        <i class="bi bi-image" style="font-size:2.5rem; color: var(--km-text-dim);"></i>
                    </div>
                    @endif

                    @if($sp->phan_tram_giam > 0)
                    <span class="badge-luxury badge-sale">Giảm {{ $sp->phan_tram_giam }}%</span>
                    @elseif($sp->gia > 3000000)
                    <span class="badge-luxury badge-hot">Signature</span>
                    @endif
                </div>

                <div class="product-info">
                    <h3 class="product-title" title="{{ $sp->ten_sp }}">{{ $sp->ten_sp }}</h3>

                    @if($sp->xuat_xu || $sp->chat_lieu)
                    <div class="product-meta">
                        @if($sp->xuat_xu)
                        <div class="meta-tag">
                            <i class="bi bi-geo-alt"></i>
                            <span>{{ $sp->xuat_xu }}</span>
                        </div>
                        @endif
                        @if($sp->chat_lieu)
                        <div class="meta-tag">
                            <i class="bi bi-gem"></i>
                            <span>{{ $sp->chat_lieu }}</span>
                        </div>
                        @endif
                    </div>
                    @endif

                    <div class="product-price-box">
                        @if($sp->phan_tram_giam > 0)
                            <span class="price-sale-current">{{ number_format($sp->gia_khuyen_mai, 0, ',', '.') }} ₫</span>
                            <span class="price-old">{{ number_format($sp->gia_ban, 0, ',', '.') }} ₫</span>
                        @else
                            <span class="price-current">{{ number_format($sp->gia, 0, ',', '.') }} ₫</span>
                        @endif
                    </div>

                    <a href="{{ url('/san-pham/' . $sp->id) }}" class="btn-view-suit">
                        <span>Chi tiết tác phẩm</span>
                        <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- PHÂN TRANG --}}
    <div class="d-flex justify-content-center">
        <nav>
            {{ $sanPhams->onEachSide(1)->links('pagination::bootstrap-5') }}
        </nav>
    </div>

    @else
    <div class="empty-state-box">
        <i class="bi bi-inbox"></i>
        <h3>Không tìm thấy tác phẩm phù hợp</h3>
        <p>Hiện chưa có trang phục trong danh mục này hoặc bộ lọc của bạn quá hẹp.</p>
        <a href="{{ url('/san-pham') }}" class="btn-submit-search mt-3" style="text-decoration:none;">
            <i class="bi bi-arrow-left"></i> Xem tất cả tác phẩm
        </a>
    </div>
    @endif
</section>
@endsection