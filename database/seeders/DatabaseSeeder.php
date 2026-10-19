<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Thêm dữ liệu bảng users
        DB::table('users')->insert([
            ['name' => 'Bùi Xuân Hiếu', 'email' => 'buixuanhieu@gmail.com', 'password' => Hash::make('xuanhieu123'), 'quyen' => 'Admin', 'phone' => null, 'address' => null],
            ['name' => 'Huỳnh Minh Quân', 'email' => 'huynhminhquan@gmail.com', 'password' => Hash::make('minhquan456'), 'quyen' => 'Admin', 'phone' => null, 'address' => null],
            ['name' => 'Lê Quốc Huy', 'email' => 'lequochuy@gmail.com', 'password' => Hash::make('quochuy000'), 'quyen' => 'Admin', 'phone' => '0901023100', 'address' => '111 khu phố abc, thành phố Buôn Mê Thuột,tỉnh Lâm Đồng'],
            ['name' => 'Nguyễn Văn A', 'email' => 'nguyenvana@gmail.com', 'password' => Hash::make('123456'), 'quyen' => 'Khách hàng', 'phone' => '0901012345', 'address' => '111 khu phố xyz, thành phố Sa Đéc,tỉnh Đồng Tháp'],
            ['name' => 'Vũ Phú Lộc', 'email' => 'vuphuloc@gmail.com', 'password' => Hash::make('123456'), 'quyen' => 'Khách hàng', 'phone' => '0901054321', 'address' => '111 khu phố aaac,t,tỉnh Quảng Ngãi'],
        ]);

        // 2. Thêm dữ liệu bảng danh_mucs
        DB::table('danh_mucs')->insert([
            ['ten_danh_muc' => 'Phong cách Mỹ'],
            ['ten_danh_muc' => 'Phong cách Anh'],
            ['ten_danh_muc' => 'Three-piece suit'],
            ['ten_danh_muc' => 'Blazer'],
            ['ten_danh_muc' => 'Tuxedo'],
        ]);

        // 3. Thêm dữ liệu bảng san_phams (Mỗi danh mục 3 sản phẩm)
        DB::table('san_phams')->insert([
            ['ten_sp' => 'Vest 2 mảnh đen cổ điển', 'xuat_xu' => 'Việt Nam', 'chat_lieu' => 'Vải Wool pha', 'gia' => 2500000, 'hinh_anh' => 'vest2manh_codien.jpg', 'mo_ta' => 'Bộ vest 2 mảnh màu đen, phong cách công sở.', 'danh_muc_id' => 1],
            ['ten_sp' => 'Vest 2 mảnh xanh rêu', 'xuat_xu' => 'Anh', 'chat_lieu' => 'Vải Tweed', 'gia' => 3200000, 'hinh_anh' => 'vest2manh_xanhreu.jfif', 'mo_ta' => 'Bộ vest 2 mảnh chất Tweed, màu xanh rêu.', 'danh_muc_id' => 1],
            ['ten_sp' => 'Vest 2 mảnh dark burnt orange', 'xuat_xu' => 'Anh', 'chat_lieu' => 'Vải Tweed', 'gia' => 3200000, 'hinh_anh' => 'vest2manh_darkburnt_orange.jfif', 'mo_ta' => 'Bộ vest 2 mảnh chất lượng cao từ Anh.', 'danh_muc_id' => 1],
            ['ten_sp' => 'Gile đen', 'xuat_xu' => 'Pháp', 'chat_lieu' => 'Vải Wool pha', 'gia' => 4200000, 'hinh_anh' => 'gile_den.jpg', 'mo_ta' => 'Gile màu kem sang trọng, dễ phối đồ.', 'danh_muc_id' => 2],
            ['ten_sp' => 'Gile màu xám nhạt', 'xuat_xu' => 'Ý', 'chat_lieu' => 'Vải Cotton', 'gia' => 4200000, 'hinh_anh' => 'gile_mauxamnhat.jpg', 'mo_ta' => 'Gile màu be quý phái.', 'danh_muc_id' => 2],
            ['ten_sp' => 'Gile màu xanh navy', 'xuat_xu' => 'Anh', 'chat_lieu' => 'Vải Wool pha', 'gia' => 4200000, 'hinh_anh' => 'gile_xanhnavy.jfif', 'mo_ta' => 'Gile xanh navy nhẹ, dễ phối đồ.', 'danh_muc_id' => 2],
            ['ten_sp' => 'Vest 3 mảnh than chì', 'xuat_xu' => 'Anh', 'chat_lieu' => 'Vải Wool', 'gia' => 3200000, 'hinh_anh' => 'vest3manh_thanchi.jfif', 'mo_ta' => 'Bộ vest 3 mảnh màu than chì', 'danh_muc_id' => 3],
            ['ten_sp' => 'Vest 3 mảnh xám xanh', 'xuat_xu' => 'Pháp', 'chat_lieu' => 'Vải Wool 100%', 'gia' => 3200000, 'hinh_anh' => 'vest3manh_xamxanh.jpg', 'mo_ta' => 'Bộ vest 3 mảnh màu xám tro', 'danh_muc_id' => 3],
            ['ten_sp' => 'Vest 3 mảnh nâu đất', 'xuat_xu' => 'Ý', 'chat_lieu' => 'Vải Cotton', 'gia' => 3200000, 'hinh_anh' => 'vest3manh_naudat.jfif', 'mo_ta' => 'Bộ vest 3 mảnh màu nâu đất', 'danh_muc_id' => 3],
            ['ten_sp' => 'Blazer xanh navy', 'xuat_xu' => 'Ý', 'chat_lieu' => 'Vải Linen', 'gia' => 2900000, 'hinh_anh' => 'blazer_xanhnavy.jfif', 'mo_ta' => 'Blazer xanh navy nhẹ', 'danh_muc_id' => 4],
            ['ten_sp' => 'Blazer đen', 'xuat_xu' => 'Mỹ', 'chat_lieu' => 'Vải Linen', 'gia' => 2900000, 'hinh_anh' => 'blazer_den.jfif', 'mo_ta' => 'Blazer đen trang trọng', 'danh_muc_id' => 4],
            ['ten_sp' => 'Blazer xám đậm', 'xuat_xu' => 'Anh', 'chat_lieu' => 'Vải Cotton', 'gia' => 2900000, 'hinh_anh' => 'blazer_xamdam.jpg', 'mo_ta' => 'Blazer xám đậm quý phái', 'danh_muc_id' => 4],
            ['ten_sp' => 'Tuxedo đen bóng', 'xuat_xu' => 'Pháp', 'chat_lieu' => 'Vải Satin bóng', 'gia' => 4800000, 'hinh_anh' => 'tuxedo_denbong.jpg', 'mo_ta' => 'Tuxedo đen cổ shawl', 'danh_muc_id' => 5],
            ['ten_sp' => 'Tuxedo trắng viền đen', 'xuat_xu' => 'Pháp', 'chat_lieu' => 'Vải Satin', 'gia' => 4200000, 'hinh_anh' => 'tuxedotrang_vienden.jpg', 'mo_ta' => 'Tuxedo trắng dành cho tiệc tối', 'danh_muc_id' => 5],
            ['ten_sp' => 'Tuxedo xanh navy', 'xuat_xu' => 'Ý', 'chat_lieu' => 'Vải Satin', 'gia' => 4200000, 'hinh_anh' => 'tuxedo_xanhnavy.jfif', 'mo_ta' => 'Tuxedo xanh navy thoáng mát', 'danh_muc_id' => 5],
        ]);

        // 4. Hóa đơn
        DB::table('hoa_dons')->insert([
            ['id' => 1, 'user_id' => 5, 'ngay_ban' => Carbon::parse('2025-01-21'), 'trang_thai' => 'Hoàn Thành', 'thanh_toan' => 5700000, 'dia_chi' => '111 khu phố aaac, tỉnh Quảng Ngãi'],
            ['id' => 2, 'user_id' => 4, 'ngay_ban' => Carbon::parse('2025-07-21'), 'trang_thai' => 'Hoàn Thành', 'thanh_toan' => 12600000, 'dia_chi' => '111 khu phố xyz, thành phố Sa Đéc, tỉnh Đồng Tháp'],
            ['id' => 3, 'user_id' => 5, 'ngay_ban' => Carbon::parse('2025-06-10'), 'trang_thai' => 'Hoàn Thành', 'thanh_toan' => 6100000, 'dia_chi' => '111 khu phố aaac, tỉnh Quảng Ngãi'],
            ['id' => 4, 'user_id' => 5, 'ngay_ban' => Carbon::parse('2025-08-15'), 'trang_thai' => 'Đang giao', 'thanh_toan' => 4800000, 'dia_chi' => '111 khu phố aaac, tỉnh Quảng Ngãi'],
            ['id' => 5, 'user_id' => 4, 'ngay_ban' => Carbon::parse('2025-09-02'), 'trang_thai' => 'Chờ xử lý', 'thanh_toan' => 3200000, 'dia_chi' => '111 khu phố xyz, thành phố Sa Đéc, tỉnh Đồng Tháp'],
        ]);

        // 5. Chi tiết hóa đơn
        DB::table('chi_tiet_hoa_dons')->insert([
            ['hoa_don_id' => 1, 'san_pham_id' => 1, 'size' => 'M', 'gia_sp' => 2500000, 'so_luong' => 1, 'tong_tien' => 2500000],
            ['hoa_don_id' => 1, 'san_pham_id' => 2, 'size' => 'L', 'gia_sp' => 3200000, 'so_luong' => 1, 'tong_tien' => 3200000],
            ['hoa_don_id' => 2, 'san_pham_id' => 4, 'size' => 'S', 'gia_sp' => 4200000, 'so_luong' => 1, 'tong_tien' => 4200000],
            ['hoa_don_id' => 2, 'san_pham_id' => 14, 'size' => 'M', 'gia_sp' => 4200000, 'so_luong' => 2, 'tong_tien' => 8400000],
            ['hoa_don_id' => 3, 'san_pham_id' => 7, 'size' => 'XL', 'gia_sp' => 3200000, 'so_luong' => 1, 'tong_tien' => 3200000],
            ['hoa_don_id' => 3, 'san_pham_id' => 10, 'size' => 'L', 'gia_sp' => 2900000, 'so_luong' => 1, 'tong_tien' => 2900000],
            ['hoa_don_id' => 4, 'san_pham_id' => 13, 'size' => 'M', 'gia_sp' => 4800000, 'so_luong' => 1, 'tong_tien' => 4800000],
            ['hoa_don_id' => 5, 'san_pham_id' => 8, 'size' => 'S', 'gia_sp' => 3200000, 'so_luong' => 1, 'tong_tien' => 3200000],
        ]);

        // 6. Thêm dữ liệu bảng sizes
        DB::table('sizes')->insert([
            ['ten_size' => 'XS', 'so_kg' => '50-55KG', 'chieu_cao' => '1m5 - 1m6'],
            ['ten_size' => 'S',  'so_kg' => '60-70KG', 'chieu_cao' => '1m6 - 1m7'],
            ['ten_size' => 'M',  'so_kg' => '75-80KG', 'chieu_cao' => '1m65 - 1m72'],
            ['ten_size' => 'L',  'so_kg' => '75-85KG', 'chieu_cao' => '1m7 - 1m8'],
            ['ten_size' => 'XL', 'so_kg' => '80-90KG', 'chieu_cao' => '1m72 - 1m8'],
            ['ten_size' => 'XXL', 'so_kg' => '80-90KG', 'chieu_cao' => 'trên 1m8'],
        ]);
    }
}
