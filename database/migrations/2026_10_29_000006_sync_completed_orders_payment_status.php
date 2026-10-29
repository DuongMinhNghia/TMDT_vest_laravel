<?php

use App\Models\HoaDon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('hoa_dons')
            ->where('trang_thai', HoaDon::HOAN_THANH)
            ->where('trang_thai_thanh_toan', '!=', HoaDon::DA_THANH_TOAN)
            ->where('trang_thai_thanh_toan', '!=', HoaDon::DA_HOAN_TIEN)
            ->orderBy('id')
            ->chunkById(100, function ($orders): void {
                foreach ($orders as $order) {
                    $oldStatus = $order->trang_thai_thanh_toan ?: HoaDon::CHUA_THANH_TOAN;
                    $paidAt = $order->updated_at ?: ($order->ngay_dat ?: now());

                    DB::table('hoa_dons')->where('id', $order->id)->update([
                        'trang_thai_thanh_toan' => HoaDon::DA_THANH_TOAN,
                        'thanh_toan_luc' => $order->thanh_toan_luc ?: $paidAt,
                    ]);

                    DB::table('lich_su_thanh_toans')->insert([
                        'hoa_don_id' => $order->id,
                        'tai_khoan_id' => null,
                        'trang_thai_cu' => $oldStatus,
                        'trang_thai_moi' => HoaDon::DA_THANH_TOAN,
                        'hanh_dong' => 'Hệ thống đồng bộ đơn đã hoàn thành',
                        'ghi_chu' => 'Backfill trạng thái thanh toán cho dữ liệu hiện có.',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            });
    }

    public function down(): void
    {
        // Không hoàn tác trạng thái đã thanh toán để tránh làm sai dữ liệu tài chính.
    }
};
