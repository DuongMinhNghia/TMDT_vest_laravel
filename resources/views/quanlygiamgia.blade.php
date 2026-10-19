@extends('layouts.default_layout')

@section('title', 'Quản Lý Khuyến Mãi & Flash Sale - Kingsman')

@section('content')
<style>
    .sale-wrap {
        max-width: 1300px;
        margin: 0 auto;
        padding: 10px 15px 50px;
    }

    /* Header */
    .sale-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid rgba(197, 160, 89, 0.25);
    }

    .sale-title {
        font-family: 'Playfair Display', serif;
        color: #C5A059;
        font-size: 1.85rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-gold-add {
        background: linear-gradient(135deg, #d4af37, #c19b2f);
        color: #0b0b0b;
        font-weight: 700;
        border: none;
        border-radius: 25px;
        padding: 10px 22px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.25s ease;
        text-decoration: none;
    }

    .btn-gold-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(212, 175, 55, 0.35);
        color: #000;
    }

    /* Stat Cards */
    .sale-stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 18px;
        margin-bottom: 30px;
    }

    .sale-stat-card {
        background: #141414;
        border: 1px solid #282828;
        border-radius: 12px;
        padding: 18px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: all 0.2s;
    }
    .sale-stat-card:hover {
        border-color: #444;
        transform: translateY(-2px);
    }

    .stat-label {
        color: #888;
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 5px;
    }

    .stat-num {
        font-size: 1.75rem;
        font-weight: 700;
        color: #f5f5f5;
        font-family: 'Playfair Display', serif;
    }

    .stat-badge-icon {
        width: 44px;
        height: 44px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.35rem;
    }

    /* Alerts */
    .sale-alert {
        padding: 12px 18px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.92rem;
    }

    .sale-alert-success {
        background: rgba(46, 204, 113, 0.15);
        border: 1px solid rgba(46, 204, 113, 0.4);
        color: #2ecc71;
    }

    /* Bảng danh sách */
    .sale-table-card {
        background: #141414;
        border: 1px solid #282828;
        border-radius: 14px;
        overflow: hidden;
    }

    .sale-table {
        width: 100%;
        border-collapse: collapse;
        color: #ddd;
        font-size: 0.9rem;
    }

    .sale-table th {
        background: #1a1a1a;
        color: #C5A059;
        font-weight: 600;
        padding: 14px 18px;
        text-align: left;
        border-bottom: 1px solid #282828;
        font-size: 0.82rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .sale-table td {
        padding: 16px 18px;
        border-bottom: 1px solid #202020;
        vertical-align: middle;
    }

    .sale-table tr:hover {
        background: rgba(255, 255, 255, 0.02);
    }

    /* Badges */
    .discount-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        background: linear-gradient(135deg, #e74c3c, #c0392b);
        color: #fff;
        font-weight: 800;
        font-size: 0.85rem;
        padding: 4px 10px;
        border-radius: 20px;
        box-shadow: 0 2px 8px rgba(231, 76, 60, 0.35);
    }

    .scope-pill {
        padding: 3px 9px;
        border-radius: 6px;
        font-size: 0.78rem;
        font-weight: 600;
    }
    .scope-all {
        background: rgba(197, 160, 89, 0.15);
        color: #C5A059;
        border: 1px solid rgba(197, 160, 89, 0.35);
    }
    .scope-custom {
        background: rgba(52, 152, 219, 0.15);
        color: #3498db;
        border: 1px solid rgba(52, 152, 219, 0.35);
    }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.78rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
    }
    .status-active {
        background: rgba(46, 204, 113, 0.15);
        color: #2ecc71;
        border: 1px solid rgba(46, 204, 113, 0.4);
    }
    .status-upcoming {
        background: rgba(241, 196, 15, 0.15);
        color: #f1c40f;
        border: 1px solid rgba(241, 196, 15, 0.4);
    }
    .status-ended {
        background: rgba(255, 255, 255, 0.08);
        color: #888;
        border: 1px solid rgba(255, 255, 255, 0.15);
    }

    /* Actions */
    .btn-action-icon {
        background: #202020;
        border: 1px solid #333;
        color: #ccc;
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
        border-color: #C5A059;
        color: #C5A059;
        transform: translateY(-1px);
    }
    .btn-action-del:hover {
        border-color: #e74c3c;
        color: #e74c3c;
    }

    /* Modals */
    .sale-modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.85);
        z-index: 1050;
        align-items: center;
        justify-content: center;
        padding: 20px;
        backdrop-filter: blur(5px);
    }

    .sale-modal-overlay.active {
        display: flex;
    }

    .sale-modal {
        background: #181818;
        border: 1px solid #333;
        border-radius: 16px;
        width: 100%;
        max-width: 680px;
        max-height: 90vh;
        overflow-y: auto;
        padding: 28px;
        position: relative;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.8);
    }

    .sale-modal-close {
        position: absolute;
        top: 18px;
        right: 20px;
        background: none;
        border: none;
        color: #888;
        font-size: 1.6rem;
        cursor: pointer;
        line-height: 1;
        transition: color 0.2s;
    }
    .sale-modal-close:hover {
        color: #fff;
    }

    .sale-modal h3 {
        font-family: 'Playfair Display', serif;
        color: #C5A059;
        font-size: 1.45rem;
        margin-bottom: 22px;
        display: flex;
        align-items: center;
        gap: 9px;
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
        color: #ccc;
        font-size: 0.86rem;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .form-control {
        width: 100%;
        background: #101010;
        border: 1px solid #333;
        border-radius: 8px;
        color: #f0f0f0;
        padding: 9px 13px;
        font-size: 0.9rem;
        transition: border-color 0.2s;
    }
    .form-control:focus {
        border-color: #C5A059;
        outline: none;
        background: #141414;
        color: #fff;
    }

    .radio-box {
        display: flex;
        gap: 20px;
        padding: 10px 14px;
        background: #101010;
        border: 1px solid #282828;
        border-radius: 8px;
    }

    .radio-item {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #ddd;
        font-size: 0.88rem;
        cursor: pointer;
    }

    .radio-item input[type="radio"] {
        accent-color: #C5A059;
        width: 16px;
        height: 16px;
    }

    /* Product picker */
    .suit-picker-box {
        background: #101010;
        border: 1px solid #282828;
        border-radius: 10px;
        padding: 12px;
        max-height: 200px;
        overflow-y: auto;
    }

    .suit-item-check {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 7px;
        border-radius: 6px;
        cursor: pointer;
        border-bottom: 1px solid #1a1a1a;
    }
    .suit-item-check:hover {
        background: rgba(197, 160, 89, 0.08);
    }
    .suit-item-check:last-child {
        border-bottom: none;
    }

    .suit-item-check input[type="checkbox"] {
        width: 16px;
        height: 16px;
        accent-color: #C5A059;
    }

    .suit-thumb {
        width: 36px;
        height: 36px;
        border-radius: 6px;
        object-fit: cover;
        border: 1px solid #333;
    }

    .btn-submit-gold {
        width: 100%;
        padding: 12px;
        background: linear-gradient(135deg, #d4af37, #c19b2f);
        color: #000;
        font-weight: 700;
        border: none;
        border-radius: 8px;
        font-size: 0.95rem;
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
    .del-icon { font-size: 3.2rem; color: #e74c3c; margin-bottom: 12px; }
    .del-btn-group { display: flex; gap: 10px; justify-content: center; margin-top: 20px; }
    .btn-cancel { padding: 9px 20px; background: #333; color: #ccc; border: none; border-radius: 6px; cursor: pointer; }
    .btn-confirm-del { padding: 9px 22px; background: #e74c3c; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; }
</style>

<div class="sale-wrap">

    <div class="sale-header">
        <h2 class="sale-title">
            <i class="bi bi-tag-fill" style="color:#e74c3c;"></i> Quản Lý Khuyến Mãi & Flash Sale
        </h2>
        <button class="btn-gold-add" onclick="openModal('modal-add-sale')">
            <i class="bi bi-plus-circle-fill"></i> Tạo Chiến Dịch Giảm Giá
        </button>
    </div>

    @if(session('success'))
    <div class="sale-alert sale-alert-success">
        <i class="bi bi-check-circle-fill"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(isset($errors) && $errors->any())
    <div class="sale-alert" style="background:rgba(231,76,60,0.15); border:1px solid rgba(231,76,60,0.4); color:#e74c3c;">
        <i class="bi bi-exclamation-triangle-fill"></i>
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
                <div class="stat-num">{{ $thongKe['tong'] }}</div>
            </div>
            <div class="stat-badge-icon" style="background:rgba(197,160,89,0.15); color:#C5A059;">
                <i class="bi bi-tags-fill"></i>
            </div>
        </div>
        <div class="sale-stat-card">
            <div>
                <div class="stat-label">Đang Diễn Ra (Live)</div>
                <div class="stat-num" style="color:#2ecc71;">{{ $thongKe['dang_chay'] }}</div>
            </div>
            <div class="stat-badge-icon" style="background:rgba(46,204,113,0.15); color:#2ecc71;">
                <i class="bi bi-broadcast"></i>
            </div>
        </div>
        <div class="sale-stat-card">
            <div>
                <div class="stat-label">Sắp Diễn Ra</div>
                <div class="stat-num" style="color:#f1c40f;">{{ $thongKe['sap_dien_ra'] }}</div>
            </div>
            <div class="stat-badge-icon" style="background:rgba(241,196,15,0.15); color:#f1c40f;">
                <i class="bi bi-clock-history"></i>
            </div>
        </div>
        <div class="sale-stat-card">
            <div>
                <div class="stat-label">Đã Kết Thúc / Tắt</div>
                <div class="stat-num" style="color:#888;">{{ $thongKe['ket_thuc'] }}</div>
            </div>
            <div class="stat-badge-icon" style="background:rgba(255,255,255,0.08); color:#888;">
                <i class="bi bi-slash-circle"></i>
            </div>
        </div>
    </div>

    {{-- BẢNG DANH SÁCH CHIẾN DỊCH --}}
    <div class="sale-table-card">
        @if($giamGias->isEmpty())
        <div style="text-align:center; padding: 60px 20px;">
            <i class="bi bi-ticket-perforated" style="font-size:3.5rem; color:#555;"></i>
            <h4 style="color:#aaa; margin-top:15px;">Chưa có chương trình giảm giá nào</h4>
            <p style="color:#777; font-size:0.9rem;">Hãy tạo chiến dịch Flash Sale đầu tiên để kích thích doanh số mua sắm áo vest!</p>
            <button class="btn-gold-add" onclick="openModal('modal-add-sale')" style="margin-top:10px;">
                <i class="bi bi-plus-circle-fill"></i> Tạo Khuyến Mãi Ngay
            </button>
        </div>
        @else
        <table class="sale-table">
            <thead>
                <tr>
                    <th style="width: 50px;">#ID</th>
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
                    <td style="color:#666; font-weight:600;">#{{ $g->id }}</td>
                    <td>
                        <strong style="color:#fff; font-size:0.95rem;">{{ $g->tieu_de }}</strong>
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
                        <div style="font-size:0.83rem; color:#bbb;">
                            <div><i class="bi bi-calendar-event" style="color:#C5A059;"></i> {{ $g->thoi_gian_bat_dau ? $g->thoi_gian_bat_dau->format('d/m/Y H:i') : '--' }}</div>
                            <div style="color:#777; margin-top:2px;">đến {{ $g->thoi_gian_ket_thuc ? $g->thoi_gian_ket_thuc->format('d/m/Y H:i') : '--' }}</div>
                        </div>
                    </td>
                    <td>
                        @if($isLive)
                            <span class="status-badge status-active"><i class="bi bi-broadcast"></i> Đang Chạy (Live)</span>
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
                                    <i class="bi bi-toggle-on" style="color:#2ecc71; font-size:1.3rem;"></i>
                                @else
                                    <i class="bi bi-toggle-off" style="color:#666; font-size:1.3rem;"></i>
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
        <h3><i class="bi bi-tag-fill" style="color:#e74c3c;"></i> Tạo Chiến Dịch Giảm Giá Mới</h3>

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
                        <div style="flex:1; font-size:0.85rem;">
                            <div style="color:#eee; font-weight:600;">{{ $sp->ten_sp }}</div>
                            <div style="color:#888; font-size:0.76rem;">{{ $sp->danhMuc->ten_danh_muc ?? 'Áo Vest' }} • {{ number_format($sp->gia_ban) }} VNĐ</div>
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
                        <div style="flex:1; font-size:0.85rem;">
                            <div style="color:#eee; font-weight:600;">{{ $sp->ten_sp }}</div>
                            <div style="color:#888; font-size:0.76rem;">{{ $sp->danhMuc->ten_danh_muc ?? 'Áo Vest' }} • {{ number_format($sp->gia_ban) }} VNĐ</div>
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
            <h4 style="color:#f0f0f0; margin-bottom:10px;">Xác Nhận Xóa Chiến Dịch</h4>
            <p id="del-sale-text" style="color:#aaa; font-size:0.9rem; line-height:1.5;">Bạn có chắc muốn xóa chương trình giảm giá này?</p>
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

        // Tích chọn checkbox sản phẩm cũ
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
        document.getElementById('del-sale-text').innerHTML = 'Bạn có chắc chắn muốn xóa chiến dịch <strong style="color:#C5A059;">"' + ten + '"</strong>?<br><span style="font-size:0.8rem; color:#777;">Giá của các sản phẩm sẽ được khôi phục về giá bán niêm yết ban đầu.</span>';

        openModal('modal-del-sale');
    }
</script>
@endsection
