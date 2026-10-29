<?php

namespace Tests\Feature;

use App\Models\ChiTietGioHang;
use App\Models\GioHang;
use App\Models\HoaDon;
use App\Models\SanPham;
use App\Models\Size;
use App\Models\TaiKhoan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiscontinuedCartProductTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    private function customer(): TaiKhoan
    {
        return TaiKhoan::where('email', 'khachhang@gmail.com')->firstOrFail();
    }

    private function addProductToCart(int $productId = 1, string $size = 'M'): ChiTietGioHang
    {
        $this->actingAs($this->customer())
            ->post(route('giohang.add'), ['id' => $productId, 'size' => $size])
            ->assertRedirect();

        return ChiTietGioHang::where('san_pham_id', $productId)->firstOrFail();
    }

    public function test_active_product_can_be_added_but_soft_deleted_product_cannot(): void
    {
        $this->addProductToCart();
        $this->assertDatabaseHas('chi_tiet_gio_hangs', ['san_pham_id' => 1, 'so_luong' => 1]);

        $product = SanPham::findOrFail(2);
        $product->delete();

        $this->actingAs($this->customer())
            ->post(route('giohang.add'), ['id' => $product->id, 'size' => 'L'])
            ->assertRedirect()
            ->assertSessionHas('error', 'Sản phẩm này hiện không còn kinh doanh và không thể thêm vào giỏ hàng.');

        $this->assertDatabaseMissing('chi_tiet_gio_hangs', ['san_pham_id' => $product->id]);
    }

    public function test_cart_renders_discontinued_item_and_customer_can_remove_it(): void
    {
        $item = $this->addProductToCart();
        SanPham::findOrFail(1)->delete();

        $this->actingAs($this->customer())
            ->get(route('giohang.index'))
            ->assertOk()
            ->assertSee('Sản phẩm này hiện không còn kinh doanh. Vui lòng xóa khỏi giỏ hàng.')
            ->assertSee('Vest 2 mảnh đen cổ điển');

        $this->actingAs($this->customer())
            ->delete(route('giohang.remove'), ['key' => $item->id])
            ->assertRedirect(route('giohang.index'));

        $this->assertDatabaseMissing('chi_tiet_gio_hangs', ['id' => $item->id]);
    }

    public function test_quantity_update_is_rejected_for_discontinued_item(): void
    {
        $item = $this->addProductToCart();
        SanPham::findOrFail(1)->delete();

        $this->actingAs($this->customer())
            ->patch(route('giohang.update'), ['key' => $item->id, 'type' => 1])
            ->assertRedirect(route('giohang.index'))
            ->assertSessionHas('error', 'Sản phẩm này hiện không còn kinh doanh. Vui lòng xóa khỏi giỏ hàng.');

        $this->assertSame(1, (int) $item->fresh()->so_luong);
    }

    public function test_checkout_rejects_discontinued_item_without_order_or_inventory_change(): void
    {
        $item = $this->addProductToCart();
        $size = Size::findOrFail($item->size_id);
        $stockBefore = (int) $size->so_luong_ton;
        $ordersBefore = HoaDon::count();
        SanPham::findOrFail(1)->delete();

        $this->actingAs($this->customer())
            ->post(route('giohang.checkout'), [
                'ten_nguoi_nhan' => 'Nguyễn Văn An',
                'so_dien_thoai_nhan' => '0912345678',
                'tinh_thanh_code' => app(\App\Services\VietnamAddressService::class)->provinces()[0]['code'],
                'phuong_xa_code' => app(\App\Services\VietnamAddressService::class)->wards(
                    app(\App\Services\VietnamAddressService::class)->provinces()[0]['code']
                )[0]['code'],
                'ten_duong' => 'Phố Nguyễn Trãi',
                'dia_chi_chi_tiet' => 'Số 125',
                'phuong_thuc' => 'COD',
            ])
            ->assertRedirect(route('giohang.index'))
            ->assertSessionHas('error', 'Giỏ hàng có sản phẩm hiện không còn kinh doanh. Vui lòng xóa sản phẩm đó trước khi thanh toán.');

        $this->assertSame($ordersBefore, HoaDon::count());
        $this->assertSame($stockBefore, (int) $size->fresh()->so_luong_ton);
        $this->assertDatabaseHas('chi_tiet_gio_hangs', ['id' => $item->id]);
    }
}
