@extends('layouts.default_layout')

@section('title', $boSuuTap->ten_bo_suu_tap . ' - Kingsman Vietnam')

@section('content')
<style>
    .bst-detail-wrap {
        max-width: 1300px;
        margin: 0 auto;
        padding: 0 15px 60px;
    }

    /* Hero Banner BST */
    .bst-hero {
        position: relative;
        height: 380px;
        border-radius: 18px;
        overflow: hidden;
        margin-bottom: 45px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.7);
        border: 1px solid rgba(197, 160, 89, 0.3);
    }

    .bst-hero img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: brightness(0.5);
    }

    .bst-hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(0, 0, 0, 0.2) 0%, rgba(10, 10, 10, 0.95) 100%);
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 40px 50px;
    }

    .bst-tag-badge {
        display: inline-block;
        background: rgba(197, 160, 89, 0.25);
        color: #d4af37;
        border: 1px solid #d4af37;
        border-radius: 20px;
        padding: 4px 16px;
        font-size: 0.82rem;
        font-weight: 700;
        letter-spacing: 2px;
        text-transform: uppercase;
        margin-bottom: 12px;
        width: fit-content;
    }

    .bst-hero-title {
        font-family: 'Playfair Display', serif;
        font-size: 2.8rem;
        color: #fff;
        font-weight: 700;
        margin-bottom: 12px;
        text-shadow: 0 2px 10px rgba(0, 0, 0, 0.8);
    }

    .bst-hero-desc {
        color: #ddd;
        font-size: 1.05rem;
        max-width: 750px;
        line-height: 1.6;
        margin-bottom: 0;
    }

    /* Grid sản phẩm trong BST */
    .bst-section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 1px solid #282828;
    }

    .bst-section-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.8rem;
        color: #C5A059;
        margin: 0;
    }

    .btn-back-link {
        color: #aaa;
        text-decoration: none;
        font-size: 0.92rem;
        transition: color 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .btn-back-link:hover {
        color: #C5A059;
    }

    /* Product Cards */
    .suit-card {
        background: #141414;
        border: 1px solid #262626;
        border-radius: 14px;
        overflow: hidden;
        transition: all 0.35s ease;
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .suit-card:hover {
        transform: translateY(-5px);
        border-color: #C5A059;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.6);
    }

    .suit-img-wrap {
        position: relative;
        height: 320px;
        overflow: hidden;
        background: #1e1e1e;
    }

    .suit-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s;
    }

    .suit-card:hover .suit-img {
        transform: scale(1.06);
    }

    .suit-form-badge {
        position: absolute;
        top: 14px;
        left: 14px;
        background: rgba(0, 0, 0, 0.75);
        color: #f0f0f0;
        border: 1px solid rgba(255, 255, 255, 0.25);
        padding: 3px 10px;
        border-radius: 15px;
        font-size: 0.78rem;
        backdrop-filter: blur(4px);
    }

    .suit-body {
        padding: 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .suit-category {
        color: #C5A059;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .suit-title {
        font-family: 'Playfair Display', serif;
        color: #fff;
        font-size: 1.15rem;
        font-weight: 600;
        margin-bottom: 10px;
        line-height: 1.4;
    }

    .suit-price {
        font-family: 'Playfair Display', serif;
        color: #C5A059;
        font-size: 1.3rem;
        font-weight: 700;
        margin-bottom: 14px;
    }

    /* Available sizes */
    .suit-sizes {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-bottom: 18px;
    }

    .sz-chip {
        font-size: 0.75rem;
        font-weight: 600;
        padding: 2px 7px;
        border-radius: 4px;
    }
    .sz-in {
        background: rgba(46, 204, 113, 0.15);
        color: #2ecc71;
        border: 1px solid rgba(46, 204, 113, 0.35);
    }
    .sz-out {
        background: rgba(231, 76, 60, 0.1);
        color: #666;
        border: 1px solid rgba(255, 255, 255, 0.08);
        text-decoration: line-through;
    }

    .btn-detail-gold {
        margin-top: auto;
        padding: 10px 18px;
        background: linear-gradient(135deg, #d4af37, #c19b2f);
        color: #0b0b0b;
        font-weight: 700;
        border-radius: 25px;
        text-decoration: none;
        text-align: center;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        font-size: 0.88rem;
        transition: all 0.25s;
    }
    .btn-detail-gold:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(212, 175, 55, 0.35);
        color: #000;
    }
</style>

<div class="bst-detail-wrap">

    {{-- BANNER HERO --}}
    <div class="bst-hero">
        <img src="{{ asset('images/' . ($boSuuTap->hinh_banner ?: 'banner_default.jpg')) }}"
            alt="{{ $boSuuTap->ten_bo_suu_tap }}"
            onerror="this.src='https://images.unsplash.com/photo-1594938298603-c8148c4dae35?w=1600'">
        <div class="bst-hero-overlay">
            <span class="bst-tag-badge"><i class="bi bi-gem"></i> Bộ Sưu Tập Đặc Biệt</span>
            <h1 class="bst-hero-title">{{ $boSuuTap->ten_bo_suu_tap }}</h1>
            <p class="bst-hero-desc">{{ $boSuuTap->mo_ta ?: 'Tuyển tập những thiết kế áo vest quý ông may đo tinh xảo bậc nhất.' }}</p>
        </div>
    </div>

    {{-- HEADER DANH SÁCH --}}
    <div class="bst-section-header">
        <h2 class="bst-section-title">
            <i class="bi bi-grid-fill"></i> Các Mẫu Áo Vest Trong Bộ Sưu Tập ({{ $boSuuTap->sanPhams->count() }})
        </h2>
        <a href="{{ url('/') }}" class="btn-back-link">
            <i class="bi bi-arrow-left"></i> Về Trang Chủ
        </a>
    </div>

    {{-- LƯỚI SẢN PHẨM --}}
    @if($boSuuTap->sanPhams->isEmpty())
    <div style="text-align:center; padding: 60px 20px; background:#141414; border:1px dashed #333; border-radius:14px;">
        <i class="bi bi-inbox" style="font-size:3rem; color:#666;"></i>
        <h4 style="color:#aaa; margin-top:12px;">Bộ sưu tập này hiện chưa có sản phẩm</h4>
        <p style="color:#777; font-size:0.9rem;">Quản trị viên có thể vào trang Quản lý Bộ sưu tập để thêm áo vest vào BST này.</p>
        <a href="{{ url('/san-pham') }}" class="btn-detail-gold" style="display:inline-flex; margin-top:10px;">
            <i class="bi bi-bag"></i> Xem Tất Cả Sản Phẩm
        </a>
    </div>
    @else
    <div class="row g-4">
        @foreach($boSuuTap->sanPhams as $sp)
        <div class="col-lg-3 col-md-6">
            <div class="suit-card">
                <div class="suit-img-wrap">
                    <img src="{{ asset('images/' . ($sp->hinh_anh ?: 'placeholder.jpg')) }}"
                        alt="{{ $sp->ten_sp }}"
                        class="suit-img"
                        onerror="this.src='https://via.placeholder.com/350x450?text=Kingsman+Vest'">
                    <span class="suit-form-badge">{{ $sp->form_dang ?? 'Slim-fit' }}</span>
                </div>

                <div class="suit-body">
                    <div class="suit-category">{{ $sp->danhMuc->ten_danh_muc ?? 'Vest Quý Ông' }}</div>
                    <h4 class="suit-title">{{ $sp->ten_sp }}</h4>
                    <div class="suit-price">{{ number_format($sp->gia_ban) }} VNĐ</div>

                    {{-- Sizes --}}
                    <div class="suit-sizes">
                        @forelse($sp->sizes as $sz)
                            @if($sz->so_luong_ton > 0)
                                <span class="sz-chip sz-in" title="Còn {{ $sz->so_luong_ton }} chiếc">{{ $sz->ten_size }}</span>
                            @else
                                <span class="sz-chip sz-out" title="Tạm hết size">{{ $sz->ten_size }}</span>
                            @endif
                        @empty
                            <span style="font-size:0.75rem; color:#666;">Chưa cập nhật size</span>
                        @endforelse
                    </div>

                    <a href="{{ url('/san-pham/' . $sp->id) }}" class="btn-detail-gold">
                        <i class="bi bi-eye"></i> Xem Chi Tiết
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</div>
@endsection
