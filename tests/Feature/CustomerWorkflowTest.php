<?php

namespace Tests\Feature;

use App\Models\HangThanhVien;
use App\Models\HoaDon;
use App\Models\KhachHang;
use App\Models\LichHen;
use App\Models\SoDoKhachHang;
use App\Models\TaiKhoan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CustomerWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    private function account(string $email): TaiKhoan
    {
        return TaiKhoan::where('email', $email)->firstOrFail();
    }

    private function otherCustomer(): KhachHang
    {
        $account = TaiKhoan::create([
            'email' => 'second-customer@example.test',
            'password' => '123456',
            'vai_tro' => TaiKhoan::ROLE_CUSTOMER,
        ]);

        return KhachHang::create([
            'tai_khoan_id' => $account->id,
            'hang_thanh_vien_id' => HangThanhVien::orderBy('diem_toi_thieu')->value('id'),
            'ma_kh' => 'KH' . str_pad((string) $account->id, 4, '0', STR_PAD_LEFT),
            'ho_ten' => 'Second Customer',
            'diem_tich_luy' => 0,
        ]);
    }

    private function appointment(KhachHang $customer, string $status = LichHen::CHO_XAC_NHAN): LichHen
    {
        return LichHen::create([
            'khach_hang_id' => $customer->id,
            'ma_lich_hen' => 'LH' . str_pad((string) (LichHen::max('id') + 1), 8, '0', STR_PAD_LEFT),
            'thoi_gian_hen' => now()->addDay(),
            'loai_dich_vu' => 'Thử vest có sẵn',
            'so_nguoi_di_cung' => 1,
            'trang_thai' => $status,
        ]);
    }

    public function test_only_customer_accounts_can_use_customer_profile_and_appointment_routes(): void
    {
        $admin = $this->account('admin@gmail.com');
        $staff = $this->account('nhanvien@gmail.com');

        foreach ([$admin, $staff] as $user) {
            $this->actingAs($user)->get('/khach-hang/dashboard')->assertForbidden();
            $this->actingAs($user)->get('/dat-lich')->assertForbidden();
        }

        $this->assertDatabaseCount('khach_hangs', 1);
    }

    public function test_customer_can_only_update_or_delete_own_measurements_and_profile(): void
    {
        $customer = $this->account('khachhang@gmail.com');
        $other = $this->otherCustomer();
        $otherMeasurement = SoDoKhachHang::create(['khach_hang_id' => $other->id, 'ten_ho_so' => 'Private measurements']);

        $this->actingAs($customer)->post('/khach-hang/thong-tin', [
            'ho_ten' => 'Updated Owner', 'so_dien_thoai' => '0900000000',
        ])->assertRedirect();
        $this->assertSame('Updated Owner', $customer->khachHang->fresh()->ho_ten);

        $payload = ['id' => $otherMeasurement->id, 'ten_ho_so' => 'Attempted update'];
        $this->actingAs($customer)->post('/khach-hang/so-do', $payload)->assertNotFound();
        $this->actingAs($customer)->post('/khach-hang/so-do/' . $otherMeasurement->id . '/xoa')->assertNotFound();
        $this->assertDatabaseHas('so_do_khach_hangs', ['id' => $otherMeasurement->id, 'ten_ho_so' => 'Private measurements']);
    }

    public function test_customer_can_change_password_from_their_profile(): void
    {
        $customer = $this->account('khachhang@gmail.com');

        $this->actingAs($customer)->post('/khach-hang/thong-tin', [
            'ho_ten' => $customer->khachHang->ho_ten,
            'current_password' => '123456',
            'password' => 'NewPassword@2026',
            'password_confirmation' => 'NewPassword@2026',
        ])->assertSessionHas('success');

        $this->assertTrue(Hash::check('NewPassword@2026', $customer->fresh()->password));

        $this->actingAs($customer)->post('/khach-hang/thong-tin', [
            'ho_ten' => $customer->khachHang->ho_ten,
            'current_password' => 'not-the-current-password',
            'password' => 'AnotherPassword@2026',
            'password_confirmation' => 'AnotherPassword@2026',
        ])->assertSessionHasErrors('current_password');
    }

    public function test_appointment_creation_uses_unique_id_based_codes_and_owner_cancellation(): void
    {
        $customer = $this->account('khachhang@gmail.com');
        $basePayload = [
            'so_dien_thoai' => '0900000000',
            'so_nguoi_di_cung' => 1,
            'loai_dich_vu' => 'Thử vest có sẵn',
        ];

        $this->actingAs($customer)->post('/dat-lich', $basePayload + ['thoi_gian_hen' => now()->addDays(2)->format('Y-m-d\\TH:i')])->assertRedirect();
        $this->actingAs($customer)->post('/dat-lich', $basePayload + ['thoi_gian_hen' => now()->addDays(3)->format('Y-m-d\\TH:i')])->assertRedirect();
        $appointments = LichHen::orderBy('id')->get();
        $this->assertCount(2, $appointments);
        $this->assertSame(2, $appointments->pluck('ma_lich_hen')->unique()->count());
        $this->assertSame('LH' . str_pad((string) $appointments->first()->id, 8, '0', STR_PAD_LEFT), $appointments->first()->ma_lich_hen);

        $this->actingAs($customer)->post('/lich-hen/' . $appointments->first()->id . '/huy')->assertSessionHas('success');
        $this->assertSame(LichHen::DA_HUY, $appointments->first()->fresh()->trang_thai);
        $this->actingAs($customer)->post('/lich-hen/' . $appointments->first()->id . '/huy')->assertSessionHas('error');

        $other = $this->otherCustomer();
        $otherAppointment = $this->appointment($other);
        $this->actingAs($customer)->post('/lich-hen/' . $otherAppointment->id . '/huy')->assertNotFound();
    }

    public function test_customer_can_confirm_only_their_delivering_order_as_received(): void
    {
        $customer = $this->account('khachhang@gmail.com');
        $order = HoaDon::create([
            'khach_hang_id' => $customer->khachHang->id,
            'ma_don_hang' => 'DH-RECEIVED-001',
            'ngay_dat' => now(),
            'trang_thai' => HoaDon::DANG_GIAO,
            'tong_tien' => 100000,
            'phuong_thuc_thanh_toan' => 'COD',
            'dia_chi_giao_hang' => '1 Le Loi',
        ]);

        $this->actingAs($customer)
            ->post('/don-hang/' . $order->id . '/xac-nhan-nhan-hang')
            ->assertSessionHas('success');
        $this->assertSame(HoaDon::HOAN_THANH, $order->fresh()->trang_thai);

        $other = $this->otherCustomer();
        $otherOrder = HoaDon::create([
            'khach_hang_id' => $other->id,
            'ma_don_hang' => 'DH-RECEIVED-002',
            'ngay_dat' => now(),
            'trang_thai' => HoaDon::DANG_GIAO,
            'tong_tien' => 100000,
            'phuong_thuc_thanh_toan' => 'COD',
            'dia_chi_giao_hang' => '2 Le Loi',
        ]);
        $this->actingAs($customer)
            ->post('/don-hang/' . $otherOrder->id . '/xac-nhan-nhan-hang')
            ->assertNotFound();
        $this->assertSame(HoaDon::DANG_GIAO, $otherOrder->fresh()->trang_thai);
    }

    public function test_customer_can_only_open_their_own_manual_qr_payment_page(): void
    {
        config()->set('services.manual_transfer.demo_divisor', 1);
        config()->set('services.manual_transfer.bank_id', 'MB');
        config()->set('services.manual_transfer.bank_name', 'MBBank');
        config()->set('services.manual_transfer.account_number', 'TEST-ACCOUNT');
        config()->set('services.manual_transfer.account_name', 'KINGSMAN TEST');

        $customer = $this->account('khachhang@gmail.com');
        $order = HoaDon::create([
            'khach_hang_id' => $customer->khachHang->id,
            'ma_don_hang' => 'DH-QR-001',
            'ngay_dat' => now(),
            'trang_thai' => HoaDon::CHO_XU_LY,
            'tong_tien' => 4000000,
            'phuong_thuc_thanh_toan' => 'Chuyển khoản QR',
            'dia_chi_giao_hang' => '1 Le Loi',
        ]);

        $this->actingAs($customer)->get('/don-hang/' . $order->id . '/thanh-toan')
            ->assertOk()
            ->assertSee('DH-QR-001')
            ->assertSee('4.000.000 VNĐ');

        $other = $this->otherCustomer();
        $this->actingAs($other->taiKhoan)->get('/don-hang/' . $order->id . '/thanh-toan')->assertNotFound();
    }

    public function test_staff_can_follow_but_not_skip_or_reopen_appointment_workflow(): void
    {
        $customer = $this->account('khachhang@gmail.com')->khachHang;
        $appointment = $this->appointment($customer);
        $staff = $this->account('nhanvien@gmail.com');

        $this->actingAs($staff)->post('/lich-hen/' . $appointment->id . '/cap-nhat', [
            'trang_thai' => LichHen::DA_XAC_NHAN,
        ])->assertSessionHas('success');
        $this->assertSame(LichHen::DA_XAC_NHAN, $appointment->fresh()->trang_thai);

        $this->actingAs($staff)->post('/lich-hen/' . $appointment->id . '/cap-nhat', [
            'trang_thai' => LichHen::HOAN_THANH,
        ])->assertSessionHas('error');
        $this->assertSame(LichHen::DA_XAC_NHAN, $appointment->fresh()->trang_thai);

        foreach ([LichHen::DANG_TIEP_DON, LichHen::HOAN_THANH] as $status) {
            $this->actingAs($staff)->post('/lich-hen/' . $appointment->id . '/cap-nhat', ['trang_thai' => $status])->assertSessionHas('success');
        }
        $this->actingAs($staff)->post('/lich-hen/' . $appointment->id . '/cap-nhat', ['trang_thai' => LichHen::DA_HUY])->assertSessionHas('error');
    }

    public function test_only_admin_can_change_valid_loyalty_policy_thresholds(): void
    {
        $tier = HangThanhVien::firstOrFail();
        $staff = $this->account('nhanvien@gmail.com');
        $customer = $this->account('khachhang@gmail.com');
        $admin = $this->account('admin@gmail.com');

        $this->actingAs($staff)->post('/hang-thanh-vien/' . $tier->id . '/cap-nhat', [])->assertForbidden();
        $this->actingAs($customer)->post('/hang-thanh-vien/' . $tier->id . '/cap-nhat', [])->assertForbidden();

        $duplicateThreshold = HangThanhVien::whereKeyNot($tier->id)->value('diem_toi_thieu');
        $this->actingAs($admin)->post('/hang-thanh-vien/' . $tier->id . '/cap-nhat', [
            'diem_toi_thieu' => $duplicateThreshold,
            'phan_tram_chiet_khau' => 10,
        ])->assertSessionHasErrors('diem_toi_thieu');
        $this->actingAs($admin)->post('/hang-thanh-vien/' . $tier->id . '/cap-nhat', [
            'diem_toi_thieu' => 50,
            'phan_tram_chiet_khau' => 101,
        ])->assertSessionHasErrors('phan_tram_chiet_khau');
    }
}
