<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hoa_dons', function (Blueprint $table) {
            $table->string('ten_nguoi_nhan', 100)->nullable()->after('phuong_thuc_thanh_toan');
            $table->string('so_dien_thoai_nhan', 15)->nullable()->after('ten_nguoi_nhan');
            $table->string('tinh_thanh_code', 10)->nullable()->after('so_dien_thoai_nhan');
            $table->string('phuong_xa_code', 10)->nullable()->after('tinh_thanh_code');
        });
    }

    public function down(): void
    {
        Schema::table('hoa_dons', function (Blueprint $table) {
            $table->dropColumn([
                'ten_nguoi_nhan', 'so_dien_thoai_nhan', 'tinh_thanh_code', 'phuong_xa_code',
            ]);
        });
    }
};
