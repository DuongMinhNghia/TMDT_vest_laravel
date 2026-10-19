@extends('layouts.default_layout')

@section('title', 'Thống Kê Doanh Thu - Kingsman')

@section('content')
<style>
    .admin-wrap { background: linear-gradient(to bottom, #0a0a0a, #000); min-height: 100vh; padding: 30px 20px; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 15px; border-bottom: 2px solid #d4af37; }
    .page-header h2 { color: #d4af37; font-size: 1.8rem; font-weight: 700; margin: 0; letter-spacing: 2px; }
    .stat-card { background: linear-gradient(135deg, #1a1a1a, #2a2a2a); border: 1px solid #333; border-radius: 12px; padding: 24px 20px; transition: all 0.3s; }
    .stat-card:hover { border-color: #d4af37; transform: translateY(-3px); box-shadow: 0 10px 30px rgba(212,175,55,0.2); }
    .stat-card .number { font-size: 2rem; font-weight: 700; }
    .stat-card .label { color: #888; font-size: 0.82rem; margin-top: 6px; letter-spacing: 1px; text-transform: uppercase; }
    .stat-card .icon { font-size: 2rem; margin-bottom: 12px; }
    .chart-wrap { background: #1a1a1a; border: 1px solid #333; border-radius: 12px; padding: 25px; margin-bottom: 25px; }
    .chart-wrap h5 { color: #d4af37; font-weight: 700; margin-bottom: 20px; letter-spacing: 1px; }
    .bar-chart { display: flex; align-items: flex-end; gap: 8px; height: 200px; padding-bottom: 30px; position: relative; border-bottom: 1px solid #333; }
    .bar-item { flex: 1; display: flex; flex-direction: column; align-items: center; gap: 6px; height: 100%; justify-content: flex-end; }
    .bar { width: 100%; border-radius: 6px 6px 0 0; background: linear-gradient(to top, #1F4E79, #d4af37); min-height: 4px; transition: all 0.6s ease; cursor: pointer; }
    .bar:hover { filter: brightness(1.3); }
    .bar-label { color: #666; font-size: 0.7rem; margin-top: 8px; }
    .bar-value { color: #d4af37; font-size: 0.65rem; font-weight: 700; text-align: center; }
    .top-sp-wrap { background: #1a1a1a; border: 1px solid #333; border-radius: 12px; padding: 25px; }
    .top-sp-wrap h5 { color: #d4af37; font-weight: 700; margin-bottom: 20px; letter-spacing: 1px; }
    .top-item { display: flex; align-items: center; gap: 15px; padding: 12px 0; border-bottom: 1px solid #222; }
    .top-item:last-child { border-bottom: none; }
    .top-rank { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; flex-shrink: 0; }
    .rank-1 { background: linear-gradient(135deg, #FFD700, #FFA500); color: #000; }
    .rank-2 { background: linear-gradient(135deg, #C0C0C0, #A0A0A0); color: #000; }
    .rank-3 { background: linear-gradient(135deg, #CD7F32, #A0522D); color: #fff; }
    .rank-other { background: #2a2a2a; color: #888; border: 1px solid #444; }
    .top-img { width: 45px; height: 45px; border-radius: 8px; object-fit: cover; background: #2a2a2a; flex-shrink: 0; }
    .top-info { flex: 1; }
    .top-info .name { color: #e0e0e0; font-weight: 600; font-size: 0.9rem; }
    .top-info .sold { color: #888; font-size: 0.8rem; margin-top: 3px; }
    .top-revenue { color: #d4af37; font-weight: 700; font-size: 0.9rem; white-space: nowrap; }
    .filter-bar { background: #1a1a1a; border: 1px solid #333; border-radius: 12px; padding: 16px 20px; margin-bottom: 25px; display: flex; align-items: center; gap: 15px; flex-wrap: wrap; }
    .form-select-dark { background: #0f0f0f !important; border: 1px solid #444 !important; color: #fff !important; border-radius: 8px !important; padding: 8px 14px; }
</style>

<div class="admin-wrap">
<div class="container-fluid">

<div class="page-header">
    <h2><i class="bi bi-graph-up-arrow me-2"></i>Thống Kê Doanh Thu</h2>
    <a href="{{ route('thongke.donhang') }}" style="background:transparent;border:1px solid #d4af37;color:#d4af37;padding:10px 20px;border-radius:8px;text-decoration:none;font-weight:600;font-size:0.9rem;">
        <i class="bi bi-receipt me-1"></i> Xem Đơn Hàng
    </a>
</div>

<div class="filter-bar">
    <span style="color:#888;font-size:0.85rem;">NĂM:</span>
    <form method="GET" action="{{ route('thongke.doanhthu') }}" class="d-flex gap-2 align-items-center">
        <select name="nam" class="form-select-dark" onchange="this.form.submit()">
            @foreach($danhSachNam as $n)
            <option value="{{ $n }}" {{ $n == $nam ? 'selected' : '' }}>{{ $n }}</option>
            @endforeach
        </select>
    </form>
    <span style="color:#555;font-size:0.85rem;">Đang xem năm <span style="color:#d4af37;font-weight:700;">{{ $nam }}</span></span>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-2 col-6">
        <div class="stat-card">
            <div class="icon">💰</div>
            <div class="number" style="color:#d4af37;font-size:1.4rem;">{{ number_format($tongDoanhThu/1000000, 1) }}M</div>
            <div class="label">Tổng doanh thu</div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="stat-card">
            <div class="icon">✅</div>
            <div class="number" style="color:#6fcf6f;">{{ $tongDonHoan }}</div>
            <div class="label">Đơn hoàn thành</div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="stat-card">
            <div class="icon">🚚</div>
            <div class="number" style="color:#7eb8f7;">{{ $tongDonDangGiao }}</div>
            <div class="label">Đang giao</div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="stat-card">
            <div class="icon">⏳</div>
            <div class="number" style="color:#f0c674;">{{ $tongDonCho }}</div>
            <div class="label">Chờ xử lý</div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="stat-card">
            <div class="icon">👥</div>
            <div class="number" style="color:#c792ea;">{{ $tongKhachHang }}</div>
            <div class="label">Khách hàng</div>
        </div>
    </div>
    <div class="col-md-2 col-6">
        <div class="stat-card">
            <div class="icon">🎽</div>
            <div class="number" style="color:#89ddff;">{{ $tongSanPham }}</div>
            <div class="label">Sản phẩm</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="chart-wrap">
            <h5><i class="bi bi-bar-chart-fill me-2"></i>Doanh Thu Theo Tháng — {{ $nam }}</h5>
            @php
                $arrDt = array_values($doanhThuThang);
                $maxVal = count($arrDt) > 0 ? max($arrDt) : 0;
                if ($maxVal <= 0) { $maxVal = 1; }
            @endphp
            <div class="bar-chart">
                @foreach($doanhThuThang as $thang => $dt)
                @php
                    $pct = intval(max(4, ($dt / $maxVal) * 160));
                    $label = $dt > 0 ? number_format($dt / 1000000, 1) . 'M' : '';
                @endphp
                <div class="bar-item">
                    <div class="bar-value">{{ $label }}</div>
                    <div class="bar" style="height:{{ $pct }}px;"></div>
                    <span class="bar-label">T{{ $thang }}</span>
                </div>
                @endforeach
            </div>
            @php
                $tongThang = array_sum($doanhThuThang);
            @endphp
            <div class="mt-3" style="text-align:center;color:#555;font-size:0.8rem;">
                Tổng doanh thu năm {{ $nam }}:
                <span style="color:#d4af37;font-weight:700;">{{ number_format($tongThang, 0, ',', '.') }} đ</span>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="top-sp-wrap">
            <h5><i class="bi bi-trophy-fill me-2"></i>Top Sản Phẩm Bán Chạy</h5>
            @forelse($topSanPham as $i => $sp)
            @php
                $rankClass = $i == 0 ? 'rank-1' : ($i == 1 ? 'rank-2' : ($i == 2 ? 'rank-3' : 'rank-other'));
            @endphp
            <div class="top-item">
                <div class="top-rank {{ $rankClass }}">{{ $i + 1 }}</div>
                @if($sp->hinh_anh)
                <img src="{{ asset('images/' . $sp->hinh_anh) }}" class="top-img" alt="">
                @else
                <div class="top-img d-flex align-items-center justify-content-center">
                    <i class="bi bi-image" style="color:#555;"></i>
                </div>
                @endif
                <div class="top-info">
                    <div class="name">{{ Str::limit($sp->ten_sp, 28) }}</div>
                    <div class="sold">Đã bán: {{ $sp->tong_ban }}</div>
                </div>
                <div class="top-revenue">{{ number_format($sp->tong_tien / 1000000, 1) }}M</div>
            </div>
            @empty
            <div style="text-align:center;color:#555;padding:30px 0;">
                <i class="bi bi-inbox" style="font-size:2rem;display:block;margin-bottom:8px;"></i>
                Chưa có dữ liệu
            </div>
            @endforelse
        </div>
    </div>
</div>

</div>
</div>
@endsection