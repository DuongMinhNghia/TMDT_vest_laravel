<?php

namespace Tests\Feature;

use App\Models\HangThanhVien;
use App\Models\HoaDon;
use App\Models\KhachHang;
use App\Models\LichSuThanhToan;
use App\Models\Size;
use App\Models\TaiKhoan;
use App\Services\PaymentStatusService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PaymentStatusWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    private function customer(): TaiKhoan
    {
        return TaiKhoan::where('email', 'khachhang@gmail.com')->firstOrFail();
    }

    private function admin(): TaiKhoan
    {
        return TaiKhoan::where('email', 'admin@gmail.com')->firstOrFail();
    }

    private function staff(): TaiKhoan
    {
        return TaiKhoan::where('email', 'nhanvien@gmail.com')->firstOrFail();
    }

    private function order(string $method = 'Chuyển khoản QR', string $orderStatus = HoaDon::CHO_XU_LY): HoaDon
    {
        return HoaDon::create([
            'khach_hang_id' => $this->customer()->khachHang->id,
            'ma_don_hang' => 'PAY-' . str_pad((string) (HoaDon::max('id') + 1), 5, '0', STR_PAD_LEFT),
            'ngay_dat' => now(),
            'trang_thai' => $orderStatus,
            'trang_thai_thanh_toan' => HoaDon::CHUA_THANH_TOAN,
            'tong_tien' => 4000000,
            'phuong_thuc_thanh_toan' => $method,
            'dia_chi_giao_hang' => '125 Nguyễn Trãi, Hà Nội',
        ]);
    }

    private function otherCustomer(): KhachHang
    {
        $account = TaiKhoan::create([
            'email' => 'payment-other@example.test',
            'password' => '123456',
            'vai_tro' => TaiKhoan::ROLE_CUSTOMER,
        ]);

        return KhachHang::create([
            'tai_khoan_id' => $account->id,
            'hang_thanh_vien_id' => HangThanhVien::orderBy('diem_toi_thieu')->value('id'),
            'ma_kh' => 'PAY-OTHER',
            'ho_ten' => 'Khách khác',
        ]);
    }

    public function test_new_qr_and_cod_orders_default_to_unpaid(): void
    {
        $this->assertSame(HoaDon::CHUA_THANH_TOAN, $this->order()->trang_thai_thanh_toan);
        $this->assertSame(HoaDon::CHUA_THANH_TOAN, $this->order('COD')->trang_thai_thanh_toan);
    }

    public function test_owner_can_request_qr_confirmation_without_becoming_paid(): void
    {
        $order = $this->order();

        $this->actingAs($this->customer())
            ->post(route('donhang.yeucau-thanhtoan', $order))
            ->assertSessionHas('success');

        $order->refresh();
        $this->assertSame(HoaDon::CHO_XAC_NHAN_THANH_TOAN, $order->trang_thai_thanh_toan);
        $this->assertNotNull($order->yeu_cau_xac_nhan_luc);
        $this->assertNull($order->thanh_toan_luc);
        $this->assertDatabaseHas('lich_su_thanh_toans', [
            'hoa_don_id' => $order->id,
            'trang_thai_moi' => HoaDon::CHO_XAC_NHAN_THANH_TOAN,
        ]);

        $this->actingAs($this->customer())
            ->post(route('donhang.yeucau-thanhtoan', $order))
            ->assertSessionHas('error');
        $this->assertSame(1, LichSuThanhToan::where('hoa_don_id', $order->id)->count());
    }

    public function test_customer_cannot_request_for_another_customer_or_cod_order(): void
    {
        $order = $this->order();
        $other = $this->otherCustomer();
        $this->actingAs($other->taiKhoan)
            ->post(route('donhang.yeucau-thanhtoan', $order))
            ->assertNotFound();

        $cod = $this->order('COD');
        $this->actingAs($this->customer())
            ->post(route('donhang.yeucau-thanhtoan', $cod))
            ->assertSessionHas('error');
        $this->assertSame(HoaDon::CHUA_THANH_TOAN, $cod->fresh()->trang_thai_thanh_toan);
    }

    public function test_only_admin_can_confirm_and_confirmation_is_idempotent(): void
    {
        $order = $this->order();
        app(PaymentStatusService::class)->requestConfirmation(
            $order->id, $this->customer()->khachHang->id, $this->customer()
        );

        $this->actingAs($this->staff())
            ->post(route('thongke.xacnhan-thanhtoan', $order))
            ->assertForbidden();
        $this->actingAs($this->customer())
            ->post(route('thongke.xacnhan-thanhtoan', $order))
            ->assertForbidden();

        $this->actingAs($this->admin())
            ->post(route('thongke.xacnhan-thanhtoan', $order))
            ->assertSessionHas('success');
        $order->refresh();
        $this->assertSame(HoaDon::DA_THANH_TOAN, $order->trang_thai_thanh_toan);
        $this->assertNotNull($order->thanh_toan_luc);
        $this->assertSame($this->admin()->id, $order->nguoi_xac_nhan_thanh_toan_id);

        $historyCount = LichSuThanhToan::where('hoa_don_id', $order->id)->count();
        $this->actingAs($this->admin())->post(route('thongke.xacnhan-thanhtoan', $order));
        $this->assertSame($historyCount, LichSuThanhToan::where('hoa_don_id', $order->id)->count());
    }

    public function test_admin_cannot_confirm_cod_order_even_by_posting_directly(): void
    {
        $order = $this->order('COD');

        $this->actingAs($this->admin())
            ->post(route('thongke.xacnhan-thanhtoan', $order))
            ->assertSessionHas('error');

        $this->assertSame(HoaDon::CHUA_THANH_TOAN, $order->fresh()->trang_thai_thanh_toan);
        $this->assertDatabaseMissing('lich_su_thanh_toans', ['hoa_don_id' => $order->id]);
    }

    public function test_admin_payment_confirmation_button_is_only_rendered_for_qr_orders(): void
    {
        $qr = $this->order('Chuyển khoản QR');
        $cod = $this->order('COD');

        $this->actingAs($this->admin())
            ->get(route('thongke.donhang'))
            ->assertOk()
            ->assertSee(route('thongke.xacnhan-thanhtoan', $qr), false)
            ->assertDontSee(route('thongke.xacnhan-thanhtoan', $cod), false);
    }

    public function test_admin_can_reject_pending_claim_and_customer_can_request_again(): void
    {
        $order = $this->order();
        $service = app(PaymentStatusService::class);
        $service->requestConfirmation($order->id, $this->customer()->khachHang->id, $this->customer());

        $this->actingAs($this->admin())->post(route('thongke.tuchoi-thanhtoan', $order), [
            'ly_do' => 'Không tìm thấy giao dịch',
        ])->assertSessionHas('success');

        $this->assertSame(HoaDon::CHUA_THANH_TOAN, $order->fresh()->trang_thai_thanh_toan);
        $this->assertNull($order->fresh()->yeu_cau_xac_nhan_luc);
        $this->actingAs($this->customer())
            ->post(route('donhang.yeucau-thanhtoan', $order))
            ->assertSessionHas('success');
    }

    public function test_canceled_order_cannot_be_paid_and_paid_order_cannot_be_canceled(): void
    {
        $canceled = $this->order('Chuyển khoản QR', HoaDon::DA_HUY);
        $this->actingAs($this->admin())
            ->post(route('thongke.xacnhan-thanhtoan', $canceled))
            ->assertSessionHas('error');
        $this->assertSame(HoaDon::CHUA_THANH_TOAN, $canceled->fresh()->trang_thai_thanh_toan);

        $paid = $this->order();
        app(PaymentStatusService::class)->confirm($paid->id, $this->admin());
        $this->actingAs($this->customer())
            ->post(route('donhang.huy', $paid))
            ->assertSessionHas('error');
        $this->assertSame(HoaDon::CHO_XU_LY, $paid->fresh()->trang_thai);
    }

    public function test_payment_confirmation_does_not_change_stock_points_or_order_status(): void
    {
        $order = $this->order('Chuyển khoản QR', HoaDon::DANG_GIAO);
        $size = Size::firstOrFail();
        $stock = (int) $size->so_luong_ton;
        $customer = $this->customer()->khachHang;
        $points = (int) $customer->diem_tich_luy;

        app(PaymentStatusService::class)->confirm($order->id, $this->admin());

        $this->assertSame($stock, (int) $size->fresh()->so_luong_ton);
        $this->assertSame($points, (int) $customer->fresh()->diem_tich_luy);
        $this->assertSame(HoaDon::DANG_GIAO, $order->fresh()->trang_thai);
    }

    public function test_customer_pages_display_payment_status_and_cod_has_no_qr_request_action(): void
    {
        $qr = $this->order();
        $cod = $this->order('COD');

        $this->actingAs($this->customer())->get(route('donhang.lichsu'))
            ->assertOk()->assertSee(HoaDon::CHUA_THANH_TOAN);
        $this->actingAs($this->customer())->get(route('donhang.chitiet', $qr))
            ->assertOk()->assertSee('Trạng thái thanh toán')->assertSee(HoaDon::CHUA_THANH_TOAN);
        $this->actingAs($this->customer())->get(route('donhang.chitiet', $cod))
            ->assertOk()->assertDontSee('Tôi đã chuyển khoản — Yêu cầu xác nhận');
    }
}
