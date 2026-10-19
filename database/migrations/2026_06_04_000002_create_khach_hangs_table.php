<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('khach_hangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tai_khoan_id')->unique()->constrained('tai_khoans')->onDelete('cascade');
            $table->foreignId('hang_thanh_vien_id')->nullable()->constrained('hang_thanh_viens')->nullOnDelete();
            $table->string('ma_kh', 20)->unique()->nullable();
            $table->string('ho_ten', 100);
            $table->string('so_dien_thoai', 15)->nullable();
            $table->string('dia_chi', 255)->nullable();
            $table->date('ngay_sinh')->nullable();
            $table->enum('gioi_tinh', ['Nam', 'Nu', 'Khac'])->default('Nam');
            $table->integer('diem_tich_luy')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('khach_hangs');
    }
};
