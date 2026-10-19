<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SanPhamController;
use App\Http\Controllers\DangNhapController;
use App\Http\Controllers\DangKyController;
use App\Http\Controllers\TrangChuController;
use App\Http\Controllers\GioHangController;
use App\Http\Controllers\QuanLyNguoiDungController;
use App\Http\Controllers\ThongKeController;
use App\Http\Controllers\BoSuuTapController;
use App\Http\Controllers\GiamGiaController;

// 1. Route xác thực (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/dang-nhap', [DangNhapController::class, 'index'])->name('login');
    Route::post('/dang-nhap', [DangNhapController::class, 'login']);
    Route::get('/dang-ky', [DangKyController::class, 'index']);
    Route::post('/dang-ky', [DangKyController::class, 'register']);
});

// 2. Route yêu cầu đăng nhập
Route::middleware('auth')->group(function () {
    Route::get('/', [TrangChuController::class, 'index']);
    Route::get('/san-pham/danh-sach', [SanPhamController::class, 'danhSach'])->name('sanpham.danhsach');
    Route::get('/san-pham/danhsach', [SanPhamController::class, 'danhSach']);
    Route::get('/san-pham', [SanPhamController::class, 'index'])->name('sanpham.index');
    Route::get('/san-pham/{id}', [SanPhamController::class, 'chiTiet'])->name('sanpham.chitiet');

    // Giỏ hàng
    Route::get('/gio-hang', [GioHangController::class, 'index'])->name('giohang.index');
    Route::get('/gio-hang/them', [GioHangController::class, 'addToCart'])->name('giohang.add');
    Route::get('/gio-hang/update', [GioHangController::class, 'updateSL'])->name('giohang.update');
    Route::get('/gio-hang/xoa', [GioHangController::class, 'remove'])->name('giohang.remove');
    Route::get('/gio-hang/checkout', [GioHangController::class, 'checkout'])->name('giohang.checkout');

    // CRUD Sản phẩm
    Route::post('/san-pham/them', [SanPhamController::class, 'luuThem'])->name('sanpham.luuthem');
    Route::post('/san-pham/{id}/sua', [SanPhamController::class, 'luuSua'])->name('sanpham.luusua');
    Route::post('/san-pham/{id}/xoa', [SanPhamController::class, 'xoa'])->name('sanpham.xoa');

    // Quản lý Bộ sưu tập (Task 1.2 - Thành viên 1)
    Route::get('/bo-suu-tap', [BoSuuTapController::class, 'danhSach'])->name('bosuutap.danhsach');
    Route::get('/bo-suu-tap/danh-sach', [BoSuuTapController::class, 'danhSach']);
    Route::get('/bo-suu-tap/danhsach', [BoSuuTapController::class, 'danhSach']);
    Route::post('/bo-suu-tap/them', [BoSuuTapController::class, 'luuThem'])->name('bosuutap.luuthem');
    Route::post('/bo-suu-tap/{id}/sua', [BoSuuTapController::class, 'luuSua'])->name('bosuutap.luusua');
    Route::post('/bo-suu-tap/{id}/xoa', [BoSuuTapController::class, 'xoa'])->name('bosuutap.xoa');
    Route::get('/bo-suu-tap/{id}', [BoSuuTapController::class, 'chiTiet'])->name('bosuutap.chitiet');

    // Quản lý Giảm Giá & Flash Sale (Task 1.3 - Thành viên 1)
    Route::get('/giam-gia', [GiamGiaController::class, 'danhSach'])->name('giamgia.danhsach');
    Route::get('/giam-gia/danh-sach', [GiamGiaController::class, 'danhSach']);
    Route::get('/giam-gia/danhsach', [GiamGiaController::class, 'danhSach']);
    Route::post('/giam-gia/them', [GiamGiaController::class, 'luuThem'])->name('giamgia.luuthem');
    Route::post('/giam-gia/{id}/sua', [GiamGiaController::class, 'luuSua'])->name('giamgia.luusua');
    Route::post('/giam-gia/{id}/xoa', [GiamGiaController::class, 'xoa'])->name('giamgia.xoa');
    Route::post('/giam-gia/{id}/trang-thai', [GiamGiaController::class, 'doiTrangThai'])->name('giamgia.trangthai');

    // Đăng xuất
    Route::get('/dang-xuat', function () {
        Auth::logout();
        return redirect('/dang-nhap');
    });

    // Quản lý người dùng
    Route::get('/nguoi-dung', [QuanLyNguoiDungController::class, 'index'])->name('nguoidung.index');
    Route::post('/nguoi-dung/{id}/sua', [QuanLyNguoiDungController::class, 'luuSua'])->name('nguoidung.sua');
    Route::post('/nguoi-dung/{id}/xoa', [QuanLyNguoiDungController::class, 'xoa'])->name('nguoidung.xoa');

    // Thống kê
    Route::get('/thong-ke/doanh-thu', [ThongKeController::class, 'doanhThu'])->name('thongke.doanhthu');
    Route::get('/thong-ke/don-hang', [ThongKeController::class, 'donHang'])->name('thongke.donhang');
    Route::post('/don-hang/{id}/cap-nhat', [ThongKeController::class, 'capNhatTrangThai'])->name('thongke.capnhat');
});
