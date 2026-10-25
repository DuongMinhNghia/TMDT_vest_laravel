<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\TaiKhoan;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminSidebarPagesTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    private function admin(): TaiKhoan
    {
        return TaiKhoan::where('email', 'admin@gmail.com')->first();
    }

    public function test_all_admin_sidebar_pages_render_with_admin_layout(): void
    {
        $admin = $this->admin();

        $routes = [
            '/san-pham/danh-sach' => 'Quản Lý Sản Phẩm & Kho Size',
            '/bo-suu-tap'         => 'Quản Lý Bộ Sưu Tập Thời Trang',
            '/giam-gia'           => 'Quản Lý Khuyến Mãi & Flash Sale',
            '/quan-ly-lich-hen'   => 'Quản Lý Lịch Hẹn Showroom',
            '/hang-thanh-vien'    => 'Quản Lý Hạng Thành Viên & Chính Sách VIP',
            '/nguoi-dung'         => 'Quản Lý Danh Sách Tài Khoản',
            '/thong-ke/doanh-thu' => 'Thống Kê Doanh Thu & Hiệu Quả Kinh Doanh',
            '/thong-ke/don-hang'  => 'Quản Lý & Thống Kê Đơn Hàng',
        ];

        foreach ($routes as $url => $expectedTitle) {
            $response = $this->actingAs($admin)->get($url);

            $this->assertEquals(
                200,
                $response->status(),
                "Route {$url} failed to return status 200"
            );

            // Verify sidebar elements from admin_layout
            $response->assertSee('admin-sidebar', false);
            $response->assertSee('admin-topbar', false);
            $response->assertSee('KINGSMAN', false);
            $response->assertSee('badge-admin-portal', false);
            $response->assertSee('Xem Cửa Hàng', false);
            $response->assertSee($expectedTitle, false);
        }
    }

    public function test_edge_case_empty_states_render_without_error(): void
    {
        // 1. Empty Collections View
        $viewBST = $this->view('quanlybosuutap', [
            'boSuuTaps' => collect([]),
            'sanPhams'  => collect([]),
        ]);
        $viewBST->assertSee('Chưa có bộ sưu tập nào');

        // 2. Empty Giảm Giá View
        $viewSale = $this->view('quanlygiamgia', [
            'giamGias' => collect([]),
            'sanPhams' => collect([]),
            'thongKe'  => [
                'tong'        => 0,
                'dang_chay'   => 0,
                'sap_dien_ra' => 0,
                'ket_thuc'    => 0,
            ],
        ]);
        $viewSale->assertSee('Chưa có chương trình giảm giá nào');

        // 3. Empty Lịch Hẹn View
        $paginatedEmpty = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15);
        $viewLH = $this->view('quanlylichhen', [
            'lichHens'  => $paginatedEmpty,
            'nhanViens' => collect([]),
            'thongKe'   => [
                'tong'          => 0,
                'cho_xac_nhan'  => 0,
                'da_xac_nhan'   => 0,
                'dang_tiep_don' => 0,
                'hoan_thanh'    => 0,
                'da_huy'        => 0,
            ],
        ]);
        $viewLH->assertSee('Không tìm thấy lịch hẹn nào theo tiêu chí lọc');
    }

    public function test_admin_can_update_user_and_permissions_endpoint(): void
    {
        $admin = $this->admin();
        $user = TaiKhoan::where('email', 'khachhang@gmail.com')->first();
        $this->assertNotNull($user);

        // Test editing account details and promoting role to Admin
        $response = $this->actingAs($admin)->post('/nguoi-dung/' . $user->id . '/sua', [
            'name'    => 'Khách Hàng VIP Đã Đổi Tên',
            'email'   => 'khachhang_updated@gmail.com',
            'quyen'   => 'Admin',
            'phone'   => '0988888888',
            'address' => '123 Đường Savile Row, Quận 1',
        ]);

        $response->assertRedirect('/nguoi-dung');
        $response->assertSessionHas('success');

        $user->refresh();
        $this->assertEquals('khachhang_updated@gmail.com', $user->email);
        $this->assertEquals('admin', $user->vai_tro);
        $this->assertEquals('Admin', $user->quyen);
        $this->assertEquals('Khách Hàng VIP Đã Đổi Tên', $user->name);
        $this->assertEquals('0988888888', $user->phone);
        $this->assertEquals('123 Đường Savile Row, Quận 1', $user->address);
    }

    public function test_admin_can_update_loyalty_tier_policy_endpoint(): void
    {
        $admin = $this->admin();
        $tier = \App\Models\HangThanhVien::first();
        $this->assertNotNull($tier);

        $response = $this->actingAs($admin)->post('/hang-thanh-vien/' . $tier->id . '/cap-nhat', [
            'diem_toi_thieu'       => 150,
            'phan_tram_chiet_khau' => 8,
            'mo_ta'                => 'Đặc quyền trải nghiệm may đo tại showroom Kingsman VIP',
        ]);

        $response->assertRedirect('/hang-thanh-vien');
        $response->assertSessionHas('success');

        $tier->refresh();
        $this->assertEquals(150, $tier->diem_toi_thieu);
        $this->assertEquals(8, $tier->phan_tram_chiet_khau);
        $this->assertEquals('Đặc quyền trải nghiệm may đo tại showroom Kingsman VIP', $tier->mo_ta);
    }
}

