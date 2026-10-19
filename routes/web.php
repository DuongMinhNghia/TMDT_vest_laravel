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
