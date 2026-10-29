<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('hoa_dons', function (Blueprint $table) {
            $table->string('trang_thai_thanh_toan', 30)->default('Chưa thanh toán')->after('phuong_thuc_thanh_toan');
            $table->timestamp('yeu_cau_xac_nhan_luc')->nullable()->after('trang_thai_thanh_toan');
            $table->timestamp('thanh_toan_luc')->nullable()->after('yeu_cau_xac_nhan_luc');
            $table->foreignId('nguoi_xac_nhan_thanh_toan_id')->nullable()->after('thanh_toan_luc')
                ->constrained('tai_khoans')->nullOnDelete();
        });

        Schema::create('lich_su_thanh_toans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hoa_don_id')->constrained('hoa_dons')->cascadeOnDelete();
            $table->foreignId('tai_khoan_id')->nullable()->constrained('tai_khoans')->nullOnDelete();
            $table->string('trang_thai_cu', 30);
            $table->string('trang_thai_moi', 30);
            $table->string('hanh_dong', 50);
            $table->string('ghi_chu', 500)->nullable();
            $table->timestamps();
            $table->index(['hoa_don_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lich_su_thanh_toans');
        Schema::table('hoa_dons', function (Blueprint $table) {
            $table->dropConstrainedForeignId('nguoi_xac_nhan_thanh_toan_id');
            $table->dropColumn(['trang_thai_thanh_toan', 'yeu_cau_xac_nhan_luc', 'thanh_toan_luc']);
        });
    }
};
