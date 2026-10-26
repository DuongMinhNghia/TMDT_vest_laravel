@extends('layouts.admin_layout')

@section('title', 'Danh Sách Tài Khoản - Kingsman Admin')
@section('page_name', 'Danh Sách Tài Khoản')

@section('content')
<style>
    /* Executive Luxury Dark Palette for Quản Lý Người Dùng */
    .user-container {
        padding: 4px 0 40px;
    }

    .user-top-bar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 16px;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 1px solid var(--adm-border);
    }

    .user-header-title {
        font-family: var(--adm-font-heading);
        color: var(--adm-text-main);
        font-size: 1.6rem;
        font-weight: 700;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .user-header-title i {
        color: var(--adm-gold);
    }

    .user-subtitle {
        color: var(--adm-text-muted);
        font-size: 0.82rem;
        margin-top: 4px;
    }

    /* KPI Summary Cards */
    .user-kpi-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 16px;
        margin-bottom: 24px;
    }

    .user-kpi-card {
        background: var(--adm-bg-surface);
        border: 1px solid var(--adm-border);
        border-radius: 10px;
        padding: 16px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: border-color 0.2s, transform 0.2s;
    }

    .user-kpi-card:hover {
        border-color: var(--adm-border-focus);
        transform: translateY(-2px);
    }

    .user-kpi-label {
        color: var(--adm-text-muted);
        font-size: 0.74rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.8px;
        margin-bottom: 4px;
    }

    .user-kpi-num {
        font-family: var(--adm-font-heading);
        font-size: 1.65rem;
        font-weight: 700;
        color: var(--adm-text-main);
    }

    .user-kpi-icon {
        width: 44px;
        height: 44px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
    }

    /* Filter Toolbar */
    .user-filter-card {
        background: var(--adm-bg-surface);
        border: 1px solid var(--adm-border);
        border-radius: 10px;
        padding: 16px 20px;
        margin-bottom: 20px;
    }

    .user-form-control, .user-form-select {
        background: var(--adm-bg-card);
        border: 1px solid var(--adm-border);
        color: var(--adm-text-main);
        border-radius: 6px;
        padding: 8px 12px;
        font-size: 0.84rem;
        width: 100%;
        transition: all 0.2s;
    }

    .user-form-control:focus, .user-form-select:focus {
        border-color: var(--adm-gold);
        outline: none;
        box-shadow: 0 0 0 3px var(--adm-gold-glow);
        background: #1c202b;
        color: #fff;
    }

    .btn-gold-filter {
        background: linear-gradient(135deg, var(--adm-gold-light), var(--adm-gold));
        border: none;
        color: #000;
        padding: 9px 20px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 0.84rem;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        transition: all 0.2s;
    }

    .btn-gold-filter:hover {
        filter: brightness(1.1);
        transform: translateY(-1px);
    }

    .btn-reset-filter {
        background: var(--adm-bg-card);
        border: 1px solid var(--adm-border);
        color: var(--adm-text-muted);
        padding: 9px 14px;
        border-radius: 6px;
        font-size: 0.84rem;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        transition: all 0.2s;
    }

    .btn-reset-filter:hover {
        color: #fff;
        border-color: var(--adm-border-focus);
    }

    /* Table Container */
    .user-table-card {
        background: var(--adm-bg-surface);
        border: 1px solid var(--adm-border);
        border-radius: 12px;
        overflow: hidden;
    }

    .user-table {
        width: 100%;
        border-collapse: collapse;
        color: var(--adm-text-main);
        font-size: 0.86rem;
    }

    .user-table thead th {
        background: var(--adm-bg-card);
        color: var(--adm-gold);
        border-bottom: 1px solid var(--adm-border);
        padding: 13px 16px;
        font-size: 0.76rem;
        font-weight: 600;
        letter-spacing: 0.8px;
        text-transform: uppercase;
    }

    .user-table tbody tr {
        border-bottom: 1px solid var(--adm-border);
        transition: background 0.15s;
    }

    .user-table tbody tr:hover {
        background: var(--adm-bg-hover);
    }

    .user-table tbody td {
        padding: 14px 16px;
        vertical-align: middle;
    }

    .avatar-circle {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(212,178,111,0.2), rgba(212,178,111,0.4));
        border: 1px solid var(--adm-gold);
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        color: var(--adm-gold);
        font-size: 0.88rem;
    }

    .badge-admin-role {
        background: rgba(212, 178, 111, 0.15);
        color: var(--adm-gold);
        border: 1px solid rgba(212, 178, 111, 0.35);
        padding: 3px 10px;
        border-radius: 20px;
        font-weight: 700;
        font-size: 0.74rem;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .badge-customer-role {
        background: rgba(56, 189, 248, 0.12);
        color: #38bdf8;
        border: 1px solid rgba(56, 189, 248, 0.3);
        padding: 3px 10px;
        border-radius: 20px;
        font-weight: 600;
        font-size: 0.74rem;
        display: inline-flex;
        align-items: center;
        gap: 4px;
    }

    .btn-edit-action {
        background: rgba(212, 178, 111, 0.12);
        border: 1px solid rgba(212, 178, 111, 0.3);
        color: var(--adm-gold);
        padding: 5px 12px;
        border-radius: 6px;
        font-size: 0.78rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .btn-edit-action:hover {
        background: var(--adm-gold);
        color: #000;
    }

    .btn-del-action {
        background: rgba(244, 63, 94, 0.12);
        border: 1px solid rgba(244, 63, 94, 0.3);
        color: #f43f5e;
        padding: 5px 10px;
        border-radius: 6px;
        font-size: 0.78rem;
        cursor: pointer;
        transition: all 0.2s;
    }

    .btn-del-action:hover {
        background: #f43f5e;
        color: #fff;
    }
</style>

<div class="user-container">

    <!-- TOP TOOLBAR -->
    <div class="user-top-bar">
        <div>
            <h2 class="user-header-title">
                <i class="bi bi-people-fill"></i> Quản Lý Danh Sách Tài Khoản
            </h2>
            <div class="user-subtitle">Quản trị tài khoản quản trị viên và khách hàng thân thiết trong hệ thống</div>
        </div>
    </div>

    <!-- KPI STATS -->
    <div class="user-kpi-grid">
        <div class="user-kpi-card">
            <div>
                <div class="user-kpi-label">Tổng Người Dùng</div>
                <div class="user-kpi-num" style="color: var(--adm-gold);">{{ $users->count() }}</div>
            </div>
            <div class="user-kpi-icon" style="background:var(--adm-gold-glow); color:var(--adm-gold);">
                <i class="bi bi-people"></i>
            </div>
        </div>

        <div class="user-kpi-card">
            <div>
                <div class="user-kpi-label">Quản Trị Viên (Admin)</div>
                <div class="user-kpi-num" style="color: #eab308;">{{ $users->where('quyen','Admin')->count() }}</div>
            </div>
            <div class="user-kpi-icon" style="background:rgba(234,179,8,0.12); color:#eab308;">
                <i class="bi bi-shield-lock"></i>
            </div>
        </div>

        <div class="user-kpi-card">
            <div>
                <div class="user-kpi-label">Khách Hàng</div>
                <div class="user-kpi-num" style="color: #38bdf8;">{{ $users->where('quyen','Khách hàng')->count() }}</div>
            </div>
            <div class="user-kpi-icon" style="background:rgba(56,189,248,0.12); color:#38bdf8;">
                <i class="bi bi-person-check"></i>
            </div>
        </div>

        <div class="user-kpi-card">
            <div>
                <div class="user-kpi-label">Đã Cập Nhật SĐT</div>
                <div class="user-kpi-num" style="color: #22c55e;">{{ $users->whereNotNull('phone')->count() }}</div>
            </div>
            <div class="user-kpi-icon" style="background:rgba(34,197,94,0.12); color:#22c55e;">
                <i class="bi bi-telephone-check"></i>
            </div>
        </div>
    </div>

    <!-- FILTER TOOLBAR -->
    <div class="user-filter-card">
        <form method="GET" action="{{ route('nguoidung.index') }}" class="row g-3 align-items-end">
            <div class="col-md-5">
                <label style="color: var(--adm-text-muted); font-size:0.75rem; text-transform:uppercase; letter-spacing:0.8px; margin-bottom:6px; font-weight:600;">Tìm kiếm</label>
                <input type="text" name="tim_kiem" value="{{ request('tim_kiem') }}" placeholder="Tên hoặc email..." class="user-form-control">
            </div>
            <div class="col-md-3">
                <label style="color: var(--adm-text-muted); font-size:0.75rem; text-transform:uppercase; letter-spacing:0.8px; margin-bottom:6px; font-weight:600;">Phân quyền</label>
                <select name="quyen" class="user-form-select">
                    <option value="">-- Tất cả quyền --</option>
                    <option value="Admin" {{ request('quyen')=='Admin'?'selected':'' }}>Admin</option>
                    <option value="Nhân viên" {{ request('quyen')=='Nhân viên'?'selected':'' }}>Nhân viên</option>
                    <option value="Khách hàng" {{ request('quyen')=='Khách hàng'?'selected':'' }}>Khách hàng</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
                <button type="submit" class="btn-gold-filter flex-fill">
                    <i class="bi bi-search"></i> Tìm Kiếm
                </button>
                <a href="{{ route('nguoidung.index') }}" class="btn-reset-filter" title="Xóa bộ lọc">
                    <i class="bi bi-x-circle"></i> Đặt lại
                </a>
            </div>
        </form>
    </div>

    <!-- TABLE LIST -->
    <div class="user-table-card">
        <table class="user-table">
            <thead>
                <tr>
                    <th style="width: 50px;">#</th>
                    <th>Người Dùng</th>
                    <th>Email</th>
                    <th>Quyền</th>
                    <th>Điện Thoại</th>
                    <th>Địa Chỉ</th>
                    <th style="text-align:center; width: 140px;">Thao Tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $i => $user)
                <tr>
                    <td style="color: var(--adm-text-dim);">{{ $i + 1 }}</td>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <div class="avatar-circle">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                            <span style="font-weight:600; color:var(--adm-text-main);">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td style="color: var(--adm-text-muted);">{{ $user->email }}</td>
                    <td>
                        @if($user->quyen === 'Admin')
                            <span class="badge-admin-role"><i class="bi bi-shield-shaded"></i> Admin</span>
                        @elseif($user->quyen === 'Nhân viên')
                            <span class="badge-admin-role"><i class="bi bi-person-gear"></i> Nhân viên</span>
                        @else
                            <span class="badge-customer-role"><i class="bi bi-person"></i> Khách hàng</span>
                        @endif
                    </td>
                    <td style="color: var(--adm-text-muted);">{{ $user->phone ?? '—' }}</td>
                    <td style="color: var(--adm-text-muted); max-width:180px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                        {{ $user->address ?? '—' }}
                    </td>
                    <td style="text-align:center;">
                        <div class="d-flex gap-2 justify-content-center">
                            <button class="btn-edit-action" onclick="moModalSua({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->email }}', '{{ $user->quyen }}', '{{ $user->phone }}', '{{ addslashes($user->address) }}')">
                                <i class="bi bi-pencil-fill"></i> Sửa
                            </button>
                            @if($user->id !== auth()->id())
                            <form method="POST" action="{{ route('nguoidung.xoa', $user->id) }}" onsubmit="return confirm('Xóa người dùng {{ $user->name }}?')">
                                @csrf
                                <button type="submit" class="btn-del-action" title="Xóa tài khoản"><i class="bi bi-trash-fill"></i></button>
                            </form>
                            @else
                            <span style="color: var(--adm-text-dim); font-size:0.75rem; padding:4px 6px;">Bạn</span>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center; padding:50px; color: var(--adm-text-dim);">
                        <i class="bi bi-people" style="font-size:2.5rem; display:block; margin-bottom:10px;"></i>
                        Không tìm thấy tài khoản người dùng nào
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

{{-- Modal Sửa Người Dùng (Robust Overlay) --}}
<div class="user-modal-overlay" id="modalSua" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.82); z-index:1060; align-items:center; justify-content:center; padding:20px; backdrop-filter:blur(5px);">
    <div style="background:var(--adm-bg-surface); border:1px solid var(--adm-border-focus); color:var(--adm-text-main); border-radius:14px; width:100%; max-width:540px; padding:26px; position:relative; box-shadow:0 20px 50px rgba(0,0,0,0.8);">
        <button type="button" onclick="closeUserModal()" style="position:absolute; top:18px; right:20px; background:none; border:none; color:var(--adm-text-muted); font-size:1.5rem; cursor:pointer;">&times;</button>
        <h4 style="color:var(--adm-gold); font-family:var(--adm-font-heading); font-size:1.3rem; margin-bottom:20px; display:flex; align-items:center; gap:8px;">
            <i class="bi bi-pencil-fill me-1"></i> Cập Nhật Thông Tin & Phân Quyền
        </h4>
        <form id="formSua" action="" method="POST">
            @csrf
            <div class="mb-3">
                <label style="color:var(--adm-text-main); font-size:0.84rem; font-weight:600; display:block; margin-bottom:6px;">HỌ TÊN *</label>
                <input type="text" name="name" id="sua_name" class="user-form-control" required>
            </div>
            <div class="mb-3">
                <label style="color:var(--adm-text-main); font-size:0.84rem; font-weight:600; display:block; margin-bottom:6px;">EMAIL ĐĂNG NHẬP *</label>
                <input type="email" name="email" id="sua_email" class="user-form-control" required>
            </div>
            <div class="mb-3">
                <label style="color:var(--adm-text-main); font-size:0.84rem; font-weight:600; display:block; margin-bottom:6px;">PHÂN QUYỀN VAI TRÒ *</label>
                <select name="quyen" id="sua_quyen" class="user-form-select">
                    <option value="Khách hàng">Khách hàng</option>
                    <option value="Nhân viên">Nhân viên</option>
                    <option value="Admin">Admin</option>
                </select>
            </div>
            <div class="mb-3">
                <label style="color:var(--adm-text-main); font-size:0.84rem; font-weight:600; display:block; margin-bottom:6px;">ĐIỆN THOẠI</label>
                <input type="text" name="phone" id="sua_phone" class="user-form-control">
            </div>
            <div class="mb-3">
                <label style="color:var(--adm-text-main); font-size:0.84rem; font-weight:600; display:block; margin-bottom:6px;">ĐỊA CHỈ</label>
                <textarea name="address" id="sua_address" class="user-form-control" rows="2"></textarea>
            </div>
            <div class="d-flex gap-2 justify-content-end mt-4">
                <button type="button" class="btn-reset-filter" onclick="closeUserModal()">Hủy</button>
                <button type="submit" class="btn-gold-filter">
                    <i class="bi bi-save me-1"></i> Lưu Cập Nhật
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function moModalSua(id, name, email, quyen, phone, address) {
    document.getElementById('sua_name').value = name;
    document.getElementById('sua_email').value = email;
    document.getElementById('sua_quyen').value = quyen;
    document.getElementById('sua_phone').value = phone || '';
    document.getElementById('sua_address').value = address || '';
    // Nối đúng Endpoint BE bao gồm cả subfolder (chống 404 trên localhost/demo)
    document.getElementById('formSua').action = '{{ url("/nguoi-dung") }}/' + id + '/sua';

    const modal = document.getElementById('modalSua');
    modal.style.display = 'flex';
    document.body.style.overflow = 'hidden';
}

function closeUserModal() {
    const modal = document.getElementById('modalSua');
    modal.style.display = 'none';
    document.body.style.overflow = '';
}

document.getElementById('modalSua').addEventListener('click', function(e) {
    if (e.target === this) closeUserModal();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeUserModal();
});
</script>
@endsection
