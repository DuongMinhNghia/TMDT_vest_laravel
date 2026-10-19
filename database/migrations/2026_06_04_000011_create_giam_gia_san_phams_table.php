<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('giam_gia_san_phams', function (Blueprint $table) {
            $table->foreignId('giam_gia_id')->constrained('giam_gias')->onDelete('cascade');
            $table->foreignId('san_pham_id')->constrained('san_phams')->onDelete('cascade');
            $table->primary(['giam_gia_id', 'san_pham_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('giam_gia_san_phams');
    }
};
