@extends('layouts.default_layout')

@section('title', 'Quản Lý Bộ Sưu Tập Thời Trang - Kingsman')

@section('content')
<style>
    .bst-wrap {
        max-width: 1300px;
        margin: 0 auto;
        padding: 10px 15px 50px;
    }

    /* Header */
    .bst-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid rgba(197, 160, 89, 0.25);
    }

    .bst-title {
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

    /* Alerts */
    .bst-alert {
        padding: 12px 18px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.92rem;
    }

    .bst-alert-success {
        background: rgba(46, 204, 113, 0.15);
        border: 1px solid rgba(46, 204, 113, 0.4);
        color: #2ecc71;
    }

    /* Grid danh sách BST */
    .bst-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
        gap: 25px;
    }

    .bst-card {
        background: #141414;
        border: 1px solid #2a2a2a;
        border-radius: 14px;
        overflow: hidden;
        transition: all 0.3s;
        display: flex;
        flex-direction: column;
    }

    .bst-card:hover {
        border-color: #C5A059;
        transform: translateY(-4px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.5);
    }

    .bst-banner {
        width: 100%;
        height: 190px;
        object-fit: cover;
        background: #202020;
        display: block;
    }

    .bst-banner-placeholder {
        width: 100%;
        height: 190px;
        background: linear-gradient(135deg, #1c1c1c, #2a2519);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: #C5A059;
        font-size: 2.2rem;
    }

    .bst-card-body {
        padding: 20px;
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .bst-name {
        font-family: 'Playfair Display', serif;
        font-size: 1.3rem;
        color: #f5f5f5;
        margin-bottom: 8px;
        font-weight: 700;
    }

    .bst-desc {
        color: #999;
        font-size: 0.88rem;
        line-height: 1.55;
        margin-bottom: 15px;
        min-height: 40px;
    }

    .bst-stats {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 14px;
        background: #1a1a1a;
        border-radius: 8px;
        border: 1px solid #282828;
        margin-bottom: 15px;
    }

    .bst-count {
        color: #C5A059;
        font-weight: 700;
        font-size: 0.9rem;
    }

    .bst-suits-list {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-bottom: 20px;
        max-height: 80px;
        overflow-y: auto;
    }

    .suit-tag {
        background: rgba(255, 255, 255, 0.06);
        border: 1px solid rgba(255, 255, 255, 0.15);
        color: #ddd;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 0.76rem;
    }

    .bst-actions {
        display: flex;
        gap: 8px;
        margin-top: auto;
        padding-top: 15px;
        border-top: 1px solid #222;
    }

    .btn-card-action {
        flex: 1;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 0.83rem;
        font-weight: 600;
        cursor: pointer;
        text-align: center;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        transition: all 0.2s;
        border: none;
    }

    .btn-act-view {
        background: rgba(197, 160, 89, 0.15);
        color: #C5A059;
        border: 1px solid rgba(197, 160, 89, 0.35);
    }
    .btn-act-view:hover {
        background: #C5A059;
        color: #000;
    }

    .btn-act-edit {
        background: rgba(52, 152, 219, 0.15);
        color: #3498db;
        border: 1px solid rgba(52, 152, 219, 0.35);
    }
    .btn-act-edit:hover {
        background: #3498db;
        color: #fff;
    }

    .btn-act-del {
        background: rgba(231, 76, 60, 0.15);
        color: #e74c3c;
        border: 1px solid rgba(231, 76, 60, 0.35);
    }
    .btn-act-del:hover {
        background: #e74c3c;
        color: #fff;
    }

    /* Modals */
    .bst-modal-overlay {
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

    .bst-modal-overlay.active {
        display: flex;
    }

    .bst-modal {
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

    .bst-modal-close {
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
    .bst-modal-close:hover {
        color: #fff;
    }

    .bst-modal h3 {
        font-family: 'Playfair Display', serif;
        color: #C5A059;
        font-size: 1.45rem;
        margin-bottom: 22px;
        display: flex;
        align-items: center;
        gap: 9px;
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

    .form-control, .form-select {
        width: 100%;
        background: #101010;
        border: 1px solid #333;
        border-radius: 8px;
        color: #f0f0f0;
        padding: 9px 13px;
        font-size: 0.9rem;
        transition: border-color 0.2s;
    }
    .form-control:focus, .form-select:focus {
        border-color: #C5A059;
        outline: none;
        background: #141414;
        color: #fff;
    }

    textarea.form-control {
        min-height: 75px;
        resize: vertical;
    }

    /* Product picker box */
    .suit-picker-box {
        background: #101010;
        border: 1px solid #282828;
        border-radius: 10px;
        padding: 12px;
        max-height: 220px;
        overflow-y: auto;
    }

    .suit-picker-title {
        font-size: 0.82rem;
        color: #C5A059;
        margin-bottom: 10px;
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
        border-bottom: 1px solid #1a1a1a;
    }
    .suit-item-check:hover {
        background: rgba(197, 160, 89, 0.08);
    }
    .suit-item-check:last-child {
        border-bottom: none;
    }

    .suit-item-check input[type="checkbox"] {
        width: 17px;
        height: 17px;
        accent-color: #C5A059;
        cursor: pointer;
    }

    .suit-thumb {
        width: 38px;
        height: 38px;
        border-radius: 6px;
        object-fit: cover;
        border: 1px solid #333;
    }

    .suit-picker-info {
        flex: 1;
        font-size: 0.85rem;
    }

    .suit-picker-name {
        color: #eee;
        font-weight: 600;
    }

    .suit-picker-meta {
        font-size: 0.76rem;
        color: #888;
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

    /* Modal Xóa */
    .del-center {
        text-align: center;
    }
    .del-icon {
        font-size: 3.2rem;
        color: #e74c3c;
        margin-bottom: 12px;
    }
    .del-btn-group {
        display: flex;
        gap: 10px;
        justify-content: center;
        margin-top: 20px;
    }
    .btn-cancel {
        padding: 9px 20px;
        background: #333;
        color: #ccc;
        border: none;
        border-radius: 6px;
        cursor: pointer;
    }
    .btn-confirm-del {
        padding: 9px 22px;
        background: #e74c3c;
        color: #fff;
        border: none;
        border-radius: 6px;
        cursor: pointer;
        font-weight: 600;
    }
</style>

<div class="bst-wrap">

    <div class="bst-header">
        <h2 class="bst-title">
            <i class="bi bi-collection-play-fill"></i> Quản Lý Bộ Sưu Tập Thời Trang
        </h2>
        <button class="btn-gold-add" onclick="openModal('modal-add-bst')">
            <i class="bi bi-plus-circle-fill"></i> Tạo Bộ Sưu Tập Mới
        </button>
    </div>

    @if(session('success'))
    <div class="bst-alert bst-alert-success">
        <i class="bi bi-check-circle-fill"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif

    @if(isset($errors) && $errors->any())
    <div class="bst-alert" style="background:rgba(231,76,60,0.15); border:1px solid rgba(231,76,60,0.4); color:#e74c3c;">
        <i class="bi bi-exclamation-triangle-fill"></i>
        <div>
            @foreach($errors->all() as $err)
            <div>{{ $err }}</div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- LƯỚI DANH SÁCH BỘ SƯU TẬP --}}
    @if($boSuuTaps->isEmpty())
    <div style="text-align:center; padding: 60px 20px; background:#141414; border:1px dashed #333; border-radius:14px;">
        <i class="bi bi-folder-x" style="font-size: 3.5rem; color:#555;"></i>
        <h4 style="color:#aaa; margin-top:15px;">Chưa có bộ sưu tập nào</h4>
        <p style="color:#777; font-size:0.9rem;">Hãy tạo bộ sưu tập đầu tiên để gắn kết các mẫu vest theo chủ đề (Vest Cưới, Quý Ông Công Sở, Dạ Tiệc)!</p>
        <button class="btn-gold-add" onclick="openModal('modal-add-bst')" style="margin-top:10px;">
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
                    <span style="font-size:0.85rem; font-family:sans-serif; margin-top:6px; opacity:0.8;">KINGSMAN COLLECTION</span>
                </div>
            @endif

            <div class="bst-card-body">
                <h3 class="bst-name">{{ $bst->ten_bo_suu_tap }}</h3>
                <p class="bst-desc">{{ $bst->mo_ta ?: 'Chưa có phần mô tả cảm hứng cho bộ sưu tập này.' }}</p>

                <div class="bst-stats">
                    <span style="color:#aaa; font-size:0.82rem;"><i class="bi bi-suit-heart-fill" style="color:#C5A059;"></i> Số lượng mẫu vest:</span>
                    <span class="bst-count">{{ $bst->san_phams_count }} sản phẩm</span>
                </div>

                <div class="bst-suits-list">
                    @forelse($bst->sanPhams as $sp)
                        <span class="suit-tag"><i class="bi bi-tag-fill" style="color:#C5A059;"></i> {{ $sp->ten_sp }}</span>
                    @empty
                        <span style="color:#666; font-size:0.78rem; font-style:italic;">Chưa gắn sản phẩm nào vào BST.</span>
                    @endforelse
                </div>

                <div class="bst-actions">
                    <a href="{{ route('bosuutap.chitiet', $bst->id) }}" target="_blank" class="btn-card-action btn-act-view">
                        <i class="bi bi-eye"></i> Xem web
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
                <input type="text" name="ten_bo_suu_tap" class="form-control" placeholder="VD: Bộ Sưu Tập Vest Cưới Hoàng Gia 2025" required>
            </div>

            <div class="form-group">
                <label class="form-label">Hình Banner Đại Diện</label>
                <input type="file" name="hinh_banner" class="form-control" accept="image/*">
                <small style="color:#888; font-size:0.78rem;">Khuyên dùng kích thước ngang tỷ lệ 16:9 hoặc ảnh phong cảnh chất lượng cao.</small>
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
                <div id="edit_banner_preview" style="margin-top:8px; font-size:0.8rem; color:#C5A059;"></div>
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
            <h4 style="color:#f0f0f0; margin-bottom:10px;">Xác Nhận Xóa</h4>
            <p id="del-bst-text" style="color:#aaa; font-size:0.9rem; line-height:1.5;">Bạn có chắc muốn xóa bộ sưu tập này không?</p>
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

        // Bật/tắt các checkbox theo sản phẩm đã có trong BST
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
        document.getElementById('del-bst-text').innerHTML = 'Bạn có chắc chắn muốn xóa bộ sưu tập <strong style="color:#C5A059;">"' + ten + '"</strong>?<br><span style="font-size:0.8rem; color:#777;">Các sản phẩm áo vest bên trong sẽ không bị xóa, chỉ hủy liên kết khỏi bộ sưu tập này.</span>';

        openModal('modal-del-bst');
    }
</script>
@endsection
