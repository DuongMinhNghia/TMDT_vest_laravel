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
        // 1. TẠO TÀI KHOẢN (tai_khoans)
        DB::table('tai_khoans')->insert([
            [
                'id'         => 1,
                'email'      => 'admin@gmail.com',
                'password'   => Hash::make('123456'),
                'vai_tro'    => 'admin',
                'trang_thai' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 2,
                'email'      => 'nhanvien@gmail.com',
                'password'   => Hash::make('123456'),
                'vai_tro'    => 'nhan_vien',
                'trang_thai' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id'         => 3,
                'email'      => 'khachhang@gmail.com',
                'password'   => Hash::make('123456'),
                'vai_tro'    => 'khach_hang',
                'trang_thai' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // 2. TẠO HẠNG THÀNH VIÊN (hang_thanh_viens)
        DB::table('hang_thanh_viens')->insert([
            ['id' => 1, 'ten_hang' => 'Đồng', 'diem_toi_thieu' => 0, 'phan_tram_chiet_khau' => 0, 'mo_ta' => 'Hạng khởi đầu khi mới đăng ký'],
            ['id' => 2, 'ten_hang' => 'Bạc', 'diem_toi_thieu' => 500, 'phan_tram_chiet_khau' => 5, 'mo_ta' => 'Giảm 5% mọi đơn hàng'],
            ['id' => 3, 'ten_hang' => 'Vàng', 'diem_toi_thieu' => 2000, 'phan_tram_chiet_khau' => 10, 'mo_ta' => 'Giảm 10% và ưu tiên thử đồ'],
            ['id' => 4, 'ten_hang' => 'Kim Cương (VIP)', 'diem_toi_thieu' => 5000, 'phan_tram_chiet_khau' => 15, 'mo_ta' => 'Giảm 15% và may đo tại nhà'],
        ]);

        // 3. TẠO HỒ SƠ KHÁCH HÀNG & NHÂN VIÊN
        DB::table('nhan_viens')->insert([
            [
                'tai_khoan_id'  => 2,
                'ma_nv'         => 'NV01',
                'ho_ten'        => 'Trần Văn Nhân Viên',
                'so_dien_thoai' => '0901234567',
                'chuc_vu'       => 'Tư vấn may đo',
                'luong_co_ban'  => 12000000,
                'ngay_vao_lam'  => '2025-01-01',
                'trang_thai'    => 'Đang làm việc',
            ]
        ]);

        DB::table('khach_hangs')->insert([
            [
                'tai_khoan_id'       => 3,
                'hang_thanh_vien_id' => 1,
                'ma_kh'              => 'KH0001',
                'ho_ten'             => 'Nguyễn Văn Khách Hàng',
                'so_dien_thoai'      => '0987654321',
                'dia_chi'            => '123 Đường Lê Lợi, Quận 1, TP.HCM',
                'gioi_tinh'          => 'Nam',
                'diem_tich_luy'      => 120,
            ]
        ]);

        // 4. TẠO DANH MỤC (danh_mucs)
        DB::table('danh_mucs')->insert([
            ['id' => 1, 'ten_danh_muc' => 'Vest 2 Mảnh (Two-piece)', 'mo_ta' => 'Bộ vest truyền thống gồm áo jacket và quần âu'],
            ['id' => 2, 'ten_danh_muc' => 'Vest 3 Mảnh (Three-piece)', 'mo_ta' => 'Bộ vest cao cấp kèm áo gile lịch lãm'],
            ['id' => 3, 'ten_danh_muc' => 'Áo Blazer & Jacket', 'mo_ta' => 'Áo khoác vest phối đồ phong cách trẻ trung'],
            ['id' => 4, 'ten_danh_muc' => 'Tuxedo Dạ Tiệc', 'mo_ta' => 'Trang phục dạ tiệc cao cấp cổ ve bóng'],
            ['id' => 5, 'ten_danh_muc' => 'Áo Gile (Waistcoat)', 'mo_ta' => 'Áo gile mặc trong hoặc phối rời'],
        ]);

        // 5. TẠO SẢN PHẨM MẪU (san_phams)
        $sanPhams = [
            ['id' => 1, 'danh_muc_id' => 1, 'ma_sp' => 'VEST-01', 'ten_sp' => 'Vest 2 mảnh đen cổ điển', 'xuat_xu' => 'Việt Nam', 'chat_lieu' => 'Wool pha Ý', 'form_dang' => 'Classic', 'gia_ban' => 2500000, 'hinh_anh' => 'vest2manh_codien.jpg', 'mo_ta' => 'Bộ vest 2 mảnh màu đen chuẩn phong cách công sở.'],
            ['id' => 2, 'danh_muc_id' => 1, 'ma_sp' => 'VEST-02', 'ten_sp' => 'Vest 2 mảnh xanh rêu', 'xuat_xu' => 'Anh Quốc', 'chat_lieu' => 'Vải Tweed', 'form_dang' => 'Slim-fit', 'gia_ban' => 3200000, 'hinh_anh' => 'vest2manh_xanhreu.jfif', 'mo_ta' => 'Bộ vest chất Tweed xanh rêu phong thái quý tộc.'],
            ['id' => 3, 'danh_muc_id' => 1, 'ma_sp' => 'VEST-03', 'ten_sp' => 'Vest 2 mảnh burnt orange', 'xuat_xu' => 'Anh Quốc', 'chat_lieu' => 'Vải Tweed', 'form_dang' => 'Slim-fit', 'gia_ban' => 3200000, 'hinh_anh' => 'vest2manh_darkburnt_orange.jfif', 'mo_ta' => 'Màu sắc thời thượng, phá cách cho quý ông hiện đại.'],
            ['id' => 4, 'danh_muc_id' => 2, 'ma_sp' => 'VEST-04', 'ten_sp' => 'Vest 3 mảnh than chì', 'xuat_xu' => 'Anh Quốc', 'chat_lieu' => 'Wool 100%', 'form_dang' => 'Regular', 'gia_ban' => 4500000, 'hinh_anh' => 'vest3manh_thanchi.jfif', 'mo_ta' => 'Bộ vest 3 mảnh màu than chì quyền lực cho doanh nhân.'],
            ['id' => 5, 'danh_muc_id' => 2, 'ma_sp' => 'VEST-05', 'ten_sp' => 'Vest 3 mảnh xám xanh', 'xuat_xu' => 'Pháp', 'chat_lieu' => 'Wool 100%', 'form_dang' => 'Slim-fit', 'gia_ban' => 4600000, 'hinh_anh' => 'vest3manh_xamxanh.jpg', 'mo_ta' => 'Chất vải cao cấp dệt tại Pháp, form dáng hoàn hảo.'],
            ['id' => 6, 'danh_muc_id' => 3, 'ma_sp' => 'BLZ-01',  'ten_sp' => 'Blazer xanh navy', 'xuat_xu' => 'Ý', 'chat_lieu' => 'Linen Ý', 'form_dang' => 'Slim-fit', 'gia_ban' => 2900000, 'hinh_anh' => 'blazer_xanhnavy.jfif', 'mo_ta' => 'Blazer xanh navy nhẹ nhàng thoáng mát, dễ phối đồ.'],
            ['id' => 7, 'danh_muc_id' => 3, 'ma_sp' => 'BLZ-02',  'ten_sp' => 'Blazer đen trang trọng', 'xuat_xu' => 'Mỹ', 'chat_lieu' => 'Cotton lụa', 'form_dang' => 'Regular', 'gia_ban' => 2900000, 'hinh_anh' => 'blazer_den.jfif', 'mo_ta' => 'Áo blazer đen thanh lịch mặc đi làm hoặc dự tiệc.'],
            ['id' => 8, 'danh_muc_id' => 4, 'ma_sp' => 'TUX-01',  'ten_sp' => 'Tuxedo đen bóng dạ tiệc', 'xuat_xu' => 'Pháp', 'chat_lieu' => 'Satin bóng', 'form_dang' => 'Classic', 'gia_ban' => 5200000, 'hinh_anh' => 'tuxedo_denbong.jpg', 'mo_ta' => 'Tuxedo đen cổ ve shawl lụa bóng sang trọng bậc nhất.'],
            ['id' => 9, 'danh_muc_id' => 4, 'ma_sp' => 'TUX-02',  'ten_sp' => 'Tuxedo trắng viền đen', 'xuat_xu' => 'Pháp', 'chat_lieu' => 'Satin cao cấp', 'form_dang' => 'Slim-fit', 'gia_ban' => 4800000, 'hinh_anh' => 'tuxedotrang_vienden.jpg', 'mo_ta' => 'Thiết kế Tuxedo chú rể nổi bật trong ngày trọng đại.'],
            ['id' => 10, 'danh_muc_id' => 5, 'ma_sp' => 'GIL-01', 'ten_sp' => 'Gile đen quý phái', 'xuat_xu' => 'Ý', 'chat_lieu' => 'Wool pha', 'form_dang' => 'Slim-fit', 'gia_ban' => 1200000, 'hinh_anh' => 'gile_den.jpg', 'mo_ta' => 'Gile đen dễ dàng kết hợp cùng áo sơ mi trắng.'],
        ];
        DB::table('san_phams')->insert($sanPhams);

        // 6. TẠO KHO SIZE CHO TỪNG SẢN PHẨM
        $sizesData = [];
        $sizesMap = [
            'S'   => ['ton' => 5,  'cao' => '1m60 - 1m68', 'nang' => '52kg - 60kg'],
            'M'   => ['ton' => 10, 'cao' => '1m68 - 1m74', 'nang' => '60kg - 68kg'],
            'L'   => ['ton' => 8,  'cao' => '1m74 - 1m78', 'nang' => '68kg - 75kg'],
            'XL'  => ['ton' => 3,  'cao' => '1m78 - 1m83', 'nang' => '75kg - 82kg'],
            'XXL' => ['ton' => 2,  'cao' => '1m80 - 1m88', 'nang' => '82kg - 90kg'],
        ];

        foreach (range(1, 10) as $spId) {
            foreach ($sizesMap as $tenSize => $info) {
                $sizesData[] = [
                    'san_pham_id'     => $spId,
                    'ten_size'        => $tenSize,
                    'so_luong_ton'    => $info['ton'],
                    'chieu_cao_goi_y' => $info['cao'],
                    'can_nang_goi_y'  => $info['nang'],
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ];
            }
        }
        DB::table('sizes')->insert($sizesData);

        // 7. TẠO CÁC BỘ SƯU TẬP MẪU (Task 1.2)
        $bst1 = DB::table('bo_suu_taps')->insertGetId([
            'ten_bo_suu_tap' => 'BST Vest Cưới Hoàng Gia 2026',
            'hinh_banner'    => 'skienhe.png',
            'mo_ta'          => 'Tuyển tập các mẫu vest và tuxedo chú rể đẳng cấp, sang trọng bậc nhất cho ngày trọng đại.',
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        DB::table('bo_suu_tap_san_phams')->insert([
            ['bo_suu_tap_id' => $bst1, 'san_pham_id' => 1],
            ['bo_suu_tap_id' => $bst1, 'san_pham_id' => 8],
            ['bo_suu_tap_id' => $bst1, 'san_pham_id' => 9],
        ]);

        $bst2 = DB::table('bo_suu_taps')->insertGetId([
            'ten_bo_suu_tap' => 'BST Quý Ông Doanh Nhân 2026',
            'hinh_banner'    => 'skienhe.png',
            'mo_ta'          => 'Những thiết kế vest 2 mảnh và 3 mảnh mang lại phong thái đỉnh đạc, quyền lực trong mọi cuộc đàm phán.',
            'created_at'     => now(),
            'updated_at'     => now(),
        ]);

        DB::table('bo_suu_tap_san_phams')->insert([
            ['bo_suu_tap_id' => $bst2, 'san_pham_id' => 2],
            ['bo_suu_tap_id' => $bst2, 'san_pham_id' => 4],
            ['bo_suu_tap_id' => $bst2, 'san_pham_id' => 5],
            ['bo_suu_tap_id' => $bst2, 'san_pham_id' => 7],
        ]);

        // 8. TẠO CÁC CHIẾN DỊCH KHUYẾN MÃI MẪU (Task 1.3)
        $saleId1 = DB::table('giam_gias')->insertGetId([
            'tieu_de'            => 'Flash Sale Tuần Lễ Quý Ông 2026',
            'phan_tram_giam'     => 20,
            'ap_dung_tat_ca'     => false,
            'thoi_gian_bat_dau'  => now()->subDays(1),
            'thoi_gian_ket_thuc' => now()->addDays(6),
            'trang_thai'         => true,
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);

        DB::table('giam_gia_san_phams')->insert([
            ['giam_gia_id' => $saleId1, 'san_pham_id' => 1],
            ['giam_gia_id' => $saleId1, 'san_pham_id' => 2],
            ['giam_gia_id' => $saleId1, 'san_pham_id' => 3],
        ]);

        DB::table('giam_gias')->insert([
            'tieu_de'            => 'Ưu Đãi Mùa Cưới Hoàng Gia (Toàn Shop)',
            'phan_tram_giam'     => 10,
            'ap_dung_tat_ca'     => true,
            'thoi_gian_bat_dau'  => now()->addDays(3),
            'thoi_gian_ket_thuc' => now()->addDays(15),
            'trang_thai'         => true,
            'created_at'         => now(),
            'updated_at'         => now(),
        ]);
    }
}
