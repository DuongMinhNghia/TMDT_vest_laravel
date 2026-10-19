<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lich_hens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('khach_hang_id')->constrained('khach_hangs')->onDelete('cascade');
            $table->foreignId('nhan_vien_tiep_don_id')->nullable()->constrained('nhan_viens')->nullOnDelete();
            $table->foreignId('san_pham_quan_tam_id')->nullable()->constrained('san_phams')->nullOnDelete();
            $table->string('ma_lich_hen', 30)->unique()->nullable();
            $table->dateTime('thoi_gian_hen');
            $table->string('loai_dich_vu', 50)->default('Thử vest có sẵn');
            $table->integer('so_nguoi_di_cung')->default(1);
            $table->string('trang_thai', 50)->default('Chờ xác nhận');
            $table->text('ghi_chu')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lich_hens');
    }
};
