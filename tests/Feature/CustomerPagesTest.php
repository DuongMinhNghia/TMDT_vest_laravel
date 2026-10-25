<?php

namespace Tests\Feature;

use App\Models\KhachHang;
use App\Models\LichHen;
use App\Models\SanPham;
use App\Models\SoDoKhachHang;
use App\Models\TaiKhoan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerPagesTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    private function khach(): TaiKhoan
    {
        return TaiKhoan::where('email', 'khachhang@gmail.com')->first();
    }

    public function test_trang_chu_render_thanh_cong(): void
    {
        $khach = $this->khach();
        $response = $this->actingAs($khach)->get('/');
        $response->assertOk();
        $response->assertSee('KINGSMAN');
        $response->assertSee('Bộ Sưu Tập Mùa Xuân 2025');
        $response->assertSee('Đặt Lịch May Đo Riêng');
    }

    public function test_danh_sach_san_pham_render_thanh_cong(): void
    {
        $khach = $this->khach();
        $response = $this->actingAs($khach)->get('/san-pham');
        $response->assertOk();
        $response->assertSee('Tất cả trang phục');
        $response->assertSee('Chi tiết tác phẩm');
    }

    public function test_chi_tiet_san_pham_render_thanh_cong(): void
    {
        $khach = $this->khach();
        $sp = SanPham::first();
        $response = $this->actingAs($khach)->get('/san-pham/' . $sp->id);
        $response->assertOk();
        $response->assertSee($sp->ten_sp);
        $response->assertSee('Bảng Thông Số Thể Hình Vest Nam');
        $response->assertSee('Thêm Vào Giỏ Hàng');
    }

    public function test_gio_hang_empty_state(): void
    {
        $khach = $this->khach();
        $response = $this->actingAs($khach)->get('/gio-hang');
        $response->assertOk();
        $response->assertSee('Giỏ hàng của bạn đang trống');
    }

    public function test_dat_lich_showroom_va_post_lich_hen(): void
    {
        $khach = $this->khach();
        $response = $this->actingAs($khach)->get('/dat-lich');
        $response->assertOk();
        $response->assertSee('Đặt Lịch Trải Nghiệm');
        $response->assertSee('Đặc Quyền Tiếp Đón VIP');

        $henTime = date('Y-m-d H:i:s', strtotime('+2 days 10:00:00'));
        $postResponse = $this->actingAs($khach)->post('/dat-lich', [
            'so_dien_thoai' => '0912345678',
            'thoi_gian_hen' => date('Y-m-d\TH:i', strtotime($henTime)),
            'so_nguoi_di_cung' => 2,
            'loai_dich_vu' => 'May đo vest cưới Bespoke (Chú rể)',
            'ghi_chu' => 'Test đặt lịch showroom luxury',
        ]);

        $postResponse->assertRedirect();
        $this->assertDatabaseHas('lich_hens', [
            'loai_dich_vu' => 'May đo vest cưới Bespoke (Chú rể)',
            'so_nguoi_di_cung' => 2,
            'ghi_chu' => 'Test đặt lịch showroom luxury',
        ]);
    }

    public function test_khach_hang_dashboard_va_crud_so_do(): void
    {
        $khach = $this->khach();
        $response = $this->actingAs($khach)->get('/khach-hang/dashboard');
        $response->assertOk();
        $response->assertSee('Chủ thẻ thành viên');
        $response->assertSee('Số Đo May Vest');

        // Tạo số đo mới
        $postSoDo = $this->actingAs($khach)->post('/khach-hang/so-do', [
            'ten_ho_so' => 'Số đo Luxury Test',
            'rong_vai' => 45.5,
            'vong_nguc' => 98.0,
            'vong_eo' => 84.0,
            'vong_mong' => 97.0,
            'dai_tay_ao' => 61.0,
            'dai_ao_vest' => 74.0,
            'dai_quan' => 100.0,
            'vong_dui' => 56.0,
            'vong_co' => 40.0,
            'ghi_chu' => 'Form chuẩn doanh nhân',
        ]);

        $postSoDo->assertRedirect();
        $this->assertDatabaseHas('so_do_khach_hangs', [
            'ten_ho_so' => 'Số đo Luxury Test',
            'rong_vai' => 45.5,
        ]);
    }

    public function test_auth_pages_render_thanh_cong(): void
    {
        $resLogin = $this->get('/dang-nhap');
        $resLogin->assertOk();
        $resLogin->assertSee('ĐĂNG NHẬP');

        $resReg = $this->get('/dang-ky');
        $resReg->assertOk();
        $resReg->assertSee('ĐĂNG KÝ THÀNH VIÊN');
        $resReg->assertSee('TẠO TÀI KHOẢN MỚI');
    }
}
