<?php

namespace Tests\Feature;

use App\Models\ChiTietGioHang;
use App\Models\DanhGia;
use App\Models\HoaDon;
use App\Models\KhachHang;
use App\Models\Size;
use App\Models\TaiKhoan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class Phan3Test extends TestCase
{
    use RefreshDatabase;

    protected $seed = true;

    private function khach(): TaiKhoan { return TaiKhoan::where('email', 'khachhang@gmail.com')->first(); }
    private function admin(): TaiKhoan { return TaiKhoan::where('email', 'admin@gmail.com')->first(); }
    private function ton(int $sp, string $size): int { return Size::where('san_pham_id', $sp)->where('ten_size', $size)->value('so_luong_ton'); }
    private function checkoutData(string $method = 'COD'): array
    {
        $addresses = app(\App\Services\VietnamAddressService::class);
        $province = $addresses->provinces()[0];
        $ward = $addresses->wards($province['code'])[0];

        return [
            'ten_nguoi_nhan' => 'Nguyễn Văn An',
            'so_dien_thoai_nhan' => '0912345678',
            'tinh_thanh_code' => $province['code'],
            'phuong_xa_code' => $ward['code'],
            'dia_chi_chi_tiet' => 'Số 125, phố Nguyễn Trãi',
            'phuong_thuc' => $method,
        ];
    }

    public function test_toan_bo_luong_mua_hang(): void
    {
        $khach = $this->khach();
        $tonM  = $this->ton(1, 'M');

        // 1. Thêm vào giỏ (DB)
        $this->actingAs($khach)->post('/gio-hang/them', ['id' => 1, 'size' => 'M'])->assertRedirect();
        $this->actingAs($khach)->post('/gio-hang/them', ['id' => 1, 'size' => 'M'])->assertRedirect();
        $this->assertEquals(2, ChiTietGioHang::sum('so_luong'));

        // size Custom bị từ chối, không tạo dòng
        $this->actingAs($khach)->post('/gio-hang/them', ['id' => 1, 'size' => 'Custom (Vai:45)'])->assertSessionHas('error');
        $this->assertEquals(1, ChiTietGioHang::count());

        // Vượt tồn kho bị chặn (XL còn 3)
        $tonXL = $this->ton(2, 'XL');
        for ($i = 0; $i < $tonXL + 2; $i++) {
            $this->actingAs($khach)->post('/gio-hang/them', ['id' => 2, 'size' => 'XL']);
        }
        $this->assertEquals($tonXL, ChiTietGioHang::where('san_pham_id', 2)->value('so_luong'));
        ChiTietGioHang::where('san_pham_id', 2)->delete();

        // 2. Trang giỏ hàng
        $this->actingAs($khach)->get('/gio-hang')->assertOk()->assertSee('Vest 2 mảnh đen cổ điển');

        // 3. Checkout -> trừ kho, tạo đơn
        $res = $this->actingAs($khach)->post('/gio-hang/checkout', $this->checkoutData());
        $hd  = HoaDon::first();
        $res->assertRedirect(route('donhang.chitiet', $hd->id));
        $this->assertEquals($tonM - 2, $this->ton(1, 'M'));
        $giaSale = (int) \App\Models\SanPham::find(1)->gia_khuyen_mai; // đã trừ Flash Sale của TV1
        $this->assertEquals(2 * $giaSale + 30000, (int) $hd->tong_tien); // hạng Đồng 0%
        $this->assertEquals(HoaDon::CHO_XU_LY, $hd->trang_thai);
        $this->assertNotEmpty($hd->ma_don_hang);
        $this->assertEquals(1, $hd->chiTiets()->count());
        $this->assertEquals(0, ChiTietGioHang::count());

        // 4. Xem lịch sử + chi tiết
        $this->actingAs($khach)->get('/don-hang')->assertOk()->assertSee($hd->ma_don_hang);
        $this->actingAs($khach)->get('/don-hang/' . $hd->id)->assertOk()->assertSee('Vest 2 mảnh đen cổ điển');

        // 5. Khách hủy -> hoàn kho
        $this->actingAs($khach)->post('/don-hang/' . $hd->id . '/huy');
        $this->assertEquals(HoaDon::DA_HUY, $hd->fresh()->trang_thai);
        $this->assertEquals($tonM, $this->ton(1, 'M'));

        // 6. Đơn mới -> admin duyệt theo luồng
        $this->actingAs($khach)->post('/gio-hang/them', ['id' => 1, 'size' => 'M']);
        $this->actingAs($khach)->post('/gio-hang/checkout', $this->checkoutData());
        $hd2 = HoaDon::latest('id')->first();
        $admin = $this->admin();

        $this->actingAs($admin)->post("/don-hang/{$hd2->id}/cap-nhat", ['trang_thai' => 'Hoàn Thành'])->assertSessionHas('error'); // nhảy cóc
        $this->assertEquals(HoaDon::CHO_XU_LY, $hd2->fresh()->trang_thai);

        $diemTruoc = KhachHang::where('tai_khoan_id', $khach->id)->value('diem_tich_luy');
        foreach (['Đang may/gói', 'Đang giao', 'Hoàn Thành'] as $tt) {
            $this->actingAs($admin)->post("/don-hang/{$hd2->id}/cap-nhat", ['trang_thai' => $tt])->assertSessionHas('success');
        }
        $this->assertEquals(HoaDon::HOAN_THANH, $hd2->fresh()->trang_thai);
        $this->assertEquals($diemTruoc + intdiv($giaSale, 10000), KhachHang::where('tai_khoan_id', $khach->id)->value('diem_tich_luy'));

        // Đơn đã hoàn thành không đổi lại được (không cộng điểm 2 lần)
        $this->actingAs($admin)->post("/don-hang/{$hd2->id}/cap-nhat", ['trang_thai' => 'Hoàn Thành'])->assertSessionHas('error');

        // 7. Đánh giá: đã mua SP 1 -> được; chưa mua SP 3 -> bị chặn
        $this->actingAs($khach)->post('/san-pham/1/danh-gia', ['so_sao' => 4, 'noi_dung' => 'Form rất đẹp'])->assertSessionHas('success');
        $this->assertEquals(1, DanhGia::count());
        $this->actingAs($khach)->post('/san-pham/1/danh-gia', ['so_sao' => 5, 'noi_dung' => 'Cập nhật'])->assertSessionHas('success');
        $this->assertEquals(1, DanhGia::count()); // 1 đánh giá / khách / sản phẩm
        $this->actingAs($khach)->post('/san-pham/3/danh-gia', ['so_sao' => 5])->assertSessionHas('error');
        $this->actingAs($khach)->get('/san-pham/1')->assertOk()->assertSee('Cập nhật');

        // 8. Thống kê + phân quyền
        $this->actingAs($admin)->get('/thong-ke/doanh-thu')->assertOk();
        $this->actingAs($admin)->get('/thong-ke/don-hang')->assertOk()->assertSee($hd2->ma_don_hang);
        $this->actingAs($khach)->get('/thong-ke/doanh-thu')->assertForbidden();
        $this->actingAs($khach)->post("/don-hang/{$hd2->id}/cap-nhat", ['trang_thai' => 'Đã hủy'])->assertForbidden();
    }

    public function test_chiet_khau_hang_thanh_vien_va_thang_hang(): void
    {
        $khach = $this->khach();
        KhachHang::where('tai_khoan_id', $khach->id)->update(['hang_thanh_vien_id' => 3, 'diem_tich_luy' => 2000]); // Vàng -10%

        $this->actingAs($khach)->post('/gio-hang/them', ['id' => 4, 'size' => 'M']); // 4.500.000, không sale
        $this->actingAs($khach)->get('/gio-hang')->assertOk()->assertSee('Ưu đãi hạng');
        $this->actingAs($khach)->post('/gio-hang/checkout', $this->checkoutData());

        $hd = HoaDon::first();
        $gia = (int) \App\Models\SanPham::find(4)->gia_khuyen_mai;
        $this->assertEquals($gia - (int) round($gia * 0.10) + 30000, (int) $hd->tong_tien);
        $this->actingAs($khach)->get('/don-hang/' . $hd->id)->assertOk()->assertSee('Ưu đãi hạng thành viên');

        // hoàn thành đủ điểm lên VIP (5000)
        KhachHang::where('tai_khoan_id', $khach->id)->update(['diem_tich_luy' => 4900]);
        foreach (['Đang may/gói', 'Đang giao', 'Hoàn Thành'] as $tt) {
            $this->actingAs($this->admin())->post("/don-hang/{$hd->id}/cap-nhat", ['trang_thai' => $tt]);
        }
        $this->assertEquals(4, KhachHang::where('tai_khoan_id', $khach->id)->value('hang_thanh_vien_id'));
    }

    public function test_danh_gia_co_anh(): void
    {
        $khach = $this->khach();
        $this->actingAs($khach)->post('/gio-hang/them', ['id' => 1, 'size' => 'M']);
        $this->actingAs($khach)->post('/gio-hang/checkout', $this->checkoutData());
        $hd = HoaDon::first();
        foreach (['Đang may/gói', 'Đang giao', 'Hoàn Thành'] as $tt) {
            $this->actingAs($this->admin())->post("/don-hang/{$hd->id}/cap-nhat", ['trang_thai' => $tt]);
        }

        $anh = \Illuminate\Http\UploadedFile::fake()->image('mac_vest.jpg', 300, 300);
        $this->actingAs($khach)->post('/san-pham/1/danh-gia', ['so_sao' => 5, 'noi_dung' => 'Mặc rất vừa', 'hinh_anh' => $anh])->assertSessionHas('success');

        $dg = DanhGia::first();
        $this->assertStringStartsWith('danhgia/', $dg->hinh_anh);
        $this->assertFileExists(public_path('images/' . $dg->hinh_anh));
        @unlink(public_path('images/' . $dg->hinh_anh));

        // file không phải ảnh bị từ chối
        $txt = \Illuminate\Http\UploadedFile::fake()->create('virus.pdf', 10, 'application/pdf');
        $this->actingAs($khach)->post('/san-pham/1/danh-gia', ['so_sao' => 5, 'hinh_anh' => $txt])->assertSessionHasErrors('hinh_anh');
    }
}
