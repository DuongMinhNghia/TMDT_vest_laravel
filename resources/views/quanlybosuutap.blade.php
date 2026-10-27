@extends('layouts.admin_layout')

@section('title', 'Quản Lý Bộ Sưu Tập Thời Trang - Kingsman Admin')
@section('page_name', 'Quản Lý Bộ Sưu Tập')

@section('content')
<style>
    /* Executive Luxury Dark Palette for Bộ Sưu Tập */
    .bst-container {
        padding: 4px 0 40px;
    }

    /* Page Header Toolbar */
    .bst-top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--adm-border);
    }

    .bst-header-title {
        font-family: var(--adm-font-heading);
        color: var(--adm-text-main);
        font-size: 1.6rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .bst-header-title i {
        color: var(--adm-gold);
    }

    .bst-subtitle {
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
    .bst-kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .bst-kpi-card {
        background: var(--adm-bg-surface);
        border: 1px solid var(--adm-border);
        border-radius: 10px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: border-color 0.2s, transform 0.2s;
    }

    .bst-kpi-card:hover {
        border-color: var(--adm-border-focus);
        transform: translateY(-2px);
    }

    .bst-kpi-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--adm-text-muted);
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 4px;
    }

    .bst-kpi-val {
        font-family: var(--adm-font-heading);
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--adm-gold);
    }

    .bst-kpi-icon {
        width: 44px;
        height: 44px;
        border-radius: 8px;
        background: var(--adm-gold-glow);
        color: var(--adm-gold);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
    }

    /* Grid danh sách BST */
    .bst-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(360px, 1fr));
        gap: 22px;
    }

    .bst-card {
        background: var(--adm-bg-surface);
        border: 1px solid var(--adm-border);
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        display: flex;
        flex-direction: column;
    }

    .bst-card:hover {
        border-color: var(--adm-gold);
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.5);
    }

    .bst-banner {
        width: 100%;
        height: 180px;
        object-fit: cover;
        background: #181b24;
        display: block;
        border-bottom: 1px solid var(--adm-border);
    }

    .bst-banner-placeholder {
        width: 100%;
        height: 180px;
        background: linear-gradient(135deg, #181B24, #12141A);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: var(--adm-gold);
        font-size: 2.2rem;
        border-bottom: 1px solid var(--adm-border);
    }

    .bst-card-body {
        padding: 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .bst-name {
        font-family: var(--adm-font-heading);
        font-size: 1.25rem;
        color: var(--adm-text-main);
        margin-bottom: 8px;
        font-weight: 700;
    }

    .bst-desc {
        color: var(--adm-text-muted);
        font-size: 0.84rem;
        line-height: 1.55;
        margin-bottom: 15px;
        min-height: 40px;
    }

    .bst-stats {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 14px;
        background: var(--adm-bg-card);
        border-radius: 8px;
        border: 1px solid var(--adm-border);
        margin-bottom: 14px;
    }

    .bst-count {
        color: var(--adm-gold);
        font-weight: 700;
        font-size: 0.88rem;
    }

    .bst-suits-list {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 18px;
        max-height: 80px;
        overflow-y: auto;
    }

    .suit-tag {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--adm-border);
        color: #CBD5E1;
        padding: 3px 8px;
        border-radius: 4px;
        font-size: 0.74rem;
    }

    .bst-actions {
        display: flex;
        gap: 8px;
        margin-top: auto;
        padding-top: 14px;
        border-top: 1px solid var(--adm-border);
    }

    .btn-card-action {
        flex: 1;
        padding: 7px 10px;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        transition: all 0.2s;
        border: 1px solid transparent;
    }

    .btn-act-view {
        background: rgba(212, 178, 111, 0.12);
        color: var(--adm-gold);
        border-color: rgba(212, 178, 111, 0.3);
    }
    .btn-act-view:hover {
        background: var(--adm-gold);
        color: #000;
    }

    .btn-act-edit {
        background: rgba(56, 189, 248, 0.12);
        color: #38bdf8;
        border-color: rgba(56, 189, 248, 0.3);
    }
    .btn-act-edit:hover {
        background: #38bdf8;
        color: #000;
    }

    .btn-act-del {
        background: rgba(244, 63, 94, 0.12);
        color: #f43f5e;
        border-color: rgba(244, 63, 94, 0.3);
    }
    .btn-act-del:hover {
        background: #f43f5e;
        color: #fff;
    }

    /* Modal Dark Theme */
    .bst-modal-overlay {
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

    .bst-modal-overlay.active {
        display: flex;
    }

    .bst-modal {
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

    .bst-modal-close {
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
    .bst-modal-close:hover {
        color: #fff;
    }

    .bst-modal h3 {
        font-family: var(--adm-font-heading);
        color: var(--adm-gold);
        font-size: 1.35rem;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
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

    textarea.form-control {
        min-height: 75px;
        resize: vertical;
    }

    .suit-picker-box {
        background: var(--adm-bg-card);
        border: 1px solid var(--adm-border);
        border-radius: 8px;
        padding: 10px;
        max-height: 200px;
        overflow-y: auto;
    }

    .suit-picker-title {
        font-size: 0.8rem;
        color: var(--adm-gold);
        margin-bottom: 8px;
        font-weight: 600;
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

    .suit-picker-info {
        flex: 1;
        font-size: 0.82rem;
    }

    .suit-picker-name {
        color: var(--adm-text-main);
        font-weight: 600;
    }

    .suit-picker-meta {
        font-size: 0.74rem;
        color: var(--adm-text-muted);
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

    /* Modal Xóa */
    .del-center { text-align: center; }
    .del-icon { font-size: 3rem; color: #f43f5e; margin-bottom: 12px; }
    .del-btn-group { display: flex; gap: 10px; justify-content: center; margin-top: 20px; }
    .btn-cancel { padding: 9px 20px; background: var(--adm-bg-card); color: var(--adm-text-muted); border: 1px solid var(--adm-border); border-radius: 6px; cursor: pointer; }
    .btn-cancel:hover { color: #fff; }
    .btn-confirm-del { padding: 9px 22px; background: #f43f5e; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-weight: 600; }
</style>

<div class="bst-container">

    <!-- TOP TOOLBAR -->
    <div class="bst-top-bar">
        <div>
            <h2 class="bst-header-title">
                <i class="bi bi-collection-play-fill"></i> Quản Lý Bộ Sưu Tập Thời Trang
            </h2>
            <div class="bst-subtitle">Thiết kế và tuyển chọn các chủ đề vest cưới, dạ tiệc, quý ông Savile Row</div>
        </div>
        <button class="btn-gold-action" onclick="openModal('modal-add-bst')">
            <i class="bi bi-plus-circle-fill"></i> Tạo Bộ Sưu Tập Mới
        </button>
    </div>

    <!-- KPI STATS -->
    <div class="bst-kpi-grid">
        <div class="bst-kpi-card">
            <div>
                <div class="bst-kpi-label">Tổng Bộ Sưu Tập</div>
                <div class="bst-kpi-val">{{ $boSuuTaps->count() }}</div>
            </div>
            <div class="bst-kpi-icon">
                <i class="bi bi-collection"></i>
            </div>
        </div>

        <div class="bst-kpi-card">
            <div>
                <div class="bst-kpi-label">Tổng Áo Vest Trong BST</div>
                <div class="bst-kpi-val">{{ $boSuuTaps->sum('san_phams_count') }}</div>
            </div>
            <div class="bst-kpi-icon">
                <i class="bi bi-suit-heart"></i>
            </div>
        </div>

        <div class="bst-kpi-card">
            <div>
                <div class="bst-kpi-label">Mẫu Vest Sẵn Có</div>
                <div class="bst-kpi-val">{{ $sanPhams->count() }}</div>
            </div>
            <div class="bst-kpi-icon">
                <i class="bi bi-box-seam"></i>
            </div>
        </div>
    </div>

    {{-- LƯỚI DANH SÁCH BỘ SƯU TẬP --}}
    @if($boSuuTaps->isEmpty())
    <div style="text-align:center; padding: 60px 20px; background: var(--adm-bg-surface); border:1px dashed var(--adm-border); border-radius:12px;">
        <i class="bi bi-folder-x" style="font-size: 3.5rem; color: var(--adm-text-dim);"></i>
        <h4 style="color: var(--adm-text-main); margin-top:15px; font-family: var(--adm-font-heading);">Chưa có bộ sưu tập nào</h4>
        <p style="color: var(--adm-text-muted); font-size:0.88rem;">Hãy tạo bộ sưu tập đầu tiên để gắn kết các mẫu vest theo chủ đề (Vest Cưới, Quý Ông Công Sở, Dạ Tiệc)!</p>
        <button class="btn-gold-action" onclick="openModal('modal-add-bst')" style="margin-top:10px;">
            <i class="bi bi-plus-circle-fill"></i> Tạo Ngay
        </button>
    </div>
    @else
    <div class="bst-grid">
        @foreach($boSuuTaps as $bst)
        <div class="bst-card">
            @if($bst->hinh_banner)
                <img src="{{ asset('images/' . $bst->hinh_banner) }}" alt="{{ $bst->ten_bo_suu_tap }}" class="bst-banner">
            @else
                <div class="bst-banner-placeholder">
                    <i class="bi bi-gem"></i>
                    <span style="font-size:0.75rem; font-family: var(--adm-font-body); letter-spacing: 2px; margin-top:6px; opacity:0.8;">KINGSMAN BESPOKE</span>
                </div>
            @endif

            <div class="bst-card-body">
                <h3 class="bst-name">{{ $bst->ten_bo_suu_tap }}</h3>
                <p class="bst-desc">{{ $bst->mo_ta ?: 'Chưa có phần mô tả cảm hứng cho bộ sưu tập này.' }}</p>

                <div class="bst-stats">
                    <span style="color: var(--adm-text-muted); font-size:0.82rem;"><i class="bi bi-suit-heart-fill" style="color: var(--adm-gold);"></i> Số mẫu vest:</span>
                    <span class="bst-count">{{ $bst->san_phams_count }} sản phẩm</span>
                </div>

                <div class="bst-suits-list">
                    @forelse($bst->sanPhams as $sp)
                        <span class="suit-tag"><i class="bi bi-tag-fill" style="color: var(--adm-gold);"></i> {{ $sp->ten_sp }}</span>
                    @empty
                        <span style="color: var(--adm-text-dim); font-size:0.78rem; font-style:italic;">Chưa gắn sản phẩm nào vào BST.</span>
                    @endforelse
                </div>

                <div class="bst-actions">
                    <a href="{{ route('bosuutap.chitiet', $bst->id) }}" target="_blank" class="btn-card-action btn-act-view">
                        <i class="bi bi-globe2"></i> Xem web
                    </a>
                    <button type="button" class="btn-card-action btn-act-edit"
                        data-id="{{ $bst->id }}"
                        data-ten="{{ $bst->ten_bo_suu_tap }}"
                        data-mota="{{ $bst->mo_ta }}"
                        data-banner="{{ $bst->hinh_banner }}"
                        data-spids="{{ json_encode($bst->sanPhams->pluck('id')->toArray()) }}"
                        onclick="openEditModal(this)">
                        <i class="bi bi-pencil-square"></i> Sửa
                    </button>
                    <button type="button" class="btn-card-action btn-act-del"
                        data-id="{{ $bst->id }}"
                        data-ten="{{ $bst->ten_bo_suu_tap }}"
                        onclick="openDeleteModal(this)">
                        <i class="bi bi-trash"></i> Xóa
                    </button>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</div>

{{-- MODAL THÊM BỘ SƯU TẬP --}}
<div class="bst-modal-overlay" id="modal-add-bst">
    <div class="bst-modal">
        <button class="bst-modal-close" onclick="closeModal('modal-add-bst')">&times;</button>
        <h3><i class="bi bi-plus-circle-fill"></i> Thêm Bộ Sưu Tập Mới</h3>

        <form action="{{ route('bosuutap.luuthem') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="form-label">Tên Bộ Sưu Tập *</label>
                <input type="text" name="ten_bo_suu_tap" class="form-control" placeholder="VD: Bộ Sưu Tập Vest Cưới Hoàng Gia 2026" required>
            </div>

            <div class="form-group">
                <label class="form-label">Hình Banner Đại Diện</label>
                <input type="file" name="hinh_banner" class="form-control" accept="image/*">
                <small style="color: var(--adm-text-dim); font-size:0.76rem;">Khuyên dùng kích thước ngang tỷ lệ 16:9 hoặc ảnh phong cảnh chất lượng cao.</small>
            </div>

            <div class="form-group">
                <label class="form-label">Mô Tả Cảm Hứng / Thông Điệp BST</label>
                <textarea name="mo_ta" class="form-control" placeholder="Mô tả phong cách, chất liệu chủ đạo hoặc đối tượng hướng tới..."></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Chọn Các Mẫu Áo Vest Thuộc BST Này:</label>
                <div class="suit-picker-box">
                    <div class="suit-picker-title"><i class="bi bi-check2-square"></i> Tích chọn các sản phẩm đưa vào bộ sưu tập:</div>
                    @foreach($sanPhams as $sp)
                    <label class="suit-item-check">
                        <input type="checkbox" name="san_pham_ids[]" value="{{ $sp->id }}">
                        <img src="{{ asset('images/' . ($sp->hinh_anh ?: 'placeholder.jpg')) }}" class="suit-thumb" alt="sp" onerror="this.src='https://via.placeholder.com/40'">
                        <div class="suit-picker-info">
                            <div class="suit-picker-name">{{ $sp->ten_sp }}</div>
                            <div class="suit-picker-meta">{{ $sp->danhMuc->ten_danh_muc ?? 'Chưa phân loại' }} • {{ number_format($sp->gia_ban) }} VNĐ</div>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="btn-submit-gold">
                <i class="bi bi-check-lg"></i> Lưu Bộ Sưu Tập Mới
            </button>
        </form>
    </div>
</div>

{{-- MODAL SỬA BỘ SƯU TẬP --}}
<div class="bst-modal-overlay" id="modal-edit-bst">
    <div class="bst-modal">
        <button class="bst-modal-close" onclick="closeModal('modal-edit-bst')">&times;</button>
        <h3><i class="bi bi-pencil-square"></i> Cập Nhật Bộ Sưu Tập</h3>

        <form id="form-edit-bst" action="" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="form-label">Tên Bộ Sưu Tập *</label>
                <input type="text" id="edit_ten_bst" name="ten_bo_suu_tap" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Thay Đổi Hình Banner (nếu muốn)</label>
                <input type="file" name="hinh_banner" class="form-control" accept="image/*">
                <div id="edit_banner_preview" style="margin-top:8px; font-size:0.8rem; color: var(--adm-gold);"></div>
            </div>

            <div class="form-group">
                <label class="form-label">Mô Tả Cảm Hứng / Thông Điệp BST</label>
                <textarea id="edit_mota_bst" name="mo_ta" class="form-control"></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Chọn Các Mẫu Áo Vest Thuộc BST:</label>
                <div class="suit-picker-box">
                    <div class="suit-picker-title"><i class="bi bi-check2-square"></i> Cập nhật danh sách áo vest trong BST:</div>
                    @foreach($sanPhams as $sp)
                    <label class="suit-item-check">
                        <input type="checkbox" name="san_pham_ids[]" value="{{ $sp->id }}" class="edit-suit-checkbox" id="edit_sp_{{ $sp->id }}">
                        <img src="{{ asset('images/' . ($sp->hinh_anh ?: 'placeholder.jpg')) }}" class="suit-thumb" alt="sp" onerror="this.src='https://via.placeholder.com/40'">
                        <div class="suit-picker-info">
                            <div class="suit-picker-name">{{ $sp->ten_sp }}</div>
                            <div class="suit-picker-meta">{{ $sp->danhMuc->ten_danh_muc ?? 'Chưa phân loại' }} • {{ number_format($sp->gia_ban) }} VNĐ</div>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="btn-submit-gold">
                <i class="bi bi-save2"></i> Cập Nhật Thay Đổi
            </button>
        </form>
    </div>
</div>

{{-- MODAL XÓA BỘ SƯU TẬP --}}
<div class="bst-modal-overlay" id="modal-del-bst">
    <div class="bst-modal" style="max-width: 450px;">
        <button class="bst-modal-close" onclick="closeModal('modal-del-bst')">&times;</button>
        <div class="del-center">
            <i class="bi bi-exclamation-triangle-fill del-icon"></i>
            <h4 style="color: var(--adm-text-main); margin-bottom:10px; font-family: var(--adm-font-heading);">Xác Nhận Xóa</h4>
            <p id="del-bst-text" style="color: var(--adm-text-muted); font-size:0.88rem; line-height:1.5;">Bạn có chắc muốn xóa bộ sưu tập này không?</p>
            <form id="form-del-bst" action="" method="POST">
                @csrf
                <div class="del-btn-group">
                    <button type="button" class="btn-cancel" onclick="closeModal('modal-del-bst')">Hủy bỏ</button>
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

    document.querySelectorAll('.bst-modal-overlay').forEach(function(el) {
        el.addEventListener('click', function(e) {
            if (e.target === el) closeModal(el.id);
        });
    });

    function openEditModal(btn) {
        var id     = btn.getAttribute('data-id');
        var ten    = btn.getAttribute('data-ten');
        var mota   = btn.getAttribute('data-mota') || '';
        var banner = btn.getAttribute('data-banner') || '';
        var spIds  = [];

        try {
            spIds = JSON.parse(btn.getAttribute('data-spids') || '[]');
        } catch(e) {
            spIds = [];
        }

        document.getElementById('form-edit-bst').action = '{{ url("/bo-suu-tap") }}/' + id + '/sua';
        document.getElementById('edit_ten_bst').value = ten;
        document.getElementById('edit_mota_bst').value = mota;

        var preview = document.getElementById('edit_banner_preview');
        if (banner) {
            preview.innerHTML = 'Banner hiện tại: <strong>' + banner + '</strong>';
        } else {
            preview.innerHTML = 'Chưa có ảnh banner.';
        }

        document.querySelectorAll('.edit-suit-checkbox').forEach(function(cb) {
            var val = parseInt(cb.value);
            cb.checked = spIds.includes(val);
        });

        openModal('modal-edit-bst');
    }

    function openDeleteModal(btn) {
        var id  = btn.getAttribute('data-id');
        var ten = btn.getAttribute('data-ten');

        document.getElementById('form-del-bst').action = '{{ url("/bo-suu-tap") }}/' + id + '/xoa';
        document.getElementById('del-bst-text').innerHTML = 'Bạn có chắc chắn muốn xóa bộ sưu tập <strong style="color:var(--adm-gold);">"' + ten + '"</strong>?<br><span style="font-size:0.8rem; color:var(--adm-text-dim);">Các sản phẩm áo vest bên trong sẽ không bị xóa, chỉ hủy liên kết khỏi bộ sưu tập này.</span>';

        openModal('modal-del-bst');
    }
</script>
@endsection
