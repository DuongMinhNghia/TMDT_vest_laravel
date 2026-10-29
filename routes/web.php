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
use App\Http\Controllers\QuenMatKhauController;
use App\Http\Controllers\AddressLookupController;

// 1. Route xác thực (Guest)
Route::middleware('guest')->group(function () {
    Route::get('/dang-nhap', [DangNhapController::class, 'index'])->name('login');
    Route::post('/dang-nhap', [DangNhapController::class, 'login']);
    Route::get('/dang-ky', [DangKyController::class, 'index']);
    Route::post('/dang-ky', [DangKyController::class, 'register']);
    Route::get('/quen-mat-khau', [QuenMatKhauController::class, 'formGuiMa'])->name('matkhau.quen.form');
    Route::post('/quen-mat-khau', [QuenMatKhauController::class, 'guiMa'])->name('matkhau.quen.gui');
    Route::get('/quen-mat-khau/xac-nhan', [QuenMatKhauController::class, 'formXacNhan'])->name('matkhau.xacnhan.form');
    Route::post('/quen-mat-khau/xac-nhan', [QuenMatKhauController::class, 'xacNhan'])->name('matkhau.xacnhan');
    Route::get('/dat-lai-mat-khau', [QuenMatKhauController::class, 'formDatLai'])->name('matkhau.datlai.form');
    Route::post('/dat-lai-mat-khau', [QuenMatKhauController::class, 'datLai'])->name('matkhau.datlai');
});

// 2. Nội dung công khai: chỉ xem, không thay đổi dữ liệu.
Route::get('/', [TrangChuController::class, 'index'])->name('trangchu');
Route::get('/san-pham', [SanPhamController::class, 'index'])->name('sanpham.index');

// 3. Route yêu cầu đăng nhập
Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/san-pham/danh-sach', [SanPhamController::class, 'danhSach'])->middleware('role:admin')->name('sanpham.danhsach');
    Route::get('/san-pham/danhsach', [SanPhamController::class, 'danhSach'])->middleware('role:admin');

    // Giỏ hàng
    Route::get('/gio-hang', [GioHangController::class, 'index'])->name('giohang.index');
    Route::get('/gio-hang/phuong-xa', [GioHangController::class, 'phuongXas'])->middleware('role:khach_hang')->name('giohang.phuongxa');
    Route::get('/dia-chi/goi-y-duong', [AddressLookupController::class, 'streets'])
        ->middleware(['role:khach_hang', 'throttle:30,1'])->name('diachi.goiyduong');
    Route::post('/gio-hang/them', [GioHangController::class, 'addToCart'])->middleware('role:khach_hang')->name('giohang.add');
    Route::patch('/gio-hang/update', [GioHangController::class, 'updateSL'])->middleware('role:khach_hang')->name('giohang.update');
    Route::delete('/gio-hang/xoa', [GioHangController::class, 'remove'])->middleware('role:khach_hang')->name('giohang.remove');
    Route::post('/gio-hang/checkout', [GioHangController::class, 'checkout'])->middleware('role:khach_hang')->name('giohang.checkout');

    // Đơn hàng của khách + Đánh giá sản phẩm (Task 3.3, 3.4 - Thành viên 3)
    Route::get('/don-hang', [DonHangController::class, 'lichSu'])->middleware('role:khach_hang')->name('donhang.lichsu');
    Route::get('/don-hang/{id}', [DonHangController::class, 'chiTiet'])->name('donhang.chitiet');
    Route::get('/don-hang/{id}/thanh-toan', [DonHangController::class, 'thanhToan'])->middleware('role:khach_hang')->name('donhang.thanhtoan');
    Route::post('/don-hang/{id}/huy', [DonHangController::class, 'huy'])->middleware('role:khach_hang')->name('donhang.huy');
    Route::post('/don-hang/{id}/xac-nhan-nhan-hang', [DonHangController::class, 'xacNhanNhanHang'])->middleware('role:khach_hang')->name('donhang.xacnhan-nhanhang');
    Route::post('/san-pham/{id}/danh-gia', [DanhGiaController::class, 'luu'])->middleware('role:khach_hang')->name('danhgia.luu');

    // =========================================================================
    // PHÂN HỆ THÀNH VIÊN 2: TÀI KHOẢN, HẠNG VIP, SỐ ĐO MAY VEST & LỊCH HẸN
    // =========================================================================

    // Dashboard Khách Hàng (Task 2.1, 2.2, 2.3)
    Route::get('/khach-hang/dashboard', [KhachHangDashboardController::class, 'index'])->middleware('role:khach_hang')->name('khachhang.dashboard');
    Route::post('/khach-hang/thong-tin', [KhachHangDashboardController::class, 'capNhatThongTin'])->middleware('role:khach_hang')->name('khachhang.thongtin.luu');
    Route::post('/khach-hang/so-do', [KhachHangDashboardController::class, 'luuSoDo'])->middleware('role:khach_hang')->name('khachhang.sodo.luu');
    Route::post('/khach-hang/so-do/{id}/xoa', [KhachHangDashboardController::class, 'xoaSoDo'])->middleware('role:khach_hang')->name('khachhang.sodo.xoa');

    // Đặt lịch hẹn showroom (Task 2.4 - Phía Khách Hàng)
    Route::get('/dat-lich', [LichHenController::class, 'create'])->middleware('role:khach_hang')->name('lichhen.create');
    Route::post('/dat-lich', [LichHenController::class, 'store'])->middleware('role:khach_hang')->name('lichhen.store');
    Route::post('/lich-hen/{id}/huy', [LichHenController::class, 'huy'])->middleware('role:khach_hang')->name('lichhen.huy');

    // Quản trị Lịch Hẹn Showroom (Task 2.4 - Phía Admin/Nhân viên)
    Route::get('/quan-ly-lich-hen', [LichHenController::class, 'quanLy'])->middleware('role:admin,nhan_vien')->name('lichhen.quanly');
    Route::post('/lich-hen/{id}/cap-nhat', [LichHenController::class, 'capNhat'])->middleware('role:admin,nhan_vien')->name('lichhen.capnhat');
    Route::post('/lich-hen/{id}/xoa', [LichHenController::class, 'xoa'])->middleware('role:admin')->name('lichhen.xoa');

    // Quản lý Hạng thành viên Loyalty (Task 2.2 - Phía Admin)
    Route::get('/hang-thanh-vien', [HangThanhVienController::class, 'index'])->middleware('role:admin')->name('hangthanhvien.index');
    Route::post('/hang-thanh-vien/{id}/cap-nhat', [HangThanhVienController::class, 'capNhat'])->middleware('role:admin')->name('hangthanhvien.capnhat');

    // =========================================================================
    // PHÂN HỆ THÀNH VIÊN 1: SẢN PHẨM, BỘ SƯU TẬP, GIẢM GIÁ FLASH SALE
    // =========================================================================

    // CRUD Sản phẩm
    Route::post('/san-pham/them', [SanPhamController::class, 'luuThem'])->middleware('role:admin')->name('sanpham.luuthem');
    Route::post('/san-pham/{id}/sua', [SanPhamController::class, 'luuSua'])->middleware('role:admin')->name('sanpham.luusua');
    Route::post('/san-pham/{id}/xoa', [SanPhamController::class, 'xoa'])->middleware('role:admin')->name('sanpham.xoa');

    // Quản lý Bộ sưu tập (Task 1.2 - Thành viên 1)
    Route::get('/bo-suu-tap', [BoSuuTapController::class, 'danhSach'])->middleware('role:admin')->name('bosuutap.danhsach');
    Route::get('/bo-suu-tap/danh-sach', [BoSuuTapController::class, 'danhSach'])->middleware('role:admin');
    Route::get('/bo-suu-tap/danhsach', [BoSuuTapController::class, 'danhSach'])->middleware('role:admin');
    Route::post('/bo-suu-tap/them', [BoSuuTapController::class, 'luuThem'])->middleware('role:admin')->name('bosuutap.luuthem');
    Route::post('/bo-suu-tap/{id}/sua', [BoSuuTapController::class, 'luuSua'])->middleware('role:admin')->name('bosuutap.luusua');
    Route::post('/bo-suu-tap/{id}/xoa', [BoSuuTapController::class, 'xoa'])->middleware('role:admin')->name('bosuutap.xoa');

    // Quản lý Giảm Giá & Flash Sale (Task 1.3 - Thành viên 1)
    Route::get('/giam-gia', [GiamGiaController::class, 'danhSach'])->middleware('role:admin')->name('giamgia.danhsach');
    Route::get('/giam-gia/danh-sach', [GiamGiaController::class, 'danhSach'])->middleware('role:admin');
    Route::get('/giam-gia/danhsach', [GiamGiaController::class, 'danhSach'])->middleware('role:admin');
    Route::post('/giam-gia/them', [GiamGiaController::class, 'luuThem'])->middleware('role:admin')->name('giamgia.luuthem');
    Route::post('/giam-gia/{id}/sua', [GiamGiaController::class, 'luuSua'])->middleware('role:admin')->name('giamgia.luusua');
    Route::post('/giam-gia/{id}/xoa', [GiamGiaController::class, 'xoa'])->middleware('role:admin')->name('giamgia.xoa');
    Route::post('/giam-gia/{id}/trang-thai', [GiamGiaController::class, 'doiTrangThai'])->middleware('role:admin')->name('giamgia.trangthai');

    // Đăng xuất
    Route::post('/dang-xuat', function (\Illuminate\Http\Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/dang-nhap');
    })->name('logout');

    // Quản lý người dùng
    Route::get('/nguoi-dung', [QuanLyNguoiDungController::class, 'index'])->middleware('role:admin')->name('nguoidung.index');
    Route::post('/nguoi-dung/{id}/sua', [QuanLyNguoiDungController::class, 'luuSua'])->middleware('role:admin')->name('nguoidung.sua');
    Route::post('/nguoi-dung/{id}/xoa', [QuanLyNguoiDungController::class, 'xoa'])->middleware('role:admin')->name('nguoidung.xoa');

    // Thống kê
    Route::get('/thong-ke/doanh-thu', [ThongKeController::class, 'doanhThu'])->middleware('role:admin')->name('thongke.doanhthu');
    Route::get('/thong-ke/don-hang', [ThongKeController::class, 'donHang'])->middleware('role:admin,nhan_vien')->name('thongke.donhang');
    Route::post('/don-hang/{id}/cap-nhat', [ThongKeController::class, 'capNhatTrangThai'])->middleware('role:admin,nhan_vien')->name('thongke.capnhat');
});

// Public wildcard detail routes must follow protected static management routes.
Route::get('/san-pham/{id}', [SanPhamController::class, 'chiTiet'])->name('sanpham.chitiet');
Route::get('/bo-suu-tap/{id}', [BoSuuTapController::class, 'chiTiet'])->name('bosuutap.chitiet');
