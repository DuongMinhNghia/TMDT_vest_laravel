<?php

namespace Tests\Feature;

use App\Models\HangThanhVien;
use App\Models\HoaDon;
use App\Models\KhachHang;
use App\Models\TaiKhoan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ManualQrPaymentTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    private function customer(): TaiKhoan
    {
        return TaiKhoan::where('email', 'khachhang@gmail.com')->firstOrFail();
    }

    private function configurePayment(int|string|null $divisor = 1): void
    {
        config()->set('services.manual_transfer', [
            'bank_id' => '970422',
            'bank_name' => 'MBBank Test',
            'account_number' => '0123 456 789',
            'account_name' => 'KINGSMAN VIỆT NAM',
            'demo_divisor' => $divisor,
            'allow_demo_in_production' => false,
        ]);
    }

    private function order(
        string $method = 'Chuyển khoản QR',
        string $status = HoaDon::CHO_XU_LY,
        ?KhachHang $customer = null
    ): HoaDon {
        $customer ??= $this->customer()->khachHang;
        $code = 'DH QR/2026 ' . str_pad((string) (HoaDon::max('id') + 1), 3, '0', STR_PAD_LEFT);

        return HoaDon::create([
            'khach_hang_id' => $customer->id,
            'ma_don_hang' => $code,
            'ngay_dat' => now(),
            'trang_thai' => $status,
            'tong_tien' => 4000000,
            'phuong_thuc_thanh_toan' => $method,
            'dia_chi_giao_hang' => '1 Lê Lợi',
        ]);
    }

    private function otherCustomer(): KhachHang
    {
        $account = TaiKhoan::create([
            'email' => 'qr-other@example.test',
            'password' => '123456',
            'vai_tro' => TaiKhoan::ROLE_CUSTOMER,
        ]);

        return KhachHang::create([
            'tai_khoan_id' => $account->id,
            'hang_thanh_vien_id' => HangThanhVien::orderBy('diem_toi_thieu')->value('id'),
            'ma_kh' => 'KH-QR-OTHER',
            'ho_ten' => 'QR Other Customer',
        ]);
    }

    public function test_real_mode_displays_and_encodes_the_full_invoice_amount(): void
    {
        $this->configurePayment(1);
        $order = $this->order();

        $response = $this->actingAs($this->customer())
            ->get(route('donhang.thanhtoan', $order));

        $response->assertOk()
            ->assertSee('<div class="payment-value payment-amount">4.000.000 VNĐ</div>', false)
            ->assertSee('MBBank Test')
            ->assertSee('0123 456 789')
            ->assertSee('KINGSMAN VIỆT NAM')
            ->assertDontSee('SỐ TIỀN DEMO');

        $html = html_entity_decode($response->getContent(), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $this->assertStringContainsString(
            'https://img.vietqr.io/image/970422-0123%20456%20789-compact2.png?amount=4000000&addInfo='
                . rawurlencode($order->ma_don_hang) . '&accountName=KINGSMAN%20VI%E1%BB%86T%20NAM',
            $html
        );
        $this->assertSame(4000000, (int) $order->fresh()->tong_tien);
    }

    public function test_demo_mode_labels_the_reduced_amount_and_preserves_invoice_total(): void
    {
        $this->configurePayment(1000);
        $order = $this->order();

        $response = $this->actingAs($this->customer())
            ->get(route('donhang.thanhtoan', $order));

        $response->assertOk()
            ->assertSee('<div class="payment-value payment-amount">4.000 VNĐ</div>', false)
            ->assertSee('SỐ TIỀN DEMO')
            ->assertSee('Tổng giá trị hóa đơn: 4.000.000 VNĐ');

        $html = html_entity_decode($response->getContent(), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $this->assertStringContainsString('amount=4000&addInfo=', $html);
        $this->assertSame(4000000, (int) $order->fresh()->tong_tien);
    }

    public function test_only_owner_and_qr_pending_orders_can_open_payment_page(): void
    {
        $this->configurePayment();
        $order = $this->order();
        $other = $this->otherCustomer();

        $this->actingAs($other->taiKhoan)
            ->get(route('donhang.thanhtoan', $order))
            ->assertNotFound();

        $codOrder = $this->order('COD');
        $this->actingAs($this->customer())
            ->get(route('donhang.thanhtoan', $codOrder))
            ->assertNotFound();

        foreach ([HoaDon::DA_HUY, HoaDon::HOAN_THANH] as $status) {
            $closedOrder = $this->order('Chuyển khoản QR', $status);
            $this->actingAs($this->customer())
                ->get(route('donhang.thanhtoan', $closedOrder))
                ->assertStatus(409);
        }
    }

    public function test_missing_or_invalid_payment_configuration_does_not_render_a_qr(): void
    {
        $order = $this->order();

        foreach (['bank_id', 'bank_name', 'account_number', 'account_name'] as $missingKey) {
            $this->configurePayment();
            config()->set("services.manual_transfer.{$missingKey}", '');

            $this->actingAs($this->customer())
                ->get(route('donhang.thanhtoan', $order))
                ->assertOk()
                ->assertSee('Cấu hình thanh toán chưa đầy đủ hoặc không hợp lệ.')
                ->assertDontSee('img.vietqr.io', false);
        }

        $this->configurePayment(0);
        $this->actingAs($this->customer())
            ->get(route('donhang.thanhtoan', $order))
            ->assertOk()
            ->assertSee('Cấu hình thanh toán chưa đầy đủ hoặc không hợp lệ.')
            ->assertDontSee('img.vietqr.io', false);
    }
}
