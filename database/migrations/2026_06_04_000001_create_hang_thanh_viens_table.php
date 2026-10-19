<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hang_thanh_viens', function (Blueprint $table) {
            $table->id();
            $table->string('ten_hang', 50)->unique();
            $table->integer('diem_toi_thieu')->default(0);
            $table->integer('phan_tram_chiet_khau')->default(0);
            $table->text('mo_ta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hang_thanh_viens');
    }
};
