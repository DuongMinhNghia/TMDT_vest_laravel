<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bo_suu_tap_san_phams', function (Blueprint $table) {
            $table->foreignId('bo_suu_tap_id')->constrained('bo_suu_taps')->onDelete('cascade');
            $table->foreignId('san_pham_id')->constrained('san_phams')->onDelete('cascade');
            $table->primary(['bo_suu_tap_id', 'san_pham_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bo_suu_tap_san_phams');
    }
};
