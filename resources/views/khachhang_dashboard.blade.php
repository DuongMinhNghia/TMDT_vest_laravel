@extends('layouts.default_layout')

@section('title', 'Trung Tâm Khách Hàng & Thẻ VIP - Kingsman Vietnam')

@section('content')
<style>
    .dashboard-wrap {
        background: #111317;
        min-height: calc(100vh - 200px);
        padding: 40px 0 80px;
        color: #F1F5F9;
    }

    .section-title-gold {
        color: #D4B26F;
        font-family: 'Playfair Display', Georgia, serif;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    /* DIGITAL VIP CARD */
    .vip-card-container {
        perspective: 1000px;
        margin-bottom: 25px;
    }

    .vip-card {
        background: linear-gradient(135deg, #181B24 0%, #202430 50%, #141720 100%);
        border: 1px solid rgba(212, 178, 111, 0.45);
        border-radius: 18px;
        padding: 26px 30px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 16px 40px rgba(0, 0, 0, 0.45);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .vip-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 50px rgba(212, 178, 111, 0.25);
    }

    .vip-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgba(212, 178, 111, 0.15) 0%, transparent 70%);
        pointer-events: none;
    }

    .vip-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 22px;
    }

    .vip-card-brand {
        font-family: 'Playfair Display', Georgia, serif;
        font-size: 1.3rem;
        letter-spacing: 2.5px;
        color: #D4B26F;
        font-weight: 700;
        text-transform: uppercase;
    }

    .vip-tier-badge {
        padding: 5px 14px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }

    .vip-card-chip {
        width: 44px;
        height: 32px;
        background: linear-gradient(135deg, #D4B26F 0%, #B89246 100%);
        border-radius: 6px;
        margin-bottom: 18px;
        position: relative;
    }

    .vip-card-chip::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: rgba(0,0,0,0.35);
    }

    .vip-card-number {
        font-family: 'Courier New', monospace;
        font-size: 1.3rem;
        letter-spacing: 3px;
        color: #F1F5F9;
        margin-bottom: 14px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.6);
        font-weight: 600;
    }

    .vip-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        border-top: 1px solid rgba(212, 178, 111, 0.2);
        padding-top: 14px;
    }

    .vip-info-label {
        color: #94A3B8;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
    }

    .vip-info-val {
        color: #F1F5F9;
        font-weight: 600;
        font-size: 0.92rem;
    }

    /* TIẾN TRÌNH THĂNG HẠNG */
    .progress-box {
        background: #141720;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 14px;
        padding: 20px;
        margin-bottom: 25px;
    }

    .progress-custom {
        height: 8px;
        background-color: #181B24;
        border-radius: 6px;
        overflow: hidden;
        margin: 12px 0 8px;
    }

    .progress-bar-gold {
        background: linear-gradient(90deg, #B89246 0%, #D4B26F 100%);
        transition: width 0.6s ease;
    }

    /* NAV TABS */
    .nav-tabs-luxury {
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        gap: 8px;
        margin-bottom: 22px;
    }

    .nav-tabs-luxury .nav-link {
        color: #94A3B8;
        background: transparent;
        border: none;
        padding: 11px 20px;
        font-weight: 600;
        font-size: 0.9rem;
        border-radius: 8px 8px 0 0;
        transition: all 0.2s ease;
        border-bottom: 2px solid transparent;
    }

    .nav-tabs-luxury .nav-link:hover {
        color: #D4B26F;
    }

    .nav-tabs-luxury .nav-link.active {
        color: #D4B26F;
        background: #141720;
        border-bottom: 2px solid #D4B26F;
    }

    /* CARD CHUNG */
    .card-luxury {
        background: #141720;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 14px;
        padding: 24px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
    }

    .form-control-dark, .form-select-dark {
        background: #181B24 !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
        color: #F1F5F9 !important;
        border-radius: 8px !important;
        padding: 10px 14px;
        font-size: 0.9rem;
        transition: all 0.25s ease;
    }

    .form-control-dark:focus, .form-select-dark:focus {
        border-color: #D4B26F !important;
        background: #1C202C !important;
        box-shadow: 0 0 0 3px rgba(212, 178, 111, 0.2) !important;
    }

    .password-field-wrap { position: relative; }
    .password-field-wrap .form-control-dark { padding-right: 46px; }
    .password-visibility-toggle {
        position: absolute;
        top: 50%;
        right: 8px;
        transform: translateY(-50%);
        border: 0;
        background: transparent;
        color: #94A3B8;
        padding: 6px 8px;
        line-height: 1;
    }
    .password-visibility-toggle:hover, .password-visibility-toggle:focus-visible { color: #D4B26F; }

    .btn-gold {
        background: linear-gradient(135deg, #D4B26F 0%, #B89246 100%);
        color: #0c0e12;
        font-weight: 700;
        border: none;
        padding: 9px 20px;
        border-radius: 8px;
        font-size: 0.9rem;
        transition: all 0.25s ease;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .btn-gold:hover {
        background: linear-gradient(135deg, #E8CA8C 0%, #C9A458 100%);
        color: #000;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(212, 178, 111, 0.35);
    }

    /* SỐ ĐO ITEM */
    .sodo-card {
        background: #181B24;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 12px;
        padding: 18px 22px;
        margin-bottom: 16px;
        transition: all 0.25s ease;
    }

    .sodo-card:hover {
        border-color: rgba(212, 178, 111, 0.4);
        box-shadow: 0 6px 20px rgba(0,0,0,0.3);
    }

    .sodo-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(115px, 1fr));
        gap: 10px;
        margin: 14px 0;
    }

    .sodo-metric {
        background: #111317;
        padding: 9px;
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.06);
        text-align: center;
    }

    .sodo-metric-val {
        color: #D4B26F;
        font-size: 1.1rem;
        font-weight: 700;
    }

    .sodo-metric-label {
        font-size: 0.75rem;
        color: #94A3B8;
        margin-top: 2px;
    }

    /* LUXURY MODAL OVERLAY */
    .luxury-modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.85);
        z-index: 1060;
        align-items: center;
        justify-content: center;
        padding: 20px;
        backdrop-filter: blur(6px);
    }

    .luxury-modal-overlay.active {
        display: flex;
    }

    .luxury-modal-content {
        background: #141720;
        border: 1px solid rgba(212, 178, 111, 0.4);
        border-radius: 16px;
        width: 100%;
        max-width: 780px;
        max-height: 90vh;
        overflow-y: auto;
        padding: 28px 32px;
        position: relative;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.8);
    }

    @media (max-width: 768px) {
        .dashboard-wrap {
            padding: 20px 0 60px;
        }
        .vip-card-number {
            font-size: 1.1rem;
            letter-spacing: 2px;
        }
        .nav-tabs-luxury {
            flex-wrap: nowrap;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            padding-bottom: 4px;
        }
        .nav-tabs-luxury .nav-link {
            white-space: nowrap;
            padding: 9px 14px;
            font-size: 0.82rem;
            flex-shrink: 0;
        }
        .card-luxury {
            padding: 16px 14px;
            border-radius: 12px;
        }
        .sodo-card {
            padding: 14px 16px;
        }
        .sodo-grid {
            grid-template-columns: repeat(2, 1fr);
        }
        .luxury-modal-content {
            padding: 20px 16px;
            border-radius: 12px;
        }
    }
</style>

<div class="dashboard-wrap">
    <div class="container">

        <div class="row g-4">
            {{-- CỘT TRÁI: THẺ VIP & TIẾN ĐỘ THĂNG HẠNG --}}
            <div class="col-lg-5">

                {{-- THẺ VIP DIGITAL --}}
                <div class="vip-card-container">
                    <div class="vip-card">
                        <div class="vip-card-header">
                            <div class="vip-card-brand">
                                <i class="bi bi-gem me-1"></i> KINGSMAN
                            </div>
                            @php
                                $hang = $khachHang->hangThanhVien;
                                $mauHang = $hang?->mau_sac ?? '#D4B26F';
                            @endphp
                            <div class="vip-tier-badge" style="background: {{ $mauHang }}; color: #0c0e12;">
                                <i class="bi {{ $hang?->icon ?? 'bi-patch-check-fill' }}"></i>
                                {{ $hang?->ten_hang ?? 'Thành viên' }}
                            </div>
                        </div>

                        <div class="vip-card-chip"></div>

                        <div class="vip-card-number">
                            {{ chunk_split($khachHang->ma_kh ?? 'KH0001', 4, ' ') }}
                        </div>

                        <div class="vip-card-footer">
                            <div>
                                <div class="vip-info-label">Chủ thẻ thành viên</div>
                                <div class="vip-info-val">{{ $khachHang->ho_ten ?? Auth::user()->name }}</div>
                            </div>
                            <div class="text-end">
                                <div class="vip-info-label">Chiết khấu đặc quyền</div>
                                <div class="vip-info-val" style="color: #D4B26F; font-size: 1.15rem; font-weight: 700;">
                                    -{{ (int)($hang?->phan_tram_chiet_khau ?? 0) }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- HỘP TIẾN TRÌNH THĂNG HẠNG --}}
                <div class="progress-box">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span style="font-size: 0.78rem; text-transform: uppercase; color:#94A3B8;">Điểm thưởng tích lũy</span>
                            <div style="font-size: 1.55rem; font-weight: 700; color: #D4B26F;">
                                <i class="bi bi-coin me-1"></i> {{ number_format($khachHang->diem_tich_luy) }} <small style="font-size: 0.82rem; color:#94A3B8;">điểm</small>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="badge px-3 py-2" style="background:#181B24; border:1px solid rgba(255,255,255,0.1); color:#CBD5E1; font-size: 0.82rem;">
                                {{ $hang?->ten_hang ?? 'Đồng' }}
                            </span>
                        </div>
                    </div>

                    <div class="progress-custom">
                        <div class="progress-bar-gold" style="width: {{ $tienDo['phan_tram'] }}%; height: 100%;"></div>
                    </div>

                    <div class="d-flex justify-content-between" style="font-size: 0.8rem; color:#94A3B8;">
                        <span>Tiến trình: <strong class="text-white">{{ $tienDo['phan_tram'] }}%</strong></span>
                        @if($tienDo['la_cao_nhat'])
                            <span style="color: #38BDF8;"><i class="bi bi-stars"></i> Hạng VIP cao nhất!</span>
                        @else
                            <span>Còn <strong class="text-white">{{ number_format($tienDo['diem_can_them']) }} điểm</strong> lên <strong style="color:#D4B26F;">{{ $tienDo['hang_tiep']?->ten_hang }}</strong></span>
                        @endif
                    </div>
                </div>

                {{-- DANH SÁCH 4 QUYỀN LỢI HẠNG THÀNH VIÊN --}}
                <div class="card-luxury mb-4">
                    <h5 class="section-title-gold mb-3" style="font-size: 1.1rem;">
                        <i class="bi bi-award me-2"></i>Chính Sách Khách Hàng Thân Thiết
                    </h5>
                    <div class="d-flex flex-column gap-2">
                        @foreach($cacHang as $h)
                        <div class="p-2 px-3 rounded d-flex justify-content-between align-items-center" style="background: {{ $khachHang->hang_thanh_vien_id == $h->id ? 'rgba(212,178,111,0.12)' : '#181B24' }}; border: 1px solid {{ $khachHang->hang_thanh_vien_id == $h->id ? '#D4B26F' : 'rgba(255,255,255,0.06)' }};">
                            <div>
                                <span style="font-weight: 700; color: {{ $h->mau_sac }}; font-size:0.9rem;">
                                    <i class="bi {{ $h->icon }} me-1"></i> {{ $h->ten_hang }}
                                </span>
                                <small style="color:#94A3B8; font-size: 0.74rem; display:block;">(Từ {{ number_format($h->diem_toi_thieu) }} điểm)</small>
                            </div>
                            <div class="text-end">
                                <span class="badge" style="background:#111317; border:1px solid rgba(255,255,255,0.1); color:#D4B26F; font-size: 0.78rem;">Giảm {{ $h->phan_tram_chiet_khau }}%</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <small style="color:#64748B; font-size: 0.76rem; display:block; margin-top:12px; line-height:1.5;">
                        * Cứ mỗi 10.000đ tiền hàng khi hoàn tất đơn = 1 điểm tích lũy. Điểm dùng để tự động thăng hạng trọn đời.
                    </small>
                </div>

            </div>

            {{-- CỘT PHẢI: TABS QUẢN LÝ (THÔNG TIN / SỐ ĐO / LỊCH HẸN) --}}
            <div class="col-lg-7">

                {{-- NAV TABS --}}
                <ul class="nav nav-tabs nav-tabs-luxury" id="dashboardTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">
                            <i class="bi bi-person-lines-fill me-1"></i> Hồ Sơ
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="sodo-tab" data-bs-toggle="tab" data-bs-target="#sodo" type="button" role="tab">
                            <i class="bi bi-rulers me-1"></i> Số Đo May Vest ({{ $khachHang->soDos->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="lichhen-tab" data-bs-toggle="tab" data-bs-target="#lichhen" type="button" role="tab">
                            <i class="bi bi-calendar2-heart me-1"></i> Lịch Hẹn Showroom ({{ $khachHang->lichHens->count() }})
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="dashboardTabContent">

                    {{-- TAB 1: THÔNG TIN HỒ SƠ CÁ NHÂN --}}
                    <div class="tab-pane fade show active" id="profile" role="tabpanel">
                        <div class="card-luxury">
                            <h4 class="section-title-gold mb-2" style="font-size: 1.25rem;">
                                <i class="bi bi-person-circle me-2"></i>Thông Tin Tài Khoản
                            </h4>
                            <p style="color:#94A3B8; font-size: 0.85rem; margin-bottom:20px;">Cập nhật thông tin để nghệ nhân may đo và tư vấn giao hàng chính xác nhất.</p>

                            <form action="{{ route('khachhang.thongtin.luu') }}" method="POST" autocomplete="off">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label text-muted" style="font-size:0.82rem;">Họ và tên Quý khách *</label>
                                        <input type="text" name="ho_ten" class="form-control form-control-dark" value="{{ old('ho_ten', $khachHang->ho_ten) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-muted" style="font-size:0.82rem;">Số điện thoại</label>
                                        <input type="text" name="so_dien_thoai" class="form-control form-control-dark" value="{{ old('so_dien_thoai', $khachHang->so_dien_thoai) }}" placeholder="VD: 0987654321">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-muted" style="font-size:0.82rem;">Email đăng nhập</label>
                                        <input type="text" class="form-control form-control-dark" value="{{ Auth::user()->email }}" disabled style="opacity:0.6;">
                                    </div>
                                    <div class="col-12 mt-3"><h5 class="section-title-gold mb-0" style="font-size:1.05rem;"><i class="bi bi-geo-alt me-2"></i>Địa Chỉ Giao Hàng Mặc Định</h5></div>
                                    @if($loiDuLieuDiaChi)<div class="col-12"><div class="alert alert-danger">{{ $loiDuLieuDiaChi }}</div></div>@endif
                                    <div class="col-md-6">
                                        <label class="form-label text-muted" style="font-size:0.82rem;">Tỉnh / Thành phố</label>
                                        <input id="profile_province_combobox" type="text" list="profile_province_options" class="form-control form-control-dark" placeholder="Chọn hoặc gõ Tỉnh / Thành phố" autocomplete="off">
                                        <datalist id="profile_province_options">
                                            @foreach($tinhThanhs as $province)<option value="{{ $province['name'] }}"></option>@endforeach
                                        </datalist>
                                        <select id="profile_province" name="tinh_thanh_code" class="d-none" aria-hidden="true" tabindex="-1">
                                            <option value="">Chọn Tỉnh / Thành phố</option>
                                            @foreach($tinhThanhs as $province)
                                            <option value="{{ $province['code'] }}" {{ (string) old('tinh_thanh_code', $khachHang->tinh_thanh_code) === (string) $province['code'] ? 'selected' : '' }}>{{ $province['name'] }}</option>
                                            @endforeach
                                        </select>
                                        @error('tinh_thanh_code')<div class="text-danger small">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-muted" style="font-size:0.82rem;">Phường / Xã</label>
                                        <input id="profile_ward_combobox" type="text" list="profile_ward_options" class="form-control form-control-dark" placeholder="Chọn hoặc gõ Phường / Xã" autocomplete="off" disabled>
                                        <datalist id="profile_ward_options"></datalist>
                                        <select id="profile_ward" name="phuong_xa_code" disabled data-selected="{{ old('phuong_xa_code', $khachHang->phuong_xa_code) }}" class="d-none" aria-hidden="true" tabindex="-1">
                                            <option value="">Chọn Phường / Xã</option>
                                        </select>
                                        @error('phuong_xa_code')<div class="text-danger small">{{ $message }}</div>@enderror
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label text-muted" style="font-size:0.82rem;">Địa chỉ chi tiết</label>
                                        <input type="text" name="dia_chi_chi_tiet" class="form-control form-control-dark" value="{{ old('dia_chi_chi_tiet', $khachHang->dia_chi_chi_tiet) }}" placeholder="VD: Số 125, ngõ 8, đường Nguyễn Trãi">
                                        <small style="color:#94A3B8;">Nhập số nhà, tên đường, tòa nhà, thôn hoặc ấp cụ thể.</small>
                                        @error('dia_chi_chi_tiet')<div class="text-danger small">{{ $message }}</div>@enderror
                                        @if($khachHang->dia_chi)<small class="d-block mt-2" style="color:#D4B26F;">Địa chỉ đang lưu: {{ $khachHang->dia_chi }}</small>@endif
                                    </div>
                                    <div class="col-12 mt-4 pt-4" style="border-top:1px solid rgba(212,178,111,0.2);">
                                        <h5 class="section-title-gold mb-1" style="font-size:1.05rem;"><i class="bi bi-shield-lock me-2"></i>Đổi Mật Khẩu</h5>
                                        <p class="mb-3" style="color:#94A3B8; font-size:0.82rem;">Để trống cả ba ô nếu Quý khách chưa muốn thay đổi mật khẩu.</p>
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label text-muted" style="font-size:0.82rem;">Mật khẩu hiện tại</label>
                                                <div class="password-field-wrap">
                                                    <input id="current_password" type="password" name="current_password" class="form-control form-control-dark" autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly');" data-lpignore="true" data-1p-ignore="true">
                                                    <button type="button" class="password-visibility-toggle" data-password-target="current_password" aria-label="Hiển thị mật khẩu hiện tại"><i class="bi bi-eye"></i></button>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label text-muted" style="font-size:0.82rem;">Mật khẩu mới</label>
                                                <div class="password-field-wrap">
                                                    <input id="new_password" type="password" name="password" class="form-control form-control-dark" autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly');" data-lpignore="true" data-1p-ignore="true">
                                                    <button type="button" class="password-visibility-toggle" data-password-target="new_password" aria-label="Hiển thị mật khẩu mới"><i class="bi bi-eye"></i></button>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label text-muted" style="font-size:0.82rem;">Xác nhận mật khẩu mới</label>
                                                <div class="password-field-wrap">
                                                    <input id="password_confirmation" type="password" name="password_confirmation" class="form-control form-control-dark" autocomplete="new-password" readonly onfocus="this.removeAttribute('readonly');" data-lpignore="true" data-1p-ignore="true">
                                                    <button type="button" class="password-visibility-toggle" data-password-target="password_confirmation" aria-label="Hiển thị xác nhận mật khẩu mới"><i class="bi bi-eye"></i></button>
                                                </div>
                                            </div>
                                        </div>
                                        <small class="d-block mt-2" style="color:#94A3B8;">Mật khẩu mới cần tối thiểu 8 ký tự, gồm chữ cái, chữ số và ký tự đặc biệt; đồng thời không được trùng mật khẩu hiện tại.</small>
                                    </div>
                                    <div class="col-12 text-end mt-4">
                                        <button type="submit" class="btn btn-gold">
                                            <i class="bi bi-save me-1"></i> Lưu Thay Đổi
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- TAB 2: HỒ SƠ SỐ ĐO MAY VEST --}}
                    <div class="tab-pane fade" id="sodo" role="tabpanel">
                        <div class="card-luxury">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                                <div>
                                    <h4 class="section-title-gold mb-1" style="font-size: 1.25rem;">
                                        <i class="bi bi-scissors me-2"></i>Hồ Sơ Số Đo Cơ Thể
                                    </h4>
                                    <p style="color:#94A3B8; margin-bottom:0; font-size: 0.85rem;">Lưu thông số riêng để nghệ nhân Kingsman may vest bespoke chuẩn từng centimet.</p>
                                </div>
                                <button type="button" class="btn btn-gold btn-sm" onclick="moModalThemSoDo()">
                                    <i class="bi bi-plus-lg me-1"></i> Thêm Số Đo Mới
                                </button>
                            </div>

                            @forelse($khachHang->soDos as $sd)
                            <div class="sodo-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 text-white" style="font-size: 1.05rem; font-weight:600;">
                                        <i class="bi bi-bookmark-star-fill text-warning me-2"></i> {{ $sd->ten_ho_so }}
                                    </h6>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-sm" style="border:1px solid rgba(212,178,111,0.4); color:#D4B26F; background:transparent;" onclick='moModalSuaSoDo(@json($sd))'>
                                            <i class="bi bi-pencil-square me-1"></i> Sửa
                                        </button>
                                        <form action="{{ route('khachhang.sodo.xoa', $sd->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa bộ số đo này?')">
                                            @csrf
                                            <button type="submit" class="btn btn-sm" style="border:1px solid rgba(239,68,68,0.3); color:#EF4444; background:transparent;">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <div class="sodo-grid">
                                    <div class="sodo-metric">
                                        <div class="sodo-metric-val">{{ $sd->rong_vai ?? '—' }} <small style="font-size:0.7rem;">cm</small></div>
                                        <div class="sodo-metric-label">Rộng vai</div>
                                    </div>
                                    <div class="sodo-metric">
                                        <div class="sodo-metric-val">{{ $sd->vong_nguc ?? '—' }} <small style="font-size:0.7rem;">cm</small></div>
                                        <div class="sodo-metric-label">Vòng ngực</div>
                                    </div>
                                    <div class="sodo-metric">
                                        <div class="sodo-metric-val">{{ $sd->vong_eo ?? '—' }} <small style="font-size:0.7rem;">cm</small></div>
                                        <div class="sodo-metric-label">Vòng eo</div>
                                    </div>
                                    <div class="sodo-metric">
                                        <div class="sodo-metric-val">{{ $sd->vong_mong ?? '—' }} <small style="font-size:0.7rem;">cm</small></div>
                                        <div class="sodo-metric-label">Vòng mông</div>
                                    </div>
                                    <div class="sodo-metric">
                                        <div class="sodo-metric-val">{{ $sd->dai_tay_ao ?? '—' }} <small style="font-size:0.7rem;">cm</small></div>
                                        <div class="sodo-metric-label">Dài tay áo</div>
                                    </div>
                                    <div class="sodo-metric">
                                        <div class="sodo-metric-val">{{ $sd->dai_ao_vest ?? '—' }} <small style="font-size:0.7rem;">cm</small></div>
                                        <div class="sodo-metric-label">Dài áo vest</div>
                                    </div>
                                    <div class="sodo-metric">
                                        <div class="sodo-metric-val">{{ $sd->dai_quan ?? '—' }} <small style="font-size:0.7rem;">cm</small></div>
                                        <div class="sodo-metric-label">Dài quần</div>
                                    </div>
                                    <div class="sodo-metric">
                                        <div class="sodo-metric-val">{{ $sd->vong_dui ?? '—' }} <small style="font-size:0.7rem;">cm</small></div>
                                        <div class="sodo-metric-label">Vòng đùi</div>
                                    </div>
                                </div>

                                @if($sd->ghi_chu)
                                <div style="color:#94A3B8; font-size: 0.8rem; margin-top:6px;">
                                    <i class="bi bi-chat-left-text me-1 text-warning"></i> Ghi chú: {{ $sd->ghi_chu }}
                                </div>
                                @endif
                            </div>
                            @empty
                            <div class="text-center py-5" style="background:#181B24; border-radius:12px; border:1px dashed rgba(255,255,255,0.08);">
                                <i class="bi bi-rulers" style="font-size: 2.8rem; color:#475569;"></i>
                                <p style="color:#94A3B8; margin-top:8px; margin-bottom:14px; font-size:0.9rem;">Bạn chưa có bộ số đo nào. Hãy tạo bộ số đo để tự động áp dụng khi đặt may vest.</p>
                                <button type="button" class="btn btn-gold btn-sm" onclick="moModalThemSoDo()">
                                    <i class="bi bi-plus-lg me-1"></i> Nhập Số Đo Đầu Tiên
                                </button>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- TAB 3: LỊCH HẸN SHOWROOM --}}
                    <div class="tab-pane fade" id="lichhen" role="tabpanel">
                        <div class="card-luxury">
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                                <div>
                                    <h4 class="section-title-gold mb-1" style="font-size: 1.25rem;">
                                        <i class="bi bi-calendar2-check me-2"></i>Lịch Hẹn Tại Showroom
                                    </h4>
                                    <p style="color:#94A3B8; margin-bottom:0; font-size: 0.85rem;">Các buổi hẹn thử đồ cưới, may đo riêng hoặc tư vấn trang phục tại Kingsman.</p>
                                </div>
                                <a href="{{ route('lichhen.create') }}" class="btn btn-gold btn-sm">
                                    <i class="bi bi-calendar-plus me-1"></i> Đặt Lịch Mới
                                </a>
                            </div>

                            @forelse($khachHang->lichHens as $lh)
                            <div class="p-3 mb-3 rounded" style="background:#181B24; border: 1px solid rgba(255,255,255,0.08);">
                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                                    <div>
                                        <span class="badge me-2" style="background:#111317; border:1px solid rgba(212,178,111,0.4); color:#D4B26F; font-size: 0.82rem;">
                                            {{ $lh->ma_lich_hen }}
                                        </span>
                                        <strong class="text-white" style="font-size: 0.96rem;">{{ $lh->loai_dich_vu }}</strong>
                                    </div>
                                    <div>
                                        <span class="badge {{ $lh->badge_class }} px-3 py-2">
                                            {{ $lh->trang_thai }}
                                        </span>
                                    </div>
                                </div>

                                <div class="row g-2 text-muted" style="font-size: 0.84rem;">
                                    <div class="col-md-6">
                                        <i class="bi bi-clock-fill text-warning me-1"></i> Thời gian: 
                                        <strong class="text-white">{{ $lh->thoi_gian_hen ? $lh->thoi_gian_hen->format('H:i - d/m/Y') : 'Chưa định' }}</strong>
                                    </div>
                                    <div class="col-md-6">
                                        <i class="bi bi-people-fill text-warning me-1"></i> Số người đi cùng: 
                                        <strong class="text-white">{{ $lh->so_nguoi_di_cung }} người</strong>
                                    </div>

                                    @if($lh->sanPhamQuanTam)
                                    <div class="col-12 mt-2">
                                        <div class="p-3 rounded d-flex flex-wrap align-items-center gap-3" style="background:#111317; border:1px dashed rgba(212,178,111,0.28);">
                                            <img src="{{ asset('images/' . $lh->sanPhamQuanTam->hinh_anh) }}" alt="{{ $lh->sanPhamQuanTam->ten_sp }}" style="width: 58px; height: 58px; object-fit: cover; border-radius: 8px; border:1px solid rgba(255,255,255,0.1);">
                                            <div class="flex-grow-1" style="min-width:0;">
                                                <div style="color:#94A3B8; font-size:0.73rem; text-transform:uppercase; letter-spacing:0.7px; margin-bottom:3px;">Mẫu vest quan tâm</div>
                                                <div class="text-white fw-semibold" style="font-size:0.92rem; line-height:1.35;">{{ $lh->sanPhamQuanTam->ten_sp }}</div>
                                            </div>
                                            <div class="text-end" style="padding-left:14px; border-left:1px solid rgba(255,255,255,0.1); white-space:nowrap;">
                                                <div style="color:#94A3B8; font-size:0.72rem; margin-bottom:2px;">Giá tham khảo</div>
                                                <strong style="color:#E8CA8C; font-family:'Plus Jakarta Sans', Arial, sans-serif; font-size:1rem; font-variant-numeric:tabular-nums;">{{ number_format($lh->sanPhamQuanTam->gia_khuyen_mai ?: $lh->sanPhamQuanTam->gia_ban, 0, ',', '.') }} VNĐ</strong>
                                            </div>
                                        </div>
                                    </div>
                                    @endif

                                    @if($lh->nhanVienTiepDon)
                                    <div class="col-12 mt-1" style="font-size: 0.8rem; color:#38BDF8;">
                                        <i class="bi bi-person-check-fill me-1"></i> Chuyên viên tiếp đón: <strong>{{ $lh->nhanVienTiepDon->ho_ten }}</strong> ({{ $lh->nhanVienTiepDon->chuc_vu }})
                                    </div>
                                    @endif

                                    @if($lh->ghi_chu)
                                    <div class="col-12 mt-1" style="font-size: 0.8rem; color:#94A3B8;">
                                        <i class="bi bi-chat-quote me-1"></i> Ghi chú: {{ $lh->ghi_chu }}
                                    </div>
                                    @endif
                                </div>

                                @if($lh->trang_thai === \App\Models\LichHen::CHO_XAC_NHAN)
                                <div class="text-end mt-3 pt-2" style="border-top:1px solid rgba(255,255,255,0.06);">
                                    <form action="{{ route('lichhen.huy', $lh->id) }}" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn hủy lịch hẹn này?')">
                                        @csrf
                                        <button type="submit" class="btn btn-sm" style="border:1px solid rgba(239,68,68,0.3); color:#EF4444; background:transparent; font-size: 0.78rem;">
                                            <i class="bi bi-x-circle me-1"></i> Hủy Lịch Hẹn
                                        </button>
                                    </form>
                                </div>
                                @endif
                            </div>
                            @empty
                            <div class="text-center py-5" style="background:#181B24; border-radius:12px; border:1px dashed rgba(255,255,255,0.08);">
                                <i class="bi bi-calendar-x" style="font-size: 2.8rem; color:#475569;"></i>
                                <p style="color:#94A3B8; margin-top:8px; margin-bottom:14px; font-size:0.9rem;">Bạn chưa có lịch hẹn nào tại showroom Kingsman.</p>
                                <a href="{{ route('lichhen.create') }}" class="btn btn-gold btn-sm">
                                    <i class="bi bi-calendar-plus me-1"></i> Đặt Lịch Hẹn Ngay
                                </a>
                            </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL THÊM / SỬA BỘ SỐ ĐO MAY VEST (LUXURY OVERLAY MODAL) --}}
<div class="luxury-modal-overlay" id="modalSoDo">
    <div class="luxury-modal-content">
        <div class="d-flex justify-content-between align-items-center pb-3 mb-3" style="border-bottom: 1px solid rgba(255,255,255,0.08);">
            <h5 class="modal-title mb-0" id="modalSoDoTitle" style="color: #D4B26F; font-family:'Playfair Display',serif; font-weight:700;">
                <i class="bi bi-rulers me-2"></i>Thêm Bộ Số Đo May Vest
            </h5>
            <button type="button" onclick="dongModalSoDo()" style="background:none; border:none; color:#94A3B8; font-size:1.6rem; cursor:pointer;">&times;</button>
        </div>
        <form action="{{ route('khachhang.sodo.luu') }}" method="POST">
            @csrf
            <input type="hidden" name="id" id="sodo_id">
            <div class="mb-3">
                <label class="form-label" style="font-size: 0.84rem; color:#CBD5E1; font-weight:600;">Tên hồ sơ số đo *</label>
                <input type="text" name="ten_ho_so" id="sodo_ten" class="form-control form-control-dark" placeholder="VD: Số đo vest cưới 2026, Phom Slim-fit dự tiệc..." required>
            </div>

            <div class="row g-3">
                <div class="col-md-3 col-6">
                    <label class="form-label" style="font-size: 0.8rem; color:#94A3B8;">Rộng vai (cm)</label>
                    <input type="number" step="0.5" name="rong_vai" id="sodo_vai" class="form-control form-control-dark" placeholder="VD: 44">
                </div>
                <div class="col-md-3 col-6">
                    <label class="form-label" style="font-size: 0.8rem; color:#94A3B8;">Vòng ngực (cm)</label>
                    <input type="number" step="0.5" name="vong_nguc" id="sodo_nguc" class="form-control form-control-dark" placeholder="VD: 94">
                </div>
                <div class="col-md-3 col-6">
                    <label class="form-label" style="font-size: 0.8rem; color:#94A3B8;">Vòng eo (cm)</label>
                    <input type="number" step="0.5" name="vong_eo" id="sodo_eo" class="form-control form-control-dark" placeholder="VD: 82">
                </div>
                <div class="col-md-3 col-6">
                    <label class="form-label" style="font-size: 0.8rem; color:#94A3B8;">Vòng mông (cm)</label>
                    <input type="number" step="0.5" name="vong_mong" id="sodo_mong" class="form-control form-control-dark" placeholder="VD: 96">
                </div>
                <div class="col-md-3 col-6">
                    <label class="form-label" style="font-size: 0.8rem; color:#94A3B8;">Dài tay áo (cm)</label>
                    <input type="number" step="0.5" name="dai_tay_ao" id="sodo_tay" class="form-control form-control-dark" placeholder="VD: 60">
                </div>
                <div class="col-md-3 col-6">
                    <label class="form-label" style="font-size: 0.8rem; color:#94A3B8;">Dài áo vest (cm)</label>
                    <input type="number" step="0.5" name="dai_ao_vest" id="sodo_ao" class="form-control form-control-dark" placeholder="VD: 72">
                </div>
                <div class="col-md-3 col-6">
                    <label class="form-label" style="font-size: 0.8rem; color:#94A3B8;">Dài quần (cm)</label>
                    <input type="number" step="0.5" name="dai_quan" id="sodo_quan" class="form-control form-control-dark" placeholder="VD: 98">
                </div>
                <div class="col-md-3 col-6">
                    <label class="form-label" style="font-size: 0.8rem; color:#94A3B8;">Vòng đùi (cm)</label>
                    <input type="number" step="0.5" name="vong_dui" id="sodo_dui" class="form-control form-control-dark" placeholder="VD: 54">
                </div>
                <div class="col-md-3 col-6">
                    <label class="form-label" style="font-size: 0.8rem; color:#94A3B8;">Vòng cổ (cm)</label>
                    <input type="number" step="0.5" name="vong_co" id="sodo_co" class="form-control form-control-dark" placeholder="VD: 39">
                </div>
                <div class="col-md-9 col-12">
                    <label class="form-label" style="font-size: 0.8rem; color:#94A3B8;">Ghi chú vóc dáng đặc thù</label>
                    <input type="text" name="ghi_chu" id="sodo_ghichu" class="form-control form-control-dark" placeholder="VD: Vai hơi xuôi, thích quần ôm lửng chạm mắt cá...">
                </div>
            </div>

            <div class="d-flex justify-content-end gap-2 mt-4 pt-3" style="border-top: 1px solid rgba(255,255,255,0.08);">
                <button type="button" class="btn btn-sm" style="border:1px solid rgba(255,255,255,0.15); color:#94A3B8; background:transparent;" onclick="dongModalSoDo()">Đóng</button>
                <button type="submit" class="btn btn-gold btn-sm"><i class="bi bi-check-lg me-1"></i> Lưu Số Đo</button>
            </div>
        </form>
    </div>
</div>

<script>
    function moModalThemSoDo() {
        document.getElementById('modalSoDoTitle').innerHTML = '<i class="bi bi-rulers me-2"></i>Thêm Bộ Số Đo May Vest';
        document.getElementById('sodo_id').value = '';
        document.getElementById('sodo_ten').value = 'Số đo vest ' + new Date().getFullYear();
        document.getElementById('sodo_vai').value = '';
        document.getElementById('sodo_nguc').value = '';
        document.getElementById('sodo_eo').value = '';
        document.getElementById('sodo_mong').value = '';
        document.getElementById('sodo_tay').value = '';
        document.getElementById('sodo_ao').value = '';
        document.getElementById('sodo_quan').value = '';
        document.getElementById('sodo_dui').value = '';
        document.getElementById('sodo_co').value = '';
        document.getElementById('sodo_ghichu').value = '';
        const modal = document.getElementById('modalSoDo');
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function moModalSuaSoDo(sd) {
        document.getElementById('modalSoDoTitle').innerHTML = '<i class="bi bi-pencil-square me-2"></i>Cập Nhật Bộ Số Đo: ' + (sd.ten_ho_so || '');
        document.getElementById('sodo_id').value = sd.id;
        document.getElementById('sodo_ten').value = sd.ten_ho_so || '';
        document.getElementById('sodo_vai').value = sd.rong_vai || '';
        document.getElementById('sodo_nguc').value = sd.vong_nguc || '';
        document.getElementById('sodo_eo').value = sd.vong_eo || '';
        document.getElementById('sodo_mong').value = sd.vong_mong || '';
        document.getElementById('sodo_tay').value = sd.dai_tay_ao || '';
        document.getElementById('sodo_ao').value = sd.dai_ao_vest || '';
        document.getElementById('sodo_quan').value = sd.dai_quan || '';
        document.getElementById('sodo_dui').value = sd.vong_dui || '';
        document.getElementById('sodo_co').value = sd.vong_co || '';
        document.getElementById('sodo_ghichu').value = sd.ghi_chu || '';
        const modal = document.getElementById('modalSoDo');
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function dongModalSoDo() {
        const modal = document.getElementById('modalSoDo');
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Đóng khi click ngoài modal content
    document.getElementById('modalSoDo')?.addEventListener('click', function(e) {
        if (e.target === this) {
            dongModalSoDo();
        }
    });

    // Active tab based on hash
    document.addEventListener("DOMContentLoaded", function () {
        const hash = window.location.hash;
        if (hash) {
            const tabBtn = document.querySelector(`button[data-bs-target="${hash}"]`);
            if (tabBtn) {
                // Hỗ trợ cả Bootstrap tab lẫn fallback thủ công nếu tab class thay đổi
                if (window.bootstrap && bootstrap.Tab) {
                    new bootstrap.Tab(tabBtn).show();
                } else {
                    tabBtn.click();
                }
            }
        }

        document.querySelectorAll('.password-visibility-toggle').forEach(function (button) {
            button.addEventListener('click', function () {
                const input = document.getElementById(this.dataset.passwordTarget);
                if (!input) return;

                const showing = input.type === 'text';
                input.type = showing ? 'password' : 'text';
                this.setAttribute('aria-label', showing ? 'Hiển thị mật khẩu' : 'Ẩn mật khẩu');
                this.querySelector('i').className = showing ? 'bi bi-eye' : 'bi bi-eye-slash';
            });
        });
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const province = document.getElementById('profile_province');
    const ward = document.getElementById('profile_ward');
    const provinceCombobox = document.getElementById('profile_province_combobox');
    const wardCombobox = document.getElementById('profile_ward_combobox');
    const wardDatalist = document.getElementById('profile_ward_options');
    if (!province || !ward) return;

    const wardEndpoint = @json(route('giohang.phuongxa'));
    const selectedWard = ward.dataset.selected || '';
    const provinceOptions = Array.from(province.options).slice(1).map(option => ({ value: option.value, text: option.textContent }));
    let wardOptions = [];

    function normalizeSearch(value) {
        return value.normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/đ/g, 'd').replace(/Đ/g, 'D').toLowerCase();
    }

    provinceCombobox.addEventListener('input', function () {
        const match = provinceOptions.find(item => normalizeSearch(item.text) === normalizeSearch(provinceCombobox.value.trim()));
        const nextValue = match?.value || '';
        if (province.value !== nextValue) {
            province.value = nextValue;
            province.dispatchEvent(new Event('change'));
        }
    });
    wardCombobox.addEventListener('input', function () {
        const match = wardOptions.find(item => normalizeSearch(item.text) === normalizeSearch(wardCombobox.value.trim()));
        ward.value = match?.value || '';
        ward.dispatchEvent(new Event('change'));
    });

    async function loadWards(restore = false) {
        ward.innerHTML = '<option value="">Chọn Phường / Xã</option>';
        ward.disabled = true;
        wardCombobox.disabled = true;
        wardCombobox.value = '';
        wardDatalist.innerHTML = '';
        if (!province.value) return;
        try {
            const response = await fetch(wardEndpoint + '?tinh_thanh_code=' + encodeURIComponent(province.value), { headers: { Accept: 'application/json' } });
            const payload = await response.json();
            if (!response.ok) throw new Error(payload.message || 'Không thể tải Phường/Xã.');
            payload.data.forEach(function (item) {
                const option = document.createElement('option');
                option.value = item.code;
                option.textContent = item.name;
                if (restore && String(item.code) === String(selectedWard)) option.selected = true;
                ward.appendChild(option);
            });
            wardOptions = payload.data.map(item => ({ value: String(item.code), text: item.name }));
            wardOptions.forEach(item => wardDatalist.appendChild(new Option(item.text)));
            ward.disabled = payload.data.length === 0;
            wardCombobox.disabled = payload.data.length === 0;
            const selected = wardOptions.find(item => String(item.value) === String(ward.value));
            if (selected) wardCombobox.value = selected.text;
        } catch (exception) {
            console.error(exception.message);
        }
    }

    province.addEventListener('change', function () { loadWards(false); });
    const initialProvince = provinceOptions.find(item => String(item.value) === String(province.value));
    if (initialProvince) provinceCombobox.value = initialProvince.text;
    if (province.value) loadWards(true);

});
</script>
@endsection
