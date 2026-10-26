<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('san_phams', function (Blueprint $table) {
            $table->softDeletes();
        });

        Schema::table('chi_tiet_hoa_dons', function (Blueprint $table) {
            $table->string('ten_san_pham', 100)->nullable()->after('san_pham_id');
            $table->string('ten_size', 10)->nullable()->after('size_id');
            $table->string('hinh_anh_san_pham', 255)->nullable()->after('ten_size');
        });

        // Preserve the currently resolvable details of all existing invoices before
        // product names, sizes, or images are changed in the future.
        DB::table('chi_tiet_hoa_dons')->orderBy('id')->chunkById(100, function ($items) {
            foreach ($items as $item) {
                $product = DB::table('san_phams')->where('id', $item->san_pham_id)->first();
                $size = DB::table('sizes')->where('id', $item->size_id)->first();

                DB::table('chi_tiet_hoa_dons')->where('id', $item->id)->update([
                    'ten_san_pham' => $product?->ten_sp,
                    'ten_size' => $size?->ten_size,
                    'hinh_anh_san_pham' => $product?->hinh_anh,
                ]);
            }
        });
    }

    public function down(): void
    {
        Schema::table('chi_tiet_hoa_dons', function (Blueprint $table) {
            $table->dropColumn(['ten_san_pham', 'ten_size', 'hinh_anh_san_pham']);
        });

        Schema::table('san_phams', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
};
