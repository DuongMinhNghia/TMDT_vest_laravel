@extends('layouts.default_layout')

@section('title', 'Chi Tiết Đơn Hàng ' . $donHang->ma_don_hang . ' - Kingsman Vietnam')

@section('content')
@php
    $cacBuoc = ['Chờ xử lý', 'Đang may/gói', 'Đang giao', 'Hoàn Thành'];
    $viTri   = array_search($donHang->trang_thai, $cacBuoc, true);
    $daHuy   = $donHang->trang_thai === 'Đã hủy';
    $laChuDon = Auth::user()->khachHang && Auth::user()->khachHang->id === $donHang->khach_hang_id;
@endphp
<style>
    .order-detail-wrap {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px 15px 60px;
    }

    .order-detail-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 15px;
        margin-bottom: 24px;
        padding-bottom: 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
    }

    .order-detail-title {
        font-family: 'Playfair Display', Georgia, serif;
        color: #D4B26F;
        font-size: 1.7rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-back-link {
        color: #94A3B8;
        text-decoration: none;
        font-size: 0.85rem;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: color 0.2s;
    }

    .btn-back-link:hover {
        color: #D4B26F;
    }

    /* Timeline Stepper */
    .timeline-card {
        background: #141720;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 12px;
        padding: 26px 20px;
        margin-bottom: 25px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
    }

    .stepper-wrap {
        display: flex;
        justify-content: space-between;
        position: relative;
    }

    .step-item {
        flex: 1;
        text-align: center;
        position: relative;
    }

    .step-icon {
        font-size: 1.6rem;
        margin-bottom: 6px;
        transition: color 0.3s;
    }

    .step-name {
        font-size: 0.82rem;
        font-weight: 600;
        letter-spacing: 0.5px;
    }

    /* Content Box */
    .box-card {
        background: #141720;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
    }

    .product-order-table {
        width: 100%;
        border-collapse: collapse;
        color: #F1F5F9;
        font-size: 0.86rem;
    }

    .product-order-table thead th {
        background: #181B24;
        color: #D4B26F;
        border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        padding: 13px 18px;
        font-size: 0.76rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
    }

    .product-order-table tbody td {
        padding: 15px 18px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        vertical-align: middle;
    }

    .summary-sidebar {
        padding: 24px;
        background: #141720;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 12px;
    }

    .btn-cancel-order {
        width: 100%;
        background: transparent;
        border: 1px solid rgba(244, 63, 94, 0.4);
        color: #f43f5e;
        border-radius: 8px;
        padding: 10px;
        font-weight: 700;
        font-size: 0.85rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-cancel-order:hover {
        background: #f43f5e;
        color: #fff;
    }
</style>

<div class="order-detail-wrap">
    <div class="order-detail-header">
        <h2 class="order-detail-title">
            <i class="bi bi-receipt"></i> Đơn Hàng #{{ $donHang->ma_don_hang }}
        </h2>
        <a href="{{ $laNhanSu && !$laChuDon ? route('thongke.donhang') : route('donhang.lichsu') }}" class="btn-back-link">
            <i class="bi bi-arrow-left"></i> Quay lại danh sách đơn
        </a>
    </div>

    @if(session('error'))
    <div class="alert" style="background:rgba(244,63,94,0.12); border:1px solid rgba(244,63,94,0.3); color:#f43f5e; border-radius:8px; margin-bottom:20px;">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ session('error') }}
    </div>
    @endif
    @if(session('success'))
    <div class="alert" style="background:rgba(34,197,94,0.12); border:1px solid rgba(34,197,94,0.3); color:#22c55e; border-radius:8px; margin-bottom:20px;">
        <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
    </div>
    @endif

    {{-- TIẾN TRÌNH ĐƠN HÀNG --}}
    <div class="timeline-card">
        @if($daHuy)
            <div style="color:#f43f5e; font-weight:700; text-align:center; font-size:1.05rem;">
                <i class="bi bi-x-circle-fill me-2"></i> Đơn hàng này đã bị hủy bỏ
            </div>
        @else
            <div class="stepper-wrap">
                @foreach($cacBuoc as $i => $buoc)
                @php
                    $isDone = ($viTri !== false && $i <= $viTri);
                    $color = $isDone ? '#D4B26F' : '#64748B';
                @endphp
                <div class="step-item" style="color:{{ $color }};">
                    <div class="step-icon">
                        <i class="bi {{ $isDone ? 'bi-check-circle-fill' : 'bi-circle' }}"></i>
                    </div>
                    <div class="step-name">{{ $buoc }}</div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    <div class="row g-4">
        {{-- DANH SÁCH SẢN PHẨM TRONG ĐƠN --}}
        <div class="col-lg-8">
            <div class="box-card">
                <table class="product-order-table">
                    <thead>
                        <tr>
                            <th>Sản Phẩm</th>
                            <th class="text-center">Đơn Giá</th>
                            <th class="text-center">SL</th>
                            <th class="text-end">Thành Tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($donHang->chiTiets as $ct)
                        @php
                            $tenSanPham = $ct->ten_san_pham ?? $ct->sanPham?->ten_sp ?? 'Sản phẩm đã ngừng kinh doanh';
                            $tenSize = $ct->ten_size ?? $ct->size?->ten_size ?? 'N/A';
                            $hinhAnh = $ct->hinh_anh_san_pham ?? $ct->sanPham?->hinh_anh;
                            $conBan = $ct->sanPham && !$ct->sanPham->trashed();
                        @endphp
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    @if($hinhAnh)
                                    <img src="{{ asset('images/' . $hinhAnh) }}" style="width:55px; height:55px; object-fit:cover; border-radius:6px; border:1px solid rgba(255,255,255,0.08);">
                                    @else
                                    <div style="width:55px; height:55px; display:grid; place-items:center; border-radius:6px; border:1px solid rgba(255,255,255,0.08); color:#94A3B8;"><i class="bi bi-image"></i></div>
                                    @endif
                                    <div>
                                        @if($conBan)
                                        <a href="{{ route('sanpham.chitiet', $ct->san_pham_id) }}" style="color:#F1F5F9; text-decoration:none; font-weight:600;">{{ $tenSanPham }}</a>
                                        @else
                                        <span style="color:#F1F5F9; font-weight:600;">{{ $tenSanPham }}</span>
                                        @endif
                                        <div style="color:#D4B26F; font-size:0.78rem;">Size {{ $tenSize }}</div>
                                        @if($laChuDon && $conBan && $donHang->trang_thai === 'Hoàn Thành')
                                        <a href="{{ route('sanpham.chitiet', $ct->san_pham_id) }}#danh-gia" style="color:#94A3B8; font-size:0.75rem; text-decoration:none;"><i class="bi bi-star"></i> Viết đánh giá</a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td style="padding:14px 18px;" class="text-center text-muted">{{ number_format($ct->don_gia, 0, ',', '.') }} VNĐ</td>
                            <td style="padding:14px 18px;" class="text-center font-weight-bold">{{ $ct->so_luong }}</td>
                            <td style="padding:14px 18px; color:#D4B26F; font-weight:700;" class="text-end">{{ number_format($ct->thanh_tien, 0, ',', '.') }} VNĐ</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- THÔNG TIN VẬN CHUYỂN & THANH TOÁN --}}
        <div class="col-lg-4">
            <div class="summary-sidebar">
                <div style="color:#94A3B8; font-size:0.86rem; line-height:2;">
                    <div>Ngày đặt: <strong style="color:#F1F5F9;">{{ $donHang->ngay_dat->format('d/m/Y H:i') }}</strong></div>
                    <div>Thanh toán: <strong style="color:#F1F5F9;">{{ $donHang->phuong_thuc_thanh_toan }}</strong></div>
                    <div>Giao đến: <span style="color:#F1F5F9;">{{ $donHang->dia_chi_giao_hang }}</span></div>
                    @if($donHang->ghi_chu)<div>Ghi chú: <span style="color:#CBD5E1;">"{{ $donHang->ghi_chu }}"</span></div>@endif
                    @if($laNhanSu)
                    <div>Khách hàng: <strong style="color:#F1F5F9;">{{ $donHang->khachHang->ho_ten ?? 'N/A' }}</strong></div>
                    <div>Nhân viên duyệt: <span style="color:#38bdf8;">{{ $donHang->nhanVienDuyet->ho_ten ?? 'Chưa phân công' }}</span></div>
                    @endif
                </div>

                <hr style="border-color:rgba(255,255,255,0.08); margin:16px 0;">

                <div class="d-flex justify-content-between mb-1" style="font-size:0.86rem;"><span style="color:#94A3B8;">Tạm tính:</span><span>{{ number_format($tamTinh, 0, ',', '.') }} VNĐ</span></div>
                @if($giamHang > 0)
                <div class="d-flex justify-content-between mb-1" style="font-size:0.86rem;"><span style="color:#94A3B8;">Ưu đãi hạng thành viên (VIP):</span><span style="color:#22c55e;">- {{ number_format($giamHang, 0, ',', '.') }} VNĐ</span></div>
                @endif
                <div class="d-flex justify-content-between mb-2" style="font-size:0.86rem;"><span style="color:#94A3B8;">Phí vận chuyển:</span><span>{{ number_format($phiShip, 0, ',', '.') }} VNĐ</span></div>
                
                <div class="d-flex justify-content-between pt-2" style="border-top:1px solid rgba(212,178,111,0.3); color:#D4B26F; font-weight:700; font-size:1.15rem;">
                    <span>TỔNG CỘNG:</span><span>{{ number_format($donHang->tong_tien, 0, ',', '.') }} VNĐ</span>
                </div>

                @if($coTheHuy)
                <form method="POST" action="{{ route('donhang.huy', $donHang->id) }}" onsubmit="return confirm('Bạn chắc chắn muốn hủy đơn hàng này?')" class="mt-4">
                    @csrf
                    <button type="submit" class="btn-cancel-order">
                        <i class="bi bi-x-circle me-1"></i> Hủy đơn hàng này
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
