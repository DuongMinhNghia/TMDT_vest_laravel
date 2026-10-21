@extends('layouts.default_layout')

@section('title', 'Quản Lý Lịch Hẹn Showroom - Kingsman Admin')

@section('content')
<style>
    .admin-wrap { background: linear-gradient(to bottom, #0a0a0a, #000); min-height: 100vh; padding: 30px 20px; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 15px; border-bottom: 2px solid #d4af37; }
    .page-header h2 { color: #d4af37; font-size: 1.8rem; font-weight: 700; margin: 0; letter-spacing: 2px; }
    .stat-card { background: linear-gradient(135deg, #1a1a1a, #2a2a2a); border: 1px solid #333; border-radius: 12px; padding: 18px; text-align: center; transition: all 0.3s; }
    .stat-card:hover { border-color: #d4af37; transform: translateY(-3px); box-shadow: 0 10px 30px rgba(212,175,55,0.2); }
    .stat-card .number { font-size: 2rem; font-weight: 700; color: #d4af37; }
    .stat-card .label { color: #888; font-size: 0.8rem; margin-top: 4px; letter-spacing: 1px; text-transform: uppercase; }
    .search-bar { background: #1a1a1a; border: 1px solid #333; border-radius: 12px; padding: 20px; margin-bottom: 25px; }
    .form-control-dark, .form-select-dark { background: #0f0f0f !important; border: 1px solid #444 !important; color: #fff !important; border-radius: 8px !important; }
    .form-control-dark:focus, .form-select-dark:focus { border-color: #d4af37 !important; box-shadow: 0 0 0 2px rgba(212,175,55,0.2) !important; }
    .table-wrap { background: #1a1a1a; border: 1px solid #333; border-radius: 12px; overflow: hidden; }
    .table-dark-custom { color: #e0e0e0; margin: 0; }
    .table-dark-custom thead th { background: #1F4E79; color: #fff; border: none; padding: 14px 12px; font-size: 0.85rem; letter-spacing: 1px; text-transform: uppercase; }
    .table-dark-custom tbody tr { border-bottom: 1px solid #2a2a2a; transition: background 0.2s; }
    .table-dark-custom tbody tr:hover { background: rgba(212,175,55,0.05); }
    .table-dark-custom tbody td { padding: 14px 12px; vertical-align: middle; border: none; font-size: 0.9rem; }
    .btn-edit { background: transparent; border: 1px solid #d4af37; color: #d4af37; padding: 6px 14px; border-radius: 6px; font-size: 0.82rem; transition: all 0.2s; }
    .btn-edit:hover { background: #d4af37; color: #000; }
    .btn-del { background: transparent; border: 1px solid #c00; color: #ff6b6b; padding: 6px 14px; border-radius: 6px; font-size: 0.82rem; transition: all 0.2s; }
    .btn-del:hover { background: #c00; color: #fff; }
</style>

<div class="admin-wrap">
    <div class="container-fluid">

        {{-- Header --}}
        <div class="page-header">
            <h2><i class="bi bi-calendar2-check-fill me-2"></i>Quản Lý Lịch Hẹn Showroom</h2>
        </div>

        {{-- Alert --}}
        @if(session('success'))
        <div class="alert alert-success" style="background:#0d2b0d; border:1px solid #2d6a2d; color:#6fcf6f; border-radius:8px;">
            <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        </div>
        @endif

        {{-- Thống kê nhanh --}}
        <div class="row g-3 mb-4">
            <div class="col-lg-2 col-md-4 col-6">
                <div class="stat-card">
                    <div class="number">{{ $thongKe['tong'] }}</div>
                    <div class="label">Tổng lịch hẹn</div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <div class="stat-card">
                    <div class="number" style="color: #ffc107;">{{ $thongKe['cho_xac_nhan'] }}</div>
                    <div class="label">Chờ xác nhận</div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <div class="stat-card">
                    <div class="number" style="color: #0dcaf0;">{{ $thongKe['da_xac_nhan'] }}</div>
                    <div class="label">Đã xác nhận</div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <div class="stat-card">
                    <div class="number" style="color: #0d6efd;">{{ $thongKe['dang_tiep_don'] }}</div>
                    <div class="label">Đang tiếp đón</div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <div class="stat-card">
                    <div class="number" style="color: #198754;">{{ $thongKe['hoan_thanh'] }}</div>
                    <div class="label">Hoàn thành</div>
                </div>
            </div>
            <div class="col-lg-2 col-md-4 col-6">
                <div class="stat-card">
                    <div class="number" style="color: #dc3545;">{{ $thongKe['da_huy'] }}</div>
                    <div class="label">Đã hủy</div>
                </div>
            </div>
        </div>

        {{-- Thanh tìm kiếm & lọc --}}
        <div class="search-bar">
            <form method="GET" action="{{ route('lichhen.quanly') }}" class="row g-3 align-items-end">
                <div class="col-md-4">
                    <label style="color:#888; font-size:0.82rem; margin-bottom:6px;">TÌM KIẾM</label>
                    <input type="text" name="tim_kiem" value="{{ request('tim_kiem') }}" placeholder="Tên khách, SĐT hoặc mã lịch hẹn..." class="form-control form-control-dark">
                </div>
                <div class="col-md-3">
                    <label style="color:#888; font-size:0.82rem; margin-bottom:6px;">TRẠNG THÁI</label>
                    <select name="trang_thai" class="form-select form-select-dark">
                        <option value="">-- Tất cả trạng thái --</option>
                        <option value="Chờ xác nhận" {{ request('trang_thai') == 'Chờ xác nhận' ? 'selected' : '' }}>Chờ xác nhận</option>
                        <option value="Đã xác nhận" {{ request('trang_thai') == 'Đã xác nhận' ? 'selected' : '' }}>Đã xác nhận</option>
                        <option value="Đang tiếp đón" {{ request('trang_thai') == 'Đang tiếp đón' ? 'selected' : '' }}>Đang tiếp đón</option>
                        <option value="Hoàn thành" {{ request('trang_thai') == 'Hoàn thành' ? 'selected' : '' }}>Hoàn thành</option>
                        <option value="Đã hủy" {{ request('trang_thai') == 'Đã hủy' ? 'selected' : '' }}>Đã hủy</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label style="color:#888; font-size:0.82rem; margin-bottom:6px;">NGÀY HẸN</label>
                    <input type="date" name="ngay" value="{{ request('ngay') }}" class="form-control form-control-dark">
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button type="submit" style="background: linear-gradient(135deg,#d4af37,#c19b2f); border:none; color:#000; padding:10px 18px; border-radius:8px; font-weight:700; flex:1;">
                        <i class="bi bi-search me-1"></i> Lọc
                    </button>
                    <a href="{{ route('lichhen.quanly') }}" style="background:transparent; border:1px solid #555; color:#aaa; padding:10px 14px; border-radius:8px; text-decoration:none;">
                        <i class="bi bi-x-circle"></i>
                    </a>
                </div>
            </form>
        </div>

        {{-- Bảng lịch hẹn --}}
        <div class="table-wrap">
            <table class="table table-dark-custom">
                <thead>
                    <tr>
                        <th>Mã Lịch</th>
                        <th>Khách hàng</th>
                        <th>Thời gian hẹn</th>
                        <th>Dịch vụ & Mẫu vest</th>
                        <th>Nhân viên đón tiếp</th>
                        <th>Trạng thái</th>
                        <th style="text-align:center;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($lichHens as $lh)
                    <tr>
                        <td>
                            <strong class="text-warning">{{ $lh->ma_lich_hen }}</strong>
                            <small class="text-muted d-block">{{ $lh->so_nguoi_di_cung }} người đi cùng</small>
                        </td>
                        <td>
                            <div class="text-white font-weight-bold">{{ $lh->khachHang->ho_ten ?? 'Khách vãng lai' }}</div>
                            <small class="text-muted"><i class="bi bi-telephone me-1"></i>{{ $lh->khachHang->so_dien_thoai ?? 'Chưa có SĐT' }}</small>
                            @if($lh->khachHang?->hangThanhVien)
                            <div>
                                <span class="badge" style="background: {{ $lh->khachHang->hangThanhVien->mau_sac }}; color: #000; font-size: 0.68rem;">
                                    {{ $lh->khachHang->hangThanhVien->ten_hang }}
                                </span>
                            </div>
                            @endif
                        </td>
                        <td>
                            <div class="text-white">{{ $lh->thoi_gian_hen ? $lh->thoi_gian_hen->format('H:i') : '—' }}</div>
                            <small class="text-warning">{{ $lh->thoi_gian_hen ? $lh->thoi_gian_hen->format('d/m/Y') : '—' }}</small>
                        </td>
                        <td>
                            <div class="text-white">{{ $lh->loai_dich_vu }}</div>
                            @if($lh->sanPhamQuanTam)
                            <small class="text-warning d-block">
                                <i class="bi bi-suit-heart-fill me-1"></i>{{ $lh->sanPhamQuanTam->ten_sp }}
                            </small>
                            @endif
                            @if($lh->ghi_chu)
                            <small class="text-muted d-block text-truncate" style="max-width:200px;" title="{{ $lh->ghi_chu }}">
                                "{{ $lh->ghi_chu }}"
                            </small>
                            @endif
                        </td>
                        <td>
                            @if($lh->nhanVienTiepDon)
                                <div class="text-info font-weight-bold">{{ $lh->nhanVienTiepDon->ho_ten }}</div>
                                <small class="text-muted">{{ $lh->nhanVienTiepDon->chuc_vu }}</small>
                            @else
                                <span class="text-muted fst-italic">Chưa phân công</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $lh->badge_class }} px-2 py-1">
                                {{ $lh->trang_thai }}
                            </span>
                        </td>
                        <td style="text-align:center;">
                            <div class="d-flex gap-2 justify-content-center">
                                <button class="btn-edit" onclick='moModalCapNhat(@json($lh))'>
                                    <i class="bi bi-pencil-fill me-1"></i>Duyệt
                                </button>
                                <form action="{{ route('lichhen.xoa', $lh->id) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn xóa lịch hẹn {{ $lh->ma_lich_hen }}?')">
                                    @csrf
                                    <button type="submit" class="btn-del">
                                        <i class="bi bi-trash-fill"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:50px; color:#555;">
                            <i class="bi bi-calendar-x" style="font-size:2.5rem; display:block; margin-bottom:10px;"></i>
                            Không tìm thấy lịch hẹn nào
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
</div>

{{-- MODAL DUYỆT / CẬP NHẬT TRẠNG THÁI & PHÂN CÔNG NHÂN VIÊN --}}
<div class="modal fade" id="modalCapNhatLich" tabindex="-1" style="background:rgba(0,0,0,0.85);">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="background:#171717; border:1px solid #d4af37; color:#f0f0f0;">
            <div class="modal-header" style="border-bottom:1px solid #333;">
                <h5 class="modal-title" style="color:#d4af37;">
                    <i class="bi bi-calendar-check me-2"></i>Duyệt & Xử Lý Lịch Hẹn
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" style="filter:invert(1);"></button>
            </div>
            <form id="formCapNhatLich" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label text-muted" style="font-size:0.85rem;">Mã lịch hẹn</label>
                        <input type="text" id="capnhat_ma" class="form-control form-control-dark" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted" style="font-size:0.85rem;">Khách hàng</label>
                        <input type="text" id="capnhat_khach" class="form-control form-control-dark" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted" style="font-size:0.85rem;">Trạng thái xử lý *</label>
                        <select name="trang_thai" id="capnhat_trangthai" class="form-select form-select-dark" required>
                            <option value="Chờ xác nhận">Chờ xác nhận</option>
                            <option value="Đã xác nhận">Đã xác nhận (Đã gọi điện chốt lịch)</option>
                            <option value="Đang tiếp đón">Đang tiếp đón tại showroom</option>
                            <option value="Hoàn thành">Hoàn thành (Đã thử đồ/đặt may)</option>
                            <option value="Đã hủy">Đã hủy</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted" style="font-size:0.85rem;">Phân công Nhân viên / Thợ may đón tiếp</label>
                        <select name="nhan_vien_tiep_don_id" id="capnhat_nhanvien" class="form-select form-select-dark">
                            <option value="">-- Chưa chỉ định nhân viên --</option>
                            @foreach($nhanViens as $nv)
                            <option value="{{ $nv->id }}">{{ $nv->ho_ten }} ({{ $nv->chuc_vu }} - {{ $nv->so_dien_thoai }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted" style="font-size:0.85rem;">Ghi chú nội bộ</label>
                        <textarea name="ghi_chu" id="capnhat_ghichu" rows="3" class="form-control form-control-dark" placeholder="Ghi chú thêm về vóc dáng, sở thích hoặc kết quả buổi tư vấn..."></textarea>
                    </div>
                </div>
                <div class="modal-footer" style="border-top:1px solid #333;">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Đóng</button>
                    <button type="submit" class="btn btn-warning btn-sm fw-bold">
                        <i class="bi bi-save me-1"></i> Lưu Cập Nhật
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function moModalCapNhat(lh) {
        document.getElementById('formCapNhatLich').action = '/lich-hen/' + lh.id + '/cap-nhat';
        document.getElementById('capnhat_ma').value = lh.ma_lich_hen + ' (' + lh.loai_dich_vu + ')';
        document.getElementById('capnhat_khach').value = (lh.khach_hang ? lh.khach_hang.ho_ten : 'Khách vãng lai') + ' - ' + (lh.khach_hang ? (lh.khach_hang.so_dien_thoai || 'Chưa có SĐT') : '');
        document.getElementById('capnhat_trangthai').value = lh.trang_thai;
        document.getElementById('capnhat_nhanvien').value = lh.nhan_vien_tiep_don_id || '';
        document.getElementById('capnhat_ghichu').value = lh.ghi_chu || '';
        new bootstrap.Modal(document.getElementById('modalCapNhatLich')).show();
    }
</script>
@endsection

