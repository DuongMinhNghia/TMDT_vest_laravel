@extends('layouts.admin_layout')

@section('title', 'Quản Lý Sản Phẩm & Kho Size - Kingsman Admin')
@section('page_name', 'Quản Lý Sản Phẩm & Kho Size')

@section('styles')
<style>
    /* PAGE HEADER */
    .admin-page-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
    }

    .admin-page-title h2 {
        font-family: var(--adm-font-heading);
        font-size: 1.6rem;
        color: var(--adm-text-main);
        margin-bottom: 4px;
        font-weight: 700;
        letter-spacing: 0.5px;
    }

    .admin-page-title p {
        color: var(--adm-text-muted);
        font-size: 0.85rem;
        margin-bottom: 0;
    }

    .btn-admin-add {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: var(--adm-gold);
        color: #111317;
        padding: 9px 20px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 0.84rem;
        letter-spacing: 0.3px;
        border: none;
        cursor: pointer;
        transition: all 0.25s ease;
    }

    .btn-admin-add:hover {
        background: var(--adm-gold-light);
        box-shadow: 0 4px 15px rgba(212, 178, 111, 0.3);
        transform: translateY(-1px);
    }

    /* KPI STATS CARDS */
    .kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .kpi-card {
        background: var(--adm-bg-card);
        border: 1px solid var(--adm-border);
        border-radius: 10px;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: border-color 0.2s;
    }

    .kpi-card:hover {
        border-color: rgba(212, 178, 111, 0.3);
    }

    .kpi-info-label {
        color: var(--adm-text-muted);
        font-size: 0.78rem;
        font-weight: 500;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 4px;
    }

    .kpi-info-val {
        font-family: var(--adm-font-heading);
        font-size: 1.8rem;
        font-weight: 700;
        color: var(--adm-text-main);
        line-height: 1;
    }

    .kpi-icon-box {
        width: 44px;
        height: 44px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
    }

    .kpi-icon-gold {
        background: rgba(212, 178, 111, 0.12);
        color: var(--adm-gold);
    }

    .kpi-icon-emerald {
        background: rgba(16, 185, 129, 0.12);
        color: #34d399;
    }

    .kpi-icon-amber {
        background: rgba(245, 158, 11, 0.12);
        color: #fbbf24;
    }

    .kpi-icon-blue {
        background: rgba(59, 130, 246, 0.12);
        color: #60a5fa;
    }

    /* TOOLBAR LỌC & TÌM KIẾM NHANH */
    .admin-toolbar {
        background: var(--adm-bg-card);
        border: 1px solid var(--adm-border);
        border-radius: 10px;
        padding: 14px 18px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 14px;
        flex-wrap: wrap;
    }

    .toolbar-search-box {
        position: relative;
        min-width: 260px;
        flex: 1;
    }

    .toolbar-search-box i {
        position: absolute;
        left: 14px;
        top: 50%;
        transform: translateY(-50%);
        color: var(--adm-text-dim);
        font-size: 0.9rem;
    }

    .toolbar-search-input {
        width: 100%;
        background: var(--adm-bg-main);
        border: 1px solid var(--adm-border);
        border-radius: 6px;
        color: var(--adm-text-main);
        padding: 8px 14px 8px 38px;
        font-size: 0.85rem;
        outline: none;
        transition: all 0.2s;
    }

    .toolbar-search-input:focus {
        border-color: var(--adm-gold);
        box-shadow: 0 0 0 2px var(--adm-gold-glow);
    }

    .toolbar-select {
        background: var(--adm-bg-main);
        border: 1px solid var(--adm-border);
        border-radius: 6px;
        color: var(--adm-text-muted);
        padding: 8px 14px;
        font-size: 0.84rem;
        outline: none;
        cursor: pointer;
        min-width: 160px;
    }

    .toolbar-select:focus {
        border-color: var(--adm-gold);
    }

    /* DATA TABLE */
    .admin-table-card {
        background: var(--adm-bg-card);
        border: 1px solid var(--adm-border);
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.25);
    }

    .admin-table-responsive {
        width: 100%;
        overflow-x: auto;
    }

    .adm-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
    }

    .adm-table thead {
        background: #141720;
        border-bottom: 1px solid var(--adm-border);
    }

    .adm-table thead th {
        padding: 14px 18px;
        color: var(--adm-text-muted);
        font-size: 0.78rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        white-space: nowrap;
    }

    .adm-table tbody td {
        padding: 13px 18px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.04);
        vertical-align: middle;
        font-size: 0.85rem;
    }

    .adm-table tbody tr {
        transition: background-color 0.15s ease;
    }

    .adm-table tbody tr:hover {
        background-color: var(--adm-bg-hover);
    }

    .adm-table tbody tr:last-child td {
        border-bottom: none;
    }

    /* PHẦN TỬ TRONG BẢNG */
    .sp-thumb {
        width: 52px;
        height: 52px;
        object-fit: cover;
        border-radius: 6px;
        border: 1px solid var(--adm-border);
        background: #111317;
    }

    .sp-title-cell {
        display: flex;
        flex-direction: column;
        gap: 3px;
    }

    .sp-name-text {
        font-weight: 600;
        color: var(--adm-text-main);
        font-size: 0.88rem;
    }

    .sp-sku-tag {
        font-family: monospace;
        font-size: 0.75rem;
        color: var(--adm-gold);
    }

    .sp-cat-badge {
        color: var(--adm-text-muted);
        font-size: 0.82rem;
    }

    .sp-price-text {
        font-weight: 700;
        color: var(--adm-text-main);
        white-space: nowrap;
        font-size: 0.88rem;
    }

    .sp-form-pill {
        display: inline-block;
        padding: 3px 8px;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid var(--adm-border);
        border-radius: 4px;
        font-size: 0.75rem;
        color: var(--adm-text-muted);
    }

    /* SIZE BADGES */
    .size-inventory-box {
        display: flex;
        flex-wrap: wrap;
        gap: 4px;
        max-width: 240px;
    }

    .size-pill {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        padding: 2px 6px;
        border-radius: 4px;
        font-size: 0.72rem;
        font-weight: 600;
    }

    .size-pill-in {
        background: rgba(16, 185, 129, 0.12);
        color: #34d399;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }

    .size-pill-out {
        background: rgba(239, 68, 68, 0.12);
        color: #f87171;
        border: 1px solid rgba(239, 68, 68, 0.3);
        opacity: 0.8;
    }

    .stock-summary {
        font-size: 0.75rem;
        color: var(--adm-text-dim);
        margin-bottom: 4px;
    }

    /* ACTION BUTTONS */
    .action-group {
        display: flex;
        align-items: center;
        gap: 6px;
        justify-content: center;
    }

    .btn-act {
        width: 30px;
        height: 30px;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 1px solid transparent;
        cursor: pointer;
        font-size: 0.82rem;
        transition: all 0.2s;
    }

    .btn-act-edit {
        background: rgba(212, 178, 111, 0.1);
        color: var(--adm-gold);
        border-color: rgba(212, 178, 111, 0.3);
    }

    .btn-act-edit:hover {
        background: var(--adm-gold);
        color: #111317;
    }

    .btn-act-del {
        background: rgba(239, 68, 68, 0.1);
        color: #ef4444;
        border-color: rgba(239, 68, 68, 0.3);
    }

    .btn-act-del:hover {
        background: #ef4444;
        color: #fff;
    }

    /* MODAL STYLING */
    .adm-modal-backdrop {
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, 0.75);
        backdrop-filter: blur(5px);
        z-index: 1050;
        display: none;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .adm-modal-backdrop.active {
        display: flex;
    }

    .adm-modal-card {
        background: var(--adm-bg-card);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 12px;
        width: 100%;
        max-width: 680px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: 0 25px 60px rgba(0, 0, 0, 0.7);
        position: relative;
        padding: 28px;
    }

    .adm-modal-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        border-bottom: 1px solid var(--adm-border);
        padding-bottom: 14px;
    }

    .adm-modal-title {
        font-family: var(--adm-font-heading);
        font-size: 1.35rem;
        color: var(--adm-text-main);
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .adm-modal-title i {
        color: var(--adm-gold);
    }

    .adm-modal-close {
        background: transparent;
        border: none;
        color: var(--adm-text-dim);
        font-size: 1.4rem;
        cursor: pointer;
        line-height: 1;
        transition: color 0.2s;
    }

    .adm-modal-close:hover {
        color: #fff;
    }

    .form-group-adm {
        margin-bottom: 16px;
    }

    .form-label-adm {
        display: block;
        font-size: 0.8rem;
        font-weight: 600;
        color: var(--adm-text-muted);
        margin-bottom: 6px;
        letter-spacing: 0.3px;
    }

    .form-ctrl-adm {
        width: 100%;
        background: var(--adm-bg-main);
        border: 1px solid var(--adm-border);
        border-radius: 6px;
        padding: 8px 14px;
        color: var(--adm-text-main);
        font-size: 0.85rem;
        outline: none;
        transition: all 0.2s;
    }

    .form-ctrl-adm:focus {
        border-color: var(--adm-gold);
        box-shadow: 0 0 0 2px var(--adm-gold-glow);
    }

    .form-row-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    @media (max-width: 576px) {
        .form-row-2 {
            grid-template-columns: 1fr;
        }
    }

    /* MATRIX NHẬP SIZE */
    .sizes-matrix-wrap {
        background: var(--adm-bg-main);
        border: 1px solid var(--adm-border);
        border-radius: 8px;
        padding: 16px;
        margin-bottom: 16px;
    }

    .sizes-matrix-title {
        color: var(--adm-gold);
        font-size: 0.82rem;
        font-weight: 600;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .sizes-matrix-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 10px;
    }

    @media (max-width: 576px) {
        .sizes-matrix-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    .size-input-col label {
        display: block;
        font-size: 0.75rem;
        font-weight: 600;
        color: var(--adm-text-main);
        text-align: center;
        margin-bottom: 4px;
    }

    .size-input-col input {
        text-align: center;
        padding: 6px 4px;
    }

    .size-input-col span {
        display: block;
        font-size: 0.68rem;
        color: var(--adm-text-dim);
        text-align: center;
        margin-top: 4px;
    }

    .adm-modal-actions {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        margin-top: 24px;
        padding-top: 16px;
        border-top: 1px solid var(--adm-border);
    }

    .btn-modal-cancel {
        background: transparent;
        border: 1px solid var(--adm-border);
        color: var(--adm-text-muted);
        padding: 8px 18px;
        border-radius: 6px;
        font-size: 0.84rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-modal-cancel:hover {
        color: #fff;
        border-color: rgba(255, 255, 255, 0.3);
    }

    .btn-modal-submit {
        background: var(--adm-gold);
        color: #111317;
        border: none;
        padding: 8px 22px;
        border-radius: 6px;
        font-size: 0.84rem;
        font-weight: 600;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }

    .btn-modal-submit:hover {
        background: var(--adm-gold-light);
        box-shadow: 0 4px 15px rgba(212, 178, 111, 0.3);
    }

    /* MODAL XÓA */
    .adm-modal-delete {
        max-width: 420px;
        text-align: center;
    }

    .del-alert-icon {
        font-size: 2.8rem;
        color: #ef4444;
        margin-bottom: 12px;
    }
</style>
@endsection

@section('content')
<meta name="base-img-url" content="{{ asset('images/') }}/">
<meta name="has-add-errors" content="{{ (isset($errors) && $errors->hasBag('add') && $errors->getBag('add')->any()) ? '1' : '0' }}">
<meta name="has-edit-errors" content="{{ (isset($errors) && $errors->hasBag('edit') && $errors->getBag('edit')->any()) ? '1' : '0' }}">

<!-- TẦNG 1: PAGE HEADER -->
<div class="admin-page-header">
    <div class="admin-page-title">
        <h2>Quản Lý Sản Phẩm & Kho Size</h2>
        <p>Kiểm soát danh mục âu phục vest, theo dõi ma trận số lượng tồn theo từng size (S, M, L, XL, XXL)</p>
    </div>
    <div>
        <button type="button" class="btn-admin-add" onclick="openAdminModal('modal-add')">
            <i class="bi bi-plus-lg"></i>
            <span>Thêm Sản Phẩm Mới</span>
        </button>
    </div>
</div>

<!-- TẦNG 2: 4 THẺ KPI CHỈ SỐ NHANH -->
@php
    $totalCount = $sanPhams->count();
    $totalInventory = $sanPhams->sum(fn($sp) => $sp->tongTonKho());
    $lowStockCount = $sanPhams->filter(fn($sp) => $sp->tongTonKho() <= 5)->count();
    $categoriesCount = $danhMucs->count();
@endphp
<div class="kpi-grid">
    <div class="kpi-card">
        <div>
            <div class="kpi-info-label">Tổng Mẫu Vest</div>
            <div class="kpi-info-val">{{ $totalCount }}</div>
        </div>
        <div class="kpi-icon-box kpi-icon-gold">
            <i class="bi bi-box-seam"></i>
        </div>
    </div>

    <div class="kpi-card">
        <div>
            <div class="kpi-info-label">Tổng Tồn Kho (Bộ)</div>
            <div class="kpi-info-val">{{ number_format($totalInventory, 0, ',', '.') }}</div>
        </div>
        <div class="kpi-icon-box kpi-icon-emerald">
            <i class="bi bi-stack"></i>
        </div>
    </div>

    <div class="kpi-card">
        <div>
            <div class="kpi-info-label">Sắp Hết / Hết Kho</div>
            <div class="kpi-info-val" style="color: {{ $lowStockCount > 0 ? '#fbbf24' : '#34d399' }};">{{ $lowStockCount }}</div>
        </div>
        <div class="kpi-icon-box kpi-icon-amber">
            <i class="bi bi-exclamation-triangle"></i>
        </div>
    </div>

    <div class="kpi-card">
        <div>
            <div class="kpi-info-label">Danh Mục Âu Phục</div>
            <div class="kpi-info-val">{{ $categoriesCount }}</div>
        </div>
        <div class="kpi-icon-box kpi-icon-blue">
            <i class="bi bi-tags"></i>
        </div>
    </div>
</div>

<!-- TẦNG 3: TOOLBAR TÌM KIẾM & LỌC TỨC THÌ (CLIENT-SIDE) -->
<div class="admin-toolbar">
    <div class="toolbar-search-box">
        <i class="bi bi-search"></i>
        <input type="text" id="liveSearchInput" placeholder="Tìm theo tên vest, mã SKU, chất liệu vải..." class="toolbar-search-input" onkeyup="filterProductTable()">
    </div>

    <div class="d-flex align-items-center gap-2 flex-wrap">
        <select id="filterCategorySelect" class="toolbar-select" onchange="filterProductTable()">
            <option value="">Tất cả danh mục</option>
            @foreach($danhMucs as $dm)
            <option value="{{ strtolower($dm->ten_danh_muc) }}">{{ $dm->ten_danh_muc }}</option>
            @endforeach
        </select>

        <select id="filterStockSelect" class="toolbar-select" onchange="filterProductTable()">
            <option value="">Tất cả trạng thái tồn</option>
            <option value="in_stock">Còn hàng (> 5 bộ)</option>
            <option value="low_stock">Sắp hết / Hết kho (<= 5)</option>
        </select>
    </div>
</div>

<!-- TẦNG 4: BẢNG DỮ LIỆU DATA TABLE -->
<div class="admin-table-card">
    <div class="admin-table-responsive">
        <table class="adm-table" id="productTable">
            <thead>
                <tr>
                    <th style="width: 70px;">Ảnh</th>
                    <th>Mã & Tên Sản Phẩm</th>
                    <th>Danh Mục</th>
                    <th>Giá Niêm Yết</th>
                    <th>Form Dáng</th>
                    <th>Tồn Kho Từng Size</th>
                    <th class="text-center" style="width: 100px;">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sanPhams as $sp)
                @php
                    $tongKho = $sp->tongTonKho();
                    $sku = $sp->ma_sp ?: 'VS-' . str_pad($sp->id, 3, '0', STR_PAD_LEFT);
                @endphp
                <tr class="product-row"
                    data-name="{{ strtolower($sp->ten_sp) }}"
                    data-sku="{{ strtolower($sku) }}"
                    data-cat="{{ strtolower($sp->danhMuc->ten_danh_muc ?? '') }}"
                    data-material="{{ strtolower($sp->chat_lieu ?? '') }}"
                    data-stock="{{ $tongKho }}">
                    <td>
                        @if($sp->hinh_anh)
                        <img src="{{ asset('images/' . $sp->hinh_anh) }}" alt="{{ $sp->ten_sp }}" class="sp-thumb" loading="lazy">
                        @else
                        <div class="sp-thumb d-flex align-items-center justify-content-center text-muted">
                            <i class="bi bi-image"></i>
                        </div>
                        @endif
                    </td>
                    <td>
                        <div class="sp-title-cell">
                            <span class="sp-name-text">{{ $sp->ten_sp }}</span>
                            <span class="sp-sku-tag">{{ $sku }}</span>
                        </div>
                    </td>
                    <td>
                        <span class="sp-cat-badge">{{ $sp->danhMuc->ten_danh_muc ?? 'Chưa phân loại' }}</span>
                    </td>
                    <td>
                        <span class="sp-price-text">{{ number_format($sp->gia_ban ?? $sp->gia, 0, ',', '.') }} ₫</span>
                    </td>
                    <td>
                        <span class="sp-form-pill">{{ $sp->form_dang ?: 'Slim-fit' }}</span>
                    </td>
                    <td>
                        <div class="stock-summary">
                            Tổng tồn: <strong style="color: {{ $tongKho <= 5 ? '#f87171' : 'var(--adm-gold)' }};">{{ $tongKho }}</strong> bộ
                        </div>
                        <div class="size-inventory-box">
                            @foreach($sp->sizes as $s)
                            <span class="size-pill {{ $s->so_luong_ton > 0 ? 'size-pill-in' : 'size-pill-out' }}" title="Size {{ $s->ten_size }}: {{ $s->so_luong_ton }} cái">
                                {{ $s->ten_size }}: {{ $s->so_luong_ton }}
                            </span>
                            @endforeach
                        </div>
                    </td>
                    <td>
                        <div class="action-group">
                            <button type="button" class="btn-act btn-act-edit" title="Chỉnh sửa sản phẩm & kho size"
                                data-id="{{ $sp->id }}"
                                data-masp="{{ $sp->ma_sp }}"
                                data-ten="{{ $sp->ten_sp }}"
                                data-danhmuc="{{ $sp->danh_muc_id }}"
                                data-gia="{{ $sp->gia_ban ?? $sp->gia }}"
                                data-formdang="{{ $sp->form_dang }}"
                                data-xuatxu="{{ $sp->xuat_xu }}"
                                data-chatlieu="{{ $sp->chat_lieu }}"
                                data-mota="{{ $sp->mo_ta }}"
                                data-hinhanh="{{ $sp->hinh_anh }}"
                                data-sizes="{{ json_encode($sp->sizes->pluck('so_luong_ton', 'ten_size')) }}"
                                onclick="openAdminEditModal(this)">
                                <i class="bi bi-pencil"></i>
                            </button>

                            <button type="button" class="btn-act btn-act-del" title="Xóa sản phẩm"
                                data-id="{{ $sp->id }}"
                                data-ten="{{ $sp->ten_sp }}"
                                onclick="openAdminDeleteModal(this)">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-2 d-block mb-2" style="color: var(--adm-gold); opacity: 0.4;"></i>
                        Chưa có sản phẩm nào trong kho dữ liệu.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- ================= MODAL THÊM SẢN PHẨM ================= -->
<div class="adm-modal-backdrop" id="modal-add">
    <div class="adm-modal-card">
        <div class="adm-modal-header">
            <h3 class="adm-modal-title">
                <i class="bi bi-plus-circle"></i> Thêm Sản Phẩm Mới
            </h3>
            <button type="button" class="adm-modal-close" onclick="closeAdminModal('modal-add')">&times;</button>
        </div>

        @if(isset($errors) && $errors->hasBag('add') && $errors->getBag('add')->any())
        <div class="admin-alert admin-alert-error mb-3">
            <i class="bi bi-exclamation-circle-fill fs-5"></i>
            <div>
                <ul class="mb-0 ps-3">
                    @foreach($errors->getBag('add')->all() as $err)
                    <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <form action="{{ route('sanpham.luuthem') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-row-2">
                <div class="form-group-adm">
                    <label class="form-label-adm">Tên sản phẩm vest *</label>
                    <input type="text" name="ten_sp" class="form-ctrl-adm" value="{{ old('ten_sp') }}" placeholder="VD: Vest Classic Navy 2 Cúc" required>
                </div>
                <div class="form-group-adm">
                    <label class="form-label-adm">Mã SKU (tự sinh nếu để trống)</label>
                    <input type="text" name="ma_sp" class="form-ctrl-adm" value="{{ old('ma_sp') }}" placeholder="VD: VEST-NAVY-01">
                </div>
            </div>

            <div class="form-row-2">
                <div class="form-group-adm">
                    <label class="form-label-adm">Danh mục *</label>
                    <select name="danh_muc_id" class="form-ctrl-adm" required>
                        <option value="">-- Chọn danh mục --</option>
                        @foreach($danhMucs as $dm)
                        <option value="{{ $dm->id }}" {{ old('danh_muc_id') == $dm->id ? 'selected' : '' }}>
                            {{ $dm->ten_danh_muc }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group-adm">
                    <label class="form-label-adm">Giá niêm yết (VNĐ) *</label>
                    <input type="number" name="gia" class="form-ctrl-adm" value="{{ old('gia') }}" placeholder="VD: 3200000" min="0" required>
                </div>
            </div>

            <div class="form-row-2">
                <div class="form-group-adm">
                    <label class="form-label-adm">Form dáng áo</label>
                    <select name="form_dang" class="form-ctrl-adm">
                        <option value="Slim-fit" {{ old('form_dang') == 'Slim-fit' ? 'selected' : '' }}>Slim-fit (Ôm dáng hiện đại)</option>
                        <option value="Regular" {{ old('form_dang') == 'Regular' ? 'selected' : '' }}>Regular (Vừa vặn thoải mái)</option>
                        <option value="Classic" {{ old('form_dang') == 'Classic' ? 'selected' : '' }}>Classic (Cổ điển phong độ)</option>
                    </select>
                </div>
                <div class="form-group-adm">
                    <label class="form-label-adm">Chất liệu vải</label>
                    <input type="text" name="chat_lieu" class="form-ctrl-adm" value="{{ old('chat_lieu') }}" placeholder="VD: Wool Ý 100%, Tuytsi Hàn">
                </div>
            </div>

            <div class="form-group-adm">
                <label class="form-label-adm">Xuất xứ sản phẩm</label>
                <input type="text" name="xuat_xu" class="form-ctrl-adm" value="{{ old('xuat_xu') }}" placeholder="VD: Nhập khẩu Italy, May đo thủ công Việt Nam">
            </div>

            <!-- MA TRẬN NHẬP TỒN KHO TỪNG SIZE -->
            <div class="sizes-matrix-wrap">
                <div class="sizes-matrix-title">
                    <i class="bi bi-stack"></i> Thiết lập số lượng tồn kho từng Size:
                </div>
                <div class="sizes-matrix-grid">
                    <div class="size-input-col">
                        <label>Size S</label>
                        <input type="number" name="sizes[S]" class="form-ctrl-adm" min="0" value="{{ old('sizes.S', 0) }}">
                        <span>1m60-1m68</span>
                    </div>
                    <div class="size-input-col">
                        <label>Size M</label>
                        <input type="number" name="sizes[M]" class="form-ctrl-adm" min="0" value="{{ old('sizes.M', 0) }}">
                        <span>1m68-1m74</span>
                    </div>
                    <div class="size-input-col">
                        <label>Size L</label>
                        <input type="number" name="sizes[L]" class="form-ctrl-adm" min="0" value="{{ old('sizes.L', 0) }}">
                        <span>1m74-1m78</span>
                    </div>
                    <div class="size-input-col">
                        <label>Size XL</label>
                        <input type="number" name="sizes[XL]" class="form-ctrl-adm" min="0" value="{{ old('sizes.XL', 0) }}">
                        <span>1m78-1m83</span>
                    </div>
                    <div class="size-input-col">
                        <label>Size XXL</label>
                        <input type="number" name="sizes[XXL]" class="form-ctrl-adm" min="0" value="{{ old('sizes.XXL', 0) }}">
                        <span>1m80-1m88</span>
                    </div>
                </div>
            </div>

            <div class="form-group-adm">
                <label class="form-label-adm">Mô tả sản phẩm</label>
                <textarea name="mo_ta" class="form-ctrl-adm" rows="3" placeholder="Mô tả ve áo, ve xếch, lớp lót lụa, dịp mặc phù hợp...">{{ old('mo_ta') }}</textarea>
            </div>

            <div class="form-group-adm">
                <label class="form-label-adm">Hình ảnh trang phục</label>
                <input type="file" name="hinh_anh" class="form-ctrl-adm" accept="image/*">
            </div>

            <div class="adm-modal-actions">
                <button type="button" class="btn-modal-cancel" onclick="closeAdminModal('modal-add')">Hủy bỏ</button>
                <button type="submit" class="btn-modal-submit">
                    <i class="bi bi-save"></i> Lưu Sản Phẩm
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ================= MODAL SỬA SẢN PHẨM & TỒN KHO ================= -->
<div class="adm-modal-backdrop" id="modal-edit">
    <div class="adm-modal-card">
        <div class="adm-modal-header">
            <h3 class="adm-modal-title">
                <i class="bi bi-pencil-square"></i> Cập Nhật Sản Phẩm & Kho Size
            </h3>
            <button type="button" class="adm-modal-close" onclick="closeAdminModal('modal-edit')">&times;</button>
        </div>

        @if(isset($errors) && $errors->hasBag('edit') && $errors->getBag('edit')->any())
        <div class="admin-alert admin-alert-error mb-3">
            <i class="bi bi-exclamation-circle-fill fs-5"></i>
            <div>
                <ul class="mb-0 ps-3">
                    @foreach($errors->getBag('edit')->all() as $err)
                    <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif

        <form id="form-edit" action="" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-row-2">
                <div class="form-group-adm">
                    <label class="form-label-adm">Tên sản phẩm *</label>
                    <input type="text" id="edit_ten_sp" name="ten_sp" class="form-ctrl-adm" required>
                </div>
                <div class="form-group-adm">
                    <label class="form-label-adm">Mã SKU</label>
                    <input type="text" id="edit_ma_sp" name="ma_sp" class="form-ctrl-adm">
                </div>
            </div>

            <div class="form-row-2">
                <div class="form-group-adm">
                    <label class="form-label-adm">Danh mục *</label>
                    <select id="edit_danh_muc_id" name="danh_muc_id" class="form-ctrl-adm" required>
                        <option value="">-- Chọn danh mục --</option>
                        @foreach($danhMucs as $dm)
                        <option value="{{ $dm->id }}">{{ $dm->ten_danh_muc }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group-adm">
                    <label class="form-label-adm">Giá niêm yết (VNĐ) *</label>
                    <input type="number" id="edit_gia" name="gia" class="form-ctrl-adm" min="0" required>
                </div>
            </div>

            <div class="form-row-2">
                <div class="form-group-adm">
                    <label class="form-label-adm">Form dáng áo</label>
                    <select id="edit_form_dang" name="form_dang" class="form-ctrl-adm">
                        <option value="Slim-fit">Slim-fit (Ôm dáng hiện đại)</option>
                        <option value="Regular">Regular (Vừa vặn thoải mái)</option>
                        <option value="Classic">Classic (Cổ điển)</option>
                    </select>
                </div>
                <div class="form-group-adm">
                    <label class="form-label-adm">Chất liệu vải</label>
                    <input type="text" id="edit_chat_lieu" name="chat_lieu" class="form-ctrl-adm">
                </div>
            </div>

            <div class="form-group-adm">
                <label class="form-label-adm">Xuất xứ</label>
                <input type="text" id="edit_xuat_xu" name="xuat_xu" class="form-ctrl-adm">
            </div>

            <!-- MA TRẬN SỬA TỒN KHO TỪNG SIZE -->
            <div class="sizes-matrix-wrap">
                <div class="sizes-matrix-title">
                    <i class="bi bi-stack"></i> Cập nhật tồn kho theo từng Size:
                </div>
                <div class="sizes-matrix-grid">
                    <div class="size-input-col">
                        <label>Size S</label>
                        <input type="number" id="edit_size_S" name="sizes[S]" class="form-ctrl-adm" min="0" value="0">
                        <span>1m60-1m68</span>
                    </div>
                    <div class="size-input-col">
                        <label>Size M</label>
                        <input type="number" id="edit_size_M" name="sizes[M]" class="form-ctrl-adm" min="0" value="0">
                        <span>1m68-1m74</span>
                    </div>
                    <div class="size-input-col">
                        <label>Size L</label>
                        <input type="number" id="edit_size_L" name="sizes[L]" class="form-ctrl-adm" min="0" value="0">
                        <span>1m74-1m78</span>
                    </div>
                    <div class="size-input-col">
                        <label>Size XL</label>
                        <input type="number" id="edit_size_XL" name="sizes[XL]" class="form-ctrl-adm" min="0" value="0">
                        <span>1m78-1m83</span>
                    </div>
                    <div class="size-input-col">
                        <label>Size XXL</label>
                        <input type="number" id="edit_size_XXL" name="sizes[XXL]" class="form-ctrl-adm" min="0" value="0">
                        <span>1m80-1m88</span>
                    </div>
                </div>
            </div>

            <div class="form-group-adm">
                <label class="form-label-adm">Mô tả</label>
                <textarea id="edit_mo_ta" name="mo_ta" class="form-ctrl-adm" rows="3"></textarea>
            </div>

            <div class="form-group-adm">
                <label class="form-label-adm">Hình ảnh hiện tại</label>
                <div id="edit_img_preview" class="d-flex align-items-center gap-3 mb-2 p-2 rounded" style="background: var(--adm-bg-main); border: 1px solid var(--adm-border);"></div>
                <label class="form-label-adm" style="color: var(--adm-text-dim);">Tải ảnh mới thay thế (để trống nếu giữ nguyên):</label>
                <input type="file" name="hinh_anh" class="form-ctrl-adm" accept="image/*">
            </div>

            <div class="adm-modal-actions">
                <button type="button" class="btn-modal-cancel" onclick="closeAdminModal('modal-edit')">Hủy bỏ</button>
                <button type="submit" class="btn-modal-submit">
                    <i class="bi bi-check-lg"></i> Cập Nhật
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ================= MODAL XÁC NHẬN XÓA ================= -->
<div class="adm-modal-backdrop" id="modal-delete">
    <div class="adm-modal-card adm-modal-delete">
        <button type="button" class="adm-modal-close position-absolute top-0 end-0 m-3" onclick="closeAdminModal('modal-delete')">&times;</button>
        <div class="del-alert-icon">
            <i class="bi bi-trash3"></i>
        </div>
        <h3 class="adm-modal-title justify-content-center mb-2">Xác Nhận Xóa</h3>
        <p id="del-confirm-text" class="text-muted mb-4" style="font-size: 0.9rem; line-height: 1.6;"></p>
        <form id="form-delete" action="" method="POST">
            @csrf
            <div class="d-flex justify-content-center gap-3">
                <button type="button" class="btn-modal-cancel" onclick="closeAdminModal('modal-delete')">Hủy</button>
                <button type="submit" class="btn-modal-submit" style="background:#ef4444; color:#fff;">
                    <i class="bi bi-trash"></i> Xóa Vĩnh Viễn
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    const BASE_IMG_URL = document.querySelector('meta[name="base-img-url"]').getAttribute('content');
    const HAS_ADD_ERRORS = document.querySelector('meta[name="has-add-errors"]').getAttribute('content') === '1';
    const HAS_EDIT_ERRORS = document.querySelector('meta[name="has-edit-errors"]').getAttribute('content') === '1';

    function openAdminModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.add('active');
            document.body.style.overflow = 'hidden';
        }
    }

    function closeAdminModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.remove('active');
            document.body.style.overflow = '';
        }
    }

    // Đóng modal khi bấm ra ngoài vùng card
    document.querySelectorAll('.adm-modal-backdrop').forEach(el => {
        el.addEventListener('click', e => {
            if (e.target === el) closeAdminModal(el.id);
        });
    });

    // Đóng khi bấm phím ESC
    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') {
            document.querySelectorAll('.adm-modal-backdrop.active').forEach(el => {
                closeAdminModal(el.id);
            });
        }
    });

    // Mở modal sửa và đổ dữ liệu
    function openAdminEditModal(btn) {
        const id       = btn.getAttribute('data-id');
        const masp     = btn.getAttribute('data-masp') || '';
        const ten      = btn.getAttribute('data-ten');
        const danhMuc  = btn.getAttribute('data-danhmuc');
        const gia      = btn.getAttribute('data-gia');
        const formDang = btn.getAttribute('data-formdang') || 'Slim-fit';
        const xuatXu   = btn.getAttribute('data-xuatxu') || '';
        const chatLieu = btn.getAttribute('data-chatlieu') || '';
        const moTa     = btn.getAttribute('data-mota') || '';
        const hinhAnh  = btn.getAttribute('data-hinhanh') || '';
        let sizes = {};

        try {
            sizes = JSON.parse(btn.getAttribute('data-sizes') || '{}');
        } catch(e) {
            sizes = {};
        }

        document.getElementById('form-edit').action = '{{ url("/san-pham") }}/' + id + '/sua';
        document.getElementById('edit_ma_sp').value = masp;
        document.getElementById('edit_ten_sp').value = ten;
        document.getElementById('edit_gia').value = gia;
        document.getElementById('edit_form_dang').value = formDang;
        document.getElementById('edit_xuat_xu').value = xuatXu;
        document.getElementById('edit_chat_lieu').value = chatLieu;
        document.getElementById('edit_mo_ta').value = moTa;

        const sel = document.getElementById('edit_danh_muc_id');
        for (let i = 0; i < sel.options.length; i++) {
            sel.options[i].selected = (sel.options[i].value === danhMuc);
        }

        // Điền số lượng từng size
        ['S', 'M', 'L', 'XL', 'XXL'].forEach(s => {
            const input = document.getElementById('edit_size_' + s);
            if (input) {
                input.value = (sizes[s] !== undefined) ? sizes[s] : 0;
            }
        });

        // Ảnh preview
        const preview = document.getElementById('edit_img_preview');
        if (hinhAnh) {
            preview.innerHTML = `
                <img src="${BASE_IMG_URL + hinhAnh}" alt="${ten}" style="width:48px;height:48px;object-fit:cover;border-radius:4px;border:1px solid rgba(255,255,255,0.1);">
                <span class="text-muted" style="font-size:0.8rem;">${hinhAnh}</span>
            `;
        } else {
            preview.innerHTML = `<span class="text-muted" style="font-size:0.8rem;">Chưa có ảnh</span>`;
        }

        openAdminModal('modal-edit');
    }

    // Mở modal xóa
    function openAdminDeleteModal(btn) {
        const id  = btn.getAttribute('data-id');
        const ten = btn.getAttribute('data-ten');

        document.getElementById('form-delete').action = '{{ url("/san-pham") }}/' + id + '/xoa';
        document.getElementById('del-confirm-text').innerHTML = `
            Bạn có chắc muốn xóa trang phục <strong style="color:var(--adm-gold)">"${ten}"</strong>?<br>
            <span style="color:var(--adm-text-dim);font-size:0.8rem;">Toàn bộ dữ liệu kho size liên quan sẽ bị xóa khỏi hệ thống.</span>
        `;
        openAdminModal('modal-delete');
    }

    // Tự động mở lại modal nếu có validation errors từ backend
    if (HAS_ADD_ERRORS) openAdminModal('modal-add');
    if (HAS_EDIT_ERRORS) openAdminModal('modal-edit');

    // Lọc dữ liệu tức thì không cần tải lại trang (Client-side instant filter)
    function filterProductTable() {
        const searchVal = document.getElementById('liveSearchInput').value.toLowerCase().trim();
        const catVal = document.getElementById('filterCategorySelect').value.toLowerCase().trim();
        const stockVal = document.getElementById('filterStockSelect').value;
        const rows = document.querySelectorAll('#productTable tbody tr.product-row');

        rows.forEach(row => {
            const name = row.getAttribute('data-name') || '';
            const sku = row.getAttribute('data-sku') || '';
            const cat = row.getAttribute('data-cat') || '';
            const mat = row.getAttribute('data-material') || '';
            const stock = parseInt(row.getAttribute('data-stock') || '0', 10);

            let matchSearch = !searchVal || name.includes(searchVal) || sku.includes(searchVal) || mat.includes(searchVal);
            let matchCat = !catVal || cat.includes(catVal);
            let matchStock = true;

            if (stockVal === 'in_stock') {
                matchStock = stock > 5;
            } else if (stockVal === 'low_stock') {
                matchStock = stock <= 5;
            }

            if (matchSearch && matchCat && matchStock) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    }
</script>
@endsection