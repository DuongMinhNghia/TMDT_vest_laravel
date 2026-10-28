@extends('layouts.default_layout')

@section('title', 'Trang Chủ - Kingsman Vietnam | May Đo Vest Cao Cấp')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,400;1,600&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap');

    :root {
        --km-bg-deep: #111317;
        --km-bg-surface: #141720;
        --km-bg-card: #181B24;
        --km-border: rgba(255, 255, 255, 0.08);
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

    /* CAROUSEL */
    .hero-carousel .carousel {
        height: 560px;
    }

    .hero-carousel .carousel-inner,
    .hero-carousel .carousel-item {
        height: 100%;
    }

    .carousel-slide {
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .carousel-content {
        text-align: center;
        max-width: 820px;
        padding: 40px 20px;
        animation: fadeInUp 0.8s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(25px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .carousel-badge {
        display: inline-block;
        background: rgba(212, 178, 111, 0.18);
        border: 1px solid var(--km-gold);
        color: var(--km-gold);
        padding: 6px 20px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.8rem;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        margin-bottom: 20px;
        backdrop-filter: blur(4px);
    }

    .carousel-title {
        font-family: var(--km-font-heading);
        font-size: 2.8rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 18px;
        letter-spacing: 0.5px;
        text-shadow: 0 4px 20px rgba(0, 0, 0, 0.7);
    }

    .carousel-description {
        font-size: 1.05rem;
        color: #cbd5e1;
        margin-bottom: 30px;
        font-weight: 400;
        line-height: 1.6;
    }

    .btn-carousel {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--km-gold);
        color: #111317;
        padding: 12px 30px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.88rem;
        letter-spacing: 0.5px;
        transition: all 0.25s ease;
    }

    .btn-carousel:hover {
        background: var(--km-gold-light);
        color: #111317;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(212, 178, 111, 0.35);
    }

    .carousel-control-prev,
    .carousel-control-next {
        width: 48px;
        height: 48px;
        background: rgba(20, 23, 32, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        top: 50%;
        transform: translateY(-50%);
        opacity: 0;
        transition: all 0.3s;
    }

    .hero-carousel:hover .carousel-control-prev,
    .hero-carousel:hover .carousel-control-next {
        opacity: 1;
    }

    .carousel-control-prev { left: 25px; }
    .carousel-control-next { right: 25px; }

    .carousel-indicators button {
        width: 35px;
        height: 3px;
        border-radius: 3px;
        background: rgba(255, 255, 255, 0.3);
        border: none;
        transition: all 0.3s;
    }

    .carousel-indicators button.active {
        background: var(--km-gold);
        width: 55px;
    }

    /* ABOUT SECTION */
    .about-section {
        padding: 90px 0;
        background: var(--km-bg-deep);
        position: relative;
        overflow: hidden;
    }

    .section-title {
        font-family: var(--km-font-heading);
        font-size: 2.3rem;
        font-weight: 700;
        color: var(--km-text-main);
        text-align: center;
        margin-bottom: 10px;
        letter-spacing: 0.5px;
    }

    .section-title span {
        color: var(--km-gold);
    }

    .section-subtitle {
        text-align: center;
        color: var(--km-text-muted);
        font-size: 0.95rem;
        margin-bottom: 45px;
        max-width: 650px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.6;
    }

    .about-images {
        display: flex;
        gap: 25px;
        margin-bottom: 35px;
    }

    .main-image,
    .side-image {
        flex: 1;
        border-radius: 12px;
        overflow: hidden;
        border: 1px solid var(--km-border);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        transition: all 0.4s;
    }

    .main-image:hover,
    .side-image:hover {
        transform: translateY(-4px);
        border-color: rgba(212, 178, 111, 0.3);
    }

    .main-image img,
    .side-image img {
        width: 100%;
        height: 440px;
        object-fit: cover;
        display: block;
    }

    .about-description {
        background: var(--km-bg-card);
        border: 1px solid var(--km-border);
        border-radius: 12px;
        padding: 35px 40px;
        margin-top: 30px;
        position: relative;
    }

    .about-description h3 {
        font-family: var(--km-font-heading);
        color: var(--km-gold);
        font-size: 1.55rem;
        font-weight: 600;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .about-description p {
        color: var(--km-text-muted);
        line-height: 1.75;
        font-size: 0.92rem;
        margin-bottom: 12px;
    }

    .btn-about {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-top: 15px;
        padding: 10px 24px;
        border: 1px solid var(--km-gold);
        color: var(--km-gold);
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.85rem;
        letter-spacing: 0.5px;
        transition: all 0.25s;
    }

    .btn-about:hover {
        background: var(--km-gold);
        color: #111317;
        box-shadow: 0 4px 15px rgba(212, 178, 111, 0.25);
    }

    /* STATS */
    .stats-section {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-top: 40px;
        flex-wrap: wrap;
    }

    .stat-card {
        background: var(--km-bg-surface);
        border: 1px solid var(--km-border);
        border-radius: 12px;
        padding: 24px 35px;
        text-align: center;
        transition: all 0.3s;
        min-width: 200px;
        flex: 1;
    }

    .stat-card:hover {
        transform: translateY(-4px);
        border-color: rgba(212, 178, 111, 0.4);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.25);
    }

    .stat-icon {
        background: rgba(212, 178, 111, 0.12);
        color: var(--km-gold);
        width: 52px;
        height: 52px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
        font-size: 1.35rem;
    }

    .stat-number {
        font-family: var(--km-font-heading);
        font-size: 2.2rem;
        font-weight: 700;
        color: var(--km-text-main);
        margin-bottom: 2px;
    }

    .stat-label {
        color: var(--km-text-muted);
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 500;
    }

    /* PRODUCT CARDS */
    .product-card {
        background: var(--km-bg-card);
        border: 1px solid var(--km-border);
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.35s cubic-bezier(0.165, 0.84, 0.44, 1);
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .product-card:hover {
        transform: translateY(-6px);
        border-color: rgba(212, 178, 111, 0.4);
        box-shadow: 0 16px 36px rgba(0, 0, 0, 0.35);
    }

    .product-image {
        position: relative;
        overflow: hidden;
        height: 320px;
        background: #111317;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: top center;
        transition: transform 0.6s ease;
    }

    .product-card:hover .product-image img {
        transform: scale(1.05);
    }

    .product-overlay {
        position: absolute;
        inset: 0;
        background: rgba(17, 19, 23, 0.7);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s;
        backdrop-filter: blur(2px);
    }

    .product-card:hover .product-overlay {
        opacity: 1;
    }

    .btn-view-overlay {
        border: 1px solid var(--km-gold);
        background: rgba(212, 178, 111, 0.15);
        color: var(--km-gold);
        padding: 8px 20px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.82rem;
        letter-spacing: 0.5px;
        transition: all 0.25s;
    }

    .btn-view-overlay:hover {
        background: var(--km-gold);
        color: #111317;
    }

    .product-info {
        padding: 18px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        background: var(--km-bg-card);
    }

    .product-name {
        color: var(--km-text-main);
        font-size: 0.98rem;
        font-weight: 600;
        line-height: 1.4;
        margin-bottom: 8px;
        min-height: 40px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        transition: color 0.25s ease;
    }

    .product-card:hover .product-name {
        color: var(--km-gold);
    }

    .product-price {
        color: var(--km-gold);
        font-size: 1.15rem;
        font-weight: 700;
        font-family: 'Plus Jakarta Sans', Arial, sans-serif;
        margin-bottom: 14px;
        font-variant-numeric: tabular-nums;
        line-height: 1.25;
        white-space: nowrap;
    }

    .btn-view-detail {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        width: 100%;
        padding: 8px 14px;
        background: transparent;
        border: 1px solid rgba(212, 178, 111, 0.5);
        color: var(--km-gold);
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        text-decoration: none;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        transition: all 0.25s;
        margin-top: auto;
    }

    .btn-view-detail:hover {
        background: var(--km-gold);
        color: #111317;
    }

    .btn-view-all {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--km-gold);
        color: #111317;
        padding: 12px 35px;
        border-radius: 6px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.9rem;
        letter-spacing: 0.5px;
        transition: all 0.25s;
    }

    .btn-view-all:hover {
        background: var(--km-gold-light);
        color: #111317;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(212, 178, 111, 0.35);
    }

    /* COLLECTIONS BANNER */
    .collections-section {
        padding: 80px 0;
        background: var(--km-bg-surface);
        border-top: 1px solid var(--km-border);
        border-bottom: 1px solid var(--km-border);
    }

    .collection-banner-card {
        position: relative;
        border-radius: 12px;
        overflow: hidden;
        height: 330px;
        border: 1px solid var(--km-border);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
    }

    .collection-banner-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: brightness(0.65);
        transition: transform 0.6s ease;
    }

    .collection-banner-card:hover img {
        transform: scale(1.04);
    }

    /* RESPONSIVE TRANG CHỦ CHO MOBILE & TABLET */
    @media (max-width: 991.98px) {
        .hero-carousel .carousel {
            height: 440px;
        }
        .carousel-title {
            font-size: 2.2rem;
        }
        .about-section {
            padding: 50px 0;
        }
    }

    @media (max-width: 768px) {
        .hero-carousel .carousel {
            height: 380px;
        }
        .carousel-title {
            font-size: 1.65rem;
            margin-bottom: 12px;
        }
        .carousel-badge {
            font-size: 0.72rem;
            padding: 4px 14px;
            margin-bottom: 12px;
        }
        .carousel-description {
            font-size: 0.88rem;
            margin-bottom: 20px;
        }
        .btn-carousel {
            padding: 10px 22px;
            font-size: 0.82rem;
        }
        .carousel-control-prev,
        .carousel-control-next {
            width: 36px;
            height: 36px;
        }
        .carousel-control-prev { left: 10px; }
        .carousel-control-next { right: 10px; }
        .stats-section {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
            margin-top: 25px;
        }
        .stat-card {
            min-width: unset;
            padding: 16px 10px;
        }
        .stat-number {
            font-size: 1.6rem;
        }
        .stat-label {
            font-size: 0.7rem;
        }
        .product-image {
            height: 240px;
        }
        .collection-banner-card {
            height: 200px;
            margin-bottom: 16px;
        }
        .collections-section {
            padding: 45px 0;
        }
        .section-header h2 {
            font-size: 1.5rem;
        }
    }

    @media (max-width: 575.98px) {
        .hero-carousel .carousel {
            height: 320px;
        }
        .carousel-content {
            padding: 20px 14px;
        }
        .carousel-title {
            font-size: 1.35rem;
        }
        .product-image {
            height: 200px;
        }
    }
</style>

{{-- THANH ĐIỀU KHIỂN QUẢN TRỊ VIÊN --}}
@if(Auth::check() && (Auth::user()->vai_tro == 'admin' || Auth::user()->quyen == 'Admin'))
<div style="background: var(--km-bg-surface); border-bottom: 1px solid var(--km-border); padding: 10px 20px; margin: -1.5rem -12px 20px;">
    <div class="container d-flex align-items-center justify-content-between flex-wrap gap-2">
        <div style="display:flex; align-items:center; gap:8px; color:var(--km-gold); font-weight:600; font-size:0.85rem;">
            <i class="bi bi-shield-check" style="font-size:1.05rem;"></i>
            <span>BẢNG ĐIỀU KHIỂN QUẢN TRỊ VIÊN:</span>
        </div>
        <div style="display:flex; align-items:center; gap:8px; flex-wrap:wrap;">
            <a href="{{ url('/san-pham/danh-sach') }}" class="btn-action-gold" style="font-size:0.78rem; padding:4px 12px; text-decoration:none;">
                <i class="bi bi-box-seam"></i> Quản Lý Sản Phẩm
            </a>
            <a href="{{ route('bosuutap.danhsach') }}" class="btn-action-gold" style="font-size:0.78rem; padding:4px 12px; text-decoration:none; background:rgba(212,178,111,0.15); color:var(--km-gold); font-weight:600;">
                <i class="bi bi-collection-play"></i> Bộ Sưu Tập
            </a>
            <a href="{{ route('giamgia.danhsach') }}" class="btn-action-gold" style="font-size:0.78rem; padding:4px 12px; text-decoration:none; border-color:rgba(239,68,68,0.5); color:#ef4444; font-weight:600;">
                <i class="bi bi-tag-fill"></i> Khuyến Mãi Flash Sale
            </a>
            <a href="{{ route('nguoidung.index') }}" class="btn-action-gold" style="font-size:0.78rem; padding:4px 12px; text-decoration:none;">
                <i class="bi bi-people"></i> Quản Lý Tài Khoản
            </a>
            <a href="{{ route('thongke.doanhthu') }}" class="btn-action-gold" style="font-size:0.78rem; padding:4px 12px; text-decoration:none;">
                <i class="bi bi-bar-chart"></i> Thống Kê
            </a>
        </div>
    </div>
</div>
@endif

{{-- HERO CAROUSEL --}}
<div class="hero-carousel" style="margin: -1.5rem -12px 0;">
    <div id="heroCarousel" class="carousel slide">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="3"></button>
        </div>
        <div class="carousel-inner" style="height:100%;">
            <div class="carousel-item active" style="height:100%;" data-bs-interval="4000">
                <div class="carousel-slide" style="background: linear-gradient(rgba(17,19,23,0.65), rgba(17,19,23,0.65)), url('https://images.unsplash.com/photo-1594938298603-c8148c4dae35?w=1600') center/cover;">
                    <div class="carousel-content">
                        <div class="carousel-badge">Ưu đãi mùa lễ hội</div>
                        <h2 class="carousel-title">Bộ Sưu Tập Mùa Xuân 2025</h2>
                        <p class="carousel-description">Khám phá những thiết kế vest cao cấp mới nhất được hoàn thiện thủ công tỉ mỉ</p>
                        <a href="{{ url('/san-pham') }}" class="btn-carousel"><i class="bi bi-bag-check"></i> Khám Phá Ngay</a>
                    </div>
                </div>
            </div>
            <div class="carousel-item" style="height:100%;" data-bs-interval="4000">
                <div class="carousel-slide" style="background: linear-gradient(rgba(17,19,23,0.65), rgba(17,19,23,0.65)), url('https://images.unsplash.com/photo-1617127365659-c47fa864d8bc?w=1600') center/cover;">
                    <div class="carousel-content">
                        <div class="carousel-badge">Tuyển chọn thượng hạng</div>
                        <h2 class="carousel-title">Chất Liệu Nhập Khẩu Italy</h2>
                        <p class="carousel-description">Dòng vải cao cấp Wool 100%, đem lại form dáng chuẩn mực và sự êm ái tối đa</p>
                        <a href="{{ url('/san-pham') }}" class="btn-carousel"><i class="bi bi-eye"></i> Xem Tác Phẩm</a>
                    </div>
                </div>
            </div>
            <div class="carousel-item" style="height:100%;" data-bs-interval="4000">
                <div class="carousel-slide" style="background: linear-gradient(rgba(17,19,23,0.65), rgba(17,19,23,0.65)), url('https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=1600') center/cover;">
                    <div class="carousel-content">
                        <div class="carousel-badge">Dịch vụ may đo Bespoke</div>
                        <h2 class="carousel-title">Đặt Lịch May Đo Riêng</h2>
                        <p class="carousel-description">Tư vấn 1-1 cùng chuyên gia phong cách, lấy số đo chuẩn xác theo yêu cầu</p>
                        @if(Auth::check() && !Auth::user()->isCustomer())
                            <a href="{{ route('lichhen.quanly') }}" class="btn-carousel"><i class="bi bi-calendar2-check"></i> Quản Lý Lịch Hẹn</a>
                        @else
                            <a href="{{ route('lichhen.create') }}" class="btn-carousel"><i class="bi bi-calendar2-heart"></i> Đặt Lịch Hẹn</a>
                        @endif
                    </div>
                </div>
            </div>
            <div class="carousel-item" style="height:100%;" data-bs-interval="4000">
                <div class="carousel-slide" style="background: linear-gradient(rgba(17,19,23,0.65), rgba(17,19,23,0.65)), url('https://images.unsplash.com/photo-1490578474895-699cd4e2cf59?w=1600') center/cover;">
                    <div class="carousel-content">
                        <div class="carousel-badge">Đặc quyền hội viên VIP</div>
                        <h2 class="carousel-title">Chính Sách Hạng Thành Viên</h2>
                        <p class="carousel-description">Tích lũy chi tiêu nâng hạng VIP, nhận ưu đãi độc quyền và bảo dưỡng trọn đời</p>
                        <a href="{{ url('/san-pham') }}" class="btn-carousel"><i class="bi bi-award"></i> Trải Nghiệm Ngay</a>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <i class="bi bi-chevron-left" style="font-size:1.2rem;color:#fff;"></i>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <i class="bi bi-chevron-right" style="font-size:1.2rem;color:#fff;"></i>
        </button>
    </div>
</div>

{{-- ABOUT SECTION --}}
<section class="about-section">
    <div class="container about-content">
        <h2 class="section-title">Tinh Hoa May Đo <span>Kingsman Vietnam</span></h2>
        <p class="section-subtitle">Tôn vinh khí chất quý ông qua từng đường kim mũi chỉ chuẩn mực phong cách Savile Row</p>

        <div class="about-images">
            <div class="main-image">
                <img src="https://images.unsplash.com/photo-1594938298603-c8148c4dae35?w=800" alt="Kingsman Suit">
            </div>
            <div class="side-image">
                <img src="https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=800" alt="Kingsman Store">
            </div>
        </div>

        <div class="about-description">
            <h3><i class="bi bi-gem"></i> Triết Lý Thương Hiệu Kingsman</h3>
            <p>Chào mừng bạn đến với <strong style="color:var(--km-gold);">Kingsman Vietnam</strong> — không gian thời trang âu phục cao cấp dành cho những quý ông trân trọng sự chuẩn mực và phong cách tinh tế.</p>
            <p>Mỗi bộ vest không đơn thuần là một trang phục, mà là một tác phẩm may đo tôn dáng, mang lại sự tự tin tuyệt đối cho người mặc trong mọi sự kiện quan trọng.</p>
            <a href="{{ url('/san-pham') }}" class="btn-about"><i class="bi bi-arrow-right-circle"></i> Khám Phá Bộ Sưu Tập</a>
        </div>

    </div>
</section>

{{-- BỘ SƯU TẬP THỜI TRANG ĐỘC BẢN --}}
@if(isset($boSuuTaps) && $boSuuTaps->isNotEmpty())
<section class="collections-section">
    <div class="container">
        <div class="text-center mb-5">
            <span style="color:var(--km-gold); letter-spacing:2px; text-transform:uppercase; font-size:0.8rem; font-weight:600;">Exclusive Collections</span>
            <h2 class="section-title" style="margin-top:5px;">Bộ Sưu Tập <span>Độc Bản</span></h2>
            <p class="section-subtitle">Được tuyển chọn tỉ mỉ theo từng phong cách, tôn vinh đẳng cấp quý ông</p>
            @if(Auth::check() && (Auth::user()->vai_tro == 'admin' || Auth::user()->quyen == 'Admin'))
            <div style="margin-top:10px;">
                <a href="{{ route('bosuutap.danhsach') }}" class="btn-action-gold" style="font-size:0.8rem; padding:6px 16px;">
                    <i class="bi bi-gear"></i> Quản Lý Bộ Sưu Tập (Admin)
                </a>
            </div>
            @endif
        </div>

        <div class="row g-4">
            @foreach($boSuuTaps as $bst)
            <div class="col-lg-6 col-md-12">
                <div class="collection-banner-card">
                    <img src="{{ asset('images/' . ($bst->hinh_banner ?: 'banner_default.jpg')) }}"
                        alt="{{ $bst->ten_bo_suu_tap }}"
                        class="col-img"
                        onerror="this.src='https://images.unsplash.com/photo-1594938298603-c8148c4dae35?w=1000'">
                    <div style="position:absolute; inset:0; background: linear-gradient(to top, rgba(17,19,23,0.95) 0%, rgba(17,19,23,0.4) 60%, transparent 100%); display:flex; flex-direction:column; justify-content:flex-end; padding:28px;">
                        <span style="display:inline-block; align-self:flex-start; background:rgba(212,178,111,0.2); border:1px solid var(--km-gold); color:var(--km-gold); padding:3px 12px; border-radius:15px; font-size:0.75rem; font-weight:600; margin-bottom:8px;">
                            <i class="bi bi-gem"></i> {{ $bst->san_phams_count }} Mẫu Thiết Kế Tuyển Chọn
                        </span>
                        <h3 style="font-family:var(--km-font-heading); color:#fff; font-size:1.6rem; font-weight:600; margin-bottom:6px;">{{ $bst->ten_bo_suu_tap }}</h3>
                        <p style="color:#94a3b8; font-size:0.88rem; line-height:1.5; margin-bottom:15px; max-width:480px;">{{ \Illuminate\Support\Str::limit($bst->mo_ta ?: 'Tuyển tập những mẫu vest sang trọng chuẩn phong thái quý ông.', 110) }}</p>
                        <a href="{{ route('bosuutap.chitiet', $bst->id) }}" class="btn-carousel" style="align-self:flex-start; padding:8px 20px; font-size:0.82rem;">
                            <span>Khám Phá Bộ Sưu Tập</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- SẢN PHẨM NỔI BẬT --}}
<section style="padding: 70px 0; background: var(--km-bg-deep);">
    <div class="container">
        <h2 class="section-title">Kiệt Tác <span>Nổi Bật</span></h2>
        <p class="section-subtitle">Những mẫu trang phục được yêu thích và lựa chọn nhiều nhất tại Kingsman</p>

        <div class="row g-4">
            @forelse($sanPhamNoiBat as $sp)
            <div class="col-lg-3 col-md-6 col-12">
                <div class="product-card">
                    <div class="product-image">
                        @if($sp->hinh_anh)
                        <img src="{{ asset('images/' . $sp->hinh_anh) }}" alt="{{ $sp->ten_sp }}" loading="lazy">
                        @else
                        <div class="bg-black d-flex align-items-center justify-content-center h-100">
                            <i class="bi bi-image text-secondary" style="font-size:2.5rem;"></i>
                        </div>
                        @endif
                        <div class="product-overlay">
                            <a href="{{ url('/san-pham/' . $sp->id) }}" class="btn-view-overlay">
                                <i class="bi bi-eye"></i> Xem Chi Tiết
                            </a>
                        </div>
                    </div>
                    <div class="product-info">
                        <h5 class="product-name" title="{{ $sp->ten_sp }}">{{ $sp->ten_sp }}</h5>
                        @if($sp->phan_tram_giam > 0)
                            <p class="product-price" style="margin-bottom:12px;">
                                <span style="color:#ef4444;">{{ number_format($sp->gia_khuyen_mai, 0, ',', '.') }} ₫</span>
                                <span style="text-decoration:line-through; color:var(--km-text-dim); font-size:0.85rem; margin-left:6px; font-weight:normal;">{{ number_format($sp->gia_ban, 0, ',', '.') }} ₫</span>
                            </p>
                        @else
                            <p class="product-price" style="margin-bottom:12px;">{{ number_format($sp->gia, 0, ',', '.') }} ₫</p>
                        @endif
                        <a href="{{ url('/san-pham/' . $sp->id) }}" class="btn-view-detail">
                            <span>Chi tiết tác phẩm</span>
                            <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-12 text-center text-muted py-5">Chưa có sản phẩm nào.</div>
            @endforelse
        </div>

        <div class="text-center mt-5">
            <a href="{{ url('/san-pham') }}" class="btn-view-all">
                <span>Xem Tất Cả Bộ Sưu Tập</span>
                <i class="bi bi-arrow-right"></i>
            </a>
        </div>
    </div>
</section>

@endsection

@section('scripts')
<script>
    new bootstrap.Carousel(document.getElementById('heroCarousel'), {
        interval: 4000,
        ride: 'carousel',
        touch: true
    });
</script>
@endsection
