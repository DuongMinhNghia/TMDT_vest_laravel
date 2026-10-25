@extends('layouts.admin_layout')

@section('title', 'Thống Kê Đơn Hàng - Kingsman Admin')
@section('page_name', 'Thống Kê Đơn Hàng')

@section('content')
<style>
    /* Executive Luxury Dark Palette for Thống Kê Đơn Hàng */
    .ord-container {
        padding: 4px 0 40px;
    }

    .ord-top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--adm-border);
    }

    .ord-header-title {
        font-family: var(--adm-font-heading);
        color: var(--adm-text-main);
        font-size: 1.6rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .ord-header-title i {
        color: var(--adm-gold);
    }

    .ord-subtitle {
        color: var(--adm-text-muted);
        font-size: 0.82rem;
        margin-top: 4px;
    }

    .btn-outline-gold {
        background: transparent;
        border: 1px solid var(--adm-border);
        color: var(--adm-text-muted);
        padding: 8px 16px;
        border-radius: 8px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.84rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }

    .btn-outline-gold:hover {
        border-color: var(--adm-gold);
        color: var(--adm-gold);
        background: var(--adm-bg-hover);
    }

    /* KPI Summary Cards */
    .ord-kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .ord-kpi-card {
        background: var(--adm-bg-surface);
        border: 1px solid var(--adm-border);
        border-radius: 10px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: border-color 0.2s, transform 0.2s;
    }

    .ord-kpi-card:hover {
        border-color: var(--adm-border-focus);
        transform: translateY(-2px);
    }

    .ord-kpi-label {
        color: var(--adm-text-muted);
        font-size: 0.74rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 4px;
    }

    .ord-kpi-num {
        font-family: var(--adm-font-heading);
        font-size: 1.65rem;
        font-weight: 700;
        color: var(--adm-text-main);
    }

    .ord-kpi-icon {
        width: 44px;
        height: 44px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
    }

    /* Filter Toolbar */
    .ord-filter-card {
        background: var(--adm-bg-surface);
        border: 1px solid var(--adm-border);
        border-radius: 10px;
        padding: 16px 20px;
        margin-bottom: 20px;
    }

    .ord-form-control, .ord-form-select {
        background: var(--adm-bg-card);
        border: 1px solid var(--adm-border);
        color: var(--adm-text-main);
        border-radius: 6px;
        padding: 8px 12px;
        font-size: 0.84rem;
        width: 100%;
        transition: all 0.2s;
    }

    .ord-form-control:focus, .ord-form-select:focus {
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
        padding: 9px 20px;
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
    .ord-table-card {
        background: var(--adm-bg-surface);
        border: 1px solid var(--adm-border);
        border-radius: 12px;
        overflow: hidden;
    }

    .ord-table {
        width: 100%;
        border-collapse: collapse;
        color: var(--adm-text-main);
        font-size: 0.86rem;
    }

    .ord-table thead th {
        background: var(--adm-bg-card);
        color: var(--adm-gold);
        border-bottom: 1px solid var(--adm-border);
        padding: 13px 16px;
        font-size: 0.76rem;
        font-weight: 600;
        letter-spacing: 0.8px;
        text-transform: uppercase;
    }

    .ord-table tbody tr {
        border-bottom: 1px solid var(--adm-border);
        transition: background 0.15s;
    }

    .ord-table tbody tr:hover {
        background: var(--adm-bg-hover);
    }

    .ord-table tbody td {
        padding: 14px 16px;
        vertical-align: middle;
    }

    /* Order Status Badges */
    .badge-status-pill {
        padding: 3px 10px;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .badge-hoan {
        background: rgba(34, 197, 94, 0.12);
        color: #22c55e;
        border: 1px solid rgba(34, 197, 94, 0.3);
    }

    .badge-giao {
        background: rgba(56, 189, 248, 0.12);
        color: #38bdf8;
        border: 1px solid rgba(56, 189, 248, 0.3);
    }

    .badge-may {
        background: rgba(168, 85, 247, 0.12);
        color: #a855f7;
        border: 1px solid rgba(168, 85, 247, 0.3);
    }

    .badge-cho {
        background: rgba(234, 179, 8, 0.12);
        color: #eab308;
        border: 1px solid rgba(234, 179, 8, 0.3);
    }

    .badge-huy {
        background: rgba(244, 63, 94, 0.12);
        color: #f43f5e;
        border: 1px solid rgba(244, 63, 94, 0.3);
    }

    .btn-update-status {
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

    .btn-update-status:hover {
        background: var(--adm-gold);
        color: #000;
    }
</style>

<div class="ord-container">

    <!-- TOP TOOLBAR -->
    <div class="ord-top-bar">
        <div>
            <h2 class="ord-header-title">
                <i class="bi bi-receipt"></i> Quản Lý & Thống Kê Đơn Hàng
            </h2>
            <div class="ord-subtitle">Theo dõi trạng thái các đơn đặt may và mua sắm âu phục trên toàn hệ thống</div>
        </div>
        <a href="{{ route('thongke.doanhthu') }}" class="btn-outline-gold">
            <i class="bi bi-graph-up-arrow"></i> Xem Doanh Thu
        </a>
    </div>

    <!-- KPI STATS -->
    <div class="ord-kpi-grid">
        <div class="ord-kpi-card">
            <div>
                <div class="ord-kpi-label">Tổng Đơn Hàng</div>
                <div class="ord-kpi-num" style="color: var(--adm-gold);">{{ $tongHoanThanh + $tongDangGiao + $tongChoXuLy }}</div>
            </div>
            <div class="ord-kpi-icon" style="background:var(--adm-gold-glow); color:var(--adm-gold);">
                <i class="bi bi-receipt-cutoff"></i>
            </div>
        </div>

        <div class="ord-kpi-card">
            <div>
                <div class="ord-kpi-label">Đã Hoàn Thành</div>
                <div class="ord-kpi-num" style="color: #22c55e;">{{ $tongHoanThanh }}</div>
            </div>
            <div class="ord-kpi-icon" style="background:rgba(34,197,94,0.12); color:#22c55e;">
                <i class="bi bi-check-circle"></i>
            </div>
        </div>

        <div class="ord-kpi-card">
            <div>
                <div class="ord-kpi-label">Đang Giao Hàng</div>
                <div class="ord-kpi-num" style="color: #38bdf8;">{{ $tongDangGiao }}</div>
            </div>
            <div class="ord-kpi-icon" style="background:rgba(56,189,248,0.12); color:#38bdf8;">
                <i class="bi bi-truck"></i>
            </div>
        </div>

        <div class="ord-kpi-card">
            <div>
                <div class="ord-kpi-label">Chờ Xử Lý</div>
                <div class="ord-kpi-num" style="color: #eab308;">{{ $tongChoXuLy }}</div>
            </div>
            <div class="ord-kpi-icon" style="background:rgba(234,179,8,0.12); color:#eab308;">
                <i class="bi bi-hourglass-split"></i>
            </div>
        </div>
    </div>

    <!-- FILTER TOOLBAR -->
    <div class="ord-filter-card">
        <form method="GET" action="{{ route('thongke.donhang') }}" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label style="color: var(--adm-text-muted); font-size:0.75rem; text-transform:uppercase; letter-spacing:0.8px; margin-bottom:6px; font-weight:600;">Trạng thái</label>
                <select name="trang_thai" class="ord-form-select">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="Chờ xử lý" {{ $trangThai=='Chờ xử lý'?'selected':'' }}>Chờ xử lý</option>
                    <option value="Đang may/gói" {{ $trangThai=='Đang may/gói'?'selected':'' }}>Đang may / Gói hàng</option>
                    <option value="Đang giao" {{ $trangThai=='Đang giao'?'selected':'' }}>Đang giao</option>
                    <option value="Hoàn Thành" {{ $trangThai=='Hoàn Thành'?'selected':'' }}>Hoàn thành</option>
                    <option value="Đã hủy" {{ $trangThai=='Đã hủy'?'selected':'' }}>Đã hủy</option>
                </select>
            </div>
            <div class="col-md-3">
                <label style="color: var(--adm-text-muted); font-size:0.75rem; text-transform:uppercase; letter-spacing:0.8px; margin-bottom:6px; font-weight:600;">Từ ngày</label>
                <input type="date" name="tu_ngay" value="{{ $tuNgay }}" class="ord-form-control">
            </div>
            <div class="col-md-3">
                <label style="color: var(--adm-text-muted); font-size:0.75rem; text-transform:uppercase; letter-spacing:0.8px; margin-bottom:6px; font-weight:600;">Đến ngày</label>
                <input type="date" name="den_ngay" value="{{ $denNgay }}" class="ord-form-control">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn-gold-filter flex-fill">
                    <i class="bi bi-funnel"></i> Lọc Đơn
                </button>
                <a href="{{ route('thongke.donhang') }}" class="btn-reset-filter" title="Xóa bộ lọc">
                    <i class="bi bi-x-circle"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- TABLE LIST -->
    <div class="ord-table-card">
        <table class="ord-table">
            <thead>
                <tr>
                    <th style="width: 120px;">Mã ĐH</th>
                    <th>Khách Hàng</th>
                    <th>Ngày Đặt</th>
                    <th>Địa Chỉ Nhận</th>
                    <th>Tổng Tiền</th>
                    <th>Trạng Thái</th>
                    <th style="text-align:center; width: 120px;">Cập Nhật</th>
                </tr>
            </thead>
            <tbody>
                @forelse($donHangs as $dh)
                <tr>
                    <td>
                        <a href="{{ route('donhang.chitiet', $dh->id) }}" style="color: var(--adm-gold); font-weight: 700; text-decoration: none;">
                            {{ $dh->ma_don_hang ?? '#'.str_pad($dh->id, 4, '0', STR_PAD_LEFT) }}
                        </a>
                    </td>
                    <td>
                        <div style="font-weight: 600; color: var(--adm-text-main);">{{ $dh->khachHang->ho_ten ?? 'Khách vãng lai' }}</div>
                        <div style="color: var(--adm-text-dim); font-size: 0.76rem;">{{ $dh->khachHang->taiKhoan->email ?? '' }}</div>
                    </td>
                    <td style="color: var(--adm-text-muted);">
                        {{ \Carbon\Carbon::parse($dh->ngay_dat)->format('d/m/Y') }}
                    </td>
                    <td style="color: var(--adm-text-muted); max-width: 180px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 0.82rem;">
                        {{ $dh->dia_chi_giao_hang ?? '—' }}
                    </td>
                    <td style="color: var(--adm-gold); font-weight: 700;">
                        {{ number_format($dh->tong_tien, 0, ',', '.') }} VNĐ
                    </td>
                    <td>
                        @if($dh->trang_thai === 'Hoàn Thành')
                            <span class="badge-status-pill badge-hoan"><i class="bi bi-check-circle-fill"></i> Hoàn Thành</span>
                        @elseif($dh->trang_thai === 'Đang may/gói')
                            <span class="badge-status-pill badge-may"><i class="bi bi-scissors"></i> Đang may / gói</span>
                        @elseif($dh->trang_thai === 'Đang giao')
                            <span class="badge-status-pill badge-giao"><i class="bi bi-truck"></i> Đang giao</span>
                        @elseif($dh->trang_thai === 'Chờ xử lý')
                            <span class="badge-status-pill badge-cho"><i class="bi bi-hourglass-split"></i> Chờ xử lý</span>
                        @else
                            <span class="badge-status-pill badge-huy"><i class="bi bi-x-circle-fill"></i> Đã hủy</span>
                        @endif

                        @if($dh->nhanVienDuyet)
                            <div style="color: var(--adm-text-dim); font-size: 0.72rem; margin-top: 3px;">Duyệt bởi: {{ $dh->nhanVienDuyet->ho_ten }}</div>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        @if(count($luongTrangThai[$dh->trang_thai] ?? []) > 0)
                        <button class="btn-update-status" onclick="moModalCapNhat({{ $dh->id }}, '{{ $dh->trang_thai }}')">
                            <i class="bi bi-pencil-fill"></i> Xử lý
                        </button>
                        @else
                        <span style="color: var(--adm-text-dim); font-size: 0.78rem;">—</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding:50px; color: var(--adm-text-dim);">
                        <i class="bi bi-inbox" style="font-size:2.5rem; display:block; margin-bottom:10px;"></i>
                        Không có đơn hàng nào theo điều kiện lọc
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($donHangs->count() > 0)
    <div class="mt-3 text-end" style="color: var(--adm-text-muted); font-size:0.84rem;">
        Hiển thị <strong>{{ $donHangs->count() }}</strong> đơn hàng
        — Tổng doanh thu trang này: <strong style="color: var(--adm-gold); font-size:0.95rem;">{{ number_format($donHangs->sum('tong_tien'), 0, ',', '.') }} VNĐ</strong>
    </div>
    @endif

</div>

{{-- Modal Cập Nhật Trạng Thái Đơn Hàng --}}
<div class="modal fade" id="modalCapNhat" tabindex="-1" style="background:rgba(0,0,0,0.85);">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content" style="background: var(--adm-bg-surface); border: 1px solid var(--adm-border-focus); color: var(--adm-text-main); border-radius: 12px; box-shadow: 0 20px 50px rgba(0,0,0,0.8);">
            <div class="modal-header" style="border-bottom: 1px solid var(--adm-border); padding: 16px 20px;">
                <h5 class="modal-title" style="color: var(--adm-gold); font-family: var(--adm-font-heading); font-size: 1.15rem;">
                    <i class="bi bi-pencil-fill me-2"></i>Cập Nhật Trạng Thái
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1);"></button>
            </div>
            <form id="formCapNhat" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <label style="color: var(--adm-text-muted); font-size:0.8rem; margin-bottom:6px; display:block; font-weight:600; text-transform:uppercase; letter-spacing:0.8px;">Trạng thái tiếp theo *</label>
                    <select name="trang_thai" id="select_trangthai" class="ord-form-select"></select>
                </div>
                <div class="modal-footer" style="border-top: 1px solid var(--adm-border); padding: 12px 20px;">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn-gold-filter btn-sm">
                        <i class="bi bi-save me-1"></i> Lưu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const LUONG_TRANG_THAI = @json($luongTrangThai);
function moModalCapNhat(id, trangThai) {
    document.getElementById('formCapNhat').action = '{{ url("/don-hang") }}/' + id + '/cap-nhat';
    const sel = document.getElementById('select_trangthai');
    sel.innerHTML = '';
    (LUONG_TRANG_THAI[trangThai] || []).forEach(function (t) {
        const o = document.createElement('option');
        o.value = t; o.textContent = t;
        sel.appendChild(o);
    });
    new bootstrap.Modal(document.getElementById('modalCapNhat')).show();
}
</script>
@endsection
