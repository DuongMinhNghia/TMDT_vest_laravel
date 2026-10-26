<?php

namespace Tests\Feature;

use App\Models\TaiKhoan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class ProductCatalogFilterTest extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    private function customer(): TaiKhoan
    {
        return TaiKhoan::where('email', 'khachhang@gmail.com')->firstOrFail();
    }

    private function catalog(array $filters = [])
    {
        return $this->actingAs($this->customer())->get('/san-pham?' . http_build_query($filters));
    }

    public function test_minimum_maximum_and_combined_price_ranges_use_original_price(): void
    {
        $this->catalog(['gia_min' => 4500000])->assertOk()->assertViewHas('sanPhams', function (LengthAwarePaginator $products) {
            return $products->every(fn ($product) => $product->gia_ban >= 4500000);
        });

        $this->catalog(['gia_max' => 2900000])->assertOk()->assertViewHas('sanPhams', function (LengthAwarePaginator $products) {
            return $products->every(fn ($product) => $product->gia_ban <= 2900000);
        });

        $this->catalog(['gia_min' => 3000000, 'gia_max' => 3300000])->assertOk()->assertViewHas('sanPhams', function (LengthAwarePaginator $products) {
            return $products->isNotEmpty() && $products->every(fn ($product) => $product->gia_ban >= 3000000 && $product->gia_ban <= 3300000);
        });
    }

    public function test_keyword_search_matches_name_material_and_country(): void
    {
        foreach (['Vest', 'Tweed', 'Pháp'] as $keyword) {
            $this->catalog(['tim_kiem' => $keyword])->assertOk()->assertViewHas('sanPhams', function (LengthAwarePaginator $products) use ($keyword) {
                return $products->isNotEmpty() && $products->every(
                    fn ($product) => str_contains($product->ten_sp, $keyword)
                        || str_contains($product->chat_lieu ?? '', $keyword)
                        || str_contains($product->xuat_xu ?? '', $keyword)
                );
            });
        }
    }

    public function test_category_keyword_and_price_filters_are_combined_with_and_logic(): void
    {
        $this->catalog(['danh_muc' => 1, 'tim_kiem' => 'Tweed'])->assertOk()
            ->assertViewHas('sanPhams', fn (LengthAwarePaginator $products) => $products->isNotEmpty() && $products->every(
                fn ($product) => $product->danh_muc_id === 1 && str_contains($product->chat_lieu ?? '', 'Tweed')
            ));

        $this->catalog(['danh_muc' => 1, 'gia_min' => 3000000])->assertOk()
            ->assertViewHas('sanPhams', fn (LengthAwarePaginator $products) => $products->every(
                fn ($product) => $product->danh_muc_id === 1 && $product->gia_ban >= 3000000
            ));

        $this->catalog(['danh_muc' => 1, 'tim_kiem' => 'Tweed', 'gia_min' => 3000000, 'gia_max' => 3300000])->assertOk()
            ->assertViewHas('sanPhams', fn (LengthAwarePaginator $products) => $products->isNotEmpty() && $products->every(
                fn ($product) => $product->danh_muc_id === 1
                    && str_contains($product->chat_lieu ?? '', 'Tweed')
                    && $product->gia_ban >= 3000000
                    && $product->gia_ban <= 3300000
            ));
    }

    public function test_invalid_filters_and_empty_results_are_handled_safely(): void
    {
        $this->catalog(['gia_min' => -1])->assertRedirect()->assertSessionHasErrors('gia_min');
        $this->catalog(['gia_min' => 4000000, 'gia_max' => 3000000])->assertRedirect()->assertSessionHasErrors('gia_max');
        $this->catalog(['danh_muc' => 999999])->assertRedirect()->assertSessionHasErrors('danh_muc');
        $this->catalog(['gia_min' => 'not-a-number'])->assertRedirect()->assertSessionHasErrors('gia_min');

        $this->catalog(['tim_kiem' => 'no matching Kingsman product'])->assertOk()
            ->assertSee('Không tìm thấy tác phẩm phù hợp');
        $this->catalog(['tim_kiem' => '   '])->assertOk()->assertViewHas('sanPhams', fn (LengthAwarePaginator $products) => $products->total() === 10);
    }

    public function test_pagination_keeps_active_filters(): void
    {
        $this->catalog(['gia_min' => 0, 'page' => 2])->assertOk()
            ->assertViewHas('sanPhams', function (LengthAwarePaginator $products) {
                return $products->currentPage() === 2
                    && str_contains($products->url(1), 'gia_min=0');
            });
    }
}
