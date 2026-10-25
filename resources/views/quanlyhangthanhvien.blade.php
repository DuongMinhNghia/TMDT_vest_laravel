@extends('layouts.admin_layout')

@section('title', 'Quản Lý Hạng VIP & Chiết Khấu - Kingsman Admin')
@section('page_name', 'Chính Sách Hạng VIP')

@section('content')
<style>
    /* Executive Luxury Dark Palette for Hạng Thành Viên */
    .tier-container {
        padding: 4px 0 40px;
    }

    .tier-top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--adm-border);
    }

    .tier-header-title {
        font-family: var(--adm-font-heading);
        color: var(--adm-text-main);
        font-size: 1.6rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .tier-header-title i {
        color: var(--adm-gold);
    }

    .tier-subtitle {
        color: var(--adm-text-muted);
        font-size: 0.82rem;
        margin-top: 4px;
    }

    /* Tier Cards Grid */
    .tier-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }

    .tier-card {
        background: var(--adm-bg-surface);
        border: 1px solid var(--adm-border);
        border-radius: 14px;
        padding: 24px;
        position: relative;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        display: flex;
        flex-direction: column;
    }

    .tier-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 16px 36px rgba(0, 0, 0, 0.6);
    }

    .tier-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 16px;
    }

    .tier-name {
        font-family: var(--adm-font-heading);
        font-size: 1.3rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .tier-discount-box {
        margin-bottom: 16px;
    }

    .tier-discount-val {
        font-family: var(--adm-font-heading);
        font-size: 2.4rem;
        font-weight: 700;
        color: var(--adm-gold);
        line-height: 1;
        margin-top: 4px;
    }

    .tier-min-points {
        background: var(--adm-bg-card);
        border: 1px solid var(--adm-border);
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 0.82rem;
        color: var(--adm-text-muted);
        margin-bottom: 14px;
    }

    .tier-desc {
        color: var(--adm-text-muted);
        font-size: 0.82rem;
        line-height: 1.5;
        min-height: 48px;
        margin-bottom: 20px;
        flex: 1;
    }

    .btn-tier-edit {
        background: rgba(212, 178, 111, 0.12);
        border: 1px solid rgba(212, 178, 111, 0.3);
        color: var(--adm-gold);
        padding: 9px 14px;
        border-radius: 6px;
        font-size: 0.82rem;
        font-weight: 600;
        width: 100%;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
    }

    .btn-tier-edit:hover {
        background: var(--adm-gold);
        color: #000;
    }

    /* Loyalty Policy Explainer Card */
    .loyalty-explainer {
        background: var(--adm-bg-surface);
        border: 1px solid var(--adm-border);
        border-radius: 12px;
        padding: 24px 28px;
    }

    .loyalty-explainer h5 {
        color: var(--adm-gold);
        font-family: var(--adm-font-heading);
        font-size: 1.15rem;
        font-weight: 700;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .loyalty-rule-item {
        color: var(--adm-text-muted);
        font-size: 0.86rem;
        line-height: 1.6;
        margin-bottom: 10px;
    }

    .loyalty-rule-item:last-child {
        margin-bottom: 0;
    }

    .loyalty-rule-item strong {
        color: var(--adm-text-main);
    }

    /* Modal Dark Theme - Robust Overlay */
    .tier-modal-overlay {
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

    .tier-modal-overlay.active {
        display: flex;
    }

    .tier-modal-box {
        background: var(--adm-bg-surface);
        border: 1px solid var(--adm-border-focus);
        border-radius: 14px;
        width: 100%;
        max-width: 520px;
        padding: 26px;
        position: relative;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.8);
    }

    .tier-modal-close {
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
    .tier-modal-close:hover {
        color: #fff;
    }

    .tier-modal-title {
        color: var(--adm-gold);
        font-family: var(--adm-font-heading);
        font-size: 1.3rem;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .tier-form-control {
        background: var(--adm-bg-card);
        border: 1px solid var(--adm-border);
        color: var(--adm-text-main);
        border-radius: 6px;
        padding: 9px 12px;
        font-size: 0.86rem;
        width: 100%;
        transition: all 0.2s;
    }

    .tier-form-control:focus {
        border-color: var(--adm-gold);
        outline: none;
        box-shadow: 0 0 0 3px var(--adm-gold-glow);
        background: #1c202b;
        color: #fff;
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
</style>

<div class="tier-container">

    <!-- TOP TOOLBAR -->
    <div class="tier-top-bar">
        <div>
            <h2 class="tier-header-title">
                <i class="bi bi-award-fill"></i> Quản Lý Hạng Thành Viên & Chính Sách VIP
            </h2>
            <div class="tier-subtitle">Cấu hình cấp bậc thành viên, điểm thưởng tích lũy và chiết khấu trọn đời</div>
        </div>
    </div>

    <!-- TIER CARDS GRID -->
    <div class="tier-grid">
        @foreach($hangs as $h)
        <div class="tier-card" style="border-top: 3px solid {{ $h->mau_sac }};">
            <div class="tier-card-header">
                <span class="tier-name" style="color: {{ $h->mau_sac }};">
                    <i class="bi {{ $h->icon }}"></i> {{ $h->ten_hang }}
                </span>
                <span class="badge" style="background: rgba(255,255,255,0.08); border: 1px solid var(--adm-border); color: var(--adm-text-main); font-size: 0.72rem;">
                    {{ $h->khach_hangs_count }} khách hàng
                </span>
            </div>

            <div class="tier-discount-box">
                <small style="color: var(--adm-text-muted); text-transform: uppercase; font-size: 0.72rem; letter-spacing: 0.8px; font-weight: 600;">Chiết khấu đơn hàng</small>
                <div class="tier-discount-val">-{{ $h->phan_tram_chiet_khau }}%</div>
            </div>

            <div class="tier-min-points">
                <i class="bi bi-coin text-warning me-1"></i> Điểm tối thiểu: 
                <strong style="color: var(--adm-text-main);">{{ number_format($h->diem_toi_thieu) }} điểm</strong>
            </div>

            <p class="tier-desc">
                {{ $h->mo_ta ?? 'Chưa có mô tả đặc quyền dành riêng cho hạng này.' }}
            </p>

            <button type="button" class="btn-tier-edit" onclick='moModalSuaHang(@json($h))'>
                <i class="bi bi-pencil-square"></i> Chỉnh Sửa Chính Sách
            </button>
        </div>
        @endforeach
    </div>

    <!-- EXPLAINER CARD -->
    <div class="loyalty-explainer">
        <h5>
            <i class="bi bi-info-circle-fill"></i> Quy Tắc Tích Lũy Điểm & Thăng Hạng Tự Động
        </h5>
        <div class="loyalty-rule-item">
            1. <strong>Cơ chế tính điểm:</strong> Cứ mỗi 10.000 VNĐ tiền hàng (không tính phí vận chuyển) khi đơn hàng chuyển sang trạng thái <em>Hoàn thành</em> sẽ được hệ thống cộng 1 điểm thưởng vào tài khoản khách.
        </div>
        <div class="loyalty-rule-item">
            2. <strong>Cơ chế thăng hạng:</strong> Hệ thống tự động so khớp điểm tích lũy với mốc điểm tối thiểu của từng cấp bậc để nâng hạng trọn đời cho khách (chính sách Kingsman không bao giờ hạ bậc khách quen).
        </div>
        <div class="loyalty-rule-item">
            3. <strong>Áp dụng chiết khấu:</strong> Tại bước thanh toán giỏ hàng, hệ thống tự động trừ % chiết khấu của hạng thành viên tương ứng trước khi tính tổng tiền đơn hàng.
        </div>
    </div>

</div>

{{-- MODAL SỬA CHÍNH SÁCH HẠNG (ROBUST OVERLAY) --}}
<div class="tier-modal-overlay" id="modalSuaHang">
    <div class="tier-modal-box">
        <button type="button" class="tier-modal-close" onclick="closeTierModal()">&times;</button>
        <h3 class="tier-modal-title" id="modalHangTitle">
            <i class="bi bi-award me-2"></i>Chỉnh Sửa Chính Sách Hạng
        </h3>

        <form id="formSuaHang" action="" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label" style="color: var(--adm-text-muted); font-size:0.8rem;">Tên hạng thành viên</label>
                <input type="text" id="hang_ten" class="tier-form-control" disabled style="opacity:0.7;">
            </div>

            <div class="row g-3 mb-3">
                <div class="col-6">
                    <label class="form-label" style="color: var(--adm-text-main); font-size:0.84rem; font-weight:600;">Điểm tối thiểu *</label>
                    <input type="number" name="diem_toi_thieu" id="hang_diem" class="tier-form-control" required min="0">
                </div>
                <div class="col-6">
                    <label class="form-label" style="color: var(--adm-text-main); font-size:0.84rem; font-weight:600;">% Chiết khấu *</label>
                    <input type="number" name="phan_tram_chiet_khau" id="hang_chietkhau" class="tier-form-control" min="0" max="100" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label" style="color: var(--adm-text-main); font-size:0.84rem; font-weight:600;">Mô tả quyền lợi</label>
                <textarea name="mo_ta" id="hang_mota" rows="3" class="tier-form-control" placeholder="Đặc quyền may đo, quà sinh nhật, ưu tiên phục vụ..."></textarea>
            </div>

            <button type="submit" class="btn-submit-gold">
                <i class="bi bi-save me-1"></i> Lưu Cập Nhật Chính Sách
            </button>
        </form>
    </div>
</div>

<script>
    function moModalSuaHang(h) {
        // Nối đúng Endpoint BE bao gồm cả base subfolder (chống 404 trên localhost/demo)
        document.getElementById('formSuaHang').action = '{{ url("/hang-thanh-vien") }}/' + h.id + '/cap-nhat';
        document.getElementById('modalHangTitle').innerHTML = '<i class="bi bi-award me-2"></i>Chỉnh Sửa Hạng: ' + h.ten_hang;
        document.getElementById('hang_ten').value = h.ten_hang;
        document.getElementById('hang_diem').value = h.diem_toi_thieu;
        document.getElementById('hang_chietkhau').value = h.phan_tram_chiet_khau;
        document.getElementById('hang_mota').value = h.mo_ta || '';

        const modal = document.getElementById('modalSuaHang');
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    function closeTierModal() {
        const modal = document.getElementById('modalSuaHang');
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    // Đóng khi click ngoài hộp modal hoặc bấm ESC
    document.getElementById('modalSuaHang').addEventListener('click', function(e) {
        if (e.target === this) closeTierModal();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeTierModal();
    });
</script>
@endsection
