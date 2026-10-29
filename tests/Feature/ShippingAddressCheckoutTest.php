<?php

namespace Tests\Feature;

use App\Models\ChiTietGioHang;
use App\Models\HoaDon;
use App\Models\Size;
use App\Models\TaiKhoan;
use App\Services\VietnamAddressService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ShippingAddressCheckoutTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    private function customer(): TaiKhoan
    {
        return TaiKhoan::where('email', 'khachhang@gmail.com')->firstOrFail();
    }

    private function addresses(): VietnamAddressService
    {
        return app(VietnamAddressService::class);
    }

    private function cart(): ChiTietGioHang
    {
        $this->actingAs($this->customer())
            ->post(route('giohang.add'), ['id' => 1, 'size' => 'M'])
            ->assertRedirect();

        return ChiTietGioHang::firstOrFail();
    }

    private function validPayload(string $paymentMethod = 'COD'): array
    {
        $province = $this->addresses()->provinces()[0];
        $ward = $this->addresses()->wards($province['code'])[0];

        return [
            'ten_nguoi_nhan' => '  Nguyễn   Thị Ánh  ',
            'so_dien_thoai_nhan' => '+84 912-345-678',
            'tinh_thanh_code' => $province['code'],
            'phuong_xa_code' => $ward['code'],
            'ten_duong' => 'Đường Nguyễn Trãi',
            'dia_chi_chi_tiet' => '  Số 125, ngõ 8  ',
            'phuong_thuc' => $paymentMethod,
            'ghi_chu' => 'Gọi trước khi giao',
        ];
    }

    public function test_checkout_page_and_ward_endpoint_use_local_administrative_data(): void
    {
        $this->cart();
        $province = $this->addresses()->provinces()[0];
        $wards = $this->addresses()->wards($province['code']);

        $this->actingAs($this->customer())
            ->get(route('giohang.index'))
            ->assertOk()
            ->assertSee('Chọn Tỉnh / Thành phố')
            ->assertSee('list="province-options"', false)
            ->assertSee('list="ward-options"', false)
            ->assertSee($province['name']);

        $response = $this->actingAs($this->customer())
            ->getJson(route('giohang.phuongxa', ['tinh_thanh_code' => $province['code']]))
            ->assertOk()
            ->assertJsonPath('data.0.code', $wards[0]['code'])
            ->assertJsonPath('data.0.name', $wards[0]['name']);

        $this->assertNotEmpty($response->json('data'));
        $this->assertSame(
            array_values(array_column($wards, 'code')),
            array_values(array_column($response->json('data'), 'code'))
        );
    }

    public function test_valid_vietnamese_address_is_normalized_and_snapshotted_for_cod_order(): void
    {
        $item = $this->cart();
        $stockBefore = (int) Size::findOrFail($item->size_id)->so_luong_ton;
        $payload = $this->validPayload();
        $resolved = $this->addresses()->resolve($payload['tinh_thanh_code'], $payload['phuong_xa_code']);

        $response = $this->actingAs($this->customer())
            ->post(route('giohang.checkout'), $payload);

        $order = HoaDon::firstOrFail();
        $response->assertRedirect(route('donhang.chitiet', $order));
        $this->assertSame('Nguyễn Thị Ánh', $order->ten_nguoi_nhan);
        $this->assertSame('0912345678', $order->so_dien_thoai_nhan);
        $this->assertSame((string) $payload['tinh_thanh_code'], $order->tinh_thanh_code);
        $this->assertSame((string) $payload['phuong_xa_code'], $order->phuong_xa_code);
        $this->assertSame(
            'Số 125, ngõ 8, Đường Nguyễn Trãi, ' . $resolved['ward']['name'] . ', ' . $resolved['province']['name'],
            $order->dia_chi_giao_hang
        );
        $this->assertSame($stockBefore - 1, (int) Size::findOrFail($item->size_id)->so_luong_ton);

        $this->actingAs($this->customer())
            ->get(route('donhang.lichsu'))
            ->assertOk()
            ->assertSee($order->ma_don_hang);
        $this->actingAs($this->customer())
            ->get(route('donhang.chitiet', $order))
            ->assertOk()
            ->assertSee('Nguyễn Thị Ánh')
            ->assertSee('0912345678')
            ->assertSee($resolved['ward']['name']);
    }

    public function test_valid_structured_address_remains_compatible_with_vietqr_checkout(): void
    {
        $this->cart();
        config()->set('services.manual_transfer.bank_id', 'MB');
        config()->set('services.manual_transfer.bank_name', 'MBBank');
        config()->set('services.manual_transfer.account_number', 'TEST-ACCOUNT');
        config()->set('services.manual_transfer.account_name', 'KINGSMAN TEST');
        config()->set('services.manual_transfer.demo_divisor', 1);

        $response = $this->actingAs($this->customer())
            ->post(route('giohang.checkout'), $this->validPayload('Chuyển khoản QR'));

        $order = HoaDon::firstOrFail();
        $response->assertRedirect(route('donhang.thanhtoan', $order));
        $this->assertSame('Chuyển khoản QR', $order->phuong_thuc_thanh_toan);
        $this->assertSame('Nguyễn Thị Ánh', $order->ten_nguoi_nhan);
    }

    public function test_required_structured_address_fields_are_rejected_and_cart_is_preserved(): void
    {
        $item = $this->cart();
        $payload = $this->validPayload();

        foreach (['tinh_thanh_code', 'phuong_xa_code', 'dia_chi_chi_tiet'] as $field) {
            $invalid = $payload;
            $invalid[$field] = '';
            $this->actingAs($this->customer())
                ->post(route('giohang.checkout'), $invalid)
                ->assertSessionHasErrors($field);
        }

        $this->assertSame(0, HoaDon::count());
        $this->assertDatabaseHas('chi_tiet_gio_hangs', ['id' => $item->id, 'so_luong' => 1]);
    }

    public function test_nonexistent_and_mismatched_administrative_codes_are_rejected(): void
    {
        $item = $this->cart();
        $payload = $this->validPayload();

        $invalidProvince = $payload;
        $invalidProvince['tinh_thanh_code'] = '999999';
        $this->actingAs($this->customer())
            ->post(route('giohang.checkout'), $invalidProvince)
            ->assertSessionHasErrors('tinh_thanh_code');

        $invalidWard = $payload;
        $invalidWard['phuong_xa_code'] = '999999';
        $this->actingAs($this->customer())
            ->post(route('giohang.checkout'), $invalidWard)
            ->assertSessionHasErrors('phuong_xa_code');

        $secondProvince = $this->addresses()->provinces()[1];
        $mismatch = $payload;
        $mismatch['phuong_xa_code'] = $this->addresses()->wards($secondProvince['code'])[0]['code'];
        $this->actingAs($this->customer())
            ->post(route('giohang.checkout'), $mismatch)
            ->assertSessionHasErrors([
                'phuong_xa_code' => 'Phường/Xã đã chọn không thuộc Tỉnh/Thành phố này.',
            ]);

        $this->assertSame(0, HoaDon::count());
        $this->assertDatabaseHas('chi_tiet_gio_hangs', ['id' => $item->id]);
    }

    public function test_invalid_phone_and_meaningless_detail_do_not_create_order_or_change_stock(): void
    {
        $item = $this->cart();
        $stockBefore = (int) Size::findOrFail($item->size_id)->so_luong_ton;
        $payload = $this->validPayload();
        $payload['so_dien_thoai_nhan'] = 'abc-not-a-phone';
        $payload['dia_chi_chi_tiet'] = 'aaaaaaaa';

        $this->actingAs($this->customer())
            ->post(route('giohang.checkout'), $payload)
            ->assertSessionHasErrors(['so_dien_thoai_nhan', 'dia_chi_chi_tiet'])
            ->assertSessionHasInput('ten_nguoi_nhan');

        $this->assertSame(0, HoaDon::count());
        $this->assertSame($stockBefore, (int) Size::findOrFail($item->size_id)->so_luong_ton);
        $this->assertDatabaseHas('chi_tiet_gio_hangs', ['id' => $item->id]);
    }

    public function test_unknown_province_is_rejected_by_ward_endpoint(): void
    {
        $this->actingAs($this->customer())
            ->getJson(route('giohang.phuongxa', ['tinh_thanh_code' => '999999']))
            ->assertUnprocessable()
            ->assertJson(['message' => 'Tỉnh/Thành phố không hợp lệ.']);
    }

    public function test_google_street_suggestions_are_scoped_to_selected_ward_and_keep_key_server_side(): void
    {
        config()->set('services.google_maps.places_api_key', 'server-only-test-key');
        $province = $this->addresses()->provinces()[0];
        $ward = $this->addresses()->wards($province['code'])[0];
        Http::fake([
            'places.googleapis.com/*' => Http::response([
                'suggestions' => [[
                    'placePrediction' => [
                        'placeId' => 'google-place-1',
                        'text' => ['text' => 'Đường Nguyễn Trãi, ' . $ward['name'] . ', ' . $province['name'] . ', Việt Nam'],
                        'structuredFormat' => [
                            'mainText' => ['text' => 'Đường Nguyễn Trãi'],
                            'secondaryText' => ['text' => $ward['name'] . ', ' . $province['name'] . ', Việt Nam'],
                        ],
                    ],
                ]],
            ]),
        ]);
        $this->actingAs($this->customer())
            ->getJson(route('diachi.goiyduong', [
                'q' => 'Nguyễn Trãi',
                'tinh_thanh_code' => $province['code'],
                'phuong_xa_code' => $ward['code'],
                'session_token' => '550e8400-e29b-41d4-a716-446655440000',
            ]))
            ->assertOk()
            ->assertJsonPath('data.0.main_text', 'Đường Nguyễn Trãi')
            ->assertJsonMissing(['server-only-test-key']);

        Http::assertSent(function (Request $request) use ($province, $ward) {
            return $request->url() === 'https://places.googleapis.com/v1/places:autocomplete'
                && $request->hasHeader('X-Goog-Api-Key', 'server-only-test-key')
                && str_contains($request['input'], $ward['name'])
                && str_contains($request['input'], $province['name'])
                && $request['includedRegionCodes'] === ['vn'];
        });
    }

    public function test_structured_profile_address_is_saved_and_prefilled_at_checkout(): void
    {
        $province = $this->addresses()->provinces()[0];
        $ward = $this->addresses()->wards($province['code'])[0];
        $customer = $this->customer();

        $this->actingAs($customer)->post(route('khachhang.thongtin.luu'), [
            'ho_ten' => 'Nguyễn Thị Ánh',
            'so_dien_thoai' => '0912345678',
            'tinh_thanh_code' => $province['code'],
            'phuong_xa_code' => $ward['code'],
            'ten_duong' => 'Đường Nguyễn Trãi',
            'google_place_id' => 'google-place-1',
            'dia_chi_chi_tiet' => 'Số 125, ngõ 8',
        ])->assertSessionHas('success');

        $profile = $customer->khachHang->fresh();
        $this->assertSame((string) $province['code'], $profile->tinh_thanh_code);
        $this->assertSame((string) $ward['code'], $profile->phuong_xa_code);
        $this->assertSame('Đường Nguyễn Trãi', $profile->ten_duong);
        $this->assertSame('Số 125, ngõ 8', $profile->dia_chi_chi_tiet);
        $this->assertSame(
            'Số 125, ngõ 8, Đường Nguyễn Trãi, ' . $ward['name'] . ', ' . $province['name'],
            $profile->dia_chi
        );

        $this->actingAs($customer)
            ->get(route('khachhang.dashboard'))
            ->assertOk()
            ->assertSee('id="profile_province_combobox"', false)
            ->assertSee('id="profile_ward_combobox"', false);

        $this->cart();
        $this->actingAs($customer)
            ->get(route('giohang.index'))
            ->assertOk()
            ->assertSee('Đã tự động dùng địa chỉ trong hồ sơ')
            ->assertSee('value="Đường Nguyễn Trãi"', false)
            ->assertSee('value="' . $ward['code'] . '"', false);
    }
}
