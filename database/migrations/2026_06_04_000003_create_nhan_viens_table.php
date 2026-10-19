<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nhan_viens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tai_khoan_id')->unique()->constrained('tai_khoans')->onDelete('cascade');
            $table->string('ma_nv', 20)->unique();
            $table->string('ho_ten', 100);
            $table->string('so_dien_thoai', 15)->nullable();
            $table->string('chuc_vu', 50)->default('Nhân viên tư vấn');
            $table->decimal('luong_co_ban', 18, 0)->nullable();
            $table->date('ngay_vao_lam')->nullable();
            $table->string('trang_thai', 30)->default('Đang làm việc');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nhan_viens');
    }
};
