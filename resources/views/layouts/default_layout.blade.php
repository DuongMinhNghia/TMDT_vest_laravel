<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Kingsman Vietnam')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,400;1,600&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" />

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            overflow-x: hidden;
            max-width: 100vw;
        }

        body {
            background-color: #111317;
            color: #f1f5f9;
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        h1, h2, h3, .navbar-brand {
            font-family: 'Playfair Display', Georgia, serif;
        }

        /* TOP BAR */
        .top-bar {
            background-color: #0c0e12;
            padding: 6px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
            font-size: 0.78rem;
            color: #94a3b8;
        }

        .top-bar a {
            color: #94a3b8;
            text-decoration: none;
            transition: color 0.25s;
            margin: 0 12px;
        }

        .top-bar a:hover {
            color: #D4B26F;
        }

        .top-bar span {
            color: #94a3b8;
        }

        /* NAVBAR */
        .navbar {
            background: #141720 !important;
            border-bottom: 1px solid rgba(212, 178, 111, 0.25) !important;
            padding: 8px 0 !important;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.35);
        }

        .navbar-brand {
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 1.35rem !important;
            color: #D4B26F !important;
            letter-spacing: 2px;
            font-weight: 700;
            text-shadow: none !important;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 0 !important;
            margin-right: 22px;
            transition: all 0.3s;
        }

        .navbar-brand i {
            font-size: 1.15rem;
        }

        .navbar-brand:hover {
            color: #E8CA8C !important;
        }

        .nav-link-main {
            color: #cbd5e1 !important;
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            font-weight: 500 !important;
            font-size: 0.83rem !important;
            padding: 6px 11px !important;
            margin: 0 1px;
            border-radius: 6px;
            white-space: nowrap !important;
            display: inline-flex !important;
            align-items: center !important;
            gap: 6px !important;
            position: relative;
            transition: all 0.25s ease;
        }

        .nav-link-main i {
            font-size: 0.88rem;
            color: #94a3b8;
            transition: color 0.25s;
        }

        .nav-link-main:hover {
            color: #D4B26F !important;
            background: rgba(212, 178, 111, 0.1);
        }

        .nav-link-main:hover i {
            color: #D4B26F;
        }

        .nav-link-main::after {
            display: none !important;
        }

        /* DROPDOWN */
        .dropdown-hover:hover .dropdown-menu {
            display: block;
            margin-top: 0;
        }

        .dropdown-menu {
            background-color: #161922;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 6px 0;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
        }

        .dropdown-item {
            color: #cbd5e1;
            font-size: 0.82rem;
            font-family: 'Plus Jakarta Sans', sans-serif;
            padding: 8px 16px;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .dropdown-item:hover {
            background-color: rgba(212, 178, 111, 0.15);
            color: #D4B26F;
        }

        /* BUTTONS */
        .btn-action-gold {
            color: #D4B26F !important;
            border: 1px solid rgba(212, 178, 111, 0.5) !important;
            background: transparent;
            border-radius: 20px;
            font-size: 0.8rem !important;
            font-weight: 600;
            padding: 5px 14px !important;
            transition: all 0.25s;
            letter-spacing: 0.3px;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            text-decoration: none;
            white-space: nowrap;
        }

        .btn-action-gold:hover {
            background: #D4B26F !important;
            color: #111317 !important;
            box-shadow: 0 3px 12px rgba(212, 178, 111, 0.25);
        }

        /* USER INFO */
        .user-info {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #cbd5e1;
            font-size: 0.8rem;
            padding: 5px 12px;
            background: rgba(255, 255, 255, 0.05);
            border-radius: 20px;
            border: 1px solid rgba(255, 255, 255, 0.08);
            white-space: nowrap;
            cursor: pointer;
        }

        .user-info strong {
            color: #D4B26F;
            font-weight: 600;
        }

        .user-info:hover,
        .user-info:focus-visible {
            border-color: rgba(212, 178, 111, 0.6);
            color: #fff;
            outline: none;
        }

        .user-info::after {
            margin-left: 4px;
            color: #D4B26F;
        }

        .user-menu {
            min-width: 190px;
            margin-top: 8px !important;
        }

        .user-menu .dropdown-item {
            border: 0;
            background: transparent;
            width: 100%;
            text-align: left;
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

        /* NAVBAR TOGGLER LUXURY */
        .navbar-toggler {
            border: 1px solid rgba(212, 178, 111, 0.45) !important;
            background: rgba(212, 178, 111, 0.08) !important;
            padding: 5px 9px !important;
            border-radius: 7px !important;
            display: inline-flex !important;
            align-items: center;
            justify-content: center;
            outline: none !important;
            transition: all 0.25s ease;
        }

        .navbar-toggler:focus {
            box-shadow: 0 0 0 2px rgba(212, 178, 111, 0.35) !important;
        }

        .navbar-toggler:hover {
            background: rgba(212, 178, 111, 0.18) !important;
        }

        /* RESPONSIVE MOBILE CHO DEFAULT LAYOUT */
        @media (max-width: 991.98px) {
            .navbar-collapse {
                background: #141720;
                border: 1px solid rgba(212, 178, 111, 0.2);
                border-radius: 12px;
                padding: 16px;
                margin-top: 12px;
                box-shadow: 0 10px 30px rgba(0, 0, 0, 0.6);
            }
            .navbar-nav {
                align-items: flex-start !important;
                gap: 4px;
                width: 100%;
            }
            .nav-link-main {
                width: 100%;
                padding: 10px 14px !important;
                font-size: 0.9rem !important;
            }
            .top-bar {
                font-size: 0.72rem;
                padding: 5px 0;
            }
            .top-bar .row {
                gap: 4px;
            }
            .main-content {
                padding: 15px 0;
            }
        }

        @media (max-width: 768px) {
            html {
                font-size: 15px;
            }
            .navbar-brand {
                font-size: 1.18rem !important;
                letter-spacing: 1px;
                margin-right: 0;
            }
            .container-fluid.px-4 {
                padding-left: 12px !important;
                padding-right: 12px !important;
            }
            .main-content {
                padding: 12px 0;
            }
            footer {
                padding: 35px 0 20px;
            }
            footer h5 {
                margin-bottom: 12px;
                font-size: 1.1rem;
            }
        }
    </style>
    @yield('styles')
</head>

<body>

    {{-- TOP BAR --}}
    <div class="top-bar">
        <div class="container-fluid px-3 px-md-4">
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

    @php
        $khId = (Auth::check() && Auth::user()->isCustomer()) ? optional(Auth::user()->khachHang)->id : null;
        $soLuongGioHang = $khId
            ? \App\Models\ChiTietGioHang::whereHas('gioHang', fn($q) => $q->where('khach_hang_id', $khId))->sum('so_luong')
            : 0;
    @endphp

    {{-- NAVBAR --}}
    <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
        <div class="container-fluid px-3 px-md-4">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="bi bi-gem"></i> KINGSMAN
            </a>

            {{-- MOBILE ACTION CLUSTER (Hiển thị ngay trên mobile, không bị ẩn vào menu) --}}
            <div class="d-flex align-items-center gap-2 d-lg-none ms-auto">
                @if(Auth::check() && Auth::user()->isCustomer())
                <a href="{{ url('/gio-hang') }}" class="btn-action-gold cart-badge py-1 px-2" style="font-size: 0.8rem; text-decoration: none;">
                    <i class="bi bi-cart3"></i>
                    @if($soLuongGioHang > 0)
                    <span class="badge">{{ $soLuongGioHang }}</span>
                    @endif
                </a>
                @endif

                @if(!Auth::check())
                <a href="{{ url('/dang-nhap') }}" class="btn-action-gold py-1 px-2.5" style="font-size: 0.78rem; font-weight: 600; text-decoration: none;">
                    <i class="bi bi-person"></i> Đăng nhập
                </a>
                @else
                <a href="{{ Auth::user()->isCustomer() ? route('khachhang.dashboard') : url('/san-pham/danh-sach') }}" class="btn-action-gold py-1 px-2" style="font-size: 0.8rem; text-decoration: none;" title="{{ Auth::user()->name }}">
                    <i class="bi bi-person-circle"></i>
                </a>
                @endif

                <button class="navbar-toggler" type="button"
                    data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <i class="bi bi-list" style="font-size: 1.55rem; color: #D4B26F; line-height: 1;"></i>
                </button>
            </div>

            <div class="collapse navbar-collapse justify-content-between" id="navbarNav">
                <ul class="navbar-nav align-items-center">
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
                    <li class="nav-item">
                        @if(Auth::check() && !Auth::user()->isCustomer())
                            <a href="{{ route('lichhen.quanly') }}" class="nav-link nav-link-main">
                                <i class="bi bi-calendar2-check"></i> Quản Lý Lịch Hẹn
                            </a>
                        @else
                            <a href="{{ route('lichhen.create') }}" class="nav-link nav-link-main">
                                <i class="bi bi-calendar2-heart"></i> Đặt Lịch Hẹn
                            </a>
                        @endif
                    </li>

                    @if(Auth::check() && (Auth::user()->vai_tro == 'admin' || Auth::user()->quyen == 'Admin'))
                        {{-- MENU QUẢN LÝ CỬA HÀNG --}}
                        <li class="nav-item dropdown dropdown-hover">
                            <a class="nav-link nav-link-main dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-shop"></i> Quản Lý Cửa Hàng
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ url('/san-pham/danh-sach') }}">
                                        <i class="bi bi-box-seam me-2"></i>Quản Lý Sản Phẩm
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('bosuutap.danhsach') }}">
                                        <i class="bi bi-collection-play me-2"></i>Bộ Sưu Tập
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('giamgia.danhsach') }}">
                                        <i class="bi bi-tag-fill me-2 text-danger"></i>Khuyến Mãi
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('lichhen.quanly') }}">
                                        <i class="bi bi-calendar2-check me-2"></i>Quản Lý Lịch Hẹn
                                    </a>
                                </li>
                            </ul>
                        </li>

                        {{-- MENU HỆ THỐNG & THỐNG KÊ --}}
                        <li class="nav-item dropdown dropdown-hover">
                            <a class="nav-link nav-link-main dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-gear-wide-connected"></i> Hệ Thống & Thống Kê
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="dropdown-item" href="{{ route('nguoidung.index') }}">
                                        <i class="bi bi-person-lines-fill me-2"></i>Danh Sách Tài Khoản
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('hangthanhvien.index') }}">
                                        <i class="bi bi-award me-2"></i>Chính Sách Hạng VIP
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider" style="border-color: rgba(255,255,255,0.08);"></li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('thongke.doanhthu') }}">
                                        <i class="bi bi-bar-chart me-2"></i>Thống Kê Doanh Thu
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('thongke.donhang') }}">
                                        <i class="bi bi-receipt-cutoff me-2"></i>Thống Kê Đơn Hàng
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @endif
                </ul>

                {{-- DESKTOP ACTION BAR (Ẩn trên mobile) --}}
                <div class="d-none d-lg-flex align-items-center gap-2 ms-auto justify-content-end">
                    @if(Auth::check() && Auth::user()->isCustomer())
                    <a href="{{ url('/gio-hang') }}" class="btn-action-gold cart-badge">
                        <i class="bi bi-cart3"></i> Giỏ hàng
                        @if($soLuongGioHang > 0)
                        <span class="badge">{{ $soLuongGioHang }}</span>
                        @endif
                    </a>
                    @endif

                    @if(Auth::check())
                    <div class="dropdown">
                        <button class="user-info dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person-circle"></i>
                            <span>Xin chào, <strong>{{ Auth::user()->name }}</strong></span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end user-menu">
                            @if(Auth::user()->isCustomer())
                            <li>
                                <a class="dropdown-item" href="{{ route('khachhang.dashboard') }}">
                                    <i class="bi bi-person-badge me-2"></i>Hồ Sơ & VIP
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('donhang.lichsu') }}">
                                    <i class="bi bi-receipt me-2"></i>Đơn hàng của tôi
                                </a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('khachhang.dashboard') }}#lichhen">
                                    <i class="bi bi-calendar2-heart me-2"></i>Lịch hẹn của tôi
                                </a>
                            </li>
                            <li><hr class="dropdown-divider" style="border-color: rgba(255,255,255,0.08);"></li>
                            @endif
                            <li>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    @else
                    <a href="{{ url('/dang-nhap') }}" class="btn-action-gold">
                        <i class="bi bi-person"></i> Đăng nhập
                    </a>
                    @endif
                </div>

                {{-- MOBILE ACTIONS INSIDE MENU (Hiển thị khi mở menu hamburger) --}}
                <div class="d-lg-none mt-3 pt-3" style="border-top: 1px solid rgba(255, 255, 255, 0.08);">
                    @if(Auth::check())
                        <div class="d-flex align-items-center justify-content-between mb-3 px-1">
                            <span style="color: #D4B26F; font-size: 0.88rem; font-weight: 600;"><i class="bi bi-person-circle me-1"></i> {{ Auth::user()->name }}</span>
                            @if(Auth::user()->isCustomer())
                                <a href="{{ route('khachhang.dashboard') }}" class="badge py-1 px-2" style="background: rgba(212,178,111,0.2); color: #D4B26F; text-decoration: none;">Hồ sơ VIP</a>
                            @endif
                        </div>
                        @if(Auth::user()->isCustomer())
                        <div class="d-flex flex-column gap-2 mb-3">
                            <a href="{{ route('donhang.lichsu') }}" class="nav-link-main">
                                <i class="bi bi-receipt"></i> Đơn hàng của tôi
                            </a>
                            <a href="{{ route('khachhang.dashboard') }}#lichhen" class="nav-link-main">
                                <i class="bi bi-calendar2-heart"></i> Lịch hẹn của tôi
                            </a>
                        </div>
                        @endif
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger w-100 py-2" style="border-radius: 8px;">
                                <i class="bi bi-box-arrow-right me-1"></i> Đăng xuất
                            </button>
                        </form>
                    @else
                        <div class="d-flex flex-column gap-2">
                            <a href="{{ url('/dang-nhap') }}" class="btn-action-gold text-center py-2 text-decoration-none">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Đăng nhập tài khoản
                            </a>
                            <a href="{{ url('/dang-ky') }}" class="text-center py-2 text-decoration-none" style="border: 1px solid rgba(212,178,111,0.3); color: #D4B26F; border-radius: 6px; font-size: 0.85rem;">
                                <i class="bi bi-person-plus me-1"></i> Chưa có tài khoản? Đăng ký
                            </a>
                        </div>
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
                    <p><i class="bi bi-telephone-fill" style="color:#d4af37;"></i> Hotline: <a href="tel:0901234567" style="display:inline; margin:0;">0901 234 567</a></p>
                    <p><i class="bi bi-envelope-fill" style="color:#d4af37;"></i> Email: <a href="mailto:vip@kingsman.vn" style="display:inline; margin:0;">vip@kingsman.vn</a></p>
                    <p><i class="bi bi-clock-fill" style="color:#d4af37;"></i> Phục vụ hằng ngày: 09:00 - 21:00</p>
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

    @include('partials.system-popup')
    @yield('scripts')
</body>

</html>
