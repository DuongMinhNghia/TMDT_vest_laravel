@extends('layouts.default_layout')

@section('title', 'Đơn Hàng Của Tôi - Kingsman Vietnam')

@section('content')
@php
    $mauTrangThai = [
        'Chờ xử lý'    => ['rgba(234, 179, 8, 0.12)', '#eab308', 'rgba(234, 179, 8, 0.3)'],
        'Đang may/gói' => ['rgba(168, 85, 247, 0.12)', '#a855f7', 'rgba(168, 85, 247, 0.3)'],
        'Đang giao'    => ['rgba(56, 189, 248, 0.12)', '#38bdf8', 'rgba(56, 189, 248, 0.3)'],
        'Hoàn Thành'   => ['rgba(34, 197, 94, 0.12)', '#22c55e', 'rgba(34, 197, 94, 0.3)'],
        'Đã hủy'       => ['rgba(244, 63, 94, 0.12)', '#f43f5e', 'rgba(244, 63, 94, 0.3)'],
    ];
@endphp
<style>
    .order-history-wrap {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px 15px 60px;
    }

    .order-history-title {
        font-family: 'Playfair Display', Georgia, serif;
        color: #D4B26F;
        font-size: 1.8rem;
        font-weight: 700;
        letter-spacing: 1px;
        margin-bottom: 24px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .order-table-card {
        background: #141720;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.4);
    }

    .order-table {
        width: 100%;
        border-collapse: collapse;
        color: #F1F5F9;
        font-size: 0.88rem;
    }

    .order-table thead th {
        background: #181B24;
        color: #D4B26F;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        padding: 14px 20px;
        font-size: 0.78rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    .order-table tbody tr {
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        transition: background 0.15s;
    }

    .order-table tbody tr:hover {
        background: rgba(212, 178, 111, 0.06);
    }

    .order-table tbody td {
        padding: 16px 20px;
        vertical-align: middle;
    }

    .order-status-pill {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-size: 0.76rem;
        font-weight: 600;
        padding: 4px 12px;
        border-radius: 20px;
    }

    .btn-view-order {
        color: #D4B26F;
        text-decoration: none;
        border: 1px solid rgba(212, 178, 111, 0.35);
        background: rgba(212, 178, 111, 0.1);
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 0.8rem;
        font-weight: 600;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .btn-view-order:hover {
        background: #D4B26F;
        color: #000;
    }
    @media (max-width: 768px) {
        .order-history-wrap {
            padding: 15px 10px 45px;
        }
        .order-history-title {
            font-size: 1.35rem;
            margin-bottom: 16px;
        }
        .order-table-card {
            border-radius: 10px;
        }
        .order-table thead {
            display: none;
        }
        .order-table, .order-table tbody, .order-table tr, .order-table td {
            display: block;
            width: 100%;
        }
        .order-table tbody tr {
            padding: 14px 16px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .order-table tbody td {
            padding: 6px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
            text-align: right !important;
            border: none;
        }
        .order-table tbody td::before {
            content: attr(data-label);
            color: #94A3B8;
            font-size: 0.74rem;
            text-transform: uppercase;
            font-weight: 600;
            text-align: left;
        }
        .btn-view-order {
            width: 100%;
            justify-content: center;
            margin-top: 6px;
            padding: 8px 14px;
        }
    }
</style>

<div class="order-history-wrap">
    <h2 class="order-history-title">
        <i class="bi bi-receipt"></i> Lịch Sử Đơn Hàng Của Tôi
    </h2>

    @if($donHangs->isEmpty())
    <div style="text-align:center; padding:80px 20px; background:#141720; border:1px dashed rgba(255,255,255,0.1); border-radius:12px; color:#94A3B8;">
        <i class="bi bi-inbox" style="font-size:3.5rem; display:block; margin-bottom:15px; color:#64748B;"></i>
        <h4 style="color:#F1F5F9; font-family:'Playfair Display',serif;">Bạn chưa có đơn hàng nào</h4>
        <p style="font-size:0.9rem; color:#94A3B8;">Khám phá bộ sưu tập âu phục may đo cao cấp của Kingsman và đặt hàng ngay.</p>
        <a href="{{ route('sanpham.index') }}" class="btn-view-order" style="padding:10px 22px; font-size:0.88rem; margin-top:8px;">
            <i class="bi bi-bag"></i> Bắt đầu mua sắm
        </a>
    </div>
    @else
    <div class="order-table-card">
        <table class="order-table">
            <thead>
                <tr>
                    <th>Mã Đơn Hàng</th>
                    <th>Ngày Đặt</th>
                    <th class="text-center">Số Mẫu</th>
                    <th class="text-end">Tổng Giá Trị</th>
                    <th class="text-center">Trạng Thái Đơn</th>
                    <th class="text-end">Chi Tiết</th>
                </tr>
            </thead>
            <tbody>
                @foreach($donHangs as $dh)
                @php [$bg, $color, $border] = $mauTrangThai[$dh->trang_thai] ?? ['rgba(255,255,255,0.05)', '#94A3B8', 'rgba(255,255,255,0.1)']; @endphp
                <tr>
                    <td data-label="Mã Đơn Hàng">
                        <strong style="color:#D4B26F;">{{ $dh->ma_don_hang }}</strong>
                    </td>
                    <td data-label="Ngày Đặt" style="color:#94A3B8;">{{ $dh->ngay_dat->format('d/m/Y H:i') }}</td>
                    <td data-label="Số Lượng Mẫu" class="text-center" style="color:#CBD5E1;">{{ $dh->chi_tiets_count }} mẫu</td>
                    <td data-label="Tổng Giá Trị" style="color:#D4B26F; font-weight:700;" class="text-end">{{ number_format($dh->tong_tien, 0, ',', '.') }} VNĐ</td>
                    <td data-label="Trạng Thái" class="text-center">
                        <span class="order-status-pill" style="background:{{ $bg }}; color:{{ $color }}; border:1px solid {{ $border }};">
                            {{ $dh->trang_thai }}
                        </span>
                    </td>
                    <td data-label="" class="text-end">
                        <a href="{{ route('donhang.chitiet', $dh->id) }}" class="btn-view-order">
                            <i class="bi bi-eye"></i> Xem Chi Tiết
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
