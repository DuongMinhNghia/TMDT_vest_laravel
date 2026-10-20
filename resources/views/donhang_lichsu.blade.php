@extends('layouts.default_layout')

@section('title', 'Đơn Hàng Của Tôi - Kingsman Vietnam')

@section('content')
@php
    $mauTrangThai = [
        'Chờ xử lý'    => ['#f39c12', '⏳'],
        'Đang may/gói' => ['#3498db', '🧵'],
        'Đang giao'    => ['#9b59b6', '🚚'],
        'Hoàn Thành'   => ['#2ecc71', '✅'],
        'Đã hủy'       => ['#e74c3c', '❌'],
    ];
@endphp
<div class="container" style="padding:40px 0;">
    <h2 style="font-family:'Playfair Display',serif; color:#C5A059; font-weight:700; text-transform:uppercase; letter-spacing:2px; margin-bottom:30px;">
        <i class="bi bi-receipt"></i> Đơn Hàng Của Tôi
    </h2>

    @if(session('error'))
    <div class="alert" style="background:rgba(255,68,68,0.12); border:1px solid #ff4444; color:#ff6b6b; border-radius:10px;">{{ session('error') }}</div>
    @endif
    @if(session('success'))
    <div class="alert" style="background:rgba(197,160,89,0.15); border:1px solid #C5A059; color:#C5A059; border-radius:10px;">{{ session('success') }}</div>
    @endif

    @if($donHangs->isEmpty())
    <div style="text-align:center; padding:80px 20px; color:#777;">
        <i class="bi bi-inbox" style="font-size:4rem; display:block; margin-bottom:15px;"></i>
        <p>Bạn chưa có đơn hàng nào.</p>
        <a href="{{ route('sanpham.index') }}" style="color:#C5A059;">Bắt đầu mua sắm</a>
    </div>
    @else
    <div style="background:#1a1a1a; border:1px solid #333; border-radius:16px; overflow:hidden;">
        <table style="width:100%; color:#e0e0e0;">
            <thead>
                <tr style="background:linear-gradient(135deg,#C5A059,#a08045); color:#000;">
                    <th style="padding:14px 20px;">Mã đơn</th>
                    <th style="padding:14px 20px;">Ngày đặt</th>
                    <th style="padding:14px 20px;" class="text-center">Số món</th>
                    <th style="padding:14px 20px;" class="text-end">Tổng tiền</th>
                    <th style="padding:14px 20px;" class="text-center">Trạng thái</th>
                    <th style="padding:14px 20px;"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($donHangs as $dh)
                @php [$mau, $icon] = $mauTrangThai[$dh->trang_thai] ?? ['#aaa', '•']; @endphp
                <tr style="border-bottom:1px solid #2a2a2a;">
                    <td style="padding:14px 20px; color:#C5A059; font-weight:700;">{{ $dh->ma_don_hang }}</td>
                    <td style="padding:14px 20px;">{{ $dh->ngay_dat->format('d/m/Y H:i') }}</td>
                    <td style="padding:14px 20px;" class="text-center">{{ $dh->chi_tiets_count }}</td>
                    <td style="padding:14px 20px; color:#C5A059; font-weight:700;" class="text-end">{{ number_format($dh->tong_tien, 0, ',', '.') }} VNĐ</td>
                    <td style="padding:14px 20px;" class="text-center"><span style="color:{{ $mau }}; font-weight:600;">{{ $icon }} {{ $dh->trang_thai }}</span></td>
                    <td style="padding:14px 20px;" class="text-end">
                        <a href="{{ route('donhang.chitiet', $dh->id) }}" style="color:#C5A059; text-decoration:none; border:1px solid #C5A059; padding:6px 14px; border-radius:20px;">Xem chi tiết</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
