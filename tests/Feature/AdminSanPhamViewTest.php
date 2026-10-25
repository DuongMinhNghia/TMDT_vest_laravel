<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\TaiKhoan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminSanPhamViewTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    private function admin(): TaiKhoan
    {
        return TaiKhoan::where('email', 'admin@gmail.com')->first();
    }

    public function test_admin_product_page_renders_with_admin_layout(): void
    {
        $response = $this->actingAs($this->admin())->get('/san-pham/danh-sach');
        $response->assertStatus(200);
        $response->assertSee('Quản Lý Sản Phẩm & Kho Size');
        $response->assertSee('Tổng Mẫu Vest');
        $response->assertSee('Tổng Tồn Kho (Bộ)');
        $response->assertSee('Xem Cửa Hàng');
        $response->assertSee('Admin');
    }

    public function test_admin_product_page_edgecase_empty_products(): void
    {
        $view = $this->view('quanlysanpham', [
            'sanPhams' => collect([]),
            'danhMucs' => collect([]),
        ]);

        $view->assertSee('Chưa có sản phẩm nào trong kho dữ liệu');
        $view->assertSee('0');
    }
}
