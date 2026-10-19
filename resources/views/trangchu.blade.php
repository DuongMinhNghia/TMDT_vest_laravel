@extends('layouts.default_layout')

@section('title', 'Trang Chủ - Kingsman Vietnam')

@section('content')
<style>
    /* CAROUSEL */
    .hero-carousel .carousel {
        height: 600px;
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
        max-width: 800px;
        padding: 40px;
        animation: fadeInUp 0.8s ease-out;
    }

    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .carousel-badge {
        display: inline-block;
        background: linear-gradient(135deg, #d4af37, #c19b2f);
        color: #000;
        padding: 8px 25px;
        border-radius: 30px;
        font-weight: 700;
        font-size: 0.9rem;
        letter-spacing: 2px;
        margin-bottom: 20px;
    }

    .carousel-title {
        font-family: 'Playfair Display', serif;
        font-size: 3rem;
        font-weight: 700;
        color: #fff;
        margin-bottom: 20px;
        text-shadow: 2px 2px 10px rgba(0, 0, 0, 0.8);
    }

    .carousel-description {
        font-size: 1.1rem;
        color: #f0f0f0;
        margin-bottom: 30px;
    }

    .btn-carousel {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: linear-gradient(135deg, #d4af37, #c19b2f);
        color: #000;
        padding: 14px 35px;
        border-radius: 30px;
        text-decoration: none;
        font-weight: 700;
        transition: all 0.3s;
    }

    .btn-carousel:hover {
        transform: translateY(-3px);
        color: #000;
        box-shadow: 0 8px 25px rgba(212, 175, 55, 0.5);
    }

    .carousel-control-prev,
    .carousel-control-next {
        width: 55px;
        height: 55px;
        background: rgba(212, 175, 55, 0.3);
        border: 2px solid #d4af37;
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

    .carousel-control-prev {
        left: 25px;
    }

    .carousel-control-next {
        right: 25px;
    }

    .carousel-indicators button {
        width: 50px;
        height: 5px;
        border-radius: 5px;
        background: rgba(255, 255, 255, 0.5);
        border: none;
    }

    .carousel-indicators button.active {
        background: linear-gradient(90deg, #d4af37, #c19b2f);
        width: 70px;
    }

    /* ABOUT */
    .about-section {
        padding: 80px 0;
        background: linear-gradient(to bottom, #000, #0a0a0a, #000);
        position: relative;
        overflow: hidden;
    }

    .about-section::before {
        content: 'ABOUT US';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        font-size: 10rem;
        font-weight: 900;
        color: rgba(212, 175, 55, 0.03);
        font-family: 'Playfair Display', serif;
        pointer-events: none;
    }

    .about-content {
        position: relative;
        z-index: 1;
    }

    .section-title {
        font-family: 'Playfair Display', serif;
        font-size: 2.8rem;
        font-weight: 700;
        color: #d4af37;
        text-align: center;
        margin-bottom: 15px;
        letter-spacing: 3px;
    }

    .section-subtitle {
        text-align: center;
        color: #a0a0a0;
        font-size: 1.05rem;
        margin-bottom: 50px;
        max-width: 700px;
        margin-left: auto;
        margin-right: auto;
    }

    .about-images {
        display: flex;
        gap: 20px;
        margin-bottom: 40px;
    }

    .main-image,
    .side-image {
        flex: 1;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 10px 40px rgba(212, 175, 55, 0.2);
        transition: all 0.5s;
    }

    .main-image:hover {
        transform: scale(1.02);
    }

    .side-image:hover {
        transform: translateY(-10px);
    }

    .main-image img,
    .side-image img {
        width: 100%;
        height: 500px;
        object-fit: cover;
        display: block;
    }

    .about-description {
        background: linear-gradient(135deg, #1a1a1a, #2a2a2a);
        border: 2px solid #d4af37;
        border-radius: 15px;
        padding: 40px;
        margin-top: 40px;
        position: relative;
    }

    .about-description::before {
        content: '"';
        position: absolute;
        top: -30px;
        left: 40px;
        font-size: 8rem;
        color: #d4af37;
        opacity: 0.3;
        font-family: 'Playfair Display', serif;
    }

    .about-description h3 {
        font-family: 'Playfair Display', serif;
        color: #d4af37;
        font-size: 1.8rem;
        margin-bottom: 20px;
    }

    .about-description p {
        color: #c0c0c0;
        line-height: 1.8;
        margin-bottom: 15px;
    }

    .btn-about {
        display: inline-block;
        margin-top: 15px;
        padding: 13px 35px;
        border: 2px solid #d4af37;
        color: #d4af37;
        border-radius: 30px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-about:hover {
        background: #d4af37;
        color: #000;
        transform: translateY(-3px);
    }

    /* STATS */
    .stats-section {
        display: flex;
        justify-content: center;
        gap: 25px;
        margin-top: 50px;
        flex-wrap: wrap;
    }

    .stat-card {
        background: linear-gradient(135deg, #1a1a1a, #0f0f0f);
        border: 1px solid #d4af37;
        border-radius: 15px;
        padding: 30px 40px;
        text-align: center;
        transition: all 0.4s;
        min-width: 220px;
    }

    .stat-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(212, 175, 55, 0.3);
    }

    .stat-icon {
        background: linear-gradient(135deg, #d4af37, #c19b2f);
        width: 65px;
        height: 65px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 15px;
        font-size: 1.6rem;
        color: #000;
    }

    .stat-number {
        font-family: 'Playfair Display', serif;
        font-size: 2.8rem;
        font-weight: 700;
        color: #d4af37;
    }

    .stat-label {
        color: #a0a0a0;
        font-size: 0.9rem;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    /* PRODUCT CARDS */
    .product-card {
        background: linear-gradient(135deg, #1a1a1a, #2a2a2a);
        border: 1px solid #333;
        border-radius: 10px;
        overflow: hidden;
        transition: all 0.3s;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .product-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(212, 175, 55, 0.3);
        border-color: #d4af37;
    }

    .product-image {
        position: relative;
        overflow: hidden;
        height: 300px;
    }

    .product-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s;
    }

    .product-card:hover .product-image img {
        transform: scale(1.1);
    }

    .product-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.8);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s;
    }

    .product-card:hover .product-overlay {
        opacity: 1;
    }

    .btn-view {
        border: 2px solid #d4af37;
        color: #d4af37;
        padding: 10px 25px;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 600;
        transition: all 0.3s;
    }

    .btn-view:hover {
        background: #d4af37;
        color: #000;
    }

    .product-info {
        padding: 20px;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .product-name {
        color: #f0f0f0;
        font-size: 1.05rem;
        font-weight: 600;
        margin-bottom: 10px;
    }

    .product-price {
        color: #d4af37;
        font-size: 1.2rem;
        font-weight: 700;
        font-family: 'Playfair Display', serif;
        margin-bottom: 15px;
    }

    .btn-view-all {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: linear-gradient(135deg, #d4af37, #c19b2f);
        color: #000;
        padding: 15px 40px;
        border-radius: 30px;
        text-decoration: none;
        font-weight: 700;
        font-size: 1.05rem;
        transition: all 0.3s;
        box-shadow: 0 5px 20px rgba(212, 175, 55, 0.3);
    }

    .btn-view-all:hover {
        transform: translateY(-3px);
        color: #000;
        box-shadow: 0 8px 30px rgba(212, 175, 55, 0.5);
    }
</style>

{{-- HERO CAROUSEL --}}
<div class="hero-carousel" style="margin: -1.5rem -12px 0;">
    <div id="heroCarousel" class="carousel slide" style="height:600px;">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
            <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="3"></button>
        </div>
        <div class="carousel-inner" style="height:100%;">
            <div class="carousel-item active" style="height:100%;" data-bs-interval="4000">
                <div class="carousel-slide" style="background: linear-gradient(rgba(0,0,0,0.55),rgba(0,0,0,0.55)), url('https://images.unsplash.com/photo-1594938298603-c8148c4dae35?w=1600') center/cover;">
                    <div class="carousel-content">
                        <div class="carousel-badge">🎉 SALE UP TO 50%</div>
                        <h2 class="carousel-title">BỘ SƯU TẬP MÙA XUÂN 2025</h2>
                        <p class="carousel-description">Khám phá những thiết kế vest cao cấp mới nhất với ưu đãi đặc biệt</p>
                        <a href="{{ url('/san-pham') }}" class="btn-carousel"><i class="bi bi-bag-check"></i> Mua Ngay</a>
                    </div>
                </div>
            </div>
            <div class="carousel-item" style="height:100%;" data-bs-interval="4000">
                <div class="carousel-slide" style="background: linear-gradient(rgba(0,0,0,0.55),rgba(0,0,0,0.55)), url('https://images.unsplash.com/photo-1617127365659-c47fa864d8bc?w=1600') center/cover;">
                    <div class="carousel-content">
                        <div class="carousel-badge">✨ NEW ARRIVAL</div>
                        <h2 class="carousel-title">VEST CAO CẤP ITALY</h2>
                        <p class="carousel-description">Chất liệu nhập khẩu 100% từ Italy, may đo theo số đo cá nhân</p>
                        <a href="{{ url('/san-pham') }}" class="btn-carousel"><i class="bi bi-eye"></i> Xem Ngay</a>
                    </div>
                </div>
            </div>
            <div class="carousel-item" style="height:100%;" data-bs-interval="4000">
                <div class="carousel-slide" style="background: linear-gradient(rgba(0,0,0,0.55),rgba(0,0,0,0.55)), url('https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=1600') center/cover;">
                    <div class="carousel-content">
                        <div class="carousel-badge">💼 VIP SERVICE</div>
                        <h2 class="carousel-title">DỊCH VỤ MAY ĐO CAO CẤP</h2>
                        <p class="carousel-description">Tư vấn 1-1 với chuyên gia, đo đạc tại nhà miễn phí</p>
                        <a href="{{ url('/san-pham') }}" class="btn-carousel"><i class="bi bi-telephone"></i> Đặt Lịch</a>
                    </div>
                </div>
            </div>
            <div class="carousel-item" style="height:100%;" data-bs-interval="4000">
                <div class="carousel-slide" style="background: linear-gradient(rgba(0,0,0,0.55),rgba(0,0,0,0.55)), url('https://images.unsplash.com/photo-1490578474895-699cd4e2cf59?w=1600') center/cover;">
                    <div class="carousel-content">
                        <div class="carousel-badge">🎁 GIFT VOUCHER</div>
                        <h2 class="carousel-title">QUÀ TẶNG ĐẶC BIỆT</h2>
                        <p class="carousel-description">Tặng voucher 500K cho khách hàng mới, miễn phí sửa chữa trọn đời</p>
                        <a href="{{ url('/san-pham') }}" class="btn-carousel"><i class="bi bi-gift"></i> Nhận Ngay</a>
                    </div>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <i class="bi bi-chevron-left" style="font-size:1.5rem;color:#fff;"></i>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <i class="bi bi-chevron-right" style="font-size:1.5rem;color:#fff;"></i>
        </button>
    </div>
</div>

{{-- ABOUT SECTION --}}
<section class="about-section">
    <div class="container about-content">
        <h2 class="section-title">Kingsman Vietnam</h2>
        <p class="section-subtitle">Điểm đến lý tưởng cho những ai yêu thích sự lịch lãm và phong cách trong trang phục</p>

        <div class="about-images">
            <div class="main-image">
                <img src="https://images.unsplash.com/photo-1594938298603-c8148c4dae35?w=800" alt="Kingsman Suit">
            </div>
            <div class="side-image">
                <img src="https://images.unsplash.com/photo-1507679799987-c73779587ccf?w=800" alt="Kingsman Store">
            </div>
        </div>

        <div class="about-description">
            <h3><i class="bi bi-gem"></i> Về Kingsman Vietnam</h3>
            <p>Chào mừng bạn đến với <strong style="color:#d4af37;">Kingsman Vietnam</strong>, điểm đến lý tưởng cho những ai yêu thích sự lịch lãm và phong cách trong trang phục! Chúng tôi tự hào là cửa hàng chuyên may đo vest chất lượng hàng đầu tại Việt Nam.</p>
            <p>Với đội ngũ thợ may giàu kinh nghiệm và tâm huyết, chúng tôi cam kết mang đến cho bạn những bộ vest hoàn hảo, vừa vặn từng đường kim mũi chỉ.</p>
            <a href="{{ url('/san-pham') }}" class="btn-about"><i class="bi bi-arrow-right-circle"></i> Xem Sản Phẩm</a>
        </div>

        <div class="stats-section">
            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-bag-check-fill"></i></div>
                <div class="stat-number">500+</div>
                <div class="stat-label">Sản Phẩm</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-award-fill"></i></div>
                <div class="stat-number">10+</div>
                <div class="stat-label">Năm Kinh Nghiệm</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="bi bi-star-fill"></i></div>
                <div class="stat-number">200+</div>
                <div class="stat-label">Hợp Đồng</div>
            </div>
        </div>
    </div>
</section>

{{-- SẢN PHẨM NỔI BẬT --}}
<section style="padding: 60px 0; background:#111;">
    <div class="container">
        <h2 class="section-title"><i class="bi bi-grid-3x3-gap-fill"></i> Sản Phẩm Nổi Bật</h2>
        <p class="section-subtitle">Những thiết kế được yêu thích nhất tại Kingsman Vietnam</p>

        <div class="row g-4">
            @forelse($sanPhamNoiBat as $sp)
            <div class="col-lg-3 col-md-6">
                <div class="product-card">
                    <div class="product-image">
                        @if($sp->hinh_anh)
                        <img src="{{ asset('images/' . $sp->hinh_anh) }}" alt="{{ $sp->ten_sp }}">
                        @else
                        <div class="bg-secondary d-flex align-items-center justify-content-center" style="height:300px;">
                            <i class="bi bi-image text-white" style="font-size:3rem;"></i>
                        </div>
                        @endif
                        <div class="product-overlay">
                            <a href="{{ url('/san-pham') }}" class="btn-view">
                                <i class="bi bi-eye"></i> Xem Chi Tiết
                            </a>
                        </div>
                    </div>
                    <div class="product-info">
                        <h5 class="product-name">{{ $sp->ten_sp }}</h5>
                        <p class="product-price">{{ number_format($sp->gia, 0, ',', '.') }} đ</p>
                        <a href="{{ url('/san-pham') }}" class="btn-view text-center mt-auto">
                            <i class="bi bi-cart-plus"></i> Xem Thêm
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
                <i class="bi bi-grid-3x3-gap"></i> Xem Tất Cả Sản Phẩm <i class="bi bi-arrow-right"></i>
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