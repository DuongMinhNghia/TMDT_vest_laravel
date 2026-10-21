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
use App\Http\Controllers\DonHangController;
use App\Http\Controllers\DanhGiaController;
use App\Http\Controllers\KhachHangDashboardController;
use App\Http\Controllers\LichHenController;
use App\Http\Controllers\HangThanhVienController;

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
    Route::post('/gio-hang/checkout', [GioHangController::class, 'checkout'])->name('giohang.checkout');

    // Đơn hàng của khách + Đánh giá sản phẩm (Task 3.3, 3.4 - Thành viên 3)
    Route::get('/don-hang', [DonHangController::class, 'lichSu'])->name('donhang.lichsu');
    Route::get('/don-hang/{id}', [DonHangController::class, 'chiTiet'])->name('donhang.chitiet');
    Route::post('/don-hang/{id}/huy', [DonHangController::class, 'huy'])->name('donhang.huy');
    Route::post('/san-pham/{id}/danh-gia', [DanhGiaController::class, 'luu'])->name('danhgia.luu');

    // =========================================================================
    // PHÂN HỆ THÀNH VIÊN 2: TÀI KHOẢN, HẠNG VIP, SỐ ĐO MAY VEST & LỊCH HẸN
    // =========================================================================

    // Dashboard Khách Hàng (Task 2.1, 2.2, 2.3)
    Route::get('/khach-hang/dashboard', [KhachHangDashboardController::class, 'index'])->name('khachhang.dashboard');
    Route::post('/khach-hang/thong-tin', [KhachHangDashboardController::class, 'capNhatThongTin'])->name('khachhang.thongtin.luu');
    Route::post('/khach-hang/so-do', [KhachHangDashboardController::class, 'luuSoDo'])->name('khachhang.sodo.luu');
    Route::post('/khach-hang/so-do/{id}/xoa', [KhachHangDashboardController::class, 'xoaSoDo'])->name('khachhang.sodo.xoa');

    // Đặt lịch hẹn showroom (Task 2.4 - Phía Khách Hàng)
    Route::get('/dat-lich', [LichHenController::class, 'create'])->name('lichhen.create');
    Route::post('/dat-lich', [LichHenController::class, 'store'])->name('lichhen.store');
    Route::post('/lich-hen/{id}/huy', [LichHenController::class, 'huy'])->name('lichhen.huy');

    // Quản trị Lịch Hẹn Showroom (Task 2.4 - Phía Admin/Nhân viên)
    Route::get('/quan-ly-lich-hen', [LichHenController::class, 'quanLy'])->name('lichhen.quanly');
    Route::post('/lich-hen/{id}/cap-nhat', [LichHenController::class, 'capNhat'])->name('lichhen.capnhat');
    Route::post('/lich-hen/{id}/xoa', [LichHenController::class, 'xoa'])->name('lichhen.xoa');

    // Quản lý Hạng thành viên Loyalty (Task 2.2 - Phía Admin)
    Route::get('/hang-thanh-vien', [HangThanhVienController::class, 'index'])->name('hangthanhvien.index');
    Route::post('/hang-thanh-vien/{id}/cap-nhat', [HangThanhVienController::class, 'capNhat'])->name('hangthanhvien.capnhat');

    // =========================================================================
    // PHÂN HỆ THÀNH VIÊN 1: SẢN PHẨM, BỘ SƯU TẬP, GIẢM GIÁ FLASH SALE
    // =========================================================================

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
