<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hoa_dons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('khach_hang_id')->constrained('khach_hangs')->onDelete('cascade');
            $table->foreignId('nhan_vien_duyet_id')->nullable()->constrained('nhan_viens')->nullOnDelete();
            $table->string('ma_don_hang', 30)->unique()->nullable();
            $table->dateTime('ngay_dat')->useCurrent();
            $table->string('trang_thai', 50)->default('Chờ xử lý');
            $table->decimal('tong_tien', 18, 0)->default(0);
            $table->string('phuong_thuc_thanh_toan', 50)->default('COD');
            $table->text('dia_chi_giao_hang')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hoa_dons');
    }
};
