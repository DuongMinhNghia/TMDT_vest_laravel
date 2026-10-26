<?php

namespace Tests\Feature;

use App\Models\ChiTietGioHang;
use App\Models\TaiKhoan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthorizationSecurityTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    private function user(string $email): TaiKhoan
    {
        return TaiKhoan::where('email', $email)->firstOrFail();
    }

    public function test_admin_can_access_management_pages(): void
    {
        $admin = $this->user('admin@gmail.com');

        foreach (['/san-pham/danh-sach', '/bo-suu-tap', '/giam-gia', '/hang-thanh-vien', '/nguoi-dung', '/thong-ke/doanh-thu'] as $url) {
            $this->actingAs($admin)->get($url)->assertOk();
        }
    }

    public function test_staff_only_has_operational_management_access(): void
    {
        $staff = $this->user('nhanvien@gmail.com');

        $this->actingAs($staff)->get('/quan-ly-lich-hen')->assertOk();
        $this->actingAs($staff)->get('/thong-ke/don-hang')->assertOk();

        foreach (['/san-pham/danh-sach', '/bo-suu-tap', '/giam-gia', '/hang-thanh-vien', '/nguoi-dung', '/thong-ke/doanh-thu'] as $url) {
            $this->actingAs($staff)->get($url)->assertForbidden();
        }
    }

    public function test_customer_cannot_access_or_mutate_administrative_resources(): void
    {
        $customer = $this->user('khachhang@gmail.com');
        $target = $this->user('nhanvien@gmail.com');

        $this->actingAs($customer)->get('/nguoi-dung')->assertForbidden();
        $this->actingAs($customer)->post("/nguoi-dung/{$target->id}/sua", [
            'name' => 'Attempted escalation',
            'email' => $target->email,
            'quyen' => 'Admin',
        ])->assertForbidden();
        $this->actingAs($customer)->post("/nguoi-dung/{$target->id}/xoa")->assertForbidden();

        $target->refresh();
        $this->assertSame(TaiKhoan::ROLE_STAFF, $target->vai_tro);
    }

    public function test_disabled_account_cannot_log_in_or_continue_an_existing_session(): void
    {
        $customer = $this->user('khachhang@gmail.com');
        $customer->update(['trang_thai' => false]);

        $this->post('/dang-nhap', ['email' => $customer->email, 'password' => '123456'])
            ->assertSessionHasErrors('email');
        $this->assertGuest();

        $this->actingAs($customer)->get('/gio-hang')->assertRedirect(route('login'));
    }

    public function test_cart_mutations_and_logout_require_safe_http_methods(): void
    {
        $customer = $this->user('khachhang@gmail.com');

        $this->actingAs($customer)->get('/gio-hang/them?id=1&size=M')->assertStatus(405);
        $this->actingAs($customer)->post('/gio-hang/them', ['id' => 1, 'size' => 'M'])->assertRedirect();
        $item = ChiTietGioHang::firstOrFail();
        $this->actingAs($customer)->patch('/gio-hang/update', ['key' => $item->id, 'type' => 1])->assertRedirect();
        $this->actingAs($customer)->delete('/gio-hang/xoa', ['key' => $item->id])->assertRedirect();
        $this->assertDatabaseMissing('chi_tiet_gio_hangs', ['id' => $item->id]);

        $this->actingAs($customer)->get('/dang-xuat')->assertStatus(405);
        $this->actingAs($customer)->post('/dang-xuat')->assertRedirect('/dang-nhap');
        $this->assertGuest();
    }
}
