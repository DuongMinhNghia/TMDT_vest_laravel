<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Kingsman Vietnam')</title>

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,700;1,400&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #000;
            color: #f0f0f0;
            font-family: 'Montserrat', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* TOP BAR */
        .top-bar {
            background-color: #0a0a0a;
            padding: 10px 0;
            border-bottom: 1px solid #222;
            font-size: 0.85rem;
        }

        .top-bar a {
            color: #a0a0a0;
            text-decoration: none;
            transition: color 0.3s;
            margin: 0 15px;
        }

        .top-bar a:hover {
            color: #d4af37;
        }

        .top-bar span {
            color: #a0a0a0;
        }

        /* NAVBAR */
        .navbar {
            background: linear-gradient(180deg, #1a1a1a 0%, #0f0f0f 100%);
            border-bottom: 2px solid #d4af37;
            padding: 18px 0;
            box-shadow: 0 2px 15px rgba(212, 175, 55, 0.15);
        }

        .navbar-brand {
            font-family: 'Playfair Display', serif;
            font-size: 2rem;
            color: #d4af37 !important;
            letter-spacing: 3px;
            font-weight: 700;
            text-shadow: 0 0 10px rgba(212, 175, 55, 0.3);
            transition: all 0.3s;
        }

        .navbar-brand:hover {
            color: #f0c864 !important;
            text-shadow: 0 0 15px rgba(212, 175, 55, 0.5);
        }

        .nav-link-main {
            color: #f0f0f0 !important;
            font-weight: 500;
            font-size: 0.95rem;
            padding: 10px 18px !important;
            position: relative;
            border-radius: 4px;
            transition: all 0.3s;
        }

        .nav-link-main::after {
            content: '';
            position: absolute;
            bottom: 5px;
            left: 18px;
            width: 0;
            height: 2px;
            background: #d4af37;
            transition: width 0.3s;
        }

        .nav-link-main:hover {
            color: #d4af37 !important;
            background: rgba(212, 175, 55, 0.1);
        }

        .nav-link-main:hover::after {
            width: calc(100% - 36px);
        }

        /* DROPDOWN */
        .dropdown-hover:hover .dropdown-menu {
            display: block;
            margin-top: 0;
        }

        .dropdown-menu {
            background-color: #1a1a1a;
            border: 1px solid #d4af37;
            border-radius: 4px;
            padding: 0.5rem 0;
        }

        .dropdown-item {
            color: #c0c0c0;
            padding: 10px 20px;
            transition: all 0.3s;
        }

        .dropdown-item:hover {
            background-color: #d4af37;
            color: #000;
        }

        /* BUTTONS */
        .btn-action-gold {
            color: #d4af37 !important;
            border: 2px solid #d4af37 !important;
            background: transparent;
            border-radius: 25px;
            font-size: 0.9rem;
            font-weight: 600;
            padding: 9px 22px;
            transition: all 0.3s;
            letter-spacing: 0.5px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-action-gold:hover {
            background: #d4af37 !important;
            color: #000 !important;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(212, 175, 55, 0.4);
        }

        /* USER INFO */
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
            color: #c0c0c0;
            font-size: 0.9rem;
            padding: 8px 16px;
            background: rgba(42, 42, 42, 0.5);
            border-radius: 25px;
            border: 1px solid #333;
        }

        .user-info strong {
            color: #d4af37;
        }

        /* CART */
        .cart-badge {
            position: relative;
        }

        .cart-badge .badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #d4af37;
            color: #000;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 3px 7px;
            border-radius: 50%;
            border: 2px solid #1a1a1a;
        }

        /* SIDEBAR */
        .sidebar {
            background: linear-gradient(to bottom, #0f0f0f, #1a1a1a);
            border: 1px solid #333;
            border-radius: 5px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
            margin-bottom: 20px;
        }

        .side-heading {
            background: linear-gradient(135deg, #d4af37, #c19b2f) !important;
            color: #000 !important;
            font-family: 'Playfair Display', serif;
            font-weight: 700 !important;
            text-transform: uppercase;
            letter-spacing: 2px;
            padding: 12px;
            text-align: center;
        }

        .sidebar-body {
            padding: 10px;
        }

        .sidebar-link {
            display: block;
            padding: 10px 15px;
            color: #f0f0f0;
            text-decoration: none;
            border-bottom: 1px solid #222;
            transition: all 0.3s;
            font-size: 0.9rem;
        }

        .sidebar-link:last-child {
            border-bottom: none;
        }

        .sidebar-link:hover {
            background: rgba(212, 175, 55, 0.1);
            color: #d4af37;
            padding-left: 20px;
        }

        /* MAIN */
        .main-content {
            flex: 1;
            padding: 30px 0;
        }

        /* FOOTER */
        footer {
            background-color: #0a0a0a;
            border-top: 2px solid #d4af37;
            margin-top: auto;
            padding: 50px 0 30px;
        }

        footer h5 {
            font-family: 'Playfair Display', serif;
            letter-spacing: 2px;
            margin-bottom: 20px;
            color: #d4af37;
        }

        footer a {
            color: #a0a0a0;
            text-decoration: none;
            transition: color 0.3s;
            display: block;
            margin-bottom: 8px;
        }

        footer a:hover {
            color: #d4af37;
            padding-left: 5px;
        }

        footer p {
            color: #a0a0a0;
            line-height: 1.8;
        }

        footer hr {
            border-color: #333 !important;
            margin: 30px 0;
        }

        /* SCROLLBAR */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #1a1a1a;
        }

        ::-webkit-scrollbar-thumb {
            background: #d4af37;
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #c19b2f;
        }
    </style>
</head>

<body>

    {{-- TOP BAR --}}
    <div class="top-bar">
        <div class="container-fluid px-4">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <span><i class="bi bi-clock"></i> Thời gian làm việc: Hàng ngày từ 9h - 21h</span>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="tel:0901234567"><i class="bi bi-telephone"></i> 0901 234 567</a>
                    <a href="mailto:vip@kingsman.vn"><i class="bi bi-envelope"></i> vip@kingsman.vn</a>
                </div>
            </div>
        </div>
    </div>

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg sticky-top">
        <div class="container-fluid px-4">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="bi bi-gem"></i> KINGSMAN
            </a>

            <button class="navbar-toggler border-0" type="button"
                data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon" style="filter:invert(1);"></span>
            </button>

            <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a href="{{ url('/') }}" class="nav-link nav-link-main">
                            <i class="bi bi-house-door"></i> Trang chủ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ url('/san-pham') }}" class="nav-link nav-link-main">
                            <i class="bi bi-grid"></i> Sản phẩm
                        </a>
                    </li>

                    @if(Auth::check() && Auth::user()->quyen == 'Admin')
                        <li class="nav-item">
                            <a href="{{ url('/san-pham/danh-sach') }}" class="nav-link nav-link-main">
                                <i class="bi bi-box-seam"></i> Quản Lý Sản Phẩm
                            </a>
                        </li>

                        <li class="nav-item dropdown dropdown-hover">
                            <a class="nav-link nav-link-main dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-people"></i> Quản Lý Người Dùng
                            </a>
                            <ul class="dropdown-menu">
                                {{-- Chỗ 1: đổi url('/nguoi-dung') → route('nguoidung.index') --}}
                                <li>
                                    <a class="dropdown-item" href="{{ route('nguoidung.index') }}">
                                        <i class="bi bi-person-lines-fill me-2"></i>Danh Sách Tài Khoản
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item dropdown dropdown-hover">
                            <a class="nav-link nav-link-main dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-graph-up-arrow"></i> Thống kê
                            </a>
                            <ul class="dropdown-menu">
                                {{-- Chỗ 2: đổi url('/thong-ke/doanh-thu') → route('thongke.doanhthu') --}}
                                <li>
                                    <a class="dropdown-item" href="{{ route('thongke.doanhthu') }}">
                                        <i class="bi bi-bar-chart me-2"></i>Doanh thu
                                    </a>
                                </li>
                                {{-- Chỗ 3: đổi url('/thong-ke/don-hang') → route('thongke.donhang') --}}
                                <li>
                                    <a class="dropdown-item" href="{{ route('thongke.donhang') }}">
                                        <i class="bi bi-receipt-cutoff me-2"></i>Đơn hàng
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>

                <div class="d-flex align-items-center gap-4 ms-auto justify-content-end">
                    @if(Auth::check())
                    <div class="user-info">
                        <i class="bi bi-person-circle"></i>
                        <span>Xin chào, <strong>{{ Auth::user()->name }}</strong></span>
                    </div>
                    <a href="{{ url('/dang-xuat') }}" class="btn-action-gold" style="margin-left: 12px;margin-right: 12px;">
                        <i class="bi bi-box-arrow-right"></i> Đăng xuất
                    </a>
                    @else
                    <a href="{{ url('/dang-nhap') }}" class="btn-action-gold">
                        <i class="bi bi-person"></i> Đăng nhập
                    </a>
                    @endif

                    @if(Auth::check() && Auth::user()->quyen != 'Admin')
                    <a href="{{ url('/gio-hang') }}" class="btn-action-gold cart-badge">
                        <i class="bi bi-cart3"></i> Giỏ hàng
                        @php $soLuongGioHang = collect(session('gio_hang_' . Auth::id(), []))->sum('so_luong'); @endphp
                        @if($soLuongGioHang > 0)
                        <span class="badge">{{ $soLuongGioHang }}</span>
                        @endif
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    {{-- BODY --}}
    <div class="container-fluid main-content">
        <div class="row px-3">

            @hasSection('sidebar_danhmuc')
            <aside class="col-md-3 col-lg-2 mb-4">
                <div class="sidebar">
                    <div class="side-heading"><i class="bi bi-list-ul me-1"></i> DANH MỤC</div>
                    <div class="sidebar-body">
                        @yield('sidebar_danhmuc')
                    </div>
                </div>
            </aside>
            <main class="col-md-9 col-lg-10">
                @else
                <main class="col-12">
                    @endif
                    @yield('content')
                </main>

        </div>
    </div>

    {{-- FOOTER --}}
    <footer>
        <div class="container">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <h5><i class="bi bi-gem"></i> KINGSMAN</h5>
                    <p>Đẳng cấp quý ông. Mang đến những bộ vest sang trọng, lịch lãm và hoàn hảo cho mọi sự kiện.</p>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>LIÊN KẾT</h5>
                    <a href="{{ url('/') }}">Trang chủ</a>
                    <a href="{{ url('/san-pham') }}">Bộ sưu tập</a>
                    <a href="{{ url('/gio-hang') }}">Giỏ hàng</a>
                </div>
                <div class="col-md-4 mb-4">
                    <h5>LIÊN HỆ</h5>
                    <p><i class="bi bi-geo-alt-fill" style="color:#d4af37;"></i> 123 Đồng Khởi, Quận 1, TP.HCM</p>
                    <p><i class="bi bi-telephone-fill" style="color:#d4af37;"></i> 0901 234 567</p>
                    <p><i class="bi bi-envelope-fill" style="color:#d4af37;"></i> vip@kingsman.vn</p>
                </div>
            </div>
            <hr>
            <div class="text-center">
                <small style="color:#666;">&copy; 2026 Kingsman Bespoke. All rights reserved.</small>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Active nav highlight
        const currentPath = window.location.pathname;
        document.querySelectorAll('.nav-link-main').forEach(link => {
            if (link.getAttribute('href') === currentPath) {
                link.style.color = '#d4af37';
                link.style.backgroundColor = 'rgba(212, 175, 55, 0.1)';
            }
        });

        // Carousel
        document.addEventListener('DOMContentLoaded', function() {
            var el = document.querySelector('#heroCarousel');
            if (el && typeof bootstrap !== 'undefined') {
                new bootstrap.Carousel(el, {
                    interval: 4000,
                    ride: 'carousel',
                    touch: true
                });
            }
        });
    </script>

    @yield('scripts')
</body>

</html>