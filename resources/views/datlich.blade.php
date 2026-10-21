@extends('layouts.default_layout')

@section('title', 'Đặt Lịch Hẹn Tại Showroom - Kingsman Vietnam')

@section('content')
<style>
    .booking-wrap {
        background: linear-gradient(180deg, #0a0a0a 0%, #000 100%);
        min-height: 100vh;
        padding: 50px 0 80px;
        color: #f0f0f0;
    }

    .booking-card {
        background: #141414;
        border: 1px solid #d4af37;
        border-radius: 20px;
        padding: 35px 40px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.7);
    }

    .booking-header h2 {
        font-family: 'Playfair Display', serif;
        color: #d4af37;
        font-size: 2rem;
        font-weight: 700;
        letter-spacing: 1px;
    }

    .form-control-dark, .form-select-dark {
        background: #0d0d0d !important;
        border: 1px solid #333 !important;
        color: #fff !important;
        border-radius: 8px !important;
        padding: 12px 16px;
    }

    .form-control-dark:focus, .form-select-dark:focus {
        border-color: #d4af37 !important;
        box-shadow: 0 0 0 2px rgba(212, 175, 55, 0.25) !important;
    }

    .btn-gold {
        background: linear-gradient(135deg, #d4af37 0%, #aa8210 100%);
        color: #000;
        font-weight: 700;
        border: none;
        padding: 14px 28px;
        border-radius: 10px;
        font-size: 1rem;
        letter-spacing: 1px;
        transition: all 0.3s ease;
    }

    .btn-gold:hover {
        background: linear-gradient(135deg, #e6c555 0%, #c49918 100%);
        color: #000;
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(212, 175, 55, 0.35);
    }

    .showroom-perk {
        display: flex;
        align-items: flex-start;
        gap: 15px;
        margin-bottom: 20px;
    }

    .showroom-perk-icon {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background: rgba(212, 175, 55, 0.15);
        color: #d4af37;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        flex-shrink: 0;
    }

    .vest-preview-card {
        background: #1c1a16;
        border: 1px solid #d4af37;
        border-radius: 12px;
        padding: 16px;
        display: flex;
        align-items: center;
        gap: 16px;
        margin-bottom: 25px;
    }
</style>

<div class="booking-wrap">
    <div class="container">

        {{-- BÁO LỖI NẾU CÓ --}}
        @if($errors->any())
        <div class="alert alert-danger" style="background:#3a1010; border:1px solid #751a1a; color:#ff8e8e;">
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="row g-4 align-items-stretch">

            {{-- CỘT TRÁI: FORM ĐẶT LỊCH --}}
            <div class="col-lg-7">
                <div class="booking-card h-100">
                    <div class="booking-header mb-4">
                        <h2><i class="bi bi-calendar2-heart-fill me-2"></i>Đặt Lịch Trải Nghiệm</h2>
                        <p class="text-muted mb-0">Hân hạnh đón tiếp Quý khách tại showroom may đo cao cấp Kingsman Bespoke.</p>
                    </div>

                    {{-- NẾU CÓ SẢN PHẨM GẮN TỪ TRANG CHI TIẾT --}}
                    @if($sanPham)
                    <div class="vest-preview-card">
                        <img src="{{ asset('images/' . $sanPham->hinh_anh) }}" alt="{{ $sanPham->ten_sp }}" style="width: 75px; height: 90px; object-fit: cover; border-radius: 8px; border: 1px solid #555;">
                        <div class="flex-grow-1">
                            <span class="badge bg-warning text-dark mb-1" style="font-size: 0.72rem;">Mẫu vest bạn chọn thử</span>
                            <h6 class="text-white mb-1" style="font-size: 1.05rem;">{{ $sanPham->ten_sp }}</h6>
                            <div class="text-muted" style="font-size: 0.8rem;">Mã SP: {{ $sanPham->ma_sp }} | Chất liệu: {{ $sanPham->chat_lieu ?? 'Cao cấp' }}</div>
                            <div class="text-warning font-weight-bold mt-1">{{ number_format($sanPham->gia_khuyen_mai) }} VNĐ</div>
                        </div>
                    </div>
                    @endif

                    <form action="{{ route('lichhen.store') }}" method="POST">
                        @csrf
                        @if($sanPham)
                            <input type="hidden" name="san_pham_quan_tam_id" value="{{ $sanPham->id }}">
                        @endif

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label text-muted" style="font-size:0.85rem;">Họ và tên Quý khách *</label>
                                <input type="text" class="form-control form-control-dark" value="{{ $khachHang->ho_ten ?? Auth::user()->name }}" disabled>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-muted" style="font-size:0.85rem;">Số điện thoại liên hệ *</label>
                                <input type="text" name="so_dien_thoai" class="form-control form-control-dark" value="{{ old('so_dien_thoai', $khachHang->so_dien_thoai) }}" placeholder="VD: 0987654321" required>
                            </div>

                            <div class="col-md-7">
                                <label class="form-label text-muted" style="font-size:0.85rem;">Thời gian mong muốn đến tiệm *</label>
                                <input type="datetime-local" name="thoi_gian_hen" class="form-control form-control-dark" min="{{ date('Y-m-d\TH:i', strtotime('+1 hour')) }}" value="{{ old('thoi_gian_hen') }}" required>
                                <small class="text-muted" style="font-size: 0.75rem;">Giờ mở cửa: 08:30 - 21:00 các ngày trong tuần</small>
                            </div>

                            <div class="col-md-5">
                                <label class="form-label text-muted" style="font-size:0.85rem;">Số người đi cùng</label>
                                <select name="so_nguoi_di_cung" class="form-select form-select-dark">
                                    <option value="1" {{ old('so_nguoi_di_cung') == 1 ? 'selected' : '' }}>1 người (Chỉ mình tôi)</option>
                                    <option value="2" {{ old('so_nguoi_di_cung') == 2 ? 'selected' : '' }}>2 người (Đi cùng bạn/vợ)</option>
                                    <option value="3" {{ old('so_nguoi_di_cung') == 3 ? 'selected' : '' }}>3 người</option>
                                    <option value="4" {{ old('so_nguoi_di_cung') == 4 ? 'selected' : '' }}>4+ người (Gia đình/Đoàn rể)</option>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label text-muted" style="font-size:0.85rem;">Nhu cầu / Loại dịch vụ *</label>
                                <select name="loai_dich_vu" class="form-select form-select-dark" required>
                                    <option value="Thử vest có sẵn tại tiệm" {{ ($sanPham || old('loai_dich_vu') == 'Thử vest có sẵn tại tiệm') ? 'selected' : '' }}>
                                        Thử mẫu áo vest có sẵn tại showroom
                                    </option>
                                    <option value="May đo vest cưới Bespoke (Chú rể)" {{ old('loai_dich_vu') == 'May đo vest cưới Bespoke (Chú rể)' ? 'selected' : '' }}>
                                        May đo trọn bộ vest cưới Bespoke (Tuxedo & Suit chú rể)
                                    </option>
                                    <option value="May đo vest doanh nhân cao cấp" {{ old('loai_dich_vu') == 'May đo vest doanh nhân cao cấp' ? 'selected' : '' }}>
                                        May đo vest doanh nhân / dạ tiệc cao cấp
                                    </option>
                                    <option value="Chỉnh sửa vest theo số đo riêng" {{ old('loai_dich_vu') == 'Chỉnh sửa vest theo số đo riêng' ? 'selected' : '' }}>
                                        Chỉnh sửa bóp/nới phom vest theo số đo riêng
                                    </option>
                                    <option value="Tư vấn phong cách & Chọn vải may" {{ old('loai_dich_vu') == 'Tư vấn phong cách & Chọn vải may' ? 'selected' : '' }}>
                                        Tư vấn phong cách và chọn vải may nhập khẩu
                                    </option>
                                </select>
                            </div>

                            @if(!$sanPham)
                            <div class="col-12">
                                <label class="form-label text-muted" style="font-size:0.85rem;">Mẫu vest muốn chuẩn bị trước (Tùy chọn)</label>
                                <select name="san_pham_quan_tam_id" class="form-select form-select-dark">
                                    <option value="">-- Quý khách có thể chọn trước hoặc để trống --</option>
                                    @foreach($danhSachSanPham as $sp)
                                    <option value="{{ $sp->id }}" {{ old('san_pham_quan_tam_id') == $sp->id ? 'selected' : '' }}>
                                        {{ $sp->ten_sp }} ({{ number_format($sp->gia_ban) }} VNĐ)
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            <div class="col-12">
                                <label class="form-label text-muted" style="font-size:0.85rem;">Yêu cầu đặc biệt cho chuyên viên Kingsman</label>
                                <textarea name="ghi_chu" rows="3" class="form-control form-control-dark" placeholder="VD: Muốn thử form slim-fit màu xanh navy, cần phòng thử riêng VIP...">{{ old('ghi_chu') }}</textarea>
                            </div>

                            <div class="col-12 mt-4 text-center">
                                <button type="submit" class="btn btn-gold w-100">
                                    <i class="bi bi-calendar-check-fill me-2"></i> Xác Nhận Đặt Lịch Hẹn
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- CỘT PHẢI: ĐẶC QUYỀN SHOWROOM KINGSMAN --}}
            <div class="col-lg-5">
                <div class="booking-card h-100" style="border-color: #333;">
                    <h4 class="text-warning mb-4" style="font-family:'Playfair Display',serif; font-size: 1.4rem;">
                        <i class="bi bi-gem me-2"></i>Đặc Quyền Tiếp Đón VIP
                    </h4>

                    <div class="showroom-perk">
                        <div class="showroom-perk-icon"><i class="bi bi-shield-shaded"></i></div>
                        <div>
                            <strong class="text-white d-block">Phòng Thử Đồ VIP Riêng Tư</strong>
                            <small class="text-muted">Không gian may đo sang trọng, kín đáo đạt chuẩn quốc tế dành riêng cho quý ông và người thân.</small>
                        </div>
                    </div>

                    <div class="showroom-perk">
                        <div class="showroom-perk-icon"><i class="bi bi-person-check-fill"></i></div>
                        <div>
                            <strong class="text-white d-block">Master Tailor Đo Trực Tiếp</strong>
                            <small class="text-muted">Chuyên viên may đo với hơn 10 năm kinh nghiệm lấy trọn vẹn 10 chỉ số cơ thể, cân chỉnh form xuôi vai, gù lưng.</small>
                        </div>
                    </div>

                    <div class="showroom-perk">
                        <div class="showroom-perk-icon"><i class="bi bi-cup-hot-fill"></i></div>
                        <div>
                            <strong class="text-white d-block">Thưởng Thức Trà & Whisky</strong>
                            <small class="text-muted">Miễn phí trà hảo hạng và đồ uống thượng hạng trong suốt buổi thử đồ và tư vấn phong cách.</small>
                        </div>
                    </div>

                    <div class="showroom-perk">
                        <div class="showroom-perk-icon"><i class="bi bi-geo-alt-fill"></i></div>
                        <div>
                            <strong class="text-white d-block">Địa Điểm Showroom</strong>
                            <small class="text-muted">Số 123 Đường Lê Lợi, Phường Bến Nghé, Quận 1, TP. Hồ Chí Minh.</small>
                            <div class="text-warning mt-1" style="font-size:0.8rem;"><i class="bi bi-telephone-fill me-1"></i> Hotline: 1900 8888 (Phím 1)</div>
                        </div>
                    </div>

                    <div class="p-3 rounded mt-4 text-center" style="background:#1a1a1a; border:1px solid #333;">
                        <div style="color:#d4af37; font-weight:700; font-size:0.9rem;">
                            <i class="bi bi-clock-history me-1"></i> Chính Sách Hủy / Dời Lịch
                        </div>
                        <small class="text-muted d-block mt-1">
                            Quý khách có thể tự do hủy hoặc thay đổi giờ hẹn trước 2 tiếng trực tiếp tại trang <strong>Hồ sơ & Thẻ VIP</strong>.
                        </small>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
@endsection

