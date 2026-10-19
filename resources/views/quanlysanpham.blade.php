@extends('layouts.default_layout')
@section('title', 'Quản Lý Sản Phẩm')
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
        border-radius: 16px;
        overflow: hidden;
        border: 1px solid #2e2e2e;
    }

    .admin-table table {
        width: 100%;
        color: #e0e0e0;
        margin: 0;
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

    .sp-modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0, 0, 0, .78);
        z-index: 1050;
        align-items: center;
        justify-content: center;
        padding: 20px;
        backdrop-filter: blur(4px);
    }

    .sp-modal-overlay.active {
        display: flex;
    }

    .sp-modal {
        background: #1a1a1a;
        border: 1px solid #333;
        border-radius: 18px;
        width: 100%;
        max-width: 640px;
        max-height: 90vh;
        overflow-y: auto;
        padding: 32px;
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
        margin: 0 0 24px;
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
        color: #666;
        font-size: 1.4rem;
        cursor: pointer;
        transition: color .2s;
        line-height: 1;
    }

    .sp-modal-close:hover {
        color: #ff5555;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-label {
        color: #C5A059;
        font-weight: 600;
        font-size: .85rem;
        margin-bottom: 7px;
        display: block;
        letter-spacing: .3px;
    }

    .form-control,
    .form-select {
        background: #111;
        border: 2px solid #363636;
        color: #e0e0e0;
        border-radius: 9px;
        padding: 10px 14px;
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
        color: #484848;
    }

    .form-select option {
        background: #1a1a1a;
    }

    textarea.form-control {
        resize: vertical;
        min-height: 90px;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 16px;
    }

    @media(max-width:520px) {
        .form-row {
            grid-template-columns: 1fr;
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

    .form-errors div:last-child {
        margin-bottom: 0;
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
        margin-top: 8px;
        flex-wrap: wrap;
    }

    .btn-save {
        padding: 11px 30px;
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
        padding: 11px 30px;
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

<meta name="base-img-url" content="{{ asset('images/') }}">
<meta name="has-add-errors" content="{{ $errors->getBag('add')->any()  ? '1' : '0' }}">
<meta name="has-edit-errors" content="{{ $errors->getBag('edit')->any() ? '1' : '0' }}">

<div class="sp-wrap">

    <div class="sp-header">
        <h2 class="sp-title">
            <i class="bi bi-box-seam-fill"></i> Quản Lý Sản Phẩm
        </h2>
        <button class="btn-add" onclick="openModal('modal-add')">
            <i class="bi bi-plus-circle-fill"></i> Thêm Sản Phẩm
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
                    <th>Tên sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Giá</th>
                    <th>Xuất xứ</th>
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
                    <td><span class="sp-name">{{ $sp->ten_sp }}</span></td>
                    <td><span class="sp-sub">{{ $sp->danhMuc->ten_danh_muc ?? '—' }}</span></td>
                    <td><span class="sp-price">{{ number_format($sp->gia, 0, ',', '.') }} VNĐ</span></td>
                    <td><span class="sp-sub">{{ $sp->xuat_xu ?? '—' }}</span></td>
                    <td>
                        <div class="td-actions">
                            <button class="btn-icon btn-icon-edit" title="Sửa"
                                data-id="{{ $sp->id }}"
                                data-ten="{{ $sp->ten_sp }}"
                                data-danhmuc="{{ $sp->danh_muc_id }}"
                                data-gia="{{ $sp->gia }}"
                                data-xuatxu="{{ $sp->xuat_xu }}"
                                data-chatlieu="{{ $sp->chat_lieu }}"
                                data-mota="{{ $sp->mo_ta }}"
                                data-hinhanh="{{ $sp->hinh_anh }}"
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
                    <td colspan="6">
                        <i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:10px;"></i>
                        Chưa có sản phẩm nào
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>


//THÊM
<div class="sp-modal-overlay" id="modal-add">
    <div class="sp-modal">
        <button class="sp-modal-close" onclick="closeModal('modal-add')">&times;</button>
        <h3><i class="bi bi-plus-circle-fill"></i> Thêm Sản Phẩm</h3>

        @if($errors->getBag('add')->any())
        <div class="form-errors">
            @foreach($errors->getBag('add')->all() as $err)
            <div><i class="bi bi-exclamation-circle-fill"></i> {{ $err }}</div>
            @endforeach
        </div>
        @endif

        <form action="{{ route('sanpham.luuthem') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="form-label">Tên sản phẩm *</label>
                <input type="text" name="ten_sp" class="form-control"
                    value="{{ old('ten_sp') }}" placeholder="Nhập tên sản phẩm">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Danh mục *</label>
                    <select name="danh_muc_id" class="form-select">
                        <option value="">-- Chọn danh mục --</option>
                        @foreach($danhMucs as $dm)
                        <option value="{{ $dm->id }}" {{ old('danh_muc_id') == $dm->id ? 'selected' : '' }}>
                            {{ $dm->ten_danh_muc }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Giá (VNĐ) *</label>
                    <input type="number" name="gia" class="form-control"
                        value="{{ old('gia') }}" placeholder="VD: 2500000">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Xuất xứ</label>
                    <input type="text" name="xuat_xu" class="form-control"
                        value="{{ old('xuat_xu') }}" placeholder="VD: Việt Nam">
                </div>
                <div class="form-group">
                    <label class="form-label">Chất liệu</label>
                    <input type="text" name="chat_lieu" class="form-control"
                        value="{{ old('chat_lieu') }}" placeholder="VD: Len cao cấp">
                </div>
            </div>
            <div class="form-group">
                <label class="form-label">Mô tả</label>
                <textarea name="mo_ta" class="form-control" placeholder="Mô tả sản phẩm...">{{ old('mo_ta') }}</textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Hình ảnh</label>
                <input type="file" name="hinh_anh" class="form-control" accept="image/*">
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn-save"><i class="bi bi-save-fill"></i> Lưu</button>
                <button type="button" class="btn-cancel-modal" onclick="closeModal('modal-add')">
                    <i class="bi bi-x-circle"></i> Hủy
                </button>
            </div>
        </form>
    </div>
</div>


//SỬA
<div class="sp-modal-overlay" id="modal-edit">
    <div class="sp-modal">
        <button class="sp-modal-close" onclick="closeModal('modal-edit')">&times;</button>
        <h3><i class="bi bi-pencil-fill"></i> Sửa Sản Phẩm</h3>

        @if($errors->getBag('edit')->any())
        <div class="form-errors">
            @foreach($errors->getBag('edit')->all() as $err)
            <div><i class="bi bi-exclamation-circle-fill"></i> {{ $err }}</div>
            @endforeach
        </div>
        @endif

        <form id="form-edit" action="" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="form-label">Tên sản phẩm *</label>
                <input type="text" id="edit_ten_sp" name="ten_sp" class="form-control">
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Danh mục *</label>
                    <select id="edit_danh_muc_id" name="danh_muc_id" class="form-select">
                        <option value="">-- Chọn danh mục --</option>
                        @foreach($danhMucs as $dm)
                        <option value="{{ $dm->id }}">{{ $dm->ten_danh_muc }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">Giá (VNĐ) *</label>
                    <input type="number" id="edit_gia" name="gia" class="form-control">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">Xuất xứ</label>
                    <input type="text" id="edit_xuat_xu" name="xuat_xu" class="form-control">
                </div>
                <div class="form-group">
                    <label class="form-label">Chất liệu</label>
                    <input type="text" id="edit_chat_lieu" name="chat_lieu" class="form-control">
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
                <button type="submit" class="btn-save"><i class="bi bi-save-fill"></i> Lưu</button>
                <button type="button" class="btn-cancel-modal" onclick="closeModal('modal-edit')">
                    <i class="bi bi-x-circle"></i> Hủy
                </button>
            </div>
        </form>
    </div>
</div>


//XÓA
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

    /* ===== Helpers ===== */
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

    // Sửa sản phẩm
    function openEditModal(btn) {
        var id = btn.getAttribute('data-id');
        var ten = btn.getAttribute('data-ten');
        var danhMuc = btn.getAttribute('data-danhmuc');
        var gia = btn.getAttribute('data-gia');
        var xuatXu = btn.getAttribute('data-xuatxu') || '';
        var chatLieu = btn.getAttribute('data-chatlieu') || '';
        var moTa = btn.getAttribute('data-mota') || '';
        var hinhAnh = btn.getAttribute('data-hinhanh') || '';

        document.getElementById('form-edit').action = '{{ url("/san-pham") }}/' + id + '/sua';
        document.getElementById('edit_ten_sp').value = ten;
        document.getElementById('edit_gia').value = gia;
        document.getElementById('edit_xuat_xu').value = xuatXu;
        document.getElementById('edit_chat_lieu').value = chatLieu;
        document.getElementById('edit_mo_ta').value = moTa;

        var sel = document.getElementById('edit_danh_muc_id');
        for (var i = 0; i < sel.options.length; i++) {
            sel.options[i].selected = (sel.options[i].value === danhMuc);
        }

        var preview = document.getElementById('edit_img_preview');
        if (hinhAnh) {
            preview.innerHTML = '<img src="' + BASE_IMG_URL + hinhAnh + '" alt="anh hien tai"><span>' + hinhAnh + '</span>';
        } else {
            preview.innerHTML = '<span style="color:#555">Chưa có ảnh</span>';
        }

        openModal('modal-edit');
    }

    //Xóa sản phẩm
    function openDeleteModal(btn) {
        var id = btn.getAttribute('data-id');
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