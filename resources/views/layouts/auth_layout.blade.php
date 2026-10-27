<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'Kingsman Vietnam')</title>

    <!-- Google Fonts: Playfair Display + Plus Jakarta Sans (Full Vietnamese Support) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,500;0,600;0,700;1,400&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    <!-- Bootstrap Icons & Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet" />

    <style>
        :root {
            --auth-bg-main: #111317;
            --auth-bg-surface: #141720;
            --auth-bg-card: #181B24;
            --auth-border: rgba(255, 255, 255, 0.08);
            --auth-border-focus: rgba(212, 178, 111, 0.4);
            --auth-gold: #D4B26F;
            --auth-gold-light: #E8CA8C;
            --auth-gold-glow: rgba(212, 178, 111, 0.15);
            --auth-text-main: #F1F5F9;
            --auth-text-muted: #94A3B8;
            --auth-text-dim: #64748B;
            --auth-font-heading: 'Playfair Display', Georgia, serif;
            --auth-font-body: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: var(--auth-bg-main);
            color: var(--auth-text-main);
            font-family: var(--auth-font-body);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* TOP BAR */
        .top-bar {
            background-color: #0c0e12;
            padding: 7px 0;
            border-bottom: 1px solid var(--auth-border);
            font-size: 0.78rem;
            color: var(--auth-text-muted);
        }

        .top-bar a {
            color: var(--auth-text-muted);
            text-decoration: none;
            margin: 0 12px;
            transition: color 0.2s;
        }

        .top-bar a:hover {
            color: var(--auth-gold);
        }

        /* NAVBAR */
        .navbar {
            background: var(--auth-bg-surface);
            border-bottom: 1px solid var(--auth-border);
            padding: 12px 0;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.35);
        }

        .navbar-brand {
            font-family: var(--auth-font-heading);
            font-size: 1.4rem;
            color: var(--auth-gold) !important;
            letter-spacing: 2px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            transition: all 0.2s;
        }

        .navbar-brand:hover {
            color: var(--auth-gold-light) !important;
        }

        /* CARD */
        .auth-card {
            background: var(--auth-bg-surface);
            border: 1px solid var(--auth-border);
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6);
            transition: border-color 0.3s;
        }

        .auth-card:hover {
            border-color: var(--auth-border-focus);
        }

        .auth-card-header {
            background: var(--auth-bg-card);
            color: var(--auth-gold);
            font-family: var(--auth-font-heading);
            font-size: 1.25rem;
            font-weight: 700;
            letter-spacing: 1.5px;
            text-align: center;
            padding: 20px;
            border-bottom: 1px solid var(--auth-border);
        }

        .auth-card-body {
            padding: 32px 30px;
        }

        /* FORM */
        .form-label {
            color: var(--auth-text-main);
            font-size: 0.82rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .form-control {
            background: var(--auth-bg-card) !important;
            border: 1px solid var(--auth-border) !important;
            border-radius: 8px !important;
            color: var(--auth-text-main) !important;
            padding: 10px 14px;
            font-size: 0.88rem;
            transition: all 0.2s;
        }

        .form-control:focus {
            border-color: var(--auth-gold) !important;
            box-shadow: 0 0 0 3px var(--auth-gold-glow) !important;
            background: #1c202b !important;
            color: #fff !important;
        }

        /* Ngăn màu nền sáng mặc định của trình duyệt khi tự điền tài khoản. */
        .form-control:-webkit-autofill,
        .form-control:-webkit-autofill:hover,
        .form-control:-webkit-autofill:focus,
        .form-control:-webkit-autofill:active {
            -webkit-text-fill-color: var(--auth-text-main) !important;
            -webkit-box-shadow: 0 0 0 1000px var(--auth-bg-card) inset !important;
            box-shadow: 0 0 0 1000px var(--auth-bg-card) inset !important;
            caret-color: var(--auth-text-main);
            transition: background-color 9999s ease-out 0s;
        }

        .form-control:-moz-autofill {
            background-color: var(--auth-bg-card) !important;
            color: var(--auth-text-main) !important;
        }

        .form-control::placeholder {
            color: var(--auth-text-dim);
        }

        .password-field {
            position: relative;
        }

        .password-field .form-control {
            padding-right: 48px;
        }

        .password-toggle {
            position: absolute;
            top: 50%;
            right: 12px;
            transform: translateY(-50%);
            border: 0;
            background: transparent;
            color: var(--auth-text-muted);
            cursor: pointer;
            padding: 4px;
            line-height: 1;
        }

        .password-toggle:hover,
        .password-toggle:focus-visible {
            color: var(--auth-gold);
            outline: none;
        }

        /* ALERTS */
        .alert-success {
            background: rgba(34, 197, 94, 0.12) !important;
            border: 1px solid rgba(34, 197, 94, 0.3) !important;
            color: #22c55e !important;
            border-radius: 8px;
            font-size: 0.85rem;
        }

        .alert-danger {
            background: rgba(244, 63, 94, 0.12) !important;
            border: 1px solid rgba(244, 63, 94, 0.3) !important;
            color: #f43f5e !important;
            border-radius: 8px;
            font-size: 0.85rem;
        }

        /* BUTTON */
        .btn-gold {
            background: linear-gradient(135deg, var(--auth-gold-light), var(--auth-gold));
            color: #0b0b0b;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.92rem;
            letter-spacing: 0.8px;
            padding: 11px;
            width: 100%;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 4px 14px rgba(212, 178, 111, 0.2);
        }

        .btn-gold:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(212, 178, 111, 0.35);
            color: #000;
        }

        .link-gold {
            color: var(--auth-gold);
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s;
        }

        .link-gold:hover {
            color: var(--auth-gold-light);
            text-decoration: underline;
        }

        /* FOOTER */
        footer {
            background-color: #0c0e12;
            border-top: 1px solid var(--auth-border);
            padding: 16px 0;
            margin-top: auto;
            color: var(--auth-text-dim);
            font-size: 0.8rem;
        }
    </style>
</head>

<body>

    {{-- TOP BAR --}}
    <div class="top-bar">
        <div class="container-fluid px-4">
            <div class="row align-items-center">
                <div class="col-md-6 text-center text-md-start">
                    <span><i class="bi bi-clock me-1"></i> Thời gian phục vụ: Hàng ngày từ 9:00 - 21:00</span>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="tel:0901234567"><i class="bi bi-telephone me-1"></i> 0901 234 567</a>
                    <a href="mailto:vip@kingsman.vn"><i class="bi bi-envelope me-1"></i> vip@kingsman.vn</a>
                </div>
            </div>
        </div>
    </div>

    {{-- NAVBAR --}}
    <nav class="navbar">
        <div class="container justify-content-center">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="bi bi-gem"></i> KINGSMAN
            </a>
        </div>
    </nav>

    {{-- CONTENT --}}
    <div class="container d-flex align-items-center justify-content-center py-5" style="flex: 1;">
        <div style="width: 100%; max-width: 450px;">
            @yield('content')
        </div>
    </div>

    {{-- FOOTER --}}
    <footer>
        <div class="container text-center">
            &copy; {{ date('Y') }} Kingsman Vietnam — Savile Row Heritage Tailoring. All rights reserved.
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @include('partials.system-popup')
    <script>
        document.querySelectorAll('[data-password-toggle]').forEach(function (button) {
            button.addEventListener('click', function () {
                const input = document.getElementById(button.dataset.passwordToggle);
                const isHidden = input.type === 'password';

                input.type = isHidden ? 'text' : 'password';
                button.setAttribute('aria-label', isHidden ? 'Ẩn mật khẩu' : 'Hiển thị mật khẩu');
                button.setAttribute('title', isHidden ? 'Ẩn mật khẩu' : 'Hiển thị mật khẩu');
                button.querySelector('i').className = isHidden ? 'bi bi-eye-slash' : 'bi bi-eye';
            });
        });
    </script>
    @yield('scripts')
</body>

</html>
