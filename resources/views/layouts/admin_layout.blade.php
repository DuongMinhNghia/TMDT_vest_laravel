<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Quản Trị Hệ Thống - Kingsman Vietnam')</title>

    <!-- Google Fonts: Playfair Display + Plus Jakarta Sans (Full Vietnamese Support) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons & Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" />

    <style>
        :root {
            --adm-bg-main: #111317;
            --adm-bg-surface: #141720;
            --adm-bg-card: #181B24;
            --adm-bg-hover: rgba(212, 178, 111, 0.08);
            --adm-border: rgba(255, 255, 255, 0.08);
            --adm-border-focus: rgba(212, 178, 111, 0.4);
            --adm-gold: #D4B26F;
            --adm-gold-light: #E8CA8C;
            --adm-gold-glow: rgba(212, 178, 111, 0.15);
            --adm-text-main: #F1F5F9;
            --adm-text-muted: #94A3B8;
            --adm-text-dim: #64748B;
            --adm-sidebar-width: 250px;
            --adm-topbar-height: 60px;
            --adm-font-heading: 'Playfair Display', Georgia, serif;
            --adm-font-body: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--adm-bg-main);
            color: var(--adm-text-main);
            font-family: var(--adm-font-body);
            font-size: 0.88rem;
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* SIDEBAR (CỐ ĐỊNH TRÁI) */
        .admin-sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: var(--adm-sidebar-width);
            background-color: var(--adm-bg-surface);
            border-right: 1px solid var(--adm-border);
            z-index: 1040;
            display: flex;
            flex-direction: column;
            transition: transform 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .sidebar-brand-box {
            height: var(--adm-topbar-height);
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-bottom: 1px solid var(--adm-border);
        }

        .sidebar-brand {
            font-family: var(--adm-font-heading);
            font-size: 1.25rem;
            color: var(--adm-gold);
            text-decoration: none;
            font-weight: 700;
            letter-spacing: 1.5px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .sidebar-brand i {
            font-size: 1.1rem;
        }

        .badge-admin-portal {
            background: rgba(212, 178, 111, 0.15);
            color: var(--adm-gold);
            border: 1px solid rgba(212, 178, 111, 0.3);
            font-size: 0.65rem;
            font-weight: 700;
            padding: 2px 6px;
            border-radius: 4px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .sidebar-menu-wrapper {
            flex: 1;
            overflow-y: auto;
            padding: 18px 12px;
        }

        .sidebar-group-title {
            color: var(--adm-text-dim);
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            padding: 10px 12px 6px;
            margin-top: 6px;
        }

        .sidebar-nav-link {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            color: var(--adm-text-muted);
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            font-size: 0.84rem;
            transition: all 0.2s ease;
            margin-bottom: 2px;
        }

        .sidebar-nav-link i {
            font-size: 0.95rem;
            width: 18px;
            text-align: center;
            color: var(--adm-text-dim);
            transition: color 0.2s;
        }

        .sidebar-nav-link:hover {
            color: var(--adm-text-main);
            background: var(--adm-bg-hover);
        }

        .sidebar-nav-link:hover i {
            color: var(--adm-gold);
        }

        .sidebar-nav-link.active {
            color: var(--adm-gold);
            background: rgba(212, 178, 111, 0.12);
            font-weight: 600;
            border-left: 3px solid var(--adm-gold);
        }

        .sidebar-nav-link.active i {
            color: var(--adm-gold);
        }

        /* TOPBAR (CỐ ĐỊNH TRÊN) */
        .admin-topbar {
            position: fixed;
            top: 0;
            left: var(--adm-sidebar-width);
            right: 0;
            height: var(--adm-topbar-height);
            background-color: var(--adm-bg-surface);
            border-bottom: 1px solid var(--adm-border);
            z-index: 1030;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: left 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .btn-toggle-sidebar {
            background: transparent;
            border: 1px solid var(--adm-border);
            color: var(--adm-text-muted);
            border-radius: 6px;
            width: 34px;
            height: 34px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-toggle-sidebar:hover {
            color: var(--adm-gold);
            border-color: var(--adm-gold);
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .btn-view-store {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border: 1px solid var(--adm-border);
            color: var(--adm-text-muted);
            border-radius: 20px;
            font-size: 0.78rem;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-view-store:hover {
            color: var(--adm-gold);
            border-color: var(--adm-gold);
            background: var(--adm-bg-hover);
        }

        .admin-profile-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 5px 12px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid var(--adm-border);
            border-radius: 20px;
            font-size: 0.8rem;
            color: var(--adm-text-main);
        }

        .admin-profile-pill strong {
            color: var(--adm-gold);
            font-weight: 600;
        }

        .btn-admin-logout {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 12px;
            border: 1px solid rgba(239, 68, 68, 0.4);
            background: transparent;
            color: #ef4444;
            border-radius: 20px;
            font-size: 0.78rem;
            text-decoration: none;
            transition: all 0.2s;
        }

        .btn-admin-logout:hover {
            background: rgba(239, 68, 68, 0.1);
            color: #f87171;
            border-color: #ef4444;
        }

        /* MAIN CONTENT AREA */
        .admin-main-wrap {
            margin-left: var(--adm-sidebar-width);
            padding-top: var(--adm-topbar-height);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: margin-left 0.3s cubic-bezier(0.165, 0.84, 0.44, 1);
        }

        .admin-content-body {
            flex: 1;
            padding: 24px;
        }

        .admin-footer {
            padding: 16px 24px;
            border-top: 1px solid var(--adm-border);
            font-size: 0.75rem;
            color: var(--adm-text-dim);
            text-align: center;
        }

        /* COMPONENT THƯỜNG DÙNG: ALERTS */
        .admin-alert {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 18px;
            border-radius: 8px;
            font-size: 0.85rem;
            margin-bottom: 20px;
            border: 1px solid transparent;
        }

        .admin-alert-success {
            background: rgba(16, 185, 129, 0.1);
            border-color: rgba(16, 185, 129, 0.3);
            color: #34d399;
        }

        .admin-alert-error {
            background: rgba(239, 68, 68, 0.1);
            border-color: rgba(239, 68, 68, 0.3);
            color: #f87171;
        }

        .admin-alert-warning {
            background: rgba(245, 158, 11, 0.1);
            border-color: rgba(245, 158, 11, 0.3);
            color: #fbbf24;
        }

        /* MOBILE RESPONSIVE */
        @media (max-width: 991.98px) {
            .admin-sidebar {
                transform: translateX(-100%);
            }

            .admin-sidebar.show {
                transform: translateX(0);
                box-shadow: 10px 0 30px rgba(0, 0, 0, 0.5);
            }

            .admin-topbar {
                left: 0;
            }

            .admin-main-wrap {
                margin-left: 0;
            }

            .sidebar-overlay {
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.6);
                z-index: 1035;
                display: none;
            }

            .sidebar-overlay.show {
                display: block;
            }
        }

        @media (max-width: 768px) {
            .admin-content-body {
                padding: 14px 12px;
            }
            .admin-topbar {
                padding: 0 12px;
            }
            .admin-page-title {
                font-size: 1.1rem;
            }
        }
    </style>

    @yield('styles')
</head>

<body>
    <!-- SIDEBAR OVERLAY CHO MOBILE -->
    <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleAdminSidebar()"></div>

    <!-- ADMIN SIDEBAR -->
    <aside class="admin-sidebar" id="adminSidebar">
        <div class="sidebar-brand-box">
            <a href="{{ url('/') }}" class="sidebar-brand">
                <i class="bi bi-gem"></i> KINGSMAN
            </a>
            <span class="badge-admin-portal">Admin</span>
        </div>

        <div class="sidebar-menu-wrapper">
            <!-- NHÓM CỬA HÀNG -->
            <div class="sidebar-group-title">Quản Lý Cửa Hàng</div>

            <a href="{{ url('/san-pham/danh-sach') }}" class="sidebar-nav-link {{ request()->is('san-pham/danh-sach*') || request()->is('san-pham/danhsach*') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i>
                <span>Sản Phẩm & Kho Size</span>
            </a>

            <a href="{{ route('bosuutap.danhsach') }}" class="sidebar-nav-link {{ request()->is('bo-suu-tap*') ? 'active' : '' }}">
                <i class="bi bi-collection-play"></i>
                <span>Bộ Sưu Tập</span>
            </a>

            <a href="{{ route('giamgia.danhsach') }}" class="sidebar-nav-link {{ request()->is('giam-gia*') ? 'active' : '' }}">
                <i class="bi bi-tag"></i>
                <span>Khuyến Mãi Flash Sale</span>
            </a>

            <!-- NHÓM KHÁCH HÀNG & DỊCH VỤ -->
            <div class="sidebar-group-title">Dịch Vụ & Khách Hàng</div>

            <a href="{{ route('lichhen.quanly') }}" class="sidebar-nav-link {{ request()->is('quan-ly-lich-hen*') ? 'active' : '' }}">
                <i class="bi bi-calendar2-check"></i>
                <span>Lịch Hẹn Showroom</span>
            </a>

            <a href="{{ route('hangthanhvien.index') }}" class="sidebar-nav-link {{ request()->is('hang-thanh-vien*') ? 'active' : '' }}">
                <i class="bi bi-award"></i>
                <span>Chính Sách Hạng VIP</span>
            </a>

            <a href="{{ route('nguoidung.index') }}" class="sidebar-nav-link {{ request()->is('nguoi-dung*') ? 'active' : '' }}">
                <i class="bi bi-people"></i>
                <span>Danh Sách Tài Khoản</span>
            </a>

            <!-- NHÓM THỐNG KÊ -->
            <div class="sidebar-group-title">Báo Cáo & Thống Kê</div>

            <a href="{{ route('thongke.doanhthu') }}" class="sidebar-nav-link {{ request()->is('thong-ke/doanh-thu*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart"></i>
                <span>Thống Kê Doanh Thu</span>
            </a>

            <a href="{{ route('thongke.donhang') }}" class="sidebar-nav-link {{ request()->is('thong-ke/don-hang*') ? 'active' : '' }}">
                <i class="bi bi-receipt-cutoff"></i>
                <span>Thống Kê Đơn Hàng</span>
            </a>
        </div>
    </aside>

    <!-- ADMIN TOPBAR -->
    <header class="admin-topbar" id="adminTopbar">
        <div class="topbar-left">
            <button type="button" class="btn-toggle-sidebar" onclick="toggleAdminSidebar()" title="Đóng/Mở Sidebar">
                <i class="bi bi-list fs-5"></i>
            </button>
            <div class="d-none d-md-block text-muted" style="font-size: 0.82rem;">
                <span class="text-secondary">Kingsman Portal</span> / <span class="text-light">@yield('page_name', 'Quản Trị')</span>
            </div>
        </div>

        <div class="topbar-right">
            <a href="{{ url('/san-pham') }}" target="_blank" class="btn-view-store">
                <i class="bi bi-globe2"></i>
                <span>Xem Cửa Hàng</span>
            </a>

            @if(Auth::check())
            <div class="admin-profile-pill">
                <i class="bi bi-person-circle"></i>
                <span>{{ Auth::user()->name ?: 'Quản trị viên' }}</span>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn-admin-logout" title="Đăng xuất khỏi hệ thống">
                <i class="bi bi-box-arrow-right"></i>
                <span class="d-none d-sm-inline">Đăng xuất</span>
                </button>
            </form>
            @endif
        </div>
    </header>

    <!-- MAIN CONTENT WRAPPER -->
    <div class="admin-main-wrap">
        <main class="admin-content-body">
            <!-- NỘI DUNG TRANG CON -->
            @yield('content')
        </main>

        <footer class="admin-footer">
            &copy; {{ date('Y') }} Kingsman Vietnam — Hệ Thống Quản Trị Thương Mại Điện Tử & May Đo Âu Phục Cao Cấp
        </footer>
    </div>

    <!-- jQuery & Bootstrap 5 JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleAdminSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('sidebarOverlay');
            if (sidebar) sidebar.classList.toggle('show');
            if (overlay) overlay.classList.toggle('show');
        }
    </script>

    @include('partials.system-popup')
    @yield('scripts')
</body>

</html>
