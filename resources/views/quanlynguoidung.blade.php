@extends('layouts.default_layout')

@section('title', 'Quản Lý Người Dùng - Kingsman')

@section('content')
<style>
    .admin-wrap { background: linear-gradient(to bottom, #0a0a0a, #000); min-height: 100vh; padding: 30px 20px; }
    .page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; padding-bottom: 15px; border-bottom: 2px solid #d4af37; }
    .page-header h2 { color: #d4af37; font-size: 1.8rem; font-weight: 700; margin: 0; letter-spacing: 2px; }
    .stat-card { background: linear-gradient(135deg, #1a1a1a, #2a2a2a); border: 1px solid #333; border-radius: 12px; padding: 20px; text-align: center; transition: all 0.3s; }
    .stat-card:hover { border-color: #d4af37; transform: translateY(-3px); box-shadow: 0 10px 30px rgba(212,175,55,0.2); }
    .stat-card .number { font-size: 2.2rem; font-weight: 700; color: #d4af37; }
    .stat-card .label { color: #888; font-size: 0.85rem; margin-top: 5px; letter-spacing: 1px; }
    .stat-card .icon { font-size: 1.8rem; margin-bottom: 10px; }
    .search-bar { background: #1a1a1a; border: 1px solid #333; border-radius: 12px; padding: 20px; margin-bottom: 25px; }
    .form-control-dark { background: #0f0f0f !important; border: 1px solid #444 !important; color: #fff !important; border-radius: 8px !important; }
    .form-control-dark:focus { border-color: #d4af37 !important; box-shadow: 0 0 0 2px rgba(212,175,55,0.2) !important; }
    .form-select-dark { background: #0f0f0f !important; border: 1px solid #444 !important; color: #fff !important; border-radius: 8px !important; }
    .table-wrap { background: #1a1a1a; border: 1px solid #333; border-radius: 12px; overflow: hidden; }
    .table-dark-custom { color: #e0e0e0; margin: 0; }
    .table-dark-custom thead th { background: #1F4E79; color: #fff; border: none; padding: 15px 12px; font-size: 0.85rem; letter-spacing: 1px; text-transform: uppercase; }
    .table-dark-custom tbody tr { border-bottom: 1px solid #2a2a2a; transition: background 0.2s; }
    .table-dark-custom tbody tr:hover { background: rgba(212,175,55,0.05); }
    .table-dark-custom tbody td { padding: 14px 12px; vertical-align: middle; border: none; }
    .badge-admin { background: linear-gradient(135deg, #d4af37, #c19b2f); color: #000; padding: 4px 12px; border-radius: 20px; font-weight: 700; font-size: 0.78rem; }
    .badge-khach { background: #1a3a5c; color: #7eb8f7; padding: 4px 12px; border-radius: 20px; font-weight: 600; font-size: 0.78rem; }
    .btn-edit { background: transparent; border: 1px solid #d4af37; color: #d4af37; padding: 6px 14px; border-radius: 6px; font-size: 0.82rem; transition: all 0.2s; }
    .btn-edit:hover { background: #d4af37; color: #000; }
    .btn-del { background: transparent; border: 1px solid #c00; color: #ff6b6b; padding: 6px 14px; border-radius: 6px; font-size: 0.82rem; transition: all 0.2s; }
    .btn-del:hover { background: #c00; color: #fff; }
    .avatar { width: 38px; height: 38px; border-radius: 50%; background: linear-gradient(135deg, #1F4E79, #d4af37); display: flex; align-items: center; justify-content: center; font-weight: 700; color: #fff; font-size: 0.9rem; }
    .modal-dark .modal-content { background: #1a1a1a; border: 1px solid #d4af37; color: #e0e0e0; }
    .modal-dark .modal-header { border-bottom: 1px solid #333; }
    .modal-dark .modal-footer { border-top: 1px solid #333; }
    .modal-dark .modal-title { color: #d4af37; font-weight: 700; }
    .modal-dark .btn-close { filter: invert(1); }
    .alert-success-custom { background: #0d2b0d; border: 1px solid #2d6a2d; color: #6fcf6f; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; }
    .alert-error-custom { background: #2b0d0d; border: 1px solid #6a2d2d; color: #cf6f6f; border-radius: 8px; padding: 12px 16px; margin-bottom: 20px; }
</style>

<div class="admin-wrap">
    <div class="container-fluid">

        {{-- Header --}}
        <div class="page-header">
            <h2><i class="bi bi-people-fill me-2"></i>Quản Lý Người Dùng</h2>
        </div>

        {{-- Alert --}}
        @if(session('success'))
        <div class="alert-success-custom"><i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}</div>
        @endif
        @if(session('error'))
        <div class="alert-error-custom"><i class="bi bi-x-circle-fill me-2"></i>{{ session('error') }}</div>
        @endif

        {{-- Thống kê nhanh --}}
        <div class="row g-3 mb-4">
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="icon">👥</div>
                    <div class="number">{{ $users->count() }}</div>
                    <div class="label">Tổng người dùng</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="icon">🔑</div>
                    <div class="number">{{ $users->where('quyen','Admin')->count() }}</div>
                    <div class="label">Admin</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="icon">🛍️</div>
                    <div class="number">{{ $users->where('quyen','Khách hàng')->count() }}</div>
                    <div class="label">Khách hàng</div>
                </div>
            </div>
            <div class="col-md-3 col-6">
                <div class="stat-card">
                    <div class="icon">📋</div>
                    <div class="number">{{ $users->whereNotNull('phone')->count() }}</div>
                    <div class="label">Có số điện thoại</div>
                </div>
            </div>
        </div>

        {{-- Tìm kiếm & Lọc --}}
        <div class="search-bar">
            <form method="GET" action="{{ route('nguoidung.index') }}" class="row g-3 align-items-end">
                <div class="col-md-5">
                    <label style="color:#888; font-size:0.82rem; margin-bottom:6px;">TÌM KIẾM</label>
                    <input type="text" name="tim_kiem" value="{{ request('tim_kiem') }}"
                        placeholder="Tên hoặc email..."
                        class="form-control form-control-dark">
                </div>
                <div class="col-md-3">
                    <label style="color:#888; font-size:0.82rem; margin-bottom:6px;">QUYỀN</label>
                    <select name="quyen" class="form-select form-select-dark">
                        <option value="">-- Tất cả --</option>
                        <option value="Admin" {{ request('quyen')=='Admin'?'selected':'' }}>Admin</option>
                        <option value="Khách hàng" {{ request('quyen')=='Khách hàng'?'selected':'' }}>Khách hàng</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex gap-2">
                    <button type="submit" style="background: linear-gradient(135deg,#d4af37,#c19b2f); border:none; color:#000; padding:10px 20px; border-radius:8px; font-weight:700; flex:1;">
                        <i class="bi bi-search me-1"></i> Tìm
                    </button>
                    <a href="{{ route('nguoidung.index') }}" style="background:transparent; border:1px solid #555; color:#aaa; padding:10px 16px; border-radius:8px; text-decoration:none; display:flex; align-items:center; gap:5px;">
                        <i class="bi bi-x-circle"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Bảng người dùng --}}
        <div class="table-wrap">
            <table class="table table-dark-custom">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Người dùng</th>
                        <th>Email</th>
                        <th>Quyền</th>
                        <th>Điện thoại</th>
                        <th>Địa chỉ</th>
                        <th style="text-align:center;">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $i => $user)
                    <tr>
                        <td style="color:#555;">{{ $i + 1 }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <div class="avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                <span style="font-weight:600;">{{ $user->name }}</span>
                            </div>
                        </td>
                        <td style="color:#aaa;">{{ $user->email }}</td>
                        <td>
                            @if($user->quyen === 'Admin')
                                <span class="badge-admin">👑 Admin</span>
                            @else
                                <span class="badge-khach">🛍 Khách hàng</span>
                            @endif
                        </td>
                        <td style="color:#aaa;">{{ $user->phone ?? '—' }}</td>
                        <td style="color:#aaa; max-width:180px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">
                            {{ $user->address ?? '—' }}
                        </td>
                        <td style="text-align:center;">
                            <div class="d-flex gap-2 justify-content-center">
                                <button class="btn-edit" onclick="moModalSua({{ $user->id }}, '{{ addslashes($user->name) }}', '{{ $user->email }}', '{{ $user->quyen }}', '{{ $user->phone }}', '{{ addslashes($user->address) }}')">
                                    <i class="bi bi-pencil-fill me-1"></i>Sửa
                                </button>
                                @if($user->id !== auth()->id())
                                <form method="POST" action="{{ route('nguoidung.xoa', $user->id) }}" onsubmit="return confirm('Xóa người dùng {{ $user->name }}?')">
                                    @csrf
                                    <button type="submit" class="btn-del"><i class="bi bi-trash-fill me-1"></i>Xóa</button>
                                </form>
                                @else
                                <span style="color:#555; font-size:0.8rem; padding:6px 8px;">Tài khoản bạn</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" style="text-align:center; padding:50px; color:#555;">
                            <i class="bi bi-inbox" style="font-size:2.5rem; display:block; margin-bottom:10px;"></i>
                            Không tìm thấy người dùng nào
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</div>

{{-- Modal Sửa --}}
<div class="modal fade modal-dark" id="modalSua" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-pencil-fill me-2"></i>Sửa Người Dùng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="formSua" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label style="color:#aaa; font-size:0.85rem;">HỌ TÊN *</label>
                        <input type="text" name="name" id="sua_name" class="form-control form-control-dark mt-1" required>
                    </div>
                    <div class="mb-3">
                        <label style="color:#aaa; font-size:0.85rem;">EMAIL *</label>
                        <input type="email" name="email" id="sua_email" class="form-control form-control-dark mt-1" required>
                    </div>
                    <div class="mb-3">
                        <label style="color:#aaa; font-size:0.85rem;">QUYỀN *</label>
                        <select name="quyen" id="sua_quyen" class="form-select form-select-dark mt-1">
                            <option value="Khách hàng">Khách hàng</option>
                            <option value="Admin">Admin</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label style="color:#aaa; font-size:0.85rem;">ĐIỆN THOẠI</label>
                        <input type="text" name="phone" id="sua_phone" class="form-control form-control-dark mt-1">
                    </div>
                    <div class="mb-3">
                        <label style="color:#aaa; font-size:0.85rem;">ĐỊA CHỈ</label>
                        <textarea name="address" id="sua_address" class="form-control form-control-dark mt-1" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-del" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" style="background:linear-gradient(135deg,#d4af37,#c19b2f); border:none; color:#000; padding:10px 24px; border-radius:8px; font-weight:700;">
                        <i class="bi bi-save me-1"></i>Lưu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function moModalSua(id, name, email, quyen, phone, address) {
    document.getElementById('sua_name').value = name;
    document.getElementById('sua_email').value = email;
    document.getElementById('sua_quyen').value = quyen;
    document.getElementById('sua_phone').value = phone || '';
    document.getElementById('sua_address').value = address || '';
    document.getElementById('formSua').action = '/nguoi-dung/' + id + '/sua';
    new bootstrap.Modal(document.getElementById('modalSua')).show();
}
</script>
@endsection
