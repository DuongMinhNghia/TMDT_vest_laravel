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
            margin: 0 15px;
            transition: color 0.3s;
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
        }

        /* CARD */
        .auth-card {
            background: linear-gradient(135deg, #1a1a1a, #2a2a2a);
            border: 1px solid #d4af37;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 20px 50px rgba(212, 175, 55, 0.15);
        }

        .auth-card-header {
            background: linear-gradient(135deg, #d4af37, #c19b2f);
            color: #000;
            font-family: 'Playfair Display', serif;
            font-size: 1.3rem;
            font-weight: 700;
            letter-spacing: 2px;
            text-align: center;
            padding: 20px;
        }

        .auth-card-body {
            padding: 35px;
        }

        /* FORM */
        .form-label {
            color: #a0a0a0;
            font-size: 0.85rem;
            font-weight: 600;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        .form-control {
            background: #0f0f0f !important;
            border: 1px solid #444 !important;
            border-radius: 8px !important;
            color: #f0f0f0 !important;
            padding: 12px 15px;
            transition: all 0.3s;
        }

        .form-control:focus {
            border-color: #d4af37 !important;
            box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.15) !important;
        }

        .form-control::placeholder {
            color: #555;
        }

        /* ALERT */
        .alert-success {
            background: rgba(40, 167, 69, 0.15) !important;
            border: 1px solid rgba(40, 167, 69, 0.3) !important;
            color: #75d98b !important;
            border-radius: 8px;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.15) !important;
            border: 1px solid rgba(220, 53, 69, 0.3) !important;
            color: #f5a0a8 !important;
            border-radius: 8px;
        }

        /* BUTTON */
        .btn-gold {
            background: linear-gradient(135deg, #d4af37, #c19b2f);
            color: #000;
            border: none;
            border-radius: 30px;
            font-weight: 700;
            font-size: 1rem;
            letter-spacing: 1px;
            padding: 13px;
            width: 100%;
            transition: all 0.3s;
            text-transform: uppercase;
        }

        .btn-gold:hover {
            background: linear-gradient(135deg, #e0c148, #d4af37);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(212, 175, 55, 0.4);
            color: #000;
        }

        .link-gold {
            color: #d4af37;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.3s;
        }

        .link-gold:hover {
            color: #f0c864;
            text-decoration: underline;
        }

        /* FOOTER */
        footer {
            background-color: #0a0a0a;
            border-top: 2px solid #d4af37;
            padding: 20px 0;
            margin-top: auto;
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
    <nav class="navbar">
        <div class="container justify-content-center">
            <a class="navbar-brand" href="{{ url('/dang-nhap') }}">
                <i class="bi bi-gem"></i> KINGSMAN
            </a>
        </div>
    </nav>

    {{-- CONTENT --}}
    <div class="container grow d-flex align-items-center justify-content-center py-5">
        <div style="width: 100%; max-width: 480px;">
            @yield('content')
        </div>
    </div>

    {{-- FOOTER --}}
    <footer>
        <div class="container text-center">
            <small style="color:#666;">&copy; 2026 Kingsman Bespoke. All rights reserved.</small>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>

</html>