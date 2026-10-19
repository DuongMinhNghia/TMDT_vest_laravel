<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bo_suu_taps', function (Blueprint $table) {
            $table->id();
            $table->string('ten_bo_suu_tap', 100);
            $table->string('hinh_banner', 255)->nullable();
            $table->text('mo_ta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bo_suu_taps');
    }
};
