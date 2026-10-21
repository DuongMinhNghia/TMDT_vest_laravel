@extends('layouts.default_layout')

@section('title', 'Quản Lý Hạng Thành Viên & Chiết Khấu - Kingsman Admin')

@section('content')
<style>
    .admin-wrap { background: linear-gradient(to bottom, #0a0a0a, #000); min-height: 100vh; padding: 30px 20px; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 15px; border-bottom: 2px solid #d4af37; }
    .page-header h2 { color: #d4af37; font-size: 1.8rem; font-weight: 700; margin: 0; letter-spacing: 2px; }
    .tier-card {
        background: #141414;
        border: 2px solid #333;
        border-radius: 16px;
        padding: 24px;
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    .tier-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0,0,0,0.6);
    }
    .tier-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; }
    .tier-name { font-size: 1.3rem; font-weight: 700; }
    .tier-discount { font-size: 2.2rem; font-weight: 700; color: #d4af37; }
    .form-control-dark { background: #0f0f0f !important; border: 1px solid #444 !important; color: #fff !important; border-radius: 8px !important; }
    .form-control-dark:focus { border-color: #d4af37 !important; box-shadow: 0 0 0 2px rgba(212,175,55,0.2) !important; }
</style>

<div class="admin-wrap">
    <div class="container">

        <div class="page-header">
            <h2><i class="bi bi-award-fill me-2"></i>Quản Lý Hạng Thành Viên (Loyalty Tiers)</h2>
        </div>

        @if(session('success'))
        <div class="alert alert-success" style="background:#0d2b0d; border:1px solid #2d6a2d; color:#6fcf6f; border-radius:8px;">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        </div>
        @endif

        <div class="row g-4 mb-5">
            @foreach($hangs as $h)
            <div class="col-md-6 col-lg-3">
                <div class="tier-card" style="border-color: {{ $h->mau_sac }};">
                    <div class="tier-header">
                        <span class="tier-name" style="color: {{ $h->mau_sac }};">
                            <i class="bi {{ $h->icon }} me-1"></i> {{ $h->ten_hang }}
                        </span>
                        <span class="badge bg-dark border border-secondary">{{ $h->khach_hangs_count }} khách</span>
                    </div>

                    <div class="mb-3">
                        <small class="text-muted text-uppercase" style="font-size:0.75rem;">Chiết khấu đơn hàng</small>
                        <div class="tier-discount">-{{ $h->phan_tram_chiet_khau }}%</div>
                    </div>

                    <div class="mb-3 text-muted" style="font-size:0.85rem;">
                        <i class="bi bi-coin text-warning me-1"></i> Điểm tối thiểu: 
                        <strong class="text-white">{{ number_format($h->diem_toi_thieu) }} điểm</strong>
                    </div>

                    <p class="text-muted" style="font-size:0.8rem; min-height: 40px;">
                        {{ $h->mo_ta ?? 'Chưa có mô tả đặc quyền' }}
                    </p>

                    <button class="btn btn-outline-warning btn-sm w-100 mt-2" onclick='moModalSuaHang(@json($h))'>
                        <i class="bi bi-pencil-square me-1"></i> Chỉnh Sửa Chính Sách
                    </button>
                </div>
            </div>
            @endforeach
        </div>

        <div class="p-4 rounded" style="background:#141414; border:1px solid #282828;">
            <h5 class="text-warning mb-2" style="font-size:1.1rem;">
                <i class="bi bi-info-circle-fill me-2"></i>Quy Tắc Tích Lũy Điểm & Thăng Hạng Tự Động
            </h5>
            <p class="text-muted mb-1" style="font-size:0.88rem;">
                1. <strong>Cơ chế tính điểm:</strong> Cứ mỗi 10.000 VNĐ tiền hàng (không tính phí vận chuyển) khi khách hàng mua sắm và đơn hàng chuyển sang trạng thái <em>Hoàn thành</em> sẽ được cộng 1 điểm thưởng vào tài khoản.
            </p>
            <p class="text-muted mb-1" style="font-size:0.88rem;">
                2. <strong>Cơ chế thăng hạng:</strong> Hệ thống tự động so khớp điểm tích lũy với mức điểm tối thiểu của từng hạng để nâng hạng trọn đời cho khách (không bao giờ tụt hạng).
            </p>
            <p class="text-muted mb-0" style="font-size:0.88rem;">
                3. <strong>Áp dụng chiết khấu:</strong> Tại bước thanh toán giỏ hàng, hệ thống tự động trừ % chiết khấu của hạng thành viên tương ứng trước khi tính tổng tiền đơn hàng.
            </p>
        </div>

    </div>
</div>

{{-- MODAL SỬA CHÍNH SÁCH HẠNG --}}
<div class="modal fade" id="modalSuaHang" tabindex="-1" style="background:rgba(0,0,0,0.85);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background:#171717; border:1px solid #d4af37; color:#f0f0f0;">
            <div class="modal-header" style="border-bottom:1px solid #333;">
                <h5 class="modal-title" id="modalHangTitle" style="color:#d4af37;">
                    <i class="bi bi-award me-2"></i>Chỉnh Sửa Chính Sách Hạng
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1);"></button>
            </div>
            <form id="formSuaHang" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-muted" style="font-size:0.85rem;">Tên hạng thành viên</label>
                        <input type="text" id="hang_ten" class="form-control form-control-dark" disabled>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label text-muted" style="font-size:0.85rem;">Điểm tối thiểu *</label>
                            <input type="number" name="diem_toi_thieu" id="hang_diem" class="form-control form-control-dark" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label text-muted" style="font-size:0.85rem;">% Chiết khấu đơn *</label>
                            <input type="number" name="phan_tram_chiet_khau" id="hang_chietkhau" class="form-control form-control-dark" min="0" max="100" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted" style="font-size:0.85rem;">Mô tả quyền lợi</label>
                        <textarea name="mo_ta" id="hang_mota" rows="3" class="form-control form-control-dark" placeholder="Đặc quyền may đo, ưu tiên phục vụ..."></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #333;">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-warning btn-sm fw-bold">
                        <i class="bi bi-save me-1"></i> Lưu Cập Nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function moModalSuaHang(h) {
        document.getElementById('formSuaHang').action = '/hang-thanh-vien/' + h.id + '/cap-nhat';
        document.getElementById('modalHangTitle').innerHTML = '<i class="bi bi-award me-2"></i>Chỉnh Sửa Hạng: ' + h.ten_hang;
        document.getElementById('hang_ten').value = h.ten_hang;
        document.getElementById('hang_diem').value = h.diem_toi_thieu;
        document.getElementById('hang_chietkhau').value = h.phan_tram_chiet_khau;
        document.getElementById('hang_mota').value = h.mo_ta || '';
        new bootstrap.Modal(document.getElementById('modalSuaHang')).show();
    }
</script>
@endsection

