<?php

namespace Tests\Feature;

use App\Models\BoSuuTap;
use App\Models\ChiTietGioHang;
use App\Models\SanPham;
use App\Models\TaiKhoan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendConsistencyTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    private function customer(): TaiKhoan
    {
        return TaiKhoan::where('email', 'khachhang@gmail.com')->firstOrFail();
    }

    public function test_guests_can_browse_public_catalog_content_but_are_redirected_from_protected_actions(): void
    {
        $product = SanPham::firstOrFail();
        $collection = BoSuuTap::firstOrFail();

        $this->get('/')->assertOk();
        $this->get('/san-pham')->assertOk();
        $this->get('/san-pham/' . $product->id)->assertOk()
            ->assertSee('Đăng Nhập Để Thêm Giỏ Hàng');
        $this->get('/bo-suu-tap/' . $collection->id)->assertOk();

        foreach (['/gio-hang', '/khach-hang/dashboard', '/dat-lich'] as $url) {
            $this->get($url)->assertRedirect('/dang-nhap');
        }
        $this->post('/gio-hang/them', ['id' => $product->id, 'size' => 'M'])->assertRedirect('/dang-nhap');
    }

    public function test_standard_sizes_use_the_cart_while_custom_size_never_creates_a_cart_line(): void
    {
        $customer = $this->customer();
        $product = SanPham::firstOrFail();

        $this->actingAs($customer)->post('/gio-hang/them', ['id' => $product->id, 'size' => 'M'])->assertRedirect();
        $this->assertSame(1, ChiTietGioHang::count());

        $this->actingAs($customer)->post('/gio-hang/them', [
            'id' => $product->id,
            'size' => 'Custom (Vai:45 - Ngực:96 - Eo:84 - Dài:75)',
        ])->assertSessionHas('error');
        $this->assertSame(1, ChiTietGioHang::count());

        $this->actingAs($customer)->get('/san-pham/' . $product->id)->assertOk()
            ->assertSee('sản phẩm may đo không được thêm trực tiếp vào giỏ hàng')
            ->assertSee('Đặt Lịch Thử Tại Showroom');
    }

    public function test_navigation_exposes_no_admin_controls_to_guests_and_keeps_mobile_collapse_markup(): void
    {
        $response = $this->get('/');

        $response->assertDontSee('Quản Lý Cửa Hàng')
            ->assertSee('navbar-toggler', false)
            ->assertSee('data-bs-target="#navbarNav"', false);
    }
}
