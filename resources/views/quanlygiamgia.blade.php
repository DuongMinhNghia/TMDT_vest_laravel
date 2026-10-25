@extends('layouts.admin_layout')

@section('title', 'Quản Lý Khuyến Mãi & Flash Sale - Kingsman Admin')
@section('page_name', 'Khuyến Mãi & Flash Sale')

@section('content')
<style>
    /* Executive Luxury Dark Palette for Giảm Giá & Flash Sale */
    .sale-container {
        padding: 4px 0 40px;
    }

    /* Page Header Toolbar */
    .sale-top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--adm-border);
    }

    .sale-header-title {
        font-family: var(--adm-font-heading);
        color: var(--adm-text-main);
        font-size: 1.6rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .sale-header-title i {
        color: #f43f5e;
    }

    .sale-subtitle {
        color: var(--adm-text-muted);
        font-size: 0.82rem;
        margin-top: 4px;
    }

    .btn-gold-action {
        background: linear-gradient(135deg, var(--adm-gold-light), var(--adm-gold));
        color: #0b0b0b;
        font-weight: 700;
        border: none;
        border-radius: 8px;
        padding: 9px 20px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 0.84rem;
        cursor: pointer;
        transition: all 0.25s ease;
        text-decoration: none;
        box-shadow: 0 4px 14px rgba(212, 178, 111, 0.2);
    }

    .btn-gold-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(212, 178, 111, 0.35);
        color: #000;
    }

    /* KPI Summary Cards */
    .sale-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .sale-stat-card {
        background: var(--adm-bg-surface);
        border: 1px solid var(--adm-border);
        border-radius: 10px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: border-color 0.2s, transform 0.2s;
    }

    .sale-stat-card:hover {
        border-color: var(--adm-border-focus);
        transform: translateY(-2px);
    }

    .stat-label {
        color: var(--adm-text-muted);
        font-size: 0.74rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        font-weight: 600;
        margin-bottom: 4px;
    }

    .stat-num {
        font-size: 1.65rem;
        font-weight: 700;
        color: var(--adm-text-main);
        font-family: var(--adm-font-heading);
    }

    .stat-badge-icon {
        width: 44px;
        height: 44px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
    }

    /* Table Container */
    .sale-table-card {
        background: var(--adm-bg-surface);
        border: 1px solid var(--adm-border);
        border-radius: 12px;
        overflow: hidden;
    }

    .sale-table {
        width: 100%;
        border-collapse: collapse;
        color: var(--adm-text-main);
        font-size: 0.88rem;
    }

    .sale-table th {
        background: var(--adm-bg-card);
        color: var(--adm-gold);
        font-weight: 600;
        padding: 14px 18px;
        text-align: left;
        border-bottom: 1px solid var(--adm-border);
        font-size: 0.78rem;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    .sale-table td {
        padding: 15px 18px;
        border-bottom: 1px solid var(--adm-border);
        vertical-align: middle;
    }

    .sale-table tr:hover {
        background: var(--adm-bg-hover);
    }

    /* Badges */
    .discount-pill {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        background: rgba(244, 63, 94, 0.15);
        color: #f43f5e;
        border: 1px solid rgba(244, 63, 94, 0.35);
        font-weight: 700;
        font-size: 0.82rem;
        padding: 3px 9px;
        border-radius: 20px;
    }

    .scope-pill {
        padding: 3px 9px;
        border-radius: 6px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    .scope-all {
        background: rgba(212, 178, 111, 0.12);
        color: var(--adm-gold);
        border: 1px solid rgba(212, 178, 111, 0.3);
    }
    .scope-custom {
        background: rgba(56, 189, 248, 0.12);
        color: #38bdf8;
        border: 1px solid rgba(56, 189, 248, 0.3);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.75rem;
        font-weight: 600;
        padding: 3px 10px;
        border-radius: 20px;
    }
    .status-active {
        background: rgba(34, 197, 94, 0.12);
        color: #22c55e;
        border: 1px solid rgba(34, 197, 94, 0.3);
    }
    .status-upcoming {
        background: rgba(234, 179, 8, 0.12);
        color: #eab308;
        border: 1px solid rgba(234, 179, 8, 0.3);
    }
    .status-ended {
        background: rgba(255, 255, 255, 0.05);
        color: var(--adm-text-dim);
        border: 1px solid var(--adm-border);
    }

    /* Actions */
    .btn-action-icon {
        background: var(--adm-bg-card);
        border: 1px solid var(--adm-border);
        color: var(--adm-text-muted);
        width: 32px;
        height: 32px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }
    .btn-action-icon:hover {
        border-color: var(--adm-gold);
        color: var(--adm-gold);
        transform: translateY(-1px);
    }
    .btn-action-del:hover {
        border-color: #f43f5e;
        color: #f43f5e;
    }

    /* Modals */
    .sale-modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.82);
        z-index: 1060;
        align-items: center;
        justify-content: center;
        padding: 20px;
        backdrop-filter: blur(5px);
    }

    .sale-modal-overlay.active {
        display: flex;
    }

    .sale-modal {
        background: var(--adm-bg-surface);
        border: 1px solid var(--adm-border-focus);
        border-radius: 14px;
        width: 100%;
        max-width: 660px;
        max-height: 90vh;
        overflow-y: auto;
        padding: 26px;
        position: relative;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.8);
    }

    .sale-modal-close {
        position: absolute;
        top: 18px;
        right: 20px;
        background: none;
        border: none;
        color: var(--adm-text-muted);
        font-size: 1.5rem;
        cursor: pointer;
        line-height: 1;
        transition: color 0.2s;
    }
    .sale-modal-close:hover {
        color: #fff;
    }

    .sale-modal h3 {
        font-family: var(--adm-font-heading);
        color: var(--adm-gold);
        font-size: 1.35rem;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .form-row-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 15px;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-label {
        display: block;
        color: var(--adm-text-main);
        font-size: 0.84rem;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .form-control, .form-select {
        width: 100%;
        background: var(--adm-bg-card);
        border: 1px solid var(--adm-border);
        border-radius: 6px;
        color: var(--adm-text-main);
        padding: 9px 12px;
        font-size: 0.88rem;
        transition: all 0.2s;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--adm-gold);
        outline: none;
        box-shadow: 0 0 0 3px var(--adm-gold-glow);
        background: #1c202b;
        color: #fff;
    }

    .radio-box {
        display: flex;
        gap: 15px;
        background: var(--adm-bg-card);
        padding: 12px 14px;
        border-radius: 8px;
        border: 1px solid var(--adm-border);
    }

    .radio-item {
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        font-size: 0.85rem;
        color: var(--adm-text-main);
    }

    .radio-item input[type="radio"] {
        accent-color: var(--adm-gold);
    }

    .suit-picker-box {
        background: var(--adm-bg-card);
        border: 1px solid var(--adm-border);
        border-radius: 8px;
        padding: 10px;
        max-height: 200px;
        overflow-y: auto;
    }

    .suit-item-check {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 8px;
        border-radius: 6px;
        transition: background 0.15s;
        cursor: pointer;
        border-bottom: 1px solid rgba(255, 255, 255, 0.04);
    }
    .suit-item-check:hover {
        background: var(--adm-bg-hover);
    }
    .suit-item-check:last-child {
        border-bottom: none;
    }

    .suit-item-check input[type="checkbox"] {
        width: 16px;
        height: 16px;
        accent-color: var(--adm-gold);
        cursor: pointer;
    }

    .suit-thumb {
        width: 38px;
        height: 38px;
        border-radius: 6px;
        object-fit: cover;
        border: 1px solid var(--adm-border);
    }

    .btn-submit-gold {
        width: 100%;
        padding: 11px;
        background: linear-gradient(135deg, var(--adm-gold-light), var(--adm-gold));
        color: #000;
        font-weight: 700;
        border: none;
        border-radius: 8px;
        font-size: 0.9rem;
        cursor: pointer;
        margin-top: 15px;
        transition: all 0.2s;
    }
    .btn-submit-gold:hover {
        filter: brightness(1.1);
        transform: translateY(-1px);
    }

    /* Delete modal */
    .del-center { text-align: center; }
    .del-icon { font-size: 3rem; color: #f43f5e; margin-bottom: 12px; }
    .del-btn-group { display: flex; gap: 10px; justify-content: center; margin-top: 20px; }
    .btn-cancel { padding: 9px 20px; background: var(--adm-bg-card); color: var(--adm-text-muted); border: 1px solid var(--adm-border); border-radius: 6px; cursor: pointer; }
    .btn-cancel:hover { color: #fff; }
    .btn-confirm-del { padding: 9px 22px; background: #f43f5e; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; }
</style>

<div class="sale-container">

    <!-- TOP TOOLBAR -->
    <div class="sale-top-bar">
        <div>
            <h2 class="sale-header-title">
                <i class="bi bi-tag-fill"></i> Quản Lý Khuyến Mãi & Flash Sale
            </h2>
            <div class="sale-subtitle">Quản trị các chiến dịch ưu đãi giá, flash sale mùa cưới và sự kiện đặc biệt</div>
        </div>
        <button class="btn-gold-action" onclick="openModal('modal-add-sale')">
            <i class="bi bi-plus-circle-fill"></i> Tạo Chiến Dịch Giảm Giá
        </button>
    </div>

    <!-- VALIDATION ERRORS -->
    @if(isset($errors) && $errors->any())
    <div class="admin-alert admin-alert-error" style="margin-bottom: 20px;">
        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
        <div>
            @foreach($errors->all() as $err)
            <div>{{ $err }}</div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- THỐNG KÊ NHANH --}}
    <div class="sale-stats-grid">
        <div class="sale-stat-card">
            <div>
                <div class="stat-label">Tổng Chiến Dịch</div>
                <div class="stat-num" style="color: var(--adm-gold);">{{ $thongKe['tong'] }}</div>
            </div>
            <div class="stat-badge-icon" style="background:var(--adm-gold-glow); color:var(--adm-gold);">
                <i class="bi bi-tags-fill"></i>
            </div>
        </div>
        <div class="sale-stat-card">
            <div>
                <div class="stat-label">Đang Diễn Ra (Live)</div>
                <div class="stat-num" style="color:#22c55e;">{{ $thongKe['dang_chay'] }}</div>
            </div>
            <div class="stat-badge-icon" style="background:rgba(34,197,94,0.12); color:#22c55e;">
                <i class="bi bi-broadcast"></i>
            </div>
        </div>
        <div class="sale-stat-card">
            <div>
                <div class="stat-label">Sắp Diễn Ra</div>
                <div class="stat-num" style="color:#eab308;">{{ $thongKe['sap_dien_ra'] }}</div>
            </div>
            <div class="stat-badge-icon" style="background:rgba(234,179,8,0.12); color:#eab308;">
                <i class="bi bi-clock-history"></i>
            </div>
        </div>
        <div class="sale-stat-card">
            <div>
                <div class="stat-label">Đã Kết Thúc / Tắt</div>
                <div class="stat-num" style="color:var(--adm-text-dim);">{{ $thongKe['ket_thuc'] }}</div>
            </div>
            <div class="stat-badge-icon" style="background:rgba(255,255,255,0.05); color:var(--adm-text-dim);">
                <i class="bi bi-slash-circle"></i>
            </div>
        </div>
    </div>

    {{-- BẢNG DANH SÁCH CHIẾN DỊCH --}}
    <div class="sale-table-card">
        @if($giamGias->isEmpty())
        <div style="text-align:center; padding: 60px 20px;">
            <i class="bi bi-ticket-perforated" style="font-size:3.5rem; color:var(--adm-text-dim);"></i>
            <h4 style="color:var(--adm-text-main); margin-top:15px; font-family:var(--adm-font-heading);">Chưa có chương trình giảm giá nào</h4>
            <p style="color:var(--adm-text-muted); font-size:0.88rem;">Hãy tạo chiến dịch Flash Sale đầu tiên để kích thích doanh số mua sắm áo vest!</p>
            <button class="btn-gold-action" onclick="openModal('modal-add-sale')" style="margin-top:10px;">
                <i class="bi bi-plus-circle-fill"></i> Tạo Khuyến Mãi Ngay
            </button>
        </div>
        @else
        <table class="sale-table">
            <thead>
                <tr>
                    <th style="width: 60px;">#ID</th>
                    <th>Tên Chương Trình</th>
                    <th>Mức Giảm</th>
                    <th>Phạm Vi Áp Dụng</th>
                    <th>Thời Gian Hiệu Lực</th>
                    <th>Trạng Thái</th>
                    <th>Bật/Tắt</th>
                    <th style="text-align: right;">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @php $now = now(); @endphp
                @foreach($giamGias as $g)
                @php
                    $isLive = $g->trang_thai && $g->thoi_gian_bat_dau <= $now && $g->thoi_gian_ket_thuc >= $now;
                    $isUpcoming = $g->trang_thai && $g->thoi_gian_bat_dau > $now;
                @endphp
                <tr>
                    <td style="color:var(--adm-text-dim); font-weight:600;">#{{ $g->id }}</td>
                    <td>
                        <strong style="color:var(--adm-text-main); font-size:0.92rem;">{{ $g->tieu_de }}</strong>
                    </td>
                    <td>
                        <span class="discount-pill"><i class="bi bi-arrow-down-short"></i> -{{ $g->phan_tram_giam }}%</span>
                    </td>
                    <td>
                        @if($g->ap_dung_tat_ca)
                            <span class="scope-pill scope-all"><i class="bi bi-shop"></i> Toàn bộ cửa hàng</span>
                        @else
                            <span class="scope-pill scope-custom"><i class="bi bi-suit-heart-fill"></i> {{ $g->san_phams_count }} áo vest</span>
                        @endif
                    </td>
                    <td>
                        <div style="font-size:0.82rem; color:var(--adm-text-muted);">
                            <div><i class="bi bi-calendar-event" style="color:var(--adm-gold);"></i> {{ $g->thoi_gian_bat_dau ? $g->thoi_gian_bat_dau->format('d/m/Y H:i') : '--' }}</div>
                            <div style="color:var(--adm-text-dim); margin-top:2px;">đến {{ $g->thoi_gian_ket_thuc ? $g->thoi_gian_ket_thuc->format('d/m/Y H:i') : '--' }}</div>
                        </div>
                    </td>
                    <td>
                        @if($isLive)
                            <span class="status-badge status-active"><i class="bi bi-broadcast"></i> Đang Chạy</span>
                        @elseif($isUpcoming)
                            <span class="status-badge status-upcoming"><i class="bi bi-hourglass-split"></i> Sắp Diễn Ra</span>
                        @else
                            <span class="status-badge status-ended"><i class="bi bi-x-circle"></i> Đã Kết Thúc</span>
                        @endif
                    </td>
                    <td>
                        <form action="{{ route('giamgia.trangthai', $g->id) }}" method="POST" style="margin:0;">
                            @csrf
                            <button type="submit" class="btn-action-icon" title="{{ $g->trang_thai ? 'Bấm để Tạm dừng' : 'Bấm để Kích hoạt' }}">
                                @if($g->trang_thai)
                                    <i class="bi bi-toggle-on" style="color:#22c55e; font-size:1.3rem;"></i>
                                @else
                                    <i class="bi bi-toggle-off" style="color:var(--adm-text-dim); font-size:1.3rem;"></i>
                                @endif
                            </button>
                        </form>
                    </td>
                    <td style="text-align: right;">
                        <button type="button" class="btn-action-icon"
                            data-id="{{ $g->id }}"
                            data-ten="{{ $g->tieu_de }}"
                            data-phantram="{{ $g->phan_tram_giam }}"
                            data-all="{{ $g->ap_dung_tat_ca ? '1' : '0' }}"
                            data-start="{{ $g->thoi_gian_bat_dau ? $g->thoi_gian_bat_dau->format('Y-m-d\TH:i') : '' }}"
                            data-end="{{ $g->thoi_gian_ket_thuc ? $g->thoi_gian_ket_thuc->format('Y-m-d\TH:i') : '' }}"
                            data-spids="{{ json_encode($g->sanPhams->pluck('id')->toArray()) }}"
                            onclick="openEditModal(this)"
                            title="Sửa chiến dịch">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                        <button type="button" class="btn-action-icon btn-action-del"
                            data-id="{{ $g->id }}"
                            data-ten="{{ $g->tieu_de }}"
                            onclick="openDeleteModal(this)"
                            title="Xóa chiến dịch">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

</div>

{{-- MODAL THÊM CHIẾN DỊCH GIẢM GIÁ --}}
<div class="sale-modal-overlay" id="modal-add-sale">
    <div class="sale-modal">
        <button class="sale-modal-close" onclick="closeModal('modal-add-sale')">&times;</button>
        <h3><i class="bi bi-tag-fill" style="color:#f43f5e;"></i> Tạo Chiến Dịch Giảm Giá Mới</h3>

        <form action="{{ route('giamgia.luuthem') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Tên Chương Trình Khuyến Mãi *</label>
                <input type="text" name="tieu_de" class="form-control" placeholder="VD: Flash Sale Chào Hè 2026, Giảm Giá Mùa Cưới" required>
            </div>

            <div class="form-group">
                <label class="form-label">Mức Giảm Giá (%) *</label>
                <input type="number" name="phan_tram_giam" class="form-control" placeholder="VD: 20 (nghĩa là giảm 20%)" min="1" max="99" required>
            </div>

            <div class="form-row-2">
                <div class="form-group">
                    <label class="form-label">Thời Gian Bắt Đầu *</label>
                    <input type="datetime-local" name="thoi_gian_bat_dau" class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Thời Gian Kết Thúc *</label>
                    <input type="datetime-local" name="thoi_gian_ket_thuc" class="form-control" value="{{ now()->addDays(7)->format('Y-m-d\TH:i') }}" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Phạm Vi Áp Dụng Giảm Giá *</label>
                <div class="radio-box">
                    <label class="radio-item">
                        <input type="radio" name="pham_vi" value="all" checked onchange="toggleAddPicker(false)">
                        <span>Toàn bộ sản phẩm trong cửa hàng</span>
                    </label>
                    <label class="radio-item">
                        <input type="radio" name="pham_vi" value="custom" onchange="toggleAddPicker(true)">
                        <span>Chỉ các sản phẩm được chỉ định</span>
                    </label>
                </div>
            </div>

            <div class="form-group" id="add-suit-picker-wrap" style="display: none;">
                <label class="form-label">Tích chọn các mẫu áo vest được giảm giá:</label>
                <div class="suit-picker-box">
                    @foreach($sanPhams as $sp)
                    <label class="suit-item-check">
                        <input type="checkbox" name="san_pham_ids[]" value="{{ $sp->id }}">
                        <img src="{{ asset('images/' . ($sp->hinh_anh ?: 'placeholder.jpg')) }}" class="suit-thumb" alt="sp" onerror="this.src='https://via.placeholder.com/40'">
                        <div style="flex:1; font-size:0.82rem;">
                            <div style="color:var(--adm-text-main); font-weight:600;">{{ $sp->ten_sp }}</div>
                            <div style="color:var(--adm-text-muted); font-size:0.75rem;">{{ $sp->danhMuc->ten_danh_muc ?? 'Áo Vest' }} • {{ number_format($sp->gia_ban) }} VNĐ</div>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="btn-submit-gold">
                <i class="bi bi-check-lg"></i> Kích Hoạt Chiến Dịch Giảm Giá
            </button>
        </form>
    </div>
</div>

{{-- MODAL SỬA CHIẾN DỊCH GIẢM GIÁ --}}
<div class="sale-modal-overlay" id="modal-edit-sale">
    <div class="sale-modal">
        <button class="sale-modal-close" onclick="closeModal('modal-edit-sale')">&times;</button>
        <h3><i class="bi bi-pencil-square"></i> Cập Nhật Chiến Dịch Giảm Giá</h3>

        <form id="form-edit-sale" action="" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Tên Chương Trình Khuyến Mãi *</label>
                <input type="text" id="edit_tieu_de" name="tieu_de" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Mức Giảm Giá (%) *</label>
                <input type="number" id="edit_phan_tram" name="phan_tram_giam" class="form-control" min="1" max="99" required>
            </div>

            <div class="form-row-2">
                <div class="form-group">
                    <label class="form-label">Thời Gian Bắt Đầu *</label>
                    <input type="datetime-local" id="edit_start" name="thoi_gian_bat_dau" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Thời Gian Kết Thúc *</label>
                    <input type="datetime-local" id="edit_end" name="thoi_gian_ket_thuc" class="form-control" required>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Phạm Vi Áp Dụng Giảm Giá *</label>
                <div class="radio-box">
                    <label class="radio-item">
                        <input type="radio" id="edit_scope_all" name="pham_vi" value="all" onchange="toggleEditPicker(false)">
                        <span>Toàn bộ sản phẩm trong cửa hàng</span>
                    </label>
                    <label class="radio-item">
                        <input type="radio" id="edit_scope_custom" name="pham_vi" value="custom" onchange="toggleEditPicker(true)">
                        <span>Chỉ các sản phẩm được chỉ định</span>
                    </label>
                </div>
            </div>

            <div class="form-group" id="edit-suit-picker-wrap">
                <label class="form-label">Tích chọn các mẫu áo vest được giảm giá:</label>
                <div class="suit-picker-box">
                    @foreach($sanPhams as $sp)
                    <label class="suit-item-check">
                        <input type="checkbox" name="san_pham_ids[]" value="{{ $sp->id }}" class="edit-sale-suit-checkbox">
                        <img src="{{ asset('images/' . ($sp->hinh_anh ?: 'placeholder.jpg')) }}" class="suit-thumb" alt="sp" onerror="this.src='https://via.placeholder.com/40'">
                        <div style="flex:1; font-size:0.82rem;">
                            <div style="color:var(--adm-text-main); font-weight:600;">{{ $sp->ten_sp }}</div>
                            <div style="color:var(--adm-text-muted); font-size:0.75rem;">{{ $sp->danhMuc->ten_danh_muc ?? 'Áo Vest' }} • {{ number_format($sp->gia_ban) }} VNĐ</div>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="btn-submit-gold">
                <i class="bi bi-save2"></i> Lưu Cập Nhật Thay Đổi
            </button>
        </form>
    </div>
</div>

{{-- MODAL XÓA CHIẾN DỊCH --}}
<div class="sale-modal-overlay" id="modal-del-sale">
    <div class="sale-modal" style="max-width: 450px;">
        <button class="sale-modal-close" onclick="closeModal('modal-del-sale')">&times;</button>
        <div class="del-center">
            <i class="bi bi-exclamation-triangle-fill del-icon"></i>
            <h4 style="color:var(--adm-text-main); margin-bottom:10px; font-family:var(--adm-font-heading);">Xác Nhận Xóa Chiến Dịch</h4>
            <p id="del-sale-text" style="color:var(--adm-text-muted); font-size:0.88rem; line-height:1.5;">Bạn có chắc muốn xóa chương trình giảm giá này?</p>
            <form id="form-del-sale" action="" method="POST">
                @csrf
                <div class="del-btn-group">
                    <button type="button" class="btn-cancel" onclick="closeModal('modal-del-sale')">Hủy bỏ</button>
                    <button type="submit" class="btn-confirm-del"><i class="bi bi-trash"></i> Xóa Vĩnh Viễn</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openModal(id) {
        document.getElementById(id).classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
        document.body.style.overflow = '';
    }

    document.querySelectorAll('.sale-modal-overlay').forEach(function(el) {
        el.addEventListener('click', function(e) {
            if (e.target === el) closeModal(el.id);
        });
    });

    function toggleAddPicker(show) {
        document.getElementById('add-suit-picker-wrap').style.display = show ? 'block' : 'none';
    }

    function toggleEditPicker(show) {
        document.getElementById('edit-suit-picker-wrap').style.display = show ? 'block' : 'none';
    }

    function openEditModal(btn) {
        var id       = btn.getAttribute('data-id');
        var ten      = btn.getAttribute('data-ten');
        var phanTram = btn.getAttribute('data-phantram');
        var isAll    = btn.getAttribute('data-all') === '1';
        var start    = btn.getAttribute('data-start') || '';
        var end      = btn.getAttribute('data-end') || '';
        var spIds    = [];

        try {
            spIds = JSON.parse(btn.getAttribute('data-spids') || '[]');
        } catch(e) {
            spIds = [];
        }

        document.getElementById('form-edit-sale').action = '{{ url("/giam-gia") }}/' + id + '/sua';
        document.getElementById('edit_tieu_de').value = ten;
        document.getElementById('edit_phan_tram').value = phanTram;
        document.getElementById('edit_start').value = start;
        document.getElementById('edit_end').value = end;

        if (isAll) {
            document.getElementById('edit_scope_all').checked = true;
            toggleEditPicker(false);
        } else {
            document.getElementById('edit_scope_custom').checked = true;
            toggleEditPicker(true);
        }

        document.querySelectorAll('.edit-sale-suit-checkbox').forEach(function(cb) {
            var val = parseInt(cb.value);
            cb.checked = spIds.includes(val);
        });

        openModal('modal-edit-sale');
    }

    function openDeleteModal(btn) {
        var id  = btn.getAttribute('data-id');
        var ten = btn.getAttribute('data-ten');

        document.getElementById('form-del-sale').action = '{{ url("/giam-gia") }}/' + id + '/xoa';
        document.getElementById('del-sale-text').innerHTML = 'Bạn có chắc chắn muốn xóa chiến dịch <strong style="color:var(--adm-gold);">"' + ten + '"</strong>?<br><span style="font-size:0.8rem; color:var(--adm-text-dim);">Giá của các sản phẩm sẽ được khôi phục về giá bán niêm yết ban đầu.</span>';

        openModal('modal-del-sale');
    }
</script>
@endsection
