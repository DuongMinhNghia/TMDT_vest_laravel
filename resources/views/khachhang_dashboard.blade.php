@extends('layouts.default_layout')

@section('title', 'Trung Tâm Khách Hàng & Thẻ VIP - Kingsman Vietnam')

@section('content')
<style>
    .dashboard-wrap {
        background: linear-gradient(180deg, #0b0b0b 0%, #000 100%);
        min-height: 100vh;
        padding: 40px 0 60px;
        color: #f0f0f0;
    }

    .section-title-gold {
        color: #d4af37;
        font-family: 'Playfair Display', serif;
        font-weight: 700;
        letter-spacing: 1px;
    }

    /* VIP CARD */
    .vip-card-container {
        perspective: 1000px;
        margin-bottom: 30px;
    }

    .vip-card {
        background: linear-gradient(135deg, #1c1a17 0%, #2a2415 50%, #15130f 100%);
        border: 2px solid #d4af37;
        border-radius: 20px;
        padding: 28px 32px;
        position: relative;
        overflow: hidden;
        box-shadow: 0 15px 35px rgba(212, 175, 55, 0.25);
        transition: transform 0.4s ease;
    }

    .vip-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 45px rgba(212, 175, 55, 0.35);
    }

    .vip-card::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 100%;
        height: 100%;
        background: radial-gradient(circle, rgba(212, 175, 55, 0.15) 0%, transparent 70%);
        pointer-events: none;
    }

    .vip-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .vip-card-brand {
        font-family: 'Playfair Display', serif;
        font-size: 1.4rem;
        letter-spacing: 3px;
        color: #d4af37;
        font-weight: 700;
        text-transform: uppercase;
    }

    .vip-tier-badge {
        padding: 6px 16px;
        border-radius: 30px;
        font-weight: 700;
        font-size: 0.85rem;
        letter-spacing: 1px;
        text-transform: uppercase;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.4);
    }

    .vip-card-chip {
        width: 48px;
        height: 36px;
        background: linear-gradient(135deg, #d4af37 0%, #aa8210 100%);
        border-radius: 6px;
        margin-bottom: 20px;
        position: relative;
    }

    .vip-card-chip::after {
        content: '';
        position: absolute;
        top: 50%;
        left: 0;
        right: 0;
        height: 1px;
        background: rgba(0,0,0,0.3);
    }

    .vip-card-number {
        font-family: 'Courier New', monospace;
        font-size: 1.35rem;
        letter-spacing: 4px;
        color: #fff;
        margin-bottom: 15px;
        text-shadow: 0 2px 4px rgba(0,0,0,0.8);
    }

    .vip-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: flex-end;
        border-top: 1px solid rgba(212, 175, 55, 0.25);
        padding-top: 15px;
        margin-top: 15px;
    }

    .vip-info-label {
        color: #888;
        font-size: 0.72rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 2px;
    }

    .vip-info-val {
        color: #e0e0e0;
        font-weight: 600;
        font-size: 0.95rem;
    }

    /* TIẾN TRÌNH */
    .progress-box {
        background: #141414;
        border: 1px solid #282828;
        border-radius: 14px;
        padding: 20px;
        margin-bottom: 30px;
    }

    .progress-custom {
        height: 10px;
        background-color: #222;
        border-radius: 6px;
        overflow: hidden;
        margin: 12px 0 8px;
    }

    .progress-bar-gold {
        background: linear-gradient(90deg, #aa8210 0%, #d4af37 100%);
        transition: width 0.6s ease;
    }

    /* NAV TABS CUSTOM */
    .nav-tabs-luxury {
        border-bottom: 2px solid #262626;
        gap: 10px;
        margin-bottom: 25px;
    }

    .nav-tabs-luxury .nav-link {
        color: #888;
        background: transparent;
        border: none;
        padding: 12px 24px;
        font-weight: 600;
        font-size: 0.95rem;
        border-radius: 8px 8px 0 0;
        transition: all 0.25s ease;
    }

    .nav-tabs-luxury .nav-link:hover {
        color: #d4af37;
    }

    .nav-tabs-luxury .nav-link.active {
        color: #d4af37;
        background: #1a1a1a;
        border-bottom: 2px solid #d4af37;
    }

    /* CARD TỔNG QUAN */
    .card-luxury {
        background: #141414;
        border: 1px solid #282828;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 8px 24px rgba(0,0,0,0.4);
    }

    .form-control-dark, .form-select-dark {
        background: #0d0d0d !important;
        border: 1px solid #333 !important;
        color: #f0f0f0 !important;
        border-radius: 8px !important;
        padding: 10px 14px;
    }

    .form-control-dark:focus, .form-select-dark:focus {
        border-color: #d4af37 !important;
        box-shadow: 0 0 0 2px rgba(212, 175, 55, 0.2) !important;
    }

    .btn-gold {
        background: linear-gradient(135deg, #d4af37 0%, #aa8210 100%);
        color: #000;
        font-weight: 700;
        border: none;
        padding: 10px 22px;
        border-radius: 8px;
        transition: all 0.25s ease;
    }

    .btn-gold:hover {
        background: linear-gradient(135deg, #e6c555 0%, #c49918 100%);
        color: #000;
        transform: translateY(-2px);
        box-shadow: 0 6px 18px rgba(212, 175, 55, 0.3);
    }

    /* SỐ ĐO ITEM */
    .sodo-card {
        background: #1a1a1a;
        border: 1px solid #333;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 18px;
        transition: all 0.25s ease;
    }

    .sodo-card:hover {
        border-color: #d4af37;
        box-shadow: 0 6px 20px rgba(212,175,55,0.15);
    }

    .sodo-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
        gap: 10px;
        margin: 15px 0;
    }

    .sodo-metric {
        background: #0f0f0f;
        padding: 10px;
        border-radius: 8px;
        border: 1px solid #222;
        text-align: center;
    }

    .sodo-metric-val {
        color: #d4af37;
        font-size: 1.15rem;
        font-weight: 700;
    }

    .sodo-metric-label {
        font-size: 0.75rem;
        color: #888;
        margin-top: 2px;
    }
</style>

<div class="dashboard-wrap">
    <div class="container">

        {{-- BÁO CÁO THÀNH CÔNG HOẶC LỖI --}}
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" style="background:#0e2e14; border:1px solid #1e632e; color:#7ee896;">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" style="filter:invert(1);"></button>
        </div>
        @endif

        @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert" style="background:#3a1010; border:1px solid #751a1a; color:#ff8e8e;">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" style="filter:invert(1);"></button>
        </div>
        @endif

        @if($errors->any())
        <div class="alert alert-danger" style="background:#3a1010; border:1px solid #751a1a; color:#ff8e8e;">
            <ul class="mb-0">
                @foreach($errors->all() as $err)
                <li>{{ $err }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="row">
            {{-- CỘT TRÁI: THẺ VIP & TIẾN ĐỘ THĂNG HẠNG --}}
            <div class="col-lg-5">

                {{-- THẺ VIP DIGITAL --}}
                <div class="vip-card-container">
                    <div class="vip-card">
                        <div class="vip-card-header">
                            <div class="vip-card-brand">
                                <i class="bi bi-suit-diamond-fill me-1"></i> KINGSMAN
                            </div>
                            @php
                                $hang = $khachHang->hangThanhVien;
                                $mauHang = $hang?->mau_sac ?? '#D4AF37';
                            @endphp
                            <div class="vip-tier-badge" style="background: {{ $mauHang }}; color: #000;">
                                <i class="bi {{ $hang?->icon ?? 'bi-patch-check-fill' }}"></i>
                                {{ $hang?->ten_hang ?? 'Thành viên' }}
                            </div>
                        </div>

                        <div class="vip-card-chip"></div>

                        <div class="vip-card-number">
                            {{ chunk_split($khachHang->ma_kh ?? 'KH0001', 4, ' ') }}
                        </div>

                        <div class="vip-card-footer">
                            <div>
                                <div class="vip-info-label">Chủ thẻ thành viên</div>
                                <div class="vip-info-val">{{ $khachHang->ho_ten ?? Auth::user()->name }}</div>
                            </div>
                            <div class="text-end">
                                <div class="vip-info-label">Chiết khấu VIP</div>
                                <div class="vip-info-val" style="color: #d4af37; font-size: 1.15rem; font-weight: 700;">
                                    -{{ (int)($hang?->phan_tram_chiet_khau ?? 0) }}%
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- HỘP TIẾN TRÌNH THĂNG HẠNG --}}
                <div class="progress-box">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted" style="font-size: 0.8rem; text-transform: uppercase;">Điểm thưởng tích lũy</span>
                            <div style="font-size: 1.6rem; font-weight: 700; color: #d4af37;">
                                <i class="bi bi-coin me-1"></i> {{ number_format($khachHang->diem_tich_luy) }} <small style="font-size: 0.85rem; color:#aaa;">điểm</small>
                            </div>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-dark border border-secondary px-3 py-2" style="font-size: 0.85rem;">
                                {{ $hang?->ten_hang ?? 'Đồng' }}
                            </span>
                        </div>
                    </div>

                    <div class="progress-custom">
                        <div class="progress-bar-gold" style="width: {{ $tienDo['phan_tram'] }}%; height: 100%;"></div>
                    </div>

                    <div class="d-flex justify-content-between text-muted" style="font-size: 0.8rem;">
                        <span>Tiến trình: <strong>{{ $tienDo['phan_tram'] }}%</strong></span>
                        @if($tienDo['la_cao_nhat'])
                            <span style="color: #00D2FF;"><i class="bi bi-stars"></i> Hạng VIP cao nhất!</span>
                        @else
                            <span>Còn <strong>{{ number_format($tienDo['diem_can_them']) }} điểm</strong> lên <strong>{{ $tienDo['hang_tiep']?->ten_hang }}</strong></span>
                        @endif
                    </div>
                </div>

                {{-- DANH SÁCH 4 QUYỀN LỢI HẠNG THÀNH VIÊN --}}
                <div class="card-luxury mb-4">
                    <h5 class="section-title-gold mb-3" style="font-size: 1.1rem;">
                        <i class="bi bi-award me-2"></i>Chính Sách Khách Hàng Thân Thiết
                    </h5>
                    <div class="d-flex flex-column gap-2">
                        @foreach($cacHang as $h)
                        <div class="p-2 px-3 rounded d-flex justify-content-between align-items-center" style="background: {{ $khachHang->hang_thanh_vien_id == $h->id ? 'rgba(212,175,55,0.15)' : '#0f0f0f' }}; border: 1px solid {{ $khachHang->hang_thanh_vien_id == $h->id ? '#d4af37' : '#222' }};">
                            <div>
                                <span style="font-weight: 700; color: {{ $h->mau_sac }};">
                                    <i class="bi {{ $h->icon }} me-1"></i> {{ $h->ten_hang }}
                                </span>
                                <small class="text-muted d-block" style="font-size: 0.75rem;">(Từ {{ number_format($h->diem_toi_thieu) }} điểm)</small>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-secondary" style="font-size: 0.8rem;">Giảm {{ $h->phan_tram_chiet_khau }}%</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <small class="text-muted d-block mt-3" style="font-size: 0.75rem;">
                        * Cứ mỗi 10.000đ tiền hàng khi hoàn tất đơn = 1 điểm tích lũy. Điểm dùng để tự động thăng hạng trọn đời.
                    </small>
                </div>

            </div>

            {{-- CỘT PHẢI: TABS QUẢN LÝ (THÔNG TIN / SỐ ĐO / LỊCH HẸN / ĐƠN HÀNG) --}}
            <div class="col-lg-7">

                {{-- NAV TABS --}}
                <ul class="nav nav-tabs nav-tabs-luxury" id="dashboardTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab">
                            <i class="bi bi-person-lines-fill me-1"></i> Hồ Sơ
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="sodo-tab" data-bs-toggle="tab" data-bs-target="#sodo" type="button" role="tab">
                            <i class="bi bi-rulers me-1"></i> Số Đo May Vest ({{ $khachHang->soDos->count() }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="lichhen-tab" data-bs-toggle="tab" data-bs-target="#lichhen" type="button" role="tab">
                            <i class="bi bi-calendar2-heart me-1"></i> Lịch Hẹn Showroom ({{ $khachHang->lichHens->count() }})
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="dashboardTabContent">

                    {{-- TAB 1: THÔNG TIN HỒ SƠ CÁ NHÂN --}}
                    <div class="tab-pane fade show active" id="profile" role="tabpanel">
                        <div class="card-luxury">
                            <h4 class="section-title-gold mb-3" style="font-size: 1.25rem;">
                                <i class="bi bi-person-circle me-2"></i>Thông Tin Tài Khoản
                            </h4>
                            <p class="text-muted" style="font-size: 0.85rem;">Cập nhật thông tin để nhân viên tư vấn may vest và giao hàng chính xác.</p>

                            <form action="{{ route('khachhang.thongtin.luu') }}" method="POST">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label text-muted" style="font-size:0.85rem;">Họ và tên *</label>
                                        <input type="text" name="ho_ten" class="form-control form-control-dark" value="{{ old('ho_ten', $khachHang->ho_ten) }}" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-muted" style="font-size:0.85rem;">Số điện thoại</label>
                                        <input type="text" name="so_dien_thoai" class="form-control form-control-dark" value="{{ old('so_dien_thoai', $khachHang->so_dien_thoai) }}" placeholder="VD: 0987654321">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label text-muted" style="font-size:0.85rem;">Email đăng nhập</label>
                                        <input type="text" class="form-control form-control-dark" value="{{ Auth::user()->email }}" disabled>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-muted" style="font-size:0.85rem;">Giới tính</label>
                                        <select name="gioi_tinh" class="form-select form-select-dark">
                                            <option value="Nam" {{ $khachHang->gioi_tinh == 'Nam' ? 'selected' : '' }}>Nam</option>
                                            <option value="Nu" {{ $khachHang->gioi_tinh == 'Nu' ? 'selected' : '' }}>Nữ</option>
                                            <option value="Khac" {{ $khachHang->gioi_tinh == 'Khac' ? 'selected' : '' }}>Khác</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label text-muted" style="font-size:0.85rem;">Ngày sinh</label>
                                        <input type="date" name="ngay_sinh" class="form-control form-control-dark" value="{{ $khachHang->ngay_sinh ? $khachHang->ngay_sinh->format('Y-m-d') : '' }}">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label text-muted" style="font-size:0.85rem;">Địa chỉ giao hàng</label>
                                        <input type="text" name="dia_chi" class="form-control form-control-dark" value="{{ old('dia_chi', $khachHang->dia_chi) }}" placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành phố">
                                    </div>
                                    <div class="col-12 text-end mt-4">
                                        <button type="submit" class="btn btn-gold">
                                            <i class="bi bi-save me-1"></i> Lưu Thay Đổi
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    {{-- TAB 2: HỒ SƠ SỐ ĐO MAY VEST --}}
                    <div class="tab-pane fade" id="sodo" role="tabpanel">
                        <div class="card-luxury">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h4 class="section-title-gold mb-1" style="font-size: 1.25rem;">
                                        <i class="bi bi-scissors me-2"></i>Hồ Sơ Số Đo Cơ Thể
                                    </h4>
                                    <p class="text-muted mb-0" style="font-size: 0.85rem;">Lưu thông số đo riêng để thợ may Kingsman may vest bespoke chuẩn từng centimet.</p>
                                </div>
                                <button type="button" class="btn btn-gold btn-sm" onclick="moModalThemSoDo()">
                                    <i class="bi bi-plus-lg me-1"></i> Thêm Số Đo Mới
                                </button>
                            </div>

                            @forelse($khachHang->soDos as $sd)
                            <div class="sodo-card">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0 text-white" style="font-size: 1.05rem;">
                                        <i class="bi bi-bookmark-star-fill text-warning me-2"></i> {{ $sd->ten_ho_so }}
                                    </h6>
                                    <div class="d-flex gap-2">
                                        <button class="btn btn-outline-warning btn-sm" onclick='moModalSuaSoDo(@json($sd))'>
                                            <i class="bi bi-pencil-square"></i> Sửa
                                        </button>
                                        <form action="{{ route('khachhang.sodo.xoa', $sd->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa bộ số đo này?')">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <div class="sodo-grid">
                                    <div class="sodo-metric">
                                        <div class="sodo-metric-val">{{ $sd->rong_vai ?? '—' }} <small>cm</small></div>
                                        <div class="sodo-metric-label">Rộng vai</div>
                                    </div>
                                    <div class="sodo-metric">
                                        <div class="sodo-metric-val">{{ $sd->vong_nguc ?? '—' }} <small>cm</small></div>
                                        <div class="sodo-metric-label">Vòng ngực</div>
                                    </div>
                                    <div class="sodo-metric">
                                        <div class="sodo-metric-val">{{ $sd->vong_eo ?? '—' }} <small>cm</small></div>
                                        <div class="sodo-metric-label">Vòng eo</div>
                                    </div>
                                    <div class="sodo-metric">
                                        <div class="sodo-metric-val">{{ $sd->vong_mong ?? '—' }} <small>cm</small></div>
                                        <div class="sodo-metric-label">Vòng mông</div>
                                    </div>
                                    <div class="sodo-metric">
                                        <div class="sodo-metric-val">{{ $sd->dai_tay_ao ?? '—' }} <small>cm</small></div>
                                        <div class="sodo-metric-label">Dài tay áo</div>
                                    </div>
                                    <div class="sodo-metric">
                                        <div class="sodo-metric-val">{{ $sd->dai_ao_vest ?? '—' }} <small>cm</small></div>
                                        <div class="sodo-metric-label">Dài áo vest</div>
                                    </div>
                                    <div class="sodo-metric">
                                        <div class="sodo-metric-val">{{ $sd->dai_quan ?? '—' }} <small>cm</small></div>
                                        <div class="sodo-metric-label">Dài quần</div>
                                    </div>
                                    <div class="sodo-metric">
                                        <div class="sodo-metric-val">{{ $sd->vong_dui ?? '—' }} <small>cm</small></div>
                                        <div class="sodo-metric-label">Vòng đùi</div>
                                    </div>
                                </div>

                                @if($sd->ghi_chu)
                                <div class="text-muted" style="font-size: 0.8rem;">
                                    <i class="bi bi-chat-left-text me-1"></i> Ghi chú: {{ $sd->ghi_chu }}
                                </div>
                                @endif
                            </div>
                            @empty
                            <div class="text-center py-5" style="background:#0f0f0f; border-radius:12px;">
                                <i class="bi bi-rulers text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-2 mb-3">Bạn chưa có bộ số đo nào. Hãy tạo bộ số đo để tự động áp dụng khi đặt may đo áo vest.</p>
                                <button type="button" class="btn btn-gold btn-sm" onclick="moModalThemSoDo()">
                                    <i class="bi bi-plus-lg me-1"></i> Nhập Số Đo Đầu Tiên
                                </button>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- TAB 3: LỊCH HẸN SHOWROOM --}}
                    <div class="tab-pane fade" id="lichhen" role="tabpanel">
                        <div class="card-luxury">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <h4 class="section-title-gold mb-1" style="font-size: 1.25rem;">
                                        <i class="bi bi-calendar2-check me-2"></i>Lịch Hẹn Tại Showroom
                                    </h4>
                                    <p class="text-muted mb-0" style="font-size: 0.85rem;">Các buổi hẹn thử đồ cưới, may đo riêng hoặc tư vấn trang phục tại Kingsman.</p>
                                </div>
                                <a href="{{ route('lichhen.create') }}" class="btn btn-gold btn-sm">
                                    <i class="bi bi-calendar-plus me-1"></i> Đặt Lịch Mới
                                </a>
                            </div>

                            @forelse($khachHang->lichHens as $lh)
                            <div class="p-3 mb-3 rounded" style="background:#1a1a1a; border: 1px solid #333;">
                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                                    <div>
                                        <span class="badge bg-dark border border-warning text-warning me-2" style="font-size: 0.85rem;">
                                            {{ $lh->ma_lich_hen }}
                                        </span>
                                        <strong class="text-white" style="font-size: 1rem;">{{ $lh->loai_dich_vu }}</strong>
                                    </div>
                                    <div>
                                        <span class="badge {{ $lh->badge_class }} px-3 py-2">
                                            {{ $lh->trang_thai }}
                                        </span>
                                    </div>
                                </div>

                                <div class="row g-2 text-muted" style="font-size: 0.85rem;">
                                    <div class="col-md-6">
                                        <i class="bi bi-clock-fill text-warning me-1"></i> Thời gian: 
                                        <strong class="text-white">{{ $lh->thoi_gian_hen ? $lh->thoi_gian_hen->format('H:i - d/m/Y') : 'Chưa định' }}</strong>
                                    </div>
                                    <div class="col-md-6">
                                        <i class="bi bi-people-fill text-warning me-1"></i> Số người đi cùng: 
                                        <strong class="text-white">{{ $lh->so_nguoi_di_cung }} người</strong>
                                    </div>

                                    @if($lh->sanPhamQuanTam)
                                    <div class="col-12 mt-2 p-2 rounded d-flex align-items-center gap-3" style="background:#0e0e0e; border:1px dashed #444;">
                                        <img src="{{ asset('images/' . $lh->sanPhamQuanTam->hinh_anh) }}" style="width: 45px; height: 45px; object-fit: cover; border-radius: 6px;">
                                        <div>
                                            <div class="text-white font-weight-bold" style="font-size: 0.85rem;">Mẫu vest quan tâm: {{ $lh->sanPhamQuanTam->ten_sp }}</div>
                                            <small class="text-warning">{{ number_format($lh->sanPhamQuanTam->gia_khuyen_mai) }} VNĐ</small>
                                        </div>
                                    </div>
                                    @endif

                                    @if($lh->nhanVienTiepDon)
                                    <div class="col-12 text-info mt-1" style="font-size: 0.8rem;">
                                        <i class="bi bi-person-check-fill me-1"></i> Nhân viên tiếp đón: <strong>{{ $lh->nhanVienTiepDon->ho_ten }}</strong> ({{ $lh->nhanVienTiepDon->chuc_vu }})
                                    </div>
                                    @endif

                                    @if($lh->ghi_chu)
                                    <div class="col-12 text-muted mt-1" style="font-size: 0.8rem;">
                                        <i class="bi bi-chat-quote me-1"></i> Ghi chú: {{ $lh->ghi_chu }}
                                    </div>
                                    @endif
                                </div>

                                @if($lh->trang_thai === \App\Models\LichHen::CHO_XAC_NHAN)
                                <div class="text-end mt-3 border-top border-dark pt-2">
                                    <form action="{{ route('lichhen.huy', $lh->id) }}" method="POST" onsubmit="return confirm('Bạn chắc chắn muốn hủy lịch hẹn này?')">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-danger btn-sm" style="font-size: 0.75rem;">
                                            <i class="bi bi-x-circle me-1"></i> Hủy Lịch Hẹn
                                        </button>
                                    </form>
                                </div>
                                @endif
                            </div>
                            @empty
                            <div class="text-center py-5" style="background:#0f0f0f; border-radius:12px;">
                                <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-2 mb-3">Bạn chưa có lịch hẹn nào tại showroom.</p>
                                <a href="{{ route('lichhen.create') }}" class="btn btn-gold btn-sm">
                                    <i class="bi bi-calendar-plus me-1"></i> Đặt Lịch Hẹn Ngay
                                </a>
                            </div>
                            @endforelse
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

{{-- MODAL THÊM / SỬA BỘ SỐ ĐO MAY VEST --}}
<div class="modal fade" id="modalSoDo" tabindex="-1" style="background: rgba(0,0,0,0.85);">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content" style="background: #171717; border: 1px solid #d4af37; color: #f0f0f0;">
            <div class="modal-header" style="border-bottom: 1px solid #333;">
                <h5 class="modal-title" id="modalSoDoTitle" style="color: #d4af37;">
                    <i class="bi bi-rulers me-2"></i>Thêm Bộ Số Đo May Vest
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter: invert(1);"></button>
            </div>
            <form action="{{ route('khachhang.sodo.luu') }}" method="POST">
                @csrf
                <input type="hidden" name="id" id="sodo_id">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-muted" style="font-size: 0.85rem;">Tên hồ sơ số đo *</label>
                        <input type="text" name="ten_ho_so" id="sodo_ten" class="form-control form-control-dark" placeholder="VD: Số đo chuẩn 2026, Số đo vest cưới ôm slim" required>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <label class="form-label text-muted" style="font-size: 0.8rem;">Rộng vai (cm)</label>
                            <input type="number" step="0.5" name="rong_vai" id="sodo_vai" class="form-control form-control-dark" placeholder="VD: 44">
                        </div>
                        <div class="col-md-3 col-6">
                            <label class="form-label text-muted" style="font-size: 0.8rem;">Vòng ngực (cm)</label>
                            <input type="number" step="0.5" name="vong_nguc" id="sodo_nguc" class="form-control form-control-dark" placeholder="VD: 94">
                        </div>
                        <div class="col-md-3 col-6">
                            <label class="form-label text-muted" style="font-size: 0.8rem;">Vòng eo (cm)</label>
                            <input type="number" step="0.5" name="vong_eo" id="sodo_eo" class="form-control form-control-dark" placeholder="VD: 82">
                        </div>
                        <div class="col-md-3 col-6">
                            <label class="form-label text-muted" style="font-size: 0.8rem;">Vòng mông (cm)</label>
                            <input type="number" step="0.5" name="vong_mong" id="sodo_mong" class="form-control form-control-dark" placeholder="VD: 96">
                        </div>
                        <div class="col-md-3 col-6">
                            <label class="form-label text-muted" style="font-size: 0.8rem;">Dài tay áo (cm)</label>
                            <input type="number" step="0.5" name="dai_tay_ao" id="sodo_tay" class="form-control form-control-dark" placeholder="VD: 60">
                        </div>
                        <div class="col-md-3 col-6">
                            <label class="form-label text-muted" style="font-size: 0.8rem;">Dài áo vest (cm)</label>
                            <input type="number" step="0.5" name="dai_ao_vest" id="sodo_ao" class="form-control form-control-dark" placeholder="VD: 72">
                        </div>
                        <div class="col-md-3 col-6">
                            <label class="form-label text-muted" style="font-size: 0.8rem;">Dài quần (cm)</label>
                            <input type="number" step="0.5" name="dai_quan" id="sodo_quan" class="form-control form-control-dark" placeholder="VD: 98">
                        </div>
                        <div class="col-md-3 col-6">
                            <label class="form-label text-muted" style="font-size: 0.8rem;">Vòng đùi (cm)</label>
                            <input type="number" step="0.5" name="vong_dui" id="sodo_dui" class="form-control form-control-dark" placeholder="VD: 54">
                        </div>
                        <div class="col-md-3 col-6">
                            <label class="form-label text-muted" style="font-size: 0.8rem;">Vòng cổ (cm)</label>
                            <input type="number" step="0.5" name="vong_co" id="sodo_co" class="form-control form-control-dark" placeholder="VD: 39">
                        </div>
                        <div class="col-md-9 col-12">
                            <label class="form-label text-muted" style="font-size: 0.8rem;">Ghi chú vóc dáng đặc thù</label>
                            <input type="text" name="ghi_chu" id="sodo_ghichu" class="form-control form-control-dark" placeholder="VD: Vai hơi xuôi, thích quần ôm lửng chạm mắt cá...">
                        </div>
                    </div>
                </div>
                <div class="modal-footer" style="border-top: 1px solid #333;">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-gold btn-sm"><i class="bi bi-check-lg me-1"></i> Lưu Số Đo</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function moModalThemSoDo() {
        document.getElementById('modalSoDoTitle').innerHTML = '<i class="bi bi-rulers me-2"></i>Thêm Bộ Số Đo May Vest';
        document.getElementById('sodo_id').value = '';
        document.getElementById('sodo_ten').value = 'Số đo vest ' + new Date().getFullYear();
        document.getElementById('sodo_vai').value = '';
        document.getElementById('sodo_nguc').value = '';
        document.getElementById('sodo_eo').value = '';
        document.getElementById('sodo_mong').value = '';
        document.getElementById('sodo_tay').value = '';
        document.getElementById('sodo_ao').value = '';
        document.getElementById('sodo_quan').value = '';
        document.getElementById('sodo_dui').value = '';
        document.getElementById('sodo_co').value = '';
        document.getElementById('sodo_ghichu').value = '';
        new bootstrap.Modal(document.getElementById('modalSoDo')).show();
    }

    function moModalSuaSoDo(sd) {
        document.getElementById('modalSoDoTitle').innerHTML = '<i class="bi bi-pencil-square me-2"></i>Cập Nhật Bộ Số Đo: ' + sd.ten_ho_so;
        document.getElementById('sodo_id').value = sd.id;
        document.getElementById('sodo_ten').value = sd.ten_ho_so || '';
        document.getElementById('sodo_vai').value = sd.rong_vai || '';
        document.getElementById('sodo_nguc').value = sd.vong_nguc || '';
        document.getElementById('sodo_eo').value = sd.vong_eo || '';
        document.getElementById('sodo_mong').value = sd.vong_mong || '';
        document.getElementById('sodo_tay').value = sd.dai_tay_ao || '';
        document.getElementById('sodo_ao').value = sd.dai_ao_vest || '';
        document.getElementById('sodo_quan').value = sd.dai_quan || '';
        document.getElementById('sodo_dui').value = sd.vong_dui || '';
        document.getElementById('sodo_co').value = sd.vong_co || '';
        document.getElementById('sodo_ghichu').value = sd.ghi_chu || '';
        new bootstrap.Modal(document.getElementById('modalSoDo')).show();
    }

    // Active tab based on hash
    document.addEventListener("DOMContentLoaded", function () {
        const hash = window.location.hash;
        if (hash) {
            const tabBtn = document.querySelector(`button[data-bs-target="${hash}"]`);
            if (tabBtn) {
                new bootstrap.Tab(tabBtn).show();
            }
        }
    });
</script>
@endsection

