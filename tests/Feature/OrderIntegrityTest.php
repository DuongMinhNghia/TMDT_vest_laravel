<?php

namespace Tests\Feature;

use App\Models\ChiTietGioHang;
use App\Models\GioHang;
use App\Models\HoaDon;
use App\Models\KhachHang;
use App\Models\SanPham;
use App\Models\Size;
use App\Models\TaiKhoan;
use App\Services\DonHangService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use RuntimeException;
use Tests\TestCase;

class OrderIntegrityTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    private function customer(): TaiKhoan
    {
        return TaiKhoan::where('email', 'khachhang@gmail.com')->firstOrFail();
    }

    private function cartWith(array $items): GioHang
    {
        $cart = GioHang::firstOrCreate(['khach_hang_id' => $this->customer()->khachHang->id]);
        foreach ($items as [$productId, $sizeName, $quantity]) {
            $size = Size::where('san_pham_id', $productId)->where('ten_size', $sizeName)->firstOrFail();
            ChiTietGioHang::create([
                'gio_hang_id' => $cart->id,
                'san_pham_id' => $productId,
                'size_id' => $size->id,
                'so_luong' => $quantity,
            ]);
        }
        return $cart;
    }

    private function checkout(GioHang $cart): HoaDon
    {
        return app(DonHangService::class)->taoDonTuGioHang(
            $this->customer()->khachHang,
            $cart,
            ['dia_chi' => '1 Lê Lợi', 'phuong_thuc' => 'COD']
        );
    }

    public function test_checkout_preserves_prices_product_snapshots_and_multiple_sizes(): void
    {
        $stockM = Size::where('san_pham_id', 1)->where('ten_size', 'M')->value('so_luong_ton');
        $stockL = Size::where('san_pham_id', 2)->where('ten_size', 'L')->value('so_luong_ton');
        $order = $this->checkout($this->cartWith([[1, 'M', 2], [2, 'L', 1]]));

        $this->assertSame(0, ChiTietGioHang::count());
        $this->assertSame($stockM - 2, (int) Size::where('san_pham_id', 1)->where('ten_size', 'M')->value('so_luong_ton'));
        $this->assertSame($stockL - 1, (int) Size::where('san_pham_id', 2)->where('ten_size', 'L')->value('so_luong_ton'));

        $line = $order->chiTiets()->where('san_pham_id', 1)->firstOrFail();
        $this->assertSame('Vest 2 mảnh đen cổ điển', $line->ten_san_pham);
        $this->assertSame('M', $line->ten_size);
        $this->assertNotNull($line->hinh_anh_san_pham);
        $purchasedPrice = (int) $line->don_gia;

        SanPham::findOrFail(1)->update(['gia_ban' => 9999999]);
        $this->assertSame($purchasedPrice, (int) $line->fresh()->don_gia);
    }

    public function test_empty_or_insufficient_checkout_rolls_back_without_negative_inventory(): void
    {
        $emptyCart = GioHang::firstOrCreate(['khach_hang_id' => $this->customer()->khachHang->id]);
        $this->expectException(RuntimeException::class);
        $this->checkout($emptyCart);
    }

    public function test_insufficient_stock_keeps_cart_and_does_not_create_an_order(): void
    {
        $size = Size::where('san_pham_id', 1)->where('ten_size', 'M')->firstOrFail();
        $size->update(['so_luong_ton' => 0]);
        $cart = $this->cartWith([[1, 'M', 1]]);

        try {
            $this->checkout($cart);
            $this->fail('Checkout should reject insufficient stock.');
        } catch (RuntimeException) {
            $this->assertSame(0, HoaDon::count());
            $this->assertSame(1, ChiTietGioHang::count());
            $this->assertSame(0, (int) $size->fresh()->so_luong_ton);
        }
    }

    public function test_repeated_checkout_creates_only_one_order(): void
    {
        $cart = $this->cartWith([[1, 'M', 1]]);
        $this->checkout($cart);

        try {
            $this->checkout($cart);
            $this->fail('A cleared cart must not be checked out again.');
        } catch (RuntimeException) {
            $this->assertSame(1, HoaDon::count());
            $this->assertSame(0, ChiTietGioHang::count());
        }
    }

    public function test_customer_cancellation_restores_stock_once_and_nonowners_cannot_cancel(): void
    {
        $stock = Size::where('san_pham_id', 1)->where('ten_size', 'M')->value('so_luong_ton');
        $order = $this->checkout($this->cartWith([[1, 'M', 1]]));
        $service = app(DonHangService::class);
        $service->khachHuyDon($order->id, $this->customer()->khachHang);

        $this->assertSame(HoaDon::DA_HUY, $order->fresh()->trang_thai);
        $this->assertSame($stock, (int) Size::where('san_pham_id', 1)->where('ten_size', 'M')->value('so_luong_ton'));

        try {
            $service->khachHuyDon($order->id, $this->customer()->khachHang);
            $this->fail('A cancelled order cannot be cancelled twice.');
        } catch (RuntimeException) {
            $this->assertSame($stock, (int) Size::where('san_pham_id', 1)->where('ten_size', 'M')->value('so_luong_ton'));
        }

        $otherAccount = TaiKhoan::create(['email' => 'other@example.test', 'password' => '123456', 'vai_tro' => TaiKhoan::ROLE_CUSTOMER]);
        $otherCustomer = KhachHang::create([
            'tai_khoan_id' => $otherAccount->id,
            'hang_thanh_vien_id' => 1,
            'ma_kh' => 'KH' . str_pad($otherAccount->id, 4, '0', STR_PAD_LEFT),
            'ho_ten' => 'Other Customer',
        ]);
        $this->expectException(ModelNotFoundException::class);
        $service->khachHuyDon($order->id, $otherCustomer);
    }

    public function test_status_flow_and_loyalty_points_are_applied_once(): void
    {
        $customer = $this->customer()->khachHang;
        $order = $this->checkout($this->cartWith([[1, 'M', 1]]));
        $service = app(DonHangService::class);
        $pointsBefore = (int) $customer->diem_tich_luy;

        $service->capNhatTrangThai($order->id, HoaDon::DANG_MAY);
        $service->capNhatTrangThai($order->id, HoaDon::DANG_GIAO);
        $service->capNhatTrangThai($order->id, HoaDon::HOAN_THANH);
        $pointsAfter = (int) $customer->fresh()->diem_tich_luy;
        $this->assertGreaterThan($pointsBefore, $pointsAfter);
        $this->assertSame(HoaDon::DA_THANH_TOAN, $order->fresh()->trang_thai_thanh_toan);
        $this->assertNotNull($order->fresh()->thanh_toan_luc);
        $this->assertDatabaseHas('lich_su_thanh_toans', [
            'hoa_don_id' => $order->id,
            'trang_thai_moi' => HoaDon::DA_THANH_TOAN,
        ]);

        try {
            $service->capNhatTrangThai($order->id, HoaDon::HOAN_THANH);
            $this->fail('Completed orders must not transition again.');
        } catch (RuntimeException) {
            $this->assertSame($pointsAfter, (int) $customer->fresh()->diem_tich_luy);
        }
    }

    public function test_discontinued_products_keep_historical_order_lines_visible(): void
    {
        $order = $this->checkout($this->cartWith([[1, 'M', 1]]));
        $product = SanPham::findOrFail(1);
        $product->delete();

        $this->assertSoftDeleted('san_phams', ['id' => $product->id]);
        $line = $order->fresh()->chiTiets()->with('sanPham')->firstOrFail();
        $this->assertSame('Vest 2 mảnh đen cổ điển', $line->ten_san_pham);
        $this->assertNotNull($line->sanPham);

        $this->actingAs($this->customer())->get('/san-pham')->assertOk()->assertDontSee('Vest 2 mảnh đen cổ điển');
        $this->actingAs($this->customer())->get('/don-hang/' . $order->id)->assertOk()->assertSee('Vest 2 mảnh đen cổ điển');
    }
}
