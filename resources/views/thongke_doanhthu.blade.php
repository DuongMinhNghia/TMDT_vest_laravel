@extends('layouts.admin_layout')

@section('title', 'Thống Kê Doanh Thu - Kingsman Admin')
@section('page_name', 'Thống Kê Doanh Thu')

@section('content')
<style>
    /* Executive Luxury Dark Palette for Thống Kê Doanh Thu */
    .rev-container {
        padding: 4px 0 40px;
    }

    .rev-top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--adm-border);
    }

    .rev-header-title {
        font-family: var(--adm-font-heading);
        color: var(--adm-text-main);
        font-size: 1.6rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .rev-header-title i {
        color: var(--adm-gold);
    }

    .rev-subtitle {
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

    /* Filter Toolbar */
    .rev-filter-bar {
        background: var(--adm-bg-surface);
        border: 1px solid var(--adm-border);
        border-radius: 10px;
        padding: 14px 20px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 15px;
        flex-wrap: wrap;
    }

    .rev-select-dark {
        background: var(--adm-bg-card);
        border: 1px solid var(--adm-border);
        color: var(--adm-text-main);
        border-radius: 6px;
        padding: 7px 14px;
        font-size: 0.84rem;
        outline: none;
        transition: all 0.2s;
    }

    .rev-select-dark:focus {
        border-color: var(--adm-gold);
        box-shadow: 0 0 0 3px var(--adm-gold-glow);
    }

    /* KPI Summary Cards */
    .rev-kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 14px;
        margin-bottom: 24px;
    }

    .rev-kpi-card {
        background: var(--adm-bg-surface);
        border: 1px solid var(--adm-border);
        border-radius: 10px;
        padding: 16px 18px;
        transition: border-color 0.2s, transform 0.2s;
    }

    .rev-kpi-card:hover {
        border-color: var(--adm-border-focus);
        transform: translateY(-2px);
    }

    .rev-kpi-icon {
        font-size: 1.6rem;
        margin-bottom: 8px;
    }

    .rev-kpi-number {
        font-family: var(--adm-font-heading);
        font-size: 1.55rem;
        font-weight: 700;
        line-height: 1.1;
    }

    .rev-kpi-label {
        color: var(--adm-text-muted);
        font-size: 0.72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-top: 6px;
    }

    /* Chart & Top Products Cards */
    .rev-card-box {
        background: var(--adm-bg-surface);
        border: 1px solid var(--adm-border);
        border-radius: 12px;
        padding: 24px;
        height: 100%;
    }

    .rev-card-title {
        font-family: var(--adm-font-heading);
        color: var(--adm-gold);
        font-size: 1.15rem;
        font-weight: 700;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    /* Custom Monthly Bar Chart */
    .bar-chart {
        display: flex;
        align-items: flex-end;
        gap: 10px;
        height: 220px;
        padding-bottom: 30px;
        position: relative;
        border-bottom: 1px solid var(--adm-border);
    }

    .bar-item {
        flex: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        height: 100%;
        justify-content: flex-end;
    }

    .bar {
        width: 100%;
        border-radius: 4px 4px 0 0;
        background: linear-gradient(to top, rgba(212, 178, 111, 0.3), var(--adm-gold));
        min-height: 4px;
        transition: all 0.4s ease;
        cursor: pointer;
    }

    .bar:hover {
        background: linear-gradient(to top, var(--adm-gold), #fff);
        filter: drop-shadow(0 0 8px rgba(212, 178, 111, 0.5));
    }

    .bar-label {
        color: var(--adm-text-muted);
        font-size: 0.72rem;
        margin-top: 8px;
        font-weight: 600;
    }

    .bar-value {
        color: var(--adm-gold);
        font-size: 0.65rem;
        font-weight: 700;
        text-align: center;
        white-space: nowrap;
    }

    /* Top Products Leaderboard */
    .top-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 11px 0;
        border-bottom: 1px solid rgba(255, 255, 255, 0.04);
    }

    .top-item:last-child {
        border-bottom: none;
    }

    .top-rank {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 0.78rem;
        flex-shrink: 0;
    }

    .rank-1 { background: linear-gradient(135deg, #FFD700, #FFA500); color: #000; box-shadow: 0 0 10px rgba(255,215,0,0.3); }
    .rank-2 { background: linear-gradient(135deg, #E2E8F0, #94A3B8); color: #000; }
    .rank-3 { background: linear-gradient(135deg, #CD7F32, #A0522D); color: #fff; }
    .rank-other { background: var(--adm-bg-card); color: var(--adm-text-dim); border: 1px solid var(--adm-border); }

    .top-img {
        width: 42px;
        height: 42px;
        border-radius: 6px;
        object-fit: cover;
        background: var(--adm-bg-card);
        border: 1px solid var(--adm-border);
        flex-shrink: 0;
    }

    .top-info {
        flex: 1;
        min-width: 0;
    }

    .top-info .name {
        color: var(--adm-text-main);
        font-weight: 600;
        font-size: 0.85rem;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }

    .top-info .sold {
        color: var(--adm-text-muted);
        font-size: 0.75rem;
        margin-top: 2px;
    }

    .top-revenue {
        color: var(--adm-gold);
        font-weight: 700;
        font-size: 0.85rem;
        white-space: nowrap;
    }
</style>

<div class="rev-container">

    <!-- TOP TOOLBAR -->
    <div class="rev-top-bar">
        <div>
            <h2 class="rev-header-title">
                <i class="bi bi-graph-up-arrow"></i> Thống Kê Doanh Thu & Hiệu Quả Kinh Doanh
            </h2>
            <div class="rev-subtitle">Theo dõi tổng doanh số bán hàng, đơn hoàn tất và bảng xếp hạng áo vest bán chạy</div>
        </div>
        <a href="{{ route('thongke.donhang') }}" class="btn-outline-gold">
            <i class="bi bi-receipt"></i> Xem Đơn Hàng
        </a>
    </div>

    <!-- FILTER BAR -->
    <div class="rev-filter-bar">
        <span style="color: var(--adm-text-muted); font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.8px;">Chọn năm:</span>
        <form method="GET" action="{{ route('thongke.doanhthu') }}" class="d-flex gap-2 align-items-center">
            <select name="nam" class="rev-select-dark" onchange="this.form.submit()">
                @foreach($danhSachNam as $n)
                <option value="{{ $n }}" {{ $n == $nam ? 'selected' : '' }}>Năm {{ $n }}</option>
                @endforeach
            </select>
        </form>
        <span style="color: var(--adm-text-dim); font-size:0.82rem;">Đang hiển thị dữ liệu năm <strong style="color: var(--adm-gold);">{{ $nam }}</strong></span>
    </div>

    <!-- KPI STATS -->
    <div class="rev-kpi-grid">
        <div class="rev-kpi-card">
            <div class="rev-kpi-icon">💰</div>
            <div class="rev-kpi-number" style="color: var(--adm-gold);">{{ number_format($tongDoanhThu/1000000, 1) }}M</div>
            <div class="rev-kpi-label">Tổng Doanh Thu</div>
        </div>
        <div class="rev-kpi-card">
            <div class="rev-kpi-icon">✅</div>
            <div class="rev-kpi-number" style="color: #22c55e;">{{ $tongDonHoan }}</div>
            <div class="rev-kpi-label">Đơn Hoàn Thành</div>
        </div>
        <div class="rev-kpi-card">
            <div class="rev-kpi-icon">🚚</div>
            <div class="rev-kpi-number" style="color: #38bdf8;">{{ $tongDonDangGiao }}</div>
            <div class="rev-kpi-label">Đang Giao Hàng</div>
        </div>
        <div class="rev-kpi-card">
            <div class="rev-kpi-icon">⏳</div>
            <div class="rev-kpi-number" style="color: #eab308;">{{ $tongDonCho }}</div>
            <div class="rev-kpi-label">Chờ Xử Lý</div>
        </div>
        <div class="rev-kpi-card">
            <div class="rev-kpi-icon">👥</div>
            <div class="rev-kpi-number" style="color: #c084fc;">{{ $tongKhachHang }}</div>
            <div class="rev-kpi-label">Khách Hàng</div>
        </div>
        <div class="rev-kpi-card">
            <div class="rev-kpi-icon">🎽</div>
            <div class="rev-kpi-number" style="color: #67e8f9;">{{ $tongSanPham }}</div>
            <div class="rev-kpi-label">Mẫu Sản Phẩm</div>
        </div>
    </div>

    <!-- MAIN CHARTS ROW -->
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="rev-card-box">
                <h5 class="rev-card-title">
                    <i class="bi bi-bar-chart-fill"></i> Biểu Đồ Doanh Thu Theo Tháng — Năm {{ $nam }}
                </h5>
                @php
                    $arrDt = array_values($doanhThuThang);
                    $maxVal = count($arrDt) > 0 ? max($arrDt) : 0;
                    if ($maxVal <= 0) { $maxVal = 1; }
                @endphp
                <div class="bar-chart">
                    @foreach($doanhThuThang as $thang => $dt)
                    @php
                        $pct = intval(max(4, ($dt / $maxVal) * 165));
                        $label = $dt > 0 ? number_format($dt / 1000000, 1) . 'M' : '';
                    @endphp
                    <div class="bar-item" title="Tháng {{ $thang }}: {{ number_format($dt, 0, ',', '.') }} đ">
                        <div class="bar-value">{{ $label }}</div>
                        <div class="bar" style="height:{{ $pct }}px;"></div>
                        <span class="bar-label">T{{ $thang }}</span>
                    </div>
                    @endforeach
                </div>
                @php
                    $tongThang = array_sum($doanhThuThang);
                @endphp
                <div class="mt-3 text-center" style="color: var(--adm-text-muted); font-size:0.84rem;">
                    Tổng doanh thu tích lũy năm {{ $nam }}:
                    <strong style="color: var(--adm-gold); font-size:0.95rem;">{{ number_format($tongThang, 0, ',', '.') }} VNĐ</strong>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="rev-card-box">
                <h5 class="rev-card-title">
                    <i class="bi bi-trophy-fill"></i> Top Áo Vest Bán Chạy
                </h5>
                @forelse($topSanPham as $i => $sp)
                @php
                    $rankClass = $i == 0 ? 'rank-1' : ($i == 1 ? 'rank-2' : ($i == 2 ? 'rank-3' : 'rank-other'));
                @endphp
                <div class="top-item">
                    <div class="top-rank {{ $rankClass }}">{{ $i + 1 }}</div>
                    @if($sp->hinh_anh)
                    <img src="{{ asset('images/' . $sp->hinh_anh) }}" class="top-img" alt="{{ $sp->ten_sp }}">
                    @else
                    <div class="top-img d-flex align-items-center justify-content-center">
                        <i class="bi bi-image" style="color: var(--adm-text-dim);"></i>
                    </div>
                    @endif
                    <div class="top-info">
                        <div class="name">{{ Str::limit($sp->ten_sp, 26) }}</div>
                        <div class="sold">Đã xuất kho: <strong>{{ $sp->tong_ban }} bộ</strong></div>
                    </div>
                    <div class="top-revenue">{{ number_format($sp->tong_tien / 1000000, 1) }}M</div>
                </div>
                @empty
                <div style="text-align:center; color: var(--adm-text-dim); padding:40px 0;">
                    <i class="bi bi-inbox" style="font-size:2rem; display:block; margin-bottom:8px;"></i>
                    Chưa có số liệu bán hàng
                </div>
                @endforelse
            </div>
        </div>
    </div>

</div>
@endsection