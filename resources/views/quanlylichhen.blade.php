@extends('layouts.admin_layout')

@section('title', 'Quản Lý Lịch Hẹn Showroom - Kingsman Admin')
@section('page_name', 'Lịch Hẹn Showroom')

@section('content')
<style>
    /* Executive Luxury Dark Palette for Lịch Hẹn Showroom */
    .lh-container {
        padding: 4px 0 40px;
    }

    .lh-top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--adm-border);
    }

    .lh-header-title {
        font-family: var(--adm-font-heading);
        color: var(--adm-text-main);
        font-size: 1.6rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .lh-header-title i {
        color: var(--adm-gold);
    }

    .lh-subtitle {
        color: var(--adm-text-muted);
        font-size: 0.82rem;
        margin-top: 4px;
    }

    /* KPI Grid */
    .lh-kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 14px;
        margin-bottom: 24px;
    }

    .lh-kpi-card {
        background: var(--adm-bg-surface);
        border: 1px solid var(--adm-border);
        border-radius: 10px;
        padding: 14px 18px;
        transition: border-color 0.2s, transform 0.2s;
    }

    .lh-kpi-card:hover {
        border-color: var(--adm-border-focus);
        transform: translateY(-2px);
    }

    .lh-kpi-number {
        font-family: var(--adm-font-heading);
        font-size: 1.65rem;
        font-weight: 700;
    }

    .lh-kpi-label {
        color: var(--adm-text-muted);
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-top: 4px;
    }

    /* Filter Toolbar */
    .lh-filter-card {
        background: var(--adm-bg-surface);
        border: 1px solid var(--adm-border);
        border-radius: 10px;
        padding: 16px 20px;
        margin-bottom: 20px;
    }

    .lh-form-control, .lh-form-select {
        background: var(--adm-bg-card);
        border: 1px solid var(--adm-border);
        color: var(--adm-text-main);
        border-radius: 6px;
        padding: 8px 12px;
        font-size: 0.84rem;
        width: 100%;
        transition: all 0.2s;
    }

    .lh-form-control:focus, .lh-form-select:focus {
        border-color: var(--adm-gold);
        outline: none;
        box-shadow: 0 0 0 3px var(--adm-gold-glow);
        background: #1c202b;
        color: #fff;
    }

    .btn-gold-filter {
        background: linear-gradient(135deg, var(--adm-gold-light), var(--adm-gold));
        border: none;
        color: #000;
        padding: 9px 18px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 0.84rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: all 0.2s;
    }

    .btn-gold-filter:hover {
        filter: brightness(1.1);
        transform: translateY(-1px);
    }

    .btn-reset-filter {
        background: var(--adm-bg-card);
        border: 1px solid var(--adm-border);
        color: var(--adm-text-muted);
        padding: 9px 14px;
        border-radius: 6px;
        font-size: 0.84rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-reset-filter:hover {
        color: #fff;
        border-color: var(--adm-border-focus);
    }

    /* Table Container */
    .lh-table-card {
        background: var(--adm-bg-surface);
        border: 1px solid var(--adm-border);
        border-radius: 12px;
        overflow: hidden;
    }

    .lh-table {
        width: 100%;
        border-collapse: collapse;
        color: var(--adm-text-main);
        font-size: 0.86rem;
    }

    .lh-table thead th {
        background: var(--adm-bg-card);
        color: var(--adm-gold);
        border-bottom: 1px solid var(--adm-border);
        padding: 13px 16px;
        font-size: 0.76rem;
        font-weight: 600;
        letter-spacing: 0.8px;
        text-transform: uppercase;
    }

    .lh-table tbody tr {
        border-bottom: 1px solid var(--adm-border);
        transition: background 0.15s;
    }

    .lh-table tbody tr:hover {
        background: var(--adm-bg-hover);
    }

    .lh-table tbody td {
        padding: 14px 16px;
        vertical-align: middle;
    }

    .btn-edit-action {
        background: rgba(212, 178, 111, 0.12);
        border: 1px solid rgba(212, 178, 111, 0.3);
        color: var(--adm-gold);
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-edit-action:hover {
        background: var(--adm-gold);
        color: #000;
    }

    .btn-del-action {
        background: rgba(244, 63, 94, 0.12);
        border: 1px solid rgba(244, 63, 94, 0.3);
        color: #f43f5e;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 0.78rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-del-action:hover {
        background: #f43f5e;
        color: #fff;
    }

    /* Modal Dark */
    .modal-content-luxury {
        background: var(--adm-bg-surface);
        border: 1px solid var(--adm-border-focus);
        color: var(--adm-text-main);
        border-radius: 12px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.8);
    }

    .modal-content-luxury .modal-header {
        border-bottom: 1px solid var(--adm-border);
        padding: 18px 22px;
    }

    .modal-content-luxury .modal-footer {
        border-top: 1px solid var(--adm-border);
        padding: 14px 22px;
    }
</style>

<div class="lh-container">

    <!-- TOP TOOLBAR -->
    <div class="lh-top-bar">
        <div>
            <h2 class="lh-header-title">
                <i class="bi bi-calendar2-check-fill"></i> Quản Lý Lịch Hẹn Showroom
            </h2>
            <div class="lh-subtitle">Điều phối lịch thử đồ, lấy số đo may đo trực tiếp tại showroom Kingsman</div>
        </div>
    </div>

    <!-- KPI STATS -->
    <div class="lh-kpi-grid">
        <div class="lh-kpi-card">
            <div class="lh-kpi-number" style="color: var(--adm-gold);">{{ $thongKe['tong'] }}</div>
            <div class="lh-kpi-label">Tổng lịch hẹn</div>
        </div>
        <div class="lh-kpi-card">
            <div class="lh-kpi-number" style="color: #eab308;">{{ $thongKe['cho_xac_nhan'] }}</div>
            <div class="lh-kpi-label">Chờ xác nhận</div>
        </div>
        <div class="lh-kpi-card">
            <div class="lh-kpi-number" style="color: #38bdf8;">{{ $thongKe['da_xac_nhan'] }}</div>
            <div class="lh-kpi-label">Đã xác nhận</div>
        </div>
        <div class="lh-kpi-card">
            <div class="lh-kpi-number" style="color: #60a5fa;">{{ $thongKe['dang_tiep_don'] }}</div>
            <div class="lh-kpi-label">Đang tiếp đón</div>
        </div>
        <div class="lh-kpi-card">
            <div class="lh-kpi-number" style="color: #22c55e;">{{ $thongKe['hoan_thanh'] }}</div>
            <div class="lh-kpi-label">Hoàn thành</div>
        </div>
        <div class="lh-kpi-card">
            <div class="lh-kpi-number" style="color: #f43f5e;">{{ $thongKe['da_huy'] }}</div>
            <div class="lh-kpi-label">Đã hủy</div>
        </div>
    </div>

    <!-- FILTER TOOLBAR -->
    <div class="lh-filter-card">
        <form method="GET" action="{{ route('lichhen.quanly') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label style="color: var(--adm-text-muted); font-size:0.75rem; text-transform:uppercase; letter-spacing:0.8px; margin-bottom:6px; font-weight:600;">Tìm kiếm</label>
                <input type="text" name="tim_kiem" value="{{ request('tim_kiem') }}" placeholder="Tên khách, SĐT hoặc mã lịch hẹn..." class="lh-form-control">
            </div>
            <div class="col-md-3">
                <label style="color: var(--adm-text-muted); font-size:0.75rem; text-transform:uppercase; letter-spacing:0.8px; margin-bottom:6px; font-weight:600;">Trạng thái</label>
                <select name="trang_thai" class="lh-form-select">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="Chờ xác nhận" {{ request('trang_thai') == 'Chờ xác nhận' ? 'selected' : '' }}>Chờ xác nhận</option>
                    <option value="Đã xác nhận" {{ request('trang_thai') == 'Đã xác nhận' ? 'selected' : '' }}>Đã xác nhận</option>
                    <option value="Đang tiếp đón" {{ request('trang_thai') == 'Đang tiếp đón' ? 'selected' : '' }}>Đang tiếp đón</option>
                    <option value="Hoàn thành" {{ request('trang_thai') == 'Hoàn thành' ? 'selected' : '' }}>Hoàn thành</option>
                    <option value="Đã hủy" {{ request('trang_thai') == 'Đã hủy' ? 'selected' : '' }}>Đã hủy</option>
                </select>
            </div>
            <div class="col-md-3">
                <label style="color: var(--adm-text-muted); font-size:0.75rem; text-transform:uppercase; letter-spacing:0.8px; margin-bottom:6px; font-weight:600;">Ngày hẹn</label>
                <input type="date" name="ngay" value="{{ request('ngay') }}" class="lh-form-control">
            </div>
            <div class="col-md-2 d-flex gap-2">
                <button type="submit" class="btn-gold-filter flex-fill">
                    <i class="bi bi-search"></i> Lọc
                </button>
                <a href="{{ route('lichhen.quanly') }}" class="btn-reset-filter" title="Xóa bộ lọc">
                    <i class="bi bi-x-circle"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- TABLE LIST -->
    <div class="lh-table-card">
        <table class="lh-table">
            <thead>
                <tr>
                    <th style="width: 120px;">Mã Lịch</th>
                    <th>Khách Hàng</th>
                    <th>Thời Gian Hẹn</th>
                    <th>Dịch Vụ & Mẫu Vest</th>
                    <th>Nhân Viên Đón Tiếp</th>
                    <th>Trạng Thái</th>
                    <th style="text-align:center; width: 130px;">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lichHens as $lh)
                <tr>
                    <td>
                        <strong style="color: var(--adm-gold);">{{ $lh->ma_lich_hen }}</strong>
                        <small style="color: var(--adm-text-dim); display: block; font-size: 0.72rem;">{{ $lh->so_nguoi_di_cung }} người đi cùng</small>
                    </td>
                    <td>
                        <div style="font-weight: 600; color: var(--adm-text-main);">{{ $lh->khachHang->ho_ten ?? 'Khách vãng lai' }}</div>
                        <small style="color: var(--adm-text-muted);"><i class="bi bi-telephone me-1"></i>{{ $lh->khachHang->so_dien_thoai ?? 'Chưa có SĐT' }}</small>
                        @if($lh->khachHang?->hangThanhVien)
                        <div>
                            <span class="badge" style="background: {{ $lh->khachHang->hangThanhVien->mau_sac }}; color: #000; font-size: 0.65rem; font-weight:700;">
                                {{ $lh->khachHang->hangThanhVien->ten_hang }}
                            </span>
                        </div>
                        @endif
                    </td>
                    <td>
                        <div style="color: var(--adm-text-main); font-weight: 600;">{{ $lh->thoi_gian_hen ? $lh->thoi_gian_hen->format('H:i') : '—' }}</div>
                        <small style="color: var(--adm-gold);">{{ $lh->thoi_gian_hen ? $lh->thoi_gian_hen->format('d/m/Y') : '—' }}</small>
                    </td>
                    <td>
                        <div style="color: var(--adm-text-main);">{{ $lh->loai_dich_vu }}</div>
                        @if($lh->sanPhamQuanTam)
                        <small style="color: var(--adm-gold); display: block;">
                            <i class="bi bi-suit-heart-fill me-1"></i>{{ $lh->sanPhamQuanTam->ten_sp }}
                        </small>
                        @endif
                        @if($lh->ghi_chu)
                        <small style="color: var(--adm-text-dim); display: block; max-width:180px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;" title="{{ $lh->ghi_chu }}">
                            "{{ $lh->ghi_chu }}"
                        </small>
                        @endif
                    </td>
                    <td>
                        @if($lh->nhanVienTiepDon)
                            <div style="color: #38bdf8; font-weight: 600;">{{ $lh->nhanVienTiepDon->ho_ten }}</div>
                            <small style="color: var(--adm-text-dim);">{{ $lh->nhanVienTiepDon->chuc_vu }}</small>
                        @else
                            <span style="color: var(--adm-text-dim); font-style: italic;">Chưa phân công</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge {{ $lh->badge_class }} px-2 py-1" style="font-size:0.75rem;">
                            {{ $lh->trang_thai }}
                        </span>
                    </td>
                    <td style="text-align:center;">
                        <div class="d-flex gap-2 justify-content-center">
                            <button class="btn-edit-action" onclick='moModalCapNhat(@json($lh))'>
                                <i class="bi bi-pencil-fill"></i> Duyệt
                            </button>
                            <form action="{{ route('lichhen.xoa', $lh->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa lịch hẹn {{ $lh->ma_lich_hen }}?')">
                                @csrf
                                <button type="submit" class="btn-del-action" title="Xóa lịch hẹn">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding:50px; color: var(--adm-text-dim);">
                        <i class="bi bi-calendar-x" style="font-size:2.5rem; display:block; margin-bottom:10px;"></i>
                        Không tìm thấy lịch hẹn nào theo tiêu chí lọc
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $lichHens->withQueryString()->links() }}
    </div>

</div>

{{-- MODAL DUYỆT / CẬP NHẬT TRẠNG THÁI & PHÂN CÔNG NHÂN VIÊN --}}
<div class="modal fade" id="modalCapNhatLich" tabindex="-1" style="background:rgba(0,0,0,0.85);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-luxury">
            <div class="modal-header">
                <h5 class="modal-title" style="color: var(--adm-gold); font-family: var(--adm-font-heading); font-size: 1.25rem;">
                    <i class="bi bi-calendar-check me-2"></i>Duyệt & Xử Lý Lịch Hẹn
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1);"></button>
            </div>
            <form id="formCapNhatLich" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label" style="color: var(--adm-text-muted); font-size:0.8rem;">Mã lịch hẹn</label>
                        <input type="text" id="capnhat_ma" class="lh-form-control" disabled style="opacity: 0.7;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" style="color: var(--adm-text-muted); font-size:0.8rem;">Khách hàng</label>
                        <input type="text" id="capnhat_khach" class="lh-form-control" disabled style="opacity: 0.7;">
                    </div>

                    <div class="mb-3">
                        <label class="form-label" style="color: var(--adm-text-main); font-size:0.84rem; font-weight:600;">Trạng thái xử lý *</label>
                        <select name="trang_thai" id="capnhat_trangthai" class="lh-form-select" required>
                            <option value="Chờ xác nhận">Chờ xác nhận</option>
                            <option value="Đã xác nhận">Đã xác nhận (Đã gọi điện chốt lịch)</option>
                            <option value="Đang tiếp đón">Đang tiếp đón tại showroom</option>
                            <option value="Hoàn thành">Hoàn thành (Đã thử đồ/đặt may)</option>
                            <option value="Đã hủy">Đã hủy</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" style="color: var(--adm-text-main); font-size:0.84rem; font-weight:600;">Phân công Nhân viên / Thợ may đón tiếp</label>
                        <select name="nhan_vien_tiep_don_id" id="capnhat_nhanvien" class="lh-form-select">
                            <option value="">-- Chưa chỉ định nhân viên --</option>
                            @foreach($nhanViens as $nv)
                            <option value="{{ $nv->id }}">{{ $nv->ho_ten }} ({{ $nv->chuc_vu }} - {{ $nv->so_dien_thoai }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" style="color: var(--adm-text-main); font-size:0.84rem; font-weight:600;">Ghi chú nội bộ</label>
                        <textarea name="ghi_chu" id="capnhat_ghichu" rows="3" class="lh-form-control" placeholder="Ghi chú thêm về vóc dáng, sở thích hoặc kết quả buổi tư vấn..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn-gold-filter">
                        <i class="bi bi-save me-1"></i> Lưu Cập Nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function moModalCapNhat(lh) {
        document.getElementById('formCapNhatLich').action = '{{ url("/lich-hen") }}/' + lh.id + '/cap-nhat';
        document.getElementById('capnhat_ma').value = lh.ma_lich_hen + ' (' + lh.loai_dich_vu + ')';
        document.getElementById('capnhat_khach').value = (lh.khach_hang ? lh.khach_hang.ho_ten : 'Khách vãng lai') + ' - ' + (lh.khach_hang ? (lh.khach_hang.so_dien_thoai || 'Chưa có SĐT') : '');
        document.getElementById('capnhat_trangthai').value = lh.trang_thai;
        document.getElementById('capnhat_nhanvien').value = lh.nhan_vien_tiep_don_id || '';
        document.getElementById('capnhat_ghichu').value = lh.ghi_chu || '';
        new bootstrap.Modal(document.getElementById('modalCapNhatLich')).show();
    }
</script>
@endsection
