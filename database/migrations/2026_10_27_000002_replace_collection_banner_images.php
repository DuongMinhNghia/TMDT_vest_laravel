<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('bo_suu_taps')
            ->where('ten_bo_suu_tap', 'BST Vest Cưới Hoàng Gia 2026')
            ->update(['hinh_banner' => 'banner-vest-cuoi.jpg']);

        DB::table('bo_suu_taps')
            ->where('ten_bo_suu_tap', 'BST Quý Ông Doanh Nhân 2026')
            ->update(['hinh_banner' => 'banner-doanh-nhan.jpg']);
    }

    public function down(): void
    {
        DB::table('bo_suu_taps')
            ->whereIn('hinh_banner', ['banner-vest-cuoi.jpg', 'banner-doanh-nhan.jpg'])
            ->update(['hinh_banner' => 'skienhe.png']);
    }
};
