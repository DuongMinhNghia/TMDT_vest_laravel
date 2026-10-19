@extends('layouts.default_layout')

@section('title', 'Thống Kê Đơn Hàng - Kingsman')

@section('content')
<style>
    .admin-wrap { background: linear-gradient(to bottom, #0a0a0a, #000); min-height: 100vh; padding: 30px 20px; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 15px; border-bottom: 2px solid #d4af37; }
    .page-header h2 { color: #d4af37; font-size: 1.8rem; font-weight: 700; margin: 0; letter-spacing: 2px; }
    .stat-card { background: linear-gradient(135deg, #1a1a1a, #2a2a2a); border: 1px solid #333; border-radius: 12px; padding: 20px; text-align:center; transition: all 0.3s; }
    .stat-card:hover { border-color: #d4af37; transform: translateY(-3px); }
    .stat-card .number { font-size: 2.2rem; font-weight: 700; }
    .stat-card .label { color: #888; font-size: 0.82rem; margin-top: 5px; letter-spacing: 1px; }
    .filter-bar { background: #1a1a1a; border: 1px solid #333; border-radius: 12px; padding: 20px; margin-bottom: 25px; }
    .form-control-dark { background: #0f0f0f !important; border: 1px solid #444 !important; color: #fff !important; border-radius: 8px !important; }
    .form-control-dark:focus { border-color: #d4af37 !important; box-shadow: 0 0 0 2px rgba(212,175,55,0.2) !important; }
    .form-select-dark { background: #0f0f0f !important; border: 1px solid #444 !important; color: #fff !important; border-radius: 8px !important; }
    .table-wrap { background: #1a1a1a; border: 1px solid #333; border-radius: 12px; overflow: hidden; }
    .table-dark-custom { color: #e0e0e0; margin: 0; }
    .table-dark-custom thead th { background: #1F4E79; color: #fff; border: none; padding: 14px 12px; font-size: 0.83rem; letter-spacing: 1px; text-transform: uppercase; }
    .table-dark-custom tbody tr { border-bottom: 1px solid #2a2a2a; transition: background 0.2s; }
    .table-dark-custom tbody tr:hover { background: rgba(212,175,55,0.05); }
    .table-dark-custom tbody td { padding: 13px 12px; vertical-align: middle; border: none; }
    .badge-hoan { background: #0d2b0d; color: #6fcf6f; border: 1px solid #2d6a2d; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 600; }
    .badge-giao { background: #0d1f3c; color: #7eb8f7; border: 1px solid #1a4a8a; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 600; }
    .badge-cho { background: #2b2000; color: #f0c674; border: 1px solid #6a5000; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 600; }
    .badge-huy { background: #2b0d0d; color: #cf6f6f; border: 1px solid #6a2d2d; padding: 4px 12px; border-radius: 20px; font-size: 0.78rem; font-weight: 600; }
    .btn-update { background: transparent; border: 1px solid #d4af37; color: #d4af37; padding: 5px 12px; border-radius: 6px; font-size: 0.8rem; cursor: pointer; transition: all 0.2s; }
    .btn-update:hover { background: #d4af37; color: #000; }
    .modal-dark .modal-content { background: #1a1a1a; border: 1px solid #d4af37; color: #e0e0e0; }
    .modal-dark .modal-header { border-bottom: 1px solid #333; }
    .modal-dark .modal-footer { border-top: 1px solid #333; }
    .modal-dark .modal-title { color: #d4af37; font-weight: 700; }
    .modal-dark .btn-close { filter: invert(1); }
    .alert-success-custom { background: #0d2b0d; border: 1px solid #2d6a2d; color: #6fcf6f; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; }
</style>

<div class="admin-wrap">
    <div class="container-fluid">

        <div class="page-header">
            <h2><i class="bi bi-receipt me-2"></i>Thống Kê Đơn Hàng</h2>
            <a href="{{ route('thongke.doanhthu') }}" style="background:transparent; border:1px solid #d4af37; color:#d4af37; padding:10px 20px; border-radius:8px; text-decoration:none; font-weight:600; font-size:0.9rem;">
                <i class="bi bi-graph-up-arrow me-1"></i> Xem Doanh Thu
            </a>
        </div>

        @if(session('success'))
        <div class="alert-success-custom"><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</div>
        @endif

        {{-- Thống kê nhanh --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="number" style="color:#e0e0e0;">{{ $tongHoanThanh + $tongDangGiao + $tongChoXuLy }}</div>
                    <div class="label">📋 Tổng đơn hàng</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="number" style="color:#6fcf6f;">{{ $tongHoanThanh }}</div>
                    <div class="label">✅ Hoàn thành</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="number" style="color:#7eb8f7;">{{ $tongDangGiao }}</div>
                    <div class="label">🚚 Đang giao</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="number" style="color:#f0c674;">{{ $tongChoXuLy }}</div>
                    <div class="label">⏳ Chờ xử lý</div>
                </div>
            </div>
        </div>

        {{-- Filter --}}
        <div class="filter-bar">
            <form method="GET" action="{{ route('thongke.donhang') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label style="color:#888; font-size:0.82rem; margin-bottom:6px;">TRẠNG THÁI</label>
                    <select name="trang_thai" class="form-select form-select-dark">
                        <option value="">-- Tất cả --</option>
                        <option value="Chờ xử lý" {{ $trangThai=='Chờ xử lý'?'selected':'' }}>⏳ Chờ xử lý</option>
                        <option value="Đang giao" {{ $trangThai=='Đang giao'?'selected':'' }}>🚚 Đang giao</option>
                        <option value="Hoàn Thành" {{ $trangThai=='Hoàn Thành'?'selected':'' }}>✅ Hoàn thành</option>
                        <option value="Đã hủy" {{ $trangThai=='Đã hủy'?'selected':'' }}>❌ Đã hủy</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label style="color:#888; font-size:0.82rem; margin-bottom:6px;">TỪ NGÀY</label>
                    <input type="date" name="tu_ngay" value="{{ $tuNgay }}" class="form-control form-control-dark">
                </div>
                <div class="col-md-3">
                    <label style="color:#888; font-size:0.82rem; margin-bottom:6px;">ĐẾN NGÀY</label>
                    <input type="date" name="den_ngay" value="{{ $denNgay }}" class="form-control form-control-dark">
                </div>
                <div class="col-md-3 d-flex gap-2">
                    <button type="submit" style="background:linear-gradient(135deg,#d4af37,#c19b2f); border:none; color:#000; padding:10px 20px; border-radius:8px; font-weight:700; flex:1;">
                        <i class="bi bi-funnel me-1"></i> Lọc
                    </button>
                    <a href="{{ route('thongke.donhang') }}" style="background:transparent; border:1px solid #555; color:#aaa; padding:10px 14px; border-radius:8px; text-decoration:none; display:flex; align-items:center;">
                        <i class="bi bi-x-circle"></i>
                    </a>
                </div>
            </form>
        </div>

        {{-- Bảng đơn hàng --}}
        <div class="table-wrap">
            <table class="table table-dark-custom">
                <thead>
                    <tr>
                        <th>Mã ĐH</th>
                        <th>Khách hàng</th>
                        <th>Ngày đặt</th>
                        <th>Địa chỉ</th>
                        <th>Thanh toán</th>
                        <th>Trạng thái</th>
                        <th style="text-align:center;">Cập nhật</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($donHangs as $dh)
                    <tr>
                        <td style="color:#d4af37; font-weight:700;">#{{ str_pad($dh->id, 4, '0', STR_PAD_LEFT) }}</td>
                        <td>
                            <div style="font-weight:600;">{{ $dh->user->name ?? 'N/A' }}</div>
                            <div style="color:#666; font-size:0.78rem;">{{ $dh->user->email ?? '' }}</div>
                        </td>
                        <td style="color:#aaa;">{{ \Carbon\Carbon::parse($dh->ngay_ban)->format('d/m/Y') }}</td>
                        <td style="color:#aaa; max-width:160px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; font-size:0.85rem;">
                            {{ $dh->dia_chi ?? '—' }}
                        </td>
                        <td style="color:#d4af37; font-weight:700;">{{ number_format($dh->thanh_toan, 0, ',', '.') }} đ</td>
                        <td>
                            @if($dh->trang_thai === 'Hoàn Thành')
                                <span class="badge-hoan">✅ Hoàn Thành</span>
                            @elseif($dh->trang_thai === 'Đang giao')
                                <span class="badge-giao">🚚 Đang giao</span>
                            @elseif($dh->trang_thai === 'Chờ xử lý')
                                <span class="badge-cho">⏳ Chờ xử lý</span>
                            @else
                                <span class="badge-huy">❌ Đã hủy</span>
                            @endif
                        </td>
                        <td style="text-align:center;">
                            <button class="btn-update"
                                onclick="moModalCapNhat({{ $dh->id }}, '{{ $dh->trang_thai }}')">
                                <i class="bi bi-pencil-fill me-1"></i>Sửa
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:50px; color:#555;">
                            <i class="bi bi-inbox" style="font-size:2.5rem; display:block; margin-bottom:10px;"></i>
                            Không có đơn hàng nào
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($donHangs->count() > 0)
        <div class="mt-3" style="color:#555; font-size:0.85rem; text-align:right;">
            Hiển thị {{ $donHangs->count() }} đơn hàng
            — Tổng tiền: <span style="color:#d4af37; font-weight:700;">{{ number_format($donHangs->sum('thanh_toan'), 0, ',', '.') }} đ</span>
        </div>
        @endif

    </div>
</div>

{{-- Modal cập nhật trạng thái --}}
<div class="modal fade modal-dark" id="modalCapNhat" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil-fill me-2"></i>Cập Nhật Trạng Thái</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formCapNhat" method="POST">
                @csrf
                <div class="modal-body">
                    <label style="color:#aaa; font-size:0.85rem; margin-bottom:8px; display:block;">TRẠNG THÁI MỚI</label>
                    <select name="trang_thai" id="select_trangthai" class="form-select form-select-dark">
                        <option value="Chờ xử lý">⏳ Chờ xử lý</option>
                        <option value="Đang giao">🚚 Đang giao</option>
                        <option value="Hoàn Thành">✅ Hoàn thành</option>
                        <option value="Đã hủy">❌ Đã hủy</option>
                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" style="background:transparent; border:1px solid #555; color:#aaa; padding:8px 16px; border-radius:8px;" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" style="background:linear-gradient(135deg,#d4af37,#c19b2f); border:none; color:#000; padding:8px 20px; border-radius:8px; font-weight:700;">
                        <i class="bi bi-save me-1"></i>Lưu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function moModalCapNhat(id, trangThai) {
    document.getElementById('formCapNhat').action = '/don-hang/' + id + '/cap-nhat';
    document.getElementById('select_trangthai').value = trangThai;
    new bootstrap.Modal(document.getElementById('modalCapNhat')).show();
}
</script>
@endsection
