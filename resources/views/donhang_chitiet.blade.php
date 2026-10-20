@extends('layouts.default_layout')

@section('title', 'Đơn ' . $donHang->ma_don_hang . ' - Kingsman Vietnam')

@section('content')
@php
    $cacBuoc = ['Chờ xử lý', 'Đang may/gói', 'Đang giao', 'Hoàn Thành'];
    $viTri   = array_search($donHang->trang_thai, $cacBuoc, true);
    $daHuy   = $donHang->trang_thai === 'Đã hủy';
    $laChuDon = Auth::user()->khachHang && Auth::user()->khachHang->id === $donHang->khach_hang_id;
@endphp
<div class="container" style="padding:40px 0; color:#e0e0e0;">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
        <h2 style="font-family:'Playfair Display',serif; color:#C5A059; font-weight:700; margin:0;">
            <i class="bi bi-receipt"></i> Đơn hàng {{ $donHang->ma_don_hang }}
        </h2>
        <a href="{{ $laNhanSu && !$laChuDon ? route('thongke.donhang') : route('donhang.lichsu') }}" style="color:#C5A059; text-decoration:none;">
            <i class="bi bi-arrow-left"></i> Quay lại danh sách
        </a>
    </div>

    @if(session('error'))
    <div class="alert" style="background:rgba(255,68,68,0.12); border:1px solid #ff4444; color:#ff6b6b; border-radius:10px;">{{ session('error') }}</div>
    @endif
    @if(session('success'))
    <div class="alert" style="background:rgba(197,160,89,0.15); border:1px solid #C5A059; color:#C5A059; border-radius:10px;">{{ session('success') }}</div>
    @endif

    {{-- Tiến trình đơn hàng --}}
    <div style="background:#1a1a1a; border:1px solid #333; border-radius:16px; padding:25px; margin-bottom:25px;">
        @if($daHuy)
            <div style="color:#e74c3c; font-weight:700; text-align:center;"><i class="bi bi-x-circle-fill"></i> Đơn hàng đã bị hủy</div>
        @else
        <div class="d-flex justify-content-between text-center">
            @foreach($cacBuoc as $i => $buoc)
            <div style="flex:1; color:{{ $i <= $viTri ? '#C5A059' : '#555' }};">
                <div style="font-size:1.6rem;">
                    <i class="bi {{ $i <= $viTri ? 'bi-check-circle-fill' : 'bi-circle' }}"></i>
                </div>
                <div style="font-size:0.85rem; font-weight:600;">{{ $buoc }}</div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    <div class="row g-4">
        <div class="col-lg-8">
            <div style="background:#1a1a1a; border:1px solid #333; border-radius:16px; overflow:hidden;">
                <table style="width:100%; color:#e0e0e0;">
                    <thead>
                        <tr style="background:linear-gradient(135deg,#C5A059,#a08045); color:#000;">
                            <th style="padding:14px 20px;">Sản phẩm</th>
                            <th style="padding:14px 20px;" class="text-center">Đơn giá</th>
                            <th style="padding:14px 20px;" class="text-center">SL</th>
                            <th style="padding:14px 20px;" class="text-end">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($donHang->chiTiets as $ct)
                        <tr style="border-bottom:1px solid #2a2a2a;">
                            <td style="padding:14px 20px;">
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ asset('images/' . $ct->sanPham->hinh_anh) }}" style="width:60px; height:60px; object-fit:cover; border-radius:8px;">
                                    <div>
                                        <a href="{{ route('sanpham.chitiet', $ct->san_pham_id) }}" style="color:#e0e0e0; text-decoration:none; font-weight:600;">{{ $ct->sanPham->ten_sp }}</a>
                                        <div style="color:#C5A059; font-size:0.8rem;">Size {{ $ct->size->ten_size }}</div>
                                        @if($laChuDon && $donHang->trang_thai === 'Hoàn Thành')
                                        <a href="{{ route('sanpham.chitiet', $ct->san_pham_id) }}#danh-gia" style="color:#888; font-size:0.8rem;"><i class="bi bi-star"></i> Viết đánh giá</a>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td style="padding:14px 20px;" class="text-center">{{ number_format($ct->don_gia, 0, ',', '.') }}</td>
                            <td style="padding:14px 20px;" class="text-center">{{ $ct->so_luong }}</td>
                            <td style="padding:14px 20px; color:#C5A059; font-weight:700;" class="text-end">{{ number_format($ct->thanh_tien, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-lg-4">
            <div style="background:#1a1a1a; border:1px solid #333; border-radius:16px; padding:25px;">
                <div style="color:#aaa; font-size:0.9rem; line-height:1.9;">
                    <div>Ngày đặt: <span style="color:#e0e0e0;">{{ $donHang->ngay_dat->format('d/m/Y H:i') }}</span></div>
                    <div>Thanh toán: <span style="color:#e0e0e0;">{{ $donHang->phuong_thuc_thanh_toan }}</span></div>
                    <div>Giao đến: <span style="color:#e0e0e0;">{{ $donHang->dia_chi_giao_hang }}</span></div>
                    @if($donHang->ghi_chu)<div>Ghi chú: <span style="color:#e0e0e0;">{{ $donHang->ghi_chu }}</span></div>@endif
                    @if($laNhanSu)
                    <div>Khách hàng: <span style="color:#e0e0e0;">{{ $donHang->khachHang->ho_ten ?? 'N/A' }}</span></div>
                    <div>Nhân viên duyệt: <span style="color:#e0e0e0;">{{ $donHang->nhanVienDuyet->ho_ten ?? 'Chưa phân công' }}</span></div>
                    @endif
                </div>
                <hr style="border-color:#333;">
                <div class="d-flex justify-content-between"><span style="color:#aaa;">Tạm tính:</span><span>{{ number_format($tamTinh, 0, ',', '.') }} VNĐ</span></div>
                @if($giamHang > 0)
                <div class="d-flex justify-content-between"><span style="color:#aaa;">Ưu đãi hạng thành viên:</span><span style="color:#2ecc71;">- {{ number_format($giamHang, 0, ',', '.') }} VNĐ</span></div>
                @endif
                <div class="d-flex justify-content-between"><span style="color:#aaa;">Phí vận chuyển:</span><span>{{ number_format($phiShip, 0, ',', '.') }} VNĐ</span></div>
                <div class="d-flex justify-content-between mt-3 pt-3" style="border-top:2px solid #C5A059; color:#C5A059; font-weight:700; font-size:1.15rem;">
                    <span>TỔNG:</span><span>{{ number_format($donHang->tong_tien, 0, ',', '.') }} VNĐ</span>
                </div>

                @if($coTheHuy)
                <form method="POST" action="{{ route('donhang.huy', $donHang->id) }}" onsubmit="return confirm('Bạn chắc chắn muốn hủy đơn hàng này?')" class="mt-4">
                    @csrf
                    <button type="submit" style="width:100%; background:transparent; border:2px solid #ff4444; color:#ff4444; border-radius:50px; padding:10px; font-weight:700;">
                        <i class="bi bi-x-circle"></i> Hủy đơn hàng
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
