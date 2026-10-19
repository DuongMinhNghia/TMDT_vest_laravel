<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('so_do_khach_hangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('khach_hang_id')->constrained('khach_hangs')->onDelete('cascade');
            $table->string('ten_ho_so', 50)->default('Số đo mặc định');
            $table->decimal('vong_co', 5, 1)->nullable();
            $table->decimal('rong_vai', 5, 1)->nullable();
            $table->decimal('vong_nguc', 5, 1)->nullable();
            $table->decimal('vong_eo', 5, 1)->nullable();
            $table->decimal('vong_mong', 5, 1)->nullable();
            $table->decimal('dai_tay_ao', 5, 1)->nullable();
            $table->decimal('dai_ao_vest', 5, 1)->nullable();
            $table->decimal('dai_quan', 5, 1)->nullable();
            $table->decimal('vong_dui', 5, 1)->nullable();
            $table->text('ghi_chu')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('so_do_khach_hangs');
    }
};
