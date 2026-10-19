<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('san_phams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('danh_muc_id')->constrained('danh_mucs')->onDelete('cascade');
            $table->string('ma_sp', 50)->unique()->nullable();
            $table->string('ten_sp', 100);
            $table->string('chat_lieu', 50)->nullable();
            $table->string('xuat_xu', 50)->nullable();
            $table->string('form_dang', 50)->nullable();
            $table->decimal('gia_ban', 18, 0)->nullable();
            $table->string('hinh_anh', 255)->nullable();
            $table->text('mo_ta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('san_phams');
    }
};
