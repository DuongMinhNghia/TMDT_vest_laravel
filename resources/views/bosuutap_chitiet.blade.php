@extends('layouts.default_layout')

@section('title', $boSuuTap->ten_bo_suu_tap . ' - Kingsman Vietnam')

@section('content')
<style>
    .bst-detail-wrap {
        max-width: 1300px;
        margin: 0 auto;
        padding: 10px 15px 60px;
    }

    /* Hero Banner BST */
    .bst-hero {
        position: relative;
        height: 380px;
        border-radius: 16px;
        overflow: hidden;
        margin-bottom: 40px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.7);
        border: 1px solid rgba(212, 178, 111, 0.25);
    }

    .bst-hero img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: brightness(0.45);
    }

    .bst-hero-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(180deg, rgba(17, 19, 23, 0.2) 0%, rgba(17, 19, 23, 0.95) 100%);
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        padding: 40px 50px;
    }

    .bst-tag-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(212, 178, 111, 0.15);
        color: #D4B26F;
        border: 1px solid rgba(212, 178, 111, 0.35);
        border-radius: 20px;
        padding: 4px 14px;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        margin-bottom: 12px;
        width: fit-content;
    }

    .bst-hero-title {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 2.6rem;
        color: #F1F5F9;
        font-weight: 700;
        margin-bottom: 10px;
        letter-spacing: 0.5px;
    }

    .bst-hero-desc {
        color: #94A3B8;
        font-size: 0.95rem;
        max-width: 720px;
        line-height: 1.6;
        margin-bottom: 0;
    }

    /* Header danh sách */
    .bst-section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 28px;
        padding-bottom: 14px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .bst-section-title {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 1.6rem;
        color: #D4B26F;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-back-link {
        color: #94A3B8;
        text-decoration: none;
        font-size: 0.85rem;
        transition: color 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-back-link:hover {
        color: #D4B26F;
    }

    /* Product Cards */
    .suit-card {
        background: #141720;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .suit-card:hover {
        transform: translateY(-4px);
        border-color: #D4B26F;
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.6);
    }

    .suit-img-wrap {
        position: relative;
        height: 310px;
        overflow: hidden;
        background: #181B24;
    }

    .suit-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .suit-card:hover .suit-img {
        transform: scale(1.05);
    }

    .suit-form-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        background: rgba(17, 19, 23, 0.85);
        color: #F1F5F9;
        border: 1px solid rgba(255, 255, 255, 0.15);
        padding: 3px 10px;
        border-radius: 12px;
        font-size: 0.72rem;
        font-weight: 600;
        backdrop-filter: blur(4px);
    }

    .suit-body {
        padding: 18px 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .suit-category {
        color: #D4B26F;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .suit-title {
        font-family: 'Playfair Display', Georgia, serif;
        color: #F1F5F9;
        font-size: 1.15rem;
        font-weight: 600;
        margin-bottom: 8px;
        line-height: 1.4;
    }

    .suit-price {
        font-family: 'Plus Jakarta Sans', Arial, sans-serif;
        color: #D4B26F;
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 12px;
        font-variant-numeric: tabular-nums;
        line-height: 1.25;
        white-space: nowrap;
    }

    .suit-sizes {
        display: flex;
        flex-wrap: wrap;
        gap: 5px;
        margin-bottom: 16px;
    }

    .sz-chip {
        font-size: 0.72rem;
        font-weight: 600;
        padding: 2px 7px;
        border-radius: 4px;
    }
    .sz-in {
        background: rgba(34, 197, 94, 0.12);
        color: #22c55e;
        border: 1px solid rgba(34, 197, 94, 0.3);
    }
    .sz-out {
        background: rgba(255, 255, 255, 0.04);
        color: #64748B;
        border: 1px solid rgba(255, 255, 255, 0.06);
        text-decoration: line-through;
    }

    .btn-detail-gold {
        margin-top: auto;
        padding: 9px 18px;
        background: linear-gradient(135deg, #E8CA8C, #D4B26F);
        color: #0b0b0b;
        font-weight: 700;
        border-radius: 6px;
        text-decoration: none;
        text-align: center;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        font-size: 0.84rem;
        transition: all 0.25s;
    }
    .btn-detail-gold:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(212, 178, 111, 0.3);
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
            <span class="bst-tag-badge"><i class="bi bi-gem"></i> Bộ Sưu Tập Đặc Quyền</span>
            <h1 class="bst-hero-title">{{ $boSuuTap->ten_bo_suu_tap }}</h1>
            <p class="bst-hero-desc">{{ $boSuuTap->mo_ta ?: 'Tuyển tập những thiết kế áo vest quý ông may đo tinh xảo bậc nhất.' }}</p>
        </div>
    </div>

    {{-- HEADER DANH SÁCH --}}
    <div class="bst-section-header">
        <h2 class="bst-section-title">
            <i class="bi bi-grid-fill"></i> Thiết Kế Trong Bộ Sưu Tập ({{ $boSuuTap->sanPhams->count() }})
        </h2>
        <a href="{{ url('/') }}" class="btn-back-link">
            <i class="bi bi-arrow-left"></i> Quay lại Trang Chủ
        </a>
    </div>

    {{-- LƯỚI SẢN PHẨM --}}
    @if($boSuuTap->sanPhams->isEmpty())
    <div style="text-align:center; padding: 60px 20px; background:#141720; border:1px dashed rgba(255,255,255,0.1); border-radius:12px;">
        <i class="bi bi-inbox" style="font-size:3rem; color:#64748B;"></i>
        <h4 style="color:#F1F5F9; margin-top:12px; font-family:'Playfair Display',serif;">Bộ sưu tập này hiện chưa có sản phẩm</h4>
        <p style="color:#94A3B8; font-size:0.88rem;">Hãy đón chờ những thiết kế âu phục sắp tới từ Kingsman Vietnam.</p>
        <a href="{{ url('/san-pham') }}" class="btn-detail-gold" style="display:inline-flex; margin-top:10px;">
            <i class="bi bi-bag"></i> Khám Phá Sản Phẩm Khác
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
                            <span style="font-size:0.75rem; color:#64748B;">Chưa cập nhật size</span>
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
