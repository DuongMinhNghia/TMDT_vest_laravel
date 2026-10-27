@extends('layouts.default_layout')

@section('title', 'Đặt Lịch Trải Nghiệm Showroom - Kingsman Vietnam')

@section('content')
<style>
    .booking-page-wrap {
        background: #111317;
        min-height: calc(100vh - 200px);
        padding: 40px 0 90px;
        color: #F1F5F9;
    }

    /* HERO BANNER NHỎ */
    .booking-hero-header {
        text-align: center;
        max-width: 800px;
        margin: 0 auto 40px;
    }

    .booking-hero-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 5px 16px;
        border-radius: 30px;
        background: rgba(212, 178, 111, 0.12);
        border: 1px solid rgba(212, 178, 111, 0.35);
        color: #D4B26F;
        font-size: 0.78rem;
        font-weight: 700;
        letter-spacing: 1.5px;
        text-transform: uppercase;
        margin-bottom: 14px;
    }

    .booking-hero-title {
        font-family: 'Playfair Display', Georgia, serif;
        color: #F1F5F9;
        font-size: 2.3rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        margin-bottom: 12px;
    }

    .booking-hero-title span {
        color: #D4B26F;
    }

    .booking-hero-desc {
        color: #94A3B8;
        font-size: 0.96rem;
        line-height: 1.6;
        margin: 0 auto;
    }

    /* CARD CHÍNH */
    .booking-card {
        background: #141720;
        border: 1px solid rgba(212, 178, 111, 0.22);
        border-radius: 18px;
        padding: 36px 40px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.45);
        position: relative;
    }

    .card-section-title {
        color: #D4B26F;
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 1.25rem;
        font-weight: 700;
        letter-spacing: 0.5px;
        margin-bottom: 18px;
        display: flex;
        align-items: center;
        gap: 8px;
        padding-bottom: 10px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
    }

    .field-label {
        display: block;
        color: #CBD5E1;
        font-size: 0.82rem;
        font-weight: 600;
        letter-spacing: 0.3px;
        margin-bottom: 7px;
    }

    /* INPUT GROUPS CÓ ICON */
    .input-icon-group {
        position: relative;
    }

    .input-icon-group .input-icon {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: #D4B26F;
        font-size: 1.05rem;
        pointer-events: none;
        z-index: 2;
    }

    .input-icon-group .form-control-custom,
    .input-icon-group .form-select-custom {
        padding-left: 42px !important;
    }

    .form-control-custom, .form-select-custom {
        width: 100%;
        background: #181B24 !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: #F1F5F9 !important;
        border-radius: 8px !important;
        padding: 11px 16px;
        font-size: 0.92rem;
        transition: all 0.25s ease;
    }

    .form-control-custom:focus, .form-select-custom:focus {
        border-color: #D4B26F !important;
        background: #1C202C !important;
        box-shadow: 0 0 0 3px rgba(212, 178, 111, 0.2) !important;
        outline: none;
    }

    .form-control-custom:disabled {
        background: rgba(255, 255, 255, 0.04) !important;
        color: #64748B !important;
        border-color: rgba(255, 255, 255, 0.06) !important;
    }

    /* THẺ PREVIEW SẢN PHẨM TUYỂN CHỌN */
    .vest-preview-card {
        background: linear-gradient(135deg, #181B24 0%, #1F2433 100%);
        border: 1px solid rgba(212, 178, 111, 0.4);
        border-radius: 12px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        gap: 18px;
        margin-bottom: 25px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
    }

    .vest-preview-card img {
        width: 82px;
        height: 100px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid rgba(212, 178, 111, 0.4);
    }

    /* LƯỚI THẺ CHỌN DỊCH VỤ MAY ĐO (INTERACTIVE CARDS) */
    .service-options-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 12px;
        margin-bottom: 22px;
    }

    .service-option-item {
        position: relative;
    }

    .service-option-item input[type="radio"] {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .service-option-label {
        display: flex;
        flex-direction: column;
        height: 100%;
        background: #181B24;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        padding: 14px 16px;
        cursor: pointer;
        transition: all 0.25s ease;
        position: relative;
    }

    .service-option-label:hover {
        border-color: rgba(212, 178, 111, 0.5);
        background: #1B202D;
        transform: translateY(-2px);
    }

    .service-option-item input[type="radio"]:checked + .service-option-label {
        background: rgba(212, 178, 111, 0.12);
        border-color: #D4B26F;
        box-shadow: 0 4px 18px rgba(212, 178, 111, 0.25);
    }

    .service-option-item input[type="radio"]:checked + .service-option-label::after {
        content: '\F26E';
        font-family: 'bootstrap-icons';
        position: absolute;
        top: 10px;
        right: 10px;
        color: #D4B26F;
        font-size: 1rem;
        font-weight: 700;
    }

    .service-icon {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        background: rgba(212, 178, 111, 0.15);
        color: #D4B26F;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.15rem;
        margin-bottom: 10px;
    }

    .service-title {
        font-weight: 700;
        font-size: 0.92rem;
        color: #F1F5F9;
        margin-bottom: 4px;
        line-height: 1.3;
    }

    .service-desc {
        color: #94A3B8;
        font-size: 0.78rem;
        line-height: 1.4;
        margin: 0;
    }

    /* CHIP SELECTOR SỐ NGƯỜI ĐI CÙNG */
    .party-size-chips {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
        margin-bottom: 22px;
    }

    .party-chip-item {
        position: relative;
        flex: 1;
        min-width: 100px;
    }

    .party-chip-item input[type="radio"] {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .party-chip-label {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        background: #181B24;
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 8px;
        padding: 10px 8px;
        cursor: pointer;
        transition: all 0.25s ease;
        height: 100%;
    }

    .party-chip-label:hover {
        border-color: #D4B26F;
        background: #1B202D;
    }

    .party-chip-item input[type="radio"]:checked + .party-chip-label {
        background: linear-gradient(135deg, #D4B26F, #B89246);
        border-color: #D4B26F;
        color: #0c0e12;
        font-weight: 700;
        box-shadow: 0 4px 14px rgba(212, 178, 111, 0.3);
    }

    .party-chip-item input[type="radio"]:checked + .party-chip-label small {
        color: #0c0e12 !important;
        font-weight: 600;
    }

    /* NÚT SUBMIT LỚN */
    .btn-submit-booking {
        width: 100%;
        padding: 16px;
        border-radius: 8px;
        background: linear-gradient(135deg, #D4B26F 0%, #B89246 100%);
        color: #0c0e12;
        border: none;
        font-weight: 700;
        font-size: 1rem;
        letter-spacing: 0.8px;
        text-transform: uppercase;
        cursor: pointer;
        transition: all 0.25s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 8px 24px rgba(212, 178, 111, 0.3);
    }

    .btn-submit-booking:hover {
        background: linear-gradient(135deg, #E8CA8C 0%, #C9A458 100%);
        color: #000;
        transform: translateY(-2px);
        box-shadow: 0 12px 30px rgba(212, 178, 111, 0.45);
    }

    /* CỘT PHẢI: VIP INVITATION CARD */
    .vip-invitation-card {
        background: linear-gradient(145deg, #161922 0%, #12141A 100%);
        border: 1px solid rgba(212, 178, 111, 0.35);
        border-radius: 18px;
        padding: 36px 32px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.45);
        position: sticky;
        top: 24px;
    }

    .vip-crest-wrap {
        text-align: center;
        padding-bottom: 22px;
        margin-bottom: 24px;
        border-bottom: 1px solid rgba(212, 178, 111, 0.2);
    }

    .vip-crest-icon {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: rgba(212, 178, 111, 0.12);
        border: 2px solid #D4B26F;
        color: #D4B26F;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        margin-bottom: 12px;
        box-shadow: 0 0 20px rgba(212, 178, 111, 0.25);
    }

    .vip-perk-item {
        display: flex;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 22px;
        padding-bottom: 18px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    }

    .vip-perk-item:last-of-type {
        border-bottom: none;
        margin-bottom: 16px;
        padding-bottom: 0;
    }

    .vip-perk-icon-wrap {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        background: rgba(212, 178, 111, 0.12);
        border: 1px solid rgba(212, 178, 111, 0.3);
        color: #D4B26F;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
        flex-shrink: 0;
    }

    .vip-perk-title {
        color: #F1F5F9;
        font-weight: 700;
        font-size: 0.95rem;
        margin-bottom: 3px;
    }

    .vip-perk-desc {
        color: #94A3B8;
        font-size: 0.84rem;
        line-height: 1.5;
        margin: 0;
    }

    .showroom-contact-box {
        background: #181B24;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 12px;
        padding: 16px 20px;
        margin-top: 24px;
    }

    @media (max-width: 768px) {
        .booking-card {
            padding: 24px 20px;
        }
        .service-options-grid {
            grid-template-columns: 1fr;
        }
        .party-size-chips {
            flex-direction: column;
        }
    }
</style>

<div class="booking-page-wrap">
    <div class="container">

        {{-- HERO HEADER --}}
        <div class="booking-hero-header">
            <div class="booking-hero-badge">
                <i class="bi bi-gem me-1"></i> Private Bespoke Experience
            </div>
            <h1 class="booking-hero-title">
                Đặt Lịch Trải Nghiệm <span>Showroom Kingsman</span>
            </h1>
            <p class="booking-hero-desc">
                Không gian may đo riêng tư chuẩn Savile Row cùng các Master Tailor hàng đầu. Hãy chọn thời gian thuận tiện nhất để chuyên viên Kingsman chuẩn bị đón tiếp Quý khách chu đáo.
            </p>
        </div>

        <div class="row g-4 align-items-start">

            {{-- CỘT TRÁI: FORM ĐẶT LỊCH HẸN --}}
            <div class="col-lg-7">
                <div class="booking-card">

                    {{-- NẾU CÓ SẢN PHẨM GẮN TỪ TRANG CHI TIẾT SẢN PHẨM --}}
                    @if($sanPham)
                    <div class="vest-preview-card">
                        <img src="{{ asset('images/' . $sanPham->hinh_anh) }}" alt="{{ $sanPham->ten_sp }}">
                        <div class="flex-grow-1">
                            <span class="badge mb-1" style="background:rgba(212,178,111,0.18); border:1px solid #D4B26F; color:#D4B26F; font-size: 0.72rem; font-weight:700;">
                                <i class="bi bi-check2-circle me-1"></i>Mẫu vest Quý khách chọn thử
                            </span>
                            <h5 style="color:#F1F5F9; font-family:'Playfair Display',serif; font-size: 1.1rem; font-weight:700; margin: 4px 0;">{{ $sanPham->ten_sp }}</h5>
                            <div style="font-size: 0.82rem; color:#94A3B8;">
                                Mã: <strong style="color:#CBD5E1;">{{ $sanPham->ma_sp }}</strong> | Chất liệu: <strong style="color:#CBD5E1;">{{ $sanPham->chat_lieu ?? 'Cao cấp' }}</strong>
                            </div>
                            <div class="mt-2" style="color:#D4B26F; font-weight:700; font-size:1.05rem;">
                                {{ number_format($sanPham->gia_khuyen_mai) }} VNĐ
                            </div>
                        </div>
                    </div>
                    @endif

                    <form action="{{ route('lichhen.store') }}" method="POST">
                        @csrf
                        @if($sanPham)
                            <input type="hidden" name="san_pham_quan_tam_id" value="{{ $sanPham->id }}">
                        @endif

                        {{-- BƯỚC 1: THÔNG TIN QUÝ KHÁCH --}}
                        <div class="card-section-title">
                            <i class="bi bi-person-bounding-box"></i> 1. Thông Tin Quý Khách
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="field-label">Họ và tên Quý khách *</label>
                                <div class="input-icon-group">
                                    <i class="bi bi-person-fill input-icon"></i>
                                    <input type="text" class="form-control-custom" value="{{ $khachHang->ho_ten ?? Auth::user()->name }}" disabled>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="field-label">Số điện thoại liên hệ *</label>
                                <div class="input-icon-group">
                                    <i class="bi bi-telephone-fill input-icon"></i>
                                    <input type="text" name="so_dien_thoai" class="form-control-custom" value="{{ old('so_dien_thoai', $khachHang->so_dien_thoai) }}" placeholder="VD: 0987654321" required>
                                </div>
                            </div>
                        </div>

                        {{-- BƯỚC 2: LOẠI DỊCH VỤ TRẢI NGHIỆM --}}
                        <div class="card-section-title">
                            <i class="bi bi-scissors"></i> 2. Nhu Cầu / Loại Dịch Vụ
                        </div>
                        <div class="service-options-grid">
                            @php
                                $valDv = old('loai_dich_vu', $sanPham ? 'Thử vest có sẵn tại tiệm' : 'Thử vest có sẵn tại tiệm');
                            @endphp

                            <div class="service-option-item">
                                <input type="radio" name="loai_dich_vu" id="dv_thuviet" value="Thử vest có sẵn tại tiệm" {{ $valDv == 'Thử vest có sẵn tại tiệm' ? 'checked' : '' }} required>
                                <label for="dv_thuviet" class="service-option-label">
                                    <div class="service-icon"><i class="bi bi-collection-fill"></i></div>
                                    <div class="service-title">Thử vest có sẵn</div>
                                    <p class="service-desc">Trải nghiệm các bộ sưu tập suit & tuxedo sẵn có tại tiệm</p>
                                </label>
                            </div>

                            <div class="service-option-item">
                                <input type="radio" name="loai_dich_vu" id="dv_cuoi" value="May đo vest cưới Bespoke (Chú rể)" {{ $valDv == 'May đo vest cưới Bespoke (Chú rể)' ? 'checked' : '' }}>
                                <label for="dv_cuoi" class="service-option-label">
                                    <div class="service-icon"><i class="bi bi-heart-fill"></i></div>
                                    <div class="service-title">May đo vest cưới</div>
                                    <p class="service-desc">Tuxedo & Suit chú rể đo theo chuẩn vóc dáng ngày trọng đại</p>
                                </label>
                            </div>

                            <div class="service-option-item">
                                <input type="radio" name="loai_dich_vu" id="dv_doanhnhan" value="May đo vest doanh nhân cao cấp" {{ $valDv == 'May đo vest doanh nhân cao cấp' ? 'checked' : '' }}>
                                <label for="dv_doanhnhan" class="service-option-label">
                                    <div class="service-icon"><i class="bi bi-briefcase-fill"></i></div>
                                    <div class="service-title">Vest doanh nhân</div>
                                    <p class="service-desc">Suit dạ tiệc, ngoại giao cao cấp tôn vinh phong thái quyền lực</p>
                                </label>
                            </div>

                            <div class="service-option-item">
                                <input type="radio" name="loai_dich_vu" id="dv_chinhsua" value="Chỉnh sửa vest theo số đo riêng" {{ $valDv == 'Chỉnh sửa vest theo số đo riêng' ? 'checked' : '' }}>
                                <label for="dv_chinhsua" class="service-option-label">
                                    <div class="service-icon"><i class="bi bi-rulers"></i></div>
                                    <div class="service-title">Chỉnh sửa phom dáng</div>
                                    <p class="service-desc">Bóp, nới, cân chỉnh độ dài và tỉ lệ theo chỉ số riêng</p>
                                </label>
                            </div>

                            <div class="service-option-item">
                                <input type="radio" name="loai_dich_vu" id="dv_tuvan" value="Tư vấn phong cách & Chọn vải may" {{ $valDv == 'Tư vấn phong cách & Chọn vải may' ? 'checked' : '' }}>
                                <label for="dv_tuvan" class="service-option-label">
                                    <div class="service-icon"><i class="bi bi-palette-fill"></i></div>
                                    <div class="service-title">Tư vấn vải nhập khẩu</div>
                                    <p class="service-desc">Khám phá catalogue vải Ý, Anh quốc (Scabal, Loro Piana...)</p>
                                </label>
                            </div>
                        </div>

                        {{-- BƯỚC 3: THỜI GIAN & SỐ NGƯỜI --}}
                        <div class="card-section-title">
                            <i class="bi bi-clock-history"></i> 3. Thời Gian & Số Người Đến Tiệm
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="field-label">Thời gian đến showroom *</label>
                                <div class="input-icon-group">
                                    <i class="bi bi-calendar2-week-fill input-icon"></i>
                                    <input type="datetime-local" name="thoi_gian_hen" class="form-control-custom" min="{{ date('Y-m-d\TH:i', strtotime('+1 hour')) }}" value="{{ old('thoi_gian_hen') }}" required>
                                </div>
                                <small style="font-size: 0.76rem; color:#94A3B8; display:block; margin-top:5px;">
                                    <i class="bi bi-info-circle me-1 text-warning"></i> Giờ mở cửa: 08:30 - 21:00 tất cả các ngày trong tuần
                                </small>
                            </div>

                            <div class="col-md-6">
                                <label class="field-label">Số người đi cùng Quý khách</label>
                                @php $valParty = old('so_nguoi_di_cung', 1); @endphp
                                <div class="party-size-chips">
                                    <div class="party-chip-item">
                                        <input type="radio" name="so_nguoi_di_cung" id="party_1" value="1" {{ $valParty == 1 ? 'checked' : '' }}>
                                        <label for="party_1" class="party-chip-label">
                                            <strong>1 Người</strong>
                                            <small style="font-size:0.72rem; color:#94A3B8;">Chỉ mình tôi</small>
                                        </label>
                                    </div>
                                    <div class="party-chip-item">
                                        <input type="radio" name="so_nguoi_di_cung" id="party_2" value="2" {{ $valParty == 2 ? 'checked' : '' }}>
                                        <label for="party_2" class="party-chip-label">
                                            <strong>2 Người</strong>
                                            <small style="font-size:0.72rem; color:#94A3B8;">Cùng bạn/vợ</small>
                                        </label>
                                    </div>
                                    <div class="party-chip-item">
                                        <input type="radio" name="so_nguoi_di_cung" id="party_3" value="3" {{ $valParty == 3 ? 'checked' : '' }}>
                                        <label for="party_3" class="party-chip-label">
                                            <strong>3 Người</strong>
                                            <small style="font-size:0.72rem; color:#94A3B8;">Gia đình</small>
                                        </label>
                                    </div>
                                    <div class="party-chip-item">
                                        <input type="radio" name="so_nguoi_di_cung" id="party_4" value="4" {{ $valParty == 4 ? 'checked' : '' }}>
                                        <label for="party_4" class="party-chip-label">
                                            <strong>4+ Người</strong>
                                            <small style="font-size:0.72rem; color:#94A3B8;">Đoàn rể</small>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- BƯỚC 4: CHỌN MẪU VEST QUAN TÂM NẾU CHƯA CÓ --}}
                        @if(!$sanPham)
                        <div class="mb-4">
                            <label class="field-label">Mẫu vest muốn chuẩn bị trước (Tùy chọn)</label>
                            <div class="input-icon-group">
                                <i class="bi bi-tag-fill input-icon"></i>
                                <select name="san_pham_quan_tam_id" class="form-select-custom">
                                    <option value="">-- Quý khách có thể chọn trước hoặc để chuyên gia tư vấn tại showroom --</option>
                                    @foreach($danhSachSanPham as $sp)
                                    <option value="{{ $sp->id }}" {{ old('san_pham_quan_tam_id') == $sp->id ? 'selected' : '' }}>
                                        {{ $sp->ten_sp }} ({{ number_format($sp->gia_ban) }} VNĐ)
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif

                        {{-- BƯỚC 5: GHI CHÚ RIÊNG CHO CHUYÊN VIÊN --}}
                        <div class="mb-4">
                            <label class="field-label">Yêu cầu đặc biệt cho Master Tailor Kingsman</label>
                            <div class="input-icon-group">
                                <textarea name="ghi_chu" rows="3" class="form-control-custom" placeholder="Ví dụ: Cần phòng thử riêng VIP, ưu tiên phom slim-fit kiểu Ý, cần chuẩn bị mẫu suit xanh navy midnight...">{{ old('ghi_chu') }}</textarea>
                            </div>
                        </div>

                        {{-- NÚT XÁC NHẬN --}}
                        <div class="mt-4">
                            <button type="submit" class="btn-submit-booking">
                                <i class="bi bi-calendar-check-fill"></i>
                                <span>Xác Nhận Đặt Lịch Hẹn Bespoke</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- CỘT PHẢI: BỨC THƯ MỜI ĐẶC QUYỀN TIẾP ĐÓN VIP --}}
            <div class="col-lg-5">
                <div class="vip-invitation-card">
                    <div class="vip-crest-wrap">
                        <div class="vip-crest-icon">
                            <i class="bi bi-suit-diamond-fill"></i>
                        </div>
                        <h4 style="font-family:'Playfair Display',serif; font-size:1.4rem; color:#D4B26F; font-weight:700; margin-bottom:4px;">
                            Đặc Quyền Tiếp Đón VIP
                        </h4>
                        <div style="font-size:0.8rem; color:#94A3B8; letter-spacing:1px; text-transform:uppercase;">
                            Kingsman Bespoke Tailoring Experience
                        </div>
                    </div>

                    <div class="vip-perk-item">
                        <div class="vip-perk-icon-wrap"><i class="bi bi-shield-shaded"></i></div>
                        <div>
                            <div class="vip-perk-title">Phòng Thử Đồ VIP Riêng Tư</div>
                            <p class="vip-perk-desc">Không gian may đo sang trọng, kín đáo đạt chuẩn quốc tế dành riêng cho quý ông và người thân.</p>
                        </div>
                    </div>

                    <div class="vip-perk-item">
                        <div class="vip-perk-icon-wrap"><i class="bi bi-person-check-fill"></i></div>
                        <div>
                            <div class="vip-perk-title">Master Tailor Đo Trực Tiếp</div>
                            <p class="vip-perk-desc">Nghệ nhân may đo 10+ năm kinh nghiệm lấy trọn vẹn 10 chỉ số cơ thể, cân chỉnh form xuôi vai, gù lưng.</p>
                        </div>
                    </div>

                    <div class="vip-perk-item">
                        <div class="vip-perk-icon-wrap"><i class="bi bi-cup-hot-fill"></i></div>
                        <div>
                            <div class="vip-perk-title">Thưởng Thức Trà & Whisky</div>
                            <p class="vip-perk-desc">Miễn phí Single Malt Whisky thượng phẩm và trà thảo mộc trong suốt buổi thử đồ và tư vấn phong cách.</p>
                        </div>
                    </div>

                    <div class="vip-perk-item">
                        <div class="vip-perk-icon-wrap"><i class="bi bi-geo-alt-fill"></i></div>
                        <div>
                            <div class="vip-perk-title">Địa Điểm Showroom</div>
                            <p class="vip-perk-desc">Thông tin địa điểm showroom đang chờ chủ sở hữu xác nhận.</p>
                        </div>
                    </div>

                    <div class="showroom-contact-box">
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span style="color:#D4B26F; font-weight:700; font-size:0.88rem;">
                                <i class="bi bi-info-circle-fill me-1"></i> Liên hệ showroom:
                            </span>
                            <strong style="color:#F1F5F9; font-size:0.95rem;">Đang chờ xác nhận</strong>
                        </div>
                        <div style="font-size:0.78rem; color:#94A3B8; line-height:1.5;">
                            <i class="bi bi-clock-history text-warning me-1"></i> Quý khách có thể hủy lịch hẹn khi lịch vẫn ở trạng thái chờ xác nhận tại trang <a href="{{ route('khachhang.dashboard') }}#lichhen" style="color:#D4B26F; text-decoration:underline; font-weight:600;">Hồ sơ & Thẻ VIP</a>. Vui lòng liên hệ showroom để thay đổi lịch đã xác nhận.
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection
