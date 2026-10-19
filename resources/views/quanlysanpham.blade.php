@extends('layouts.default_layout')
@section('title', 'Quản Lý Sản Phẩm & Kho Size')
@section('content')

<style>
    .sp-wrap {
        font-family: 'Be Vietnam Pro', sans-serif;
    }

    .sp-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .sp-title {
        color: #C5A059;
        font-family: 'Playfair Display', serif;
        font-size: 1.7rem;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .sp-alert {
        background: rgba(197, 160, 89, .12);
        border: 1px solid #C5A059;
        color: #C5A059;
        border-radius: 10px;
        padding: 12px 20px;
        margin-bottom: 22px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .sp-alert-err {
        background: rgba(255, 68, 68, .10);
        border-color: #ff4444;
        color: #ff6b6b;
    }

    .btn-add {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 24px;
        background: linear-gradient(135deg, #C5A059, #a08045);
        color: #000;
        border-radius: 25px;
        font-weight: 700;
        text-decoration: none;
        font-size: .9rem;
        border: none;
        cursor: pointer;
        transition: all .25s;
    }

    .btn-add:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(197, 160, 89, .4);
        color: #000;
    }

    .btn-icon {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: none;
        cursor: pointer;
        font-size: .82rem;
        transition: all .2s;
    }

    .btn-icon-edit {
        background: rgba(197, 160, 89, .15);
        color: #C5A059;
        border: 1px solid rgba(197, 160, 89, .35);
    }

    .btn-icon-edit:hover {
        background: #C5A059;
        color: #000;
    }

    .btn-icon-del {
        background: rgba(255, 68, 68, .12);
        color: #ff5555;
        border: 1px solid rgba(255, 68, 68, .3);
    }

    .btn-icon-del:hover {
        background: #ff4444;
        color: #fff;
    }

    .admin-table {
        background: #1a1a1a;
        border: 1px solid #2e2e2e;
        border-radius: 14px;
        overflow: hidden;
        box-shadow: 0 4px 24px rgba(0, 0, 0, .4);
    }

    .admin-table table {
        width: 100%;
        border-collapse: collapse;
    }

    .admin-table thead {
        background: linear-gradient(135deg, #C5A059, #8c6e35);
    }

    .admin-table thead th {
        padding: 14px 18px;
        font-weight: 700;
        border: none;
        color: #000;
        font-size: .85rem;
        letter-spacing: .5px;
        text-transform: uppercase;
        white-space: nowrap;
    }

    .admin-table tbody td {
        padding: 13px 18px;
        border-bottom: 1px solid #242424;
        vertical-align: middle;
        font-size: .9rem;
    }

    .admin-table tbody tr:last-child td {
        border-bottom: none;
    }

    .admin-table tbody tr {
        transition: background .15s;
    }

    .admin-table tbody tr:hover {
        background: rgba(197, 160, 89, .04);
    }

    .sp-img {
        width: 56px;
        height: 56px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #333;
    }

    .sp-name {
        font-weight: 600;
        color: #f0f0f0;
        display: block;
    }

    .sp-code {
        font-size: 0.78rem;
        color: #C5A059;
        font-family: monospace;
    }

    .sp-price {
        color: #C5A059;
        font-weight: 700;
        white-space: nowrap;
    }

    .sp-sub {
        color: #888;
        font-size: .85rem;
    }

    .badge-form {
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.2);
        color: #ddd;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.78rem;
    }

    /* Size badges trong bảng */
    .size-badge {
        display: inline-block;
        padding: 2px 7px;
        font-size: 0.75rem;
        font-weight: 600;
        border-radius: 4px;
        margin: 2px;
    }

    .size-badge-in {
        background: rgba(46, 204, 113, 0.15);
        color: #2ecc71;
        border: 1px solid rgba(46, 204, 113, 0.35);
    }

    .size-badge-out {
        background: rgba(231, 76, 60, 0.15);
        color: #e74c3c;
        border: 1px solid rgba(231, 76, 60, 0.35);
        opacity: 0.7;
    }

    .td-actions {
        display: flex;
        align-items: center;
        gap: 8px;
        justify-content: center;
    }

    .empty-row td {
        text-align: center;
        padding: 40px;
        color: #555;
    }

    /* Modal Styling */
    .sp-modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .82);
        z-index: 1050;
        align-items: center;
        justify-content: center;
        padding: 20px;
        backdrop-filter: blur(5px);
    }

    .sp-modal-overlay.active {
        display: flex;
    }

    .sp-modal {
        background: #181818;
        border: 1px solid #333;
        border-radius: 18px;
        width: 100%;
        max-width: 680px;
        max-height: 90vh;
        overflow-y: auto;
        padding: 30px;
        position: relative;
        animation: modalIn .22s ease;
    }

    @keyframes modalIn {
        from {
            opacity: 0;
            transform: translateY(-18px) scale(.97);
        }
        to {
            opacity: 1;
            transform: none;
        }
    }

    .sp-modal h3 {
        color: #C5A059;
        font-family: 'Playfair Display', serif;
        font-size: 1.4rem;
        margin: 0 0 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .sp-modal-close {
        position: absolute;
        top: 16px;
        right: 18px;
        background: none;
        border: none;
        color: #777;
        font-size: 1.5rem;
        cursor: pointer;
        transition: color .2s;
        line-height: 1;
    }

    .sp-modal-close:hover {
        color: #ff5555;
    }

    .form-group {
        margin-bottom: 16px;
    }

    .form-label {
        color: #C5A059;
        font-weight: 600;
        font-size: .85rem;
        margin-bottom: 6px;
        display: block;
        letter-spacing: .3px;
    }

    .form-control,
    .form-select {
        background: #111;
        border: 2px solid #363636;
        color: #e0e0e0;
        border-radius: 9px;
        padding: 9px 13px;
        width: 100%;
        font-size: .92rem;
        transition: border-color .25s, box-shadow .25s;
        box-sizing: border-box;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #C5A059;
        background: #161616;
        outline: none;
        box-shadow: 0 0 0 3px rgba(197, 160, 89, .15);
        color: #e0e0e0;
    }

    .form-control::placeholder {
        color: #555;
    }

    .form-select option {
        background: #1a1a1a;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 80px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    /* Box quản lý số lượng tồn theo từng Size */
    .sizes-box {
        background: #131313;
        border: 1px solid #2e2e2e;
        border-radius: 12px;
        padding: 16px;
        margin-bottom: 18px;
    }

    .sizes-box-title {
        color: #C5A059;
        font-size: 0.9rem;
        font-weight: 700;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .sizes-grid {
        display: grid;
        grid-template-columns: repeat(5, 1fr);
        gap: 10px;
    }

    .size-col {
        background: #1c1c1c;
        border: 1px solid #383838;
        border-radius: 8px;
        padding: 8px;
        text-align: center;
    }

    .size-col label {
        color: #f0f0f0;
        font-weight: 700;
        font-size: 0.85rem;
        display: block;
        margin-bottom: 4px;
    }

    .size-col input {
        text-align: center;
        padding: 6px;
        font-weight: 600;
        color: #C5A059;
    }

    .size-col .size-note {
        font-size: 0.7rem;
        color: #777;
        margin-top: 4px;
    }

    @media(max-width:560px) {
        .form-row {
            grid-template-columns: 1fr;
        }
        .sizes-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    .form-errors {
        background: rgba(255, 68, 68, .08);
        border: 1px solid rgba(255, 68, 68, .35);
        border-radius: 9px;
        padding: 12px 16px;
        margin-bottom: 18px;
    }

    .form-errors div {
        color: #ff6b6b;
        font-size: .86rem;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 4px;
    }

    .current-img {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 10px;
    }

    .current-img img {
        width: 70px;
        height: 70px;
        object-fit: cover;
        border-radius: 8px;
        border: 1px solid #333;
    }

    .current-img span {
        color: #888;
        font-size: .82rem;
    }

    .modal-footer {
        display: flex;
        gap: 12px;
        margin-top: 14px;
        flex-wrap: wrap;
    }

    .btn-save {
        padding: 10px 28px;
        background: linear-gradient(135deg, #C5A059, #a08045);
        color: #000;
        border: none;
        border-radius: 25px;
        font-weight: 700;
        cursor: pointer;
        font-size: .9rem;
        transition: all .25s;
        display: inline-flex;
        align-items: center;
        gap: 7px;
    }

    .btn-save:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 16px rgba(197, 160, 89, .4);
    }

    .btn-cancel-modal {
        padding: 10px 28px;
        background: transparent;
        color: #C5A059;
        border: 2px solid #C5A059;
        border-radius: 25px;
        font-weight: 700;
        font-size: .9rem;
        cursor: pointer;
        transition: all .25s;
        display: inline-flex;
        align-items: center;
        gap: 7px;
    }

    .btn-cancel-modal:hover {
        background: #C5A059;
        color: #000;
    }

    .del-modal {
        max-width: 420px;
        text-align: center;
    }

    .del-modal .del-icon {
        font-size: 3rem;
        margin-bottom: 14px;
        color: #ff5555;
    }

    .del-modal h3 {
        justify-content: center;
    }

    .del-modal p {
        color: #aaa;
        margin-bottom: 24px;
        font-size: .95rem;
        line-height: 1.6;
    }

    .btn-del-confirm {
        padding: 11px 30px;
        background: linear-gradient(135deg, #ff4444, #cc2222);
        color: #fff;
        border: none;
        border-radius: 25px;
        font-weight: 700;
        cursor: pointer;
        font-size: .9rem;
        transition: all .25s;
        display: inline-flex;
        align-items: center;
        gap: 7px;
    }

    .btn-del-confirm:hover {
        transform: translateY(-2px);
        box-shadow: 0 5px 16px rgba(255, 68, 68, .4);
    }
</style>

<meta name="base-img-url" content="{{ asset('images/') }}/">
<meta name="has-add-errors" content="{{ $errors->getBag('add')->any()  ? '1' : '0' }}">
<meta name="has-edit-errors" content="{{ $errors->getBag('edit')->any() ? '1' : '0' }}">

<div class="sp-wrap">

    <div class="sp-header">
        <h2 class="sp-title">
            <i class="bi bi-box-seam-fill"></i> Quản Lý Sản Phẩm & Kho Size
        </h2>
        <button class="btn-add" onclick="openModal('modal-add')">
            <i class="bi bi-plus-circle-fill"></i> Thêm Sản Phẩm Mới
        </button>
    </div>

    @if(session('success'))
    <div class="sp-alert">
        <i class="bi bi-check-circle-fill"></i> {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div class="sp-alert sp-alert-err">
        <i class="bi bi-x-circle-fill"></i> {{ session('error') }}
    </div>
    @endif

    <div class="admin-table">
        <table>
            <thead>
                <tr>
                    <th>Ảnh</th>
                    <th>Sản phẩm & Mã</th>
                    <th>Danh mục</th>
                    <th>Giá bán</th>
                    <th>Dáng áo</th>
                    <th>Tồn kho theo Size</th>
                    <th class="text-center">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($sanPhams as $sp)
                <tr>
                    <td>
                        @if($sp->hinh_anh)
                        <img class="sp-img" src="{{ asset('images/'.$sp->hinh_anh) }}" alt="{{ $sp->ten_sp }}">
                        @else
                        <img class="sp-img" src="https://via.placeholder.com/56x56/1a1a1a/555?text=?" alt="no image">
                        @endif
                    </td>
                    <td>
                        <span class="sp-name">{{ $sp->ten_sp }}</span>
                        <span class="sp-code">{{ $sp->ma_sp ?? '—' }}</span>
                    </td>
                    <td><span class="sp-sub">{{ $sp->danhMuc->ten_danh_muc ?? '—' }}</span></td>
                    <td><span class="sp-price">{{ number_format($sp->gia_ban ?? $sp->gia, 0, ',', '.') }} VNĐ</span></td>
                    <td><span class="badge-form">{{ $sp->form_dang ?? 'Slim-fit' }}</span></td>
                    <td>
                        <div style="font-size:0.85rem; color:#aaa; margin-bottom:4px;">
                            Tổng kho: <strong style="color:#C5A059;">{{ $sp->tongTonKho() }}</strong> cái
                        </div>
                        <div>
                            @foreach($sp->sizes as $s)
                                <span class="size-badge {{ $s->so_luong_ton > 0 ? 'size-badge-in' : 'size-badge-out' }}">
                                    {{ $s->ten_size }}: {{ $s->so_luong_ton }}
                                </span>
                            @endforeach
                        </div>
                    </td>
                    <td>
                        <div class="td-actions">
                            <button class="btn-icon btn-icon-edit" title="Sửa & Cập nhật tồn kho"
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
                                onclick="openEditModal(this)">
                                <i class="bi bi-pencil-fill"></i>
                            </button>

                            <button class="btn-icon btn-icon-del" title="Xóa"
                                data-id="{{ $sp->id }}"
                                data-ten="{{ $sp->ten_sp }}"
                                onclick="openDeleteModal(this)">
                                <i class="bi bi-trash3-fill"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr class="empty-row">
                    <td colspan="7">
                        <i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:10px;"></i>
                        Chưa có sản phẩm nào trong kho
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<!-- ================= MODAL THÊM SẢN PHẨM ================= -->
<div class="sp-modal-overlay" id="modal-add">
    <div class="sp-modal">
        <button class="sp-modal-close" onclick="closeModal('modal-add')">&times;</button>
        <h3><i class="bi bi-plus-circle-fill"></i> Thêm Sản Phẩm Mới</h3>

        @if($errors->getBag('add')->any())
        <div class="form-errors">
            @foreach($errors->getBag('add')->all() as $err)
            <div><i class="bi bi-exclamation-circle-fill"></i> {{ $err }}</div>
            @endforeach
        </div>
        @endif

        <form action="{{ route('sanpham.luuthem') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Tên sản phẩm *</label>
                    <input type="text" name="ten_sp" class="form-control"
                        value="{{ old('ten_sp') }}" placeholder="VD: Áo Vest Classic Navy 2 Cúc" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Mã SKU (tự sinh nếu để trống)</label>
                    <input type="text" name="ma_sp" class="form-control"
                        value="{{ old('ma_sp') }}" placeholder="VD: VEST-NAVY-01">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Danh mục *</label>
                    <select name="danh_muc_id" class="form-select" required>
                        <option value="">-- Chọn danh mục --</option>
                        @foreach($danhMucs as $dm)
                        <option value="{{ $dm->id }}" {{ old('danh_muc_id') == $dm->id ? 'selected' : '' }}>
                            {{ $dm->ten_danh_muc }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Giá bán niêm yết (VNĐ) *</label>
                    <input type="number" name="gia" class="form-control"
                        value="{{ old('gia') }}" placeholder="VD: 3200000" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Form dáng áo</label>
                    <select name="form_dang" class="form-select">
                        <option value="Slim-fit" {{ old('form_dang') == 'Slim-fit' ? 'selected' : '' }}>Slim-fit (Ôm dáng hiện đại)</option>
                        <option value="Regular" {{ old('form_dang') == 'Regular' ? 'selected' : '' }}>Regular (Vừa vặn thoải mái)</option>
                        <option value="Classic" {{ old('form_dang') == 'Classic' ? 'selected' : '' }}>Classic (Cổ điển phong độ)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Chất liệu vải</label>
                    <input type="text" name="chat_lieu" class="form-control"
                        value="{{ old('chat_lieu') }}" placeholder="VD: Wool Ý 100%, Tuytsi Hàn">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Xuất xứ</label>
                <input type="text" name="xuat_xu" class="form-control"
                    value="{{ old('xuat_xu') }}" placeholder="VD: Nhập khẩu Italy, May đo Việt Nam">
            </div>

            <!-- PHẦN NHẬP TỒN KHO TỪNG SIZE -->
            <div class="sizes-box">
                <div class="sizes-box-title">
                    <i class="bi bi-stack"></i> Nhập số lượng tồn kho theo từng Size:
                </div>
                <div class="sizes-grid">
                    <div class="size-col">
                        <label>Size S</label>
                        <input type="number" name="sizes[S]" class="form-control" min="0" value="{{ old('sizes.S', 0) }}">
                        <div class="size-note">1m60-1m68</div>
                    </div>
                    <div class="size-col">
                        <label>Size M</label>
                        <input type="number" name="sizes[M]" class="form-control" min="0" value="{{ old('sizes.M', 0) }}">
                        <div class="size-note">1m68-1m74</div>
                    </div>
                    <div class="size-col">
                        <label>Size L</label>
                        <input type="number" name="sizes[L]" class="form-control" min="0" value="{{ old('sizes.L', 0) }}">
                        <div class="size-note">1m74-1m78</div>
                    </div>
                    <div class="size-col">
                        <label>Size XL</label>
                        <input type="number" name="sizes[XL]" class="form-control" min="0" value="{{ old('sizes.XL', 0) }}">
                        <div class="size-note">1m78-1m83</div>
                    </div>
                    <div class="size-col">
                        <label>Size XXL</label>
                        <input type="number" name="sizes[XXL]" class="form-control" min="0" value="{{ old('sizes.XXL', 0) }}">
                        <div class="size-note">1m80-1m88</div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Mô tả sản phẩm</label>
                <textarea name="mo_ta" class="form-control" placeholder="Mô tả ve áo, lớp lót, phong cách mặc...">{{ old('mo_ta') }}</textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Hình ảnh áo vest</label>
                <input type="file" name="hinh_anh" class="form-control" accept="image/*">
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn-save"><i class="bi bi-save-fill"></i> Lưu Sản Phẩm</button>
                <button type="button" class="btn-cancel-modal" onclick="closeModal('modal-add')">
                    <i class="bi bi-x-circle"></i> Hủy
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ================= MODAL SỬA SẢN PHẨM & TỒN KHO ================= -->
<div class="sp-modal-overlay" id="modal-edit">
    <div class="sp-modal">
        <button class="sp-modal-close" onclick="closeModal('modal-edit')">&times;</button>
        <h3><i class="bi bi-pencil-fill"></i> Sửa Sản Phẩm & Tồn Kho</h3>

        @if($errors->getBag('edit')->any())
        <div class="form-errors">
            @foreach($errors->getBag('edit')->all() as $err)
            <div><i class="bi bi-exclamation-circle-fill"></i> {{ $err }}</div>
            @endforeach
        </div>
        @endif

        <form id="form-edit" action="" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Tên sản phẩm *</label>
                    <input type="text" id="edit_ten_sp" name="ten_sp" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Mã SKU</label>
                    <input type="text" id="edit_ma_sp" name="ma_sp" class="form-control">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Danh mục *</label>
                    <select id="edit_danh_muc_id" name="danh_muc_id" class="form-select" required>
                        <option value="">-- Chọn danh mục --</option>
                        @foreach($danhMucs as $dm)
                        <option value="{{ $dm->id }}">{{ $dm->ten_danh_muc }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Giá bán (VNĐ) *</label>
                    <input type="number" id="edit_gia" name="gia" class="form-control" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Form dáng áo</label>
                    <select id="edit_form_dang" name="form_dang" class="form-select">
                        <option value="Slim-fit">Slim-fit (Ôm dáng)</option>
                        <option value="Regular">Regular (Vừa vặn)</option>
                        <option value="Classic">Classic (Cổ điển)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Chất liệu vải</label>
                    <input type="text" id="edit_chat_lieu" name="chat_lieu" class="form-control">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Xuất xứ</label>
                <input type="text" id="edit_xuat_xu" name="xuat_xu" class="form-control">
            </div>

            <!-- SỬA TỒN KHO THEO SIZE -->
            <div class="sizes-box">
                <div class="sizes-box-title">
                    <i class="bi bi-stack"></i> Cập nhật số lượng tồn kho theo từng Size:
                </div>
                <div class="sizes-grid">
                    <div class="size-col">
                        <label>Size S</label>
                        <input type="number" id="edit_size_S" name="sizes[S]" class="form-control" min="0" value="0">
                        <div class="size-note">1m60-1m68</div>
                    </div>
                    <div class="size-col">
                        <label>Size M</label>
                        <input type="number" id="edit_size_M" name="sizes[M]" class="form-control" min="0" value="0">
                        <div class="size-note">1m68-1m74</div>
                    </div>
                    <div class="size-col">
                        <label>Size L</label>
                        <input type="number" id="edit_size_L" name="sizes[L]" class="form-control" min="0" value="0">
                        <div class="size-note">1m74-1m78</div>
                    </div>
                    <div class="size-col">
                        <label>Size XL</label>
                        <input type="number" id="edit_size_XL" name="sizes[XL]" class="form-control" min="0" value="0">
                        <div class="size-note">1m78-1m83</div>
                    </div>
                    <div class="size-col">
                        <label>Size XXL</label>
                        <input type="number" id="edit_size_XXL" name="sizes[XXL]" class="form-control" min="0" value="0">
                        <div class="size-note">1m80-1m88</div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Mô tả</label>
                <textarea id="edit_mo_ta" name="mo_ta" class="form-control"></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Hình ảnh hiện tại</label>
                <div class="current-img" id="edit_img_preview"></div>
                <label class="form-label" style="color:#888;font-weight:400">
                    Đổi ảnh mới <span style="color:#555">(bỏ trống nếu giữ nguyên)</span>
                </label>
                <input type="file" name="hinh_anh" class="form-control" accept="image/*">
            </div>

            <div class="modal-footer">
                <button type="submit" class="btn-save"><i class="bi bi-save-fill"></i> Cập Nhật</button>
                <button type="button" class="btn-cancel-modal" onclick="closeModal('modal-edit')">
                    <i class="bi bi-x-circle"></i> Hủy
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ================= MODAL XÁC NHẬN XÓA ================= -->
<div class="sp-modal-overlay" id="modal-delete">
    <div class="sp-modal del-modal">
        <button class="sp-modal-close" onclick="closeModal('modal-delete')">&times;</button>
        <div class="del-icon"><i class="bi bi-trash3-fill"></i></div>
        <h3>Xác nhận xóa</h3>
        <p id="del-confirm-text"></p>
        <form id="form-delete" action="" method="POST">
            @csrf
            <div class="modal-footer" style="justify-content:center">
                <button type="submit" class="btn-del-confirm">
                    <i class="bi bi-trash3-fill"></i> Xóa
                </button>
                <button type="button" class="btn-cancel-modal" onclick="closeModal('modal-delete')">
                    <i class="bi bi-x-circle"></i> Hủy
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    var BASE_IMG_URL = document.querySelector('meta[name="base-img-url"]').getAttribute('content');
    var HAS_ADD_ERRORS = document.querySelector('meta[name="has-add-errors"]').getAttribute('content') === '1';
    var HAS_EDIT_ERRORS = document.querySelector('meta[name="has-edit-errors"]').getAttribute('content') === '1';

    function openModal(id) {
        document.getElementById(id).classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeModal(id) {
        document.getElementById(id).classList.remove('active');
        document.body.style.overflow = '';
    }

    document.querySelectorAll('.sp-modal-overlay').forEach(function(el) {
        el.addEventListener('click', function(e) {
            if (e.target === el) closeModal(el.id);
        });
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            document.querySelectorAll('.sp-modal-overlay.active').forEach(function(el) {
                closeModal(el.id);
            });
        }
    });

    // Mở Modal Sửa sản phẩm & đổ dữ liệu size hiện có
    function openEditModal(btn) {
        var id       = btn.getAttribute('data-id');
        var masp     = btn.getAttribute('data-masp') || '';
        var ten      = btn.getAttribute('data-ten');
        var danhMuc  = btn.getAttribute('data-danhmuc');
        var gia      = btn.getAttribute('data-gia');
        var formDang = btn.getAttribute('data-formdang') || 'Slim-fit';
        var xuatXu   = btn.getAttribute('data-xuatxu') || '';
        var chatLieu = btn.getAttribute('data-chatlieu') || '';
        var moTa     = btn.getAttribute('data-mota') || '';
        var hinhAnh  = btn.getAttribute('data-hinhanh') || '';
        var sizes    = {};

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

        var sel = document.getElementById('edit_danh_muc_id');
        for (var i = 0; i < sel.options.length; i++) {
            sel.options[i].selected = (sel.options[i].value === danhMuc);
        }

        // Đổ số lượng tồn kho từng size vào form
        var standardSizes = ['S', 'M', 'L', 'XL', 'XXL'];
        standardSizes.forEach(function(s) {
            var input = document.getElementById('edit_size_' + s);
            if (input) {
                input.value = (sizes[s] !== undefined) ? sizes[s] : 0;
            }
        });

        var preview = document.getElementById('edit_img_preview');
        if (hinhAnh) {
            preview.innerHTML = '<img src="' + BASE_IMG_URL + hinhAnh + '" alt="anh hien tai"><span>' + hinhAnh + '</span>';
        } else {
            preview.innerHTML = '<span style="color:#555">Chưa có ảnh</span>';
        }

        openModal('modal-edit');
    }

    // Mở Modal Xóa sản phẩm
    function openDeleteModal(btn) {
        var id  = btn.getAttribute('data-id');
        var ten = btn.getAttribute('data-ten');

        document.getElementById('form-delete').action = '{{ url("/san-pham") }}/' + id + '/xoa';
        document.getElementById('del-confirm-text').innerHTML =
            'Bạn có chắc muốn xóa sản phẩm <strong style="color:#f0f0f0">"' + ten + '"</strong>?<br>' +
            '<span style="color:#666;font-size:.88rem">Hành động này không thể hoàn tác.</span>';

        openModal('modal-delete');
    }

    if (HAS_ADD_ERRORS) openModal('modal-add');
    if (HAS_EDIT_ERRORS) openModal('modal-edit');
</script>

@endsection