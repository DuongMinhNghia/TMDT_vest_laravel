<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('khach_hangs')
            ->whereNotNull('ten_duong')
            ->where('ten_duong', '!=', '')
            ->orderBy('id')
            ->chunkById(100, function ($customers): void {
                foreach ($customers as $customer) {
                    $parts = array_values(array_filter([
                        trim((string) $customer->dia_chi_chi_tiet),
                        trim((string) $customer->ten_duong),
                    ]));

                    DB::table('khach_hangs')->where('id', $customer->id)->update([
                        'dia_chi_chi_tiet' => mb_substr(implode(', ', $parts), 0, 200),
                        'ten_duong' => null,
                        'google_place_id' => null,
                    ]);
                }
            });
    }

    public function down(): void
    {
        // Dữ liệu tên đường đã được gộp vào địa chỉ chi tiết nên không tách tự động.
    }
};
