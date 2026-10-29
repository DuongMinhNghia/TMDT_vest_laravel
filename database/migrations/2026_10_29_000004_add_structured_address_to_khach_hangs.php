<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('khach_hangs', function (Blueprint $table) {
            $table->string('tinh_thanh_code', 10)->nullable()->after('dia_chi');
            $table->string('phuong_xa_code', 10)->nullable()->after('tinh_thanh_code');
            $table->string('ten_duong', 150)->nullable()->after('phuong_xa_code');
            $table->string('google_place_id', 255)->nullable()->after('ten_duong');
            $table->string('dia_chi_chi_tiet', 200)->nullable()->after('google_place_id');
        });
    }

    public function down(): void
    {
        Schema::table('khach_hangs', function (Blueprint $table) {
            $table->dropColumn([
                'tinh_thanh_code', 'phuong_xa_code', 'ten_duong',
                'google_place_id', 'dia_chi_chi_tiet',
            ]);
        });
    }
};
