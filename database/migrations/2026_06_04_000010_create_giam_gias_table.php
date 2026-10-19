<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('giam_gias', function (Blueprint $table) {
            $table->id();
            $table->string('tieu_de', 150);
            $table->integer('phan_tram_giam');
            $table->boolean('ap_dung_tat_ca')->default(false);
            $table->dateTime('thoi_gian_bat_dau');
            $table->dateTime('thoi_gian_ket_thuc');
            $table->boolean('trang_thai')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('giam_gias');
    }
};
