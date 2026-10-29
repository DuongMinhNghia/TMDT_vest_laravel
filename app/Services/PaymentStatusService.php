<?php

namespace App\Services;

use App\Models\HoaDon;
use App\Models\LichSuThanhToan;
use App\Models\TaiKhoan;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class PaymentStatusService
{
    public function requestConfirmation(int $orderId, int $customerId, TaiKhoan $actor): HoaDon
    {
        return DB::transaction(function () use ($orderId, $customerId, $actor) {
            $order = HoaDon::whereKey($orderId)->where('khach_hang_id', $customerId)
                ->lockForUpdate()->firstOrFail();

            if ($order->phuong_thuc_thanh_toan !== 'Chuyển khoản QR') {
                throw new RuntimeException('Đơn hàng này không sử dụng phương thức chuyển khoản QR.');
            }
            $this->ensureOrderEligible($order);
            if ($order->trang_thai_thanh_toan !== HoaDon::CHUA_THANH_TOAN) {
                throw new RuntimeException('Yêu cầu xác nhận thanh toán đã được gửi hoặc đơn đã được thanh toán.');
            }

            $this->transition($order, HoaDon::CHO_XAC_NHAN_THANH_TOAN, $actor, 'Khách yêu cầu xác nhận');
            $order->update(['yeu_cau_xac_nhan_luc' => now()]);

            return $order->fresh();
        }, 3);
    }

    public function confirm(int $orderId, TaiKhoan $admin): HoaDon
    {
        return DB::transaction(function () use ($orderId, $admin) {
            $order = HoaDon::whereKey($orderId)->lockForUpdate()->firstOrFail();
            $this->ensureQrPaymentMethod($order);
            $this->ensureOrderEligible($order);

            if ($order->trang_thai_thanh_toan === HoaDon::DA_THANH_TOAN) {
                return $order;
            }
            if (!in_array($order->trang_thai_thanh_toan, [
                HoaDon::CHUA_THANH_TOAN, HoaDon::CHO_XAC_NHAN_THANH_TOAN,
            ], true)) {
                throw new RuntimeException('Trạng thái thanh toán hiện tại không thể xác nhận.');
            }

            $this->transition($order, HoaDon::DA_THANH_TOAN, $admin, 'Admin xác nhận đã nhận tiền');
            $order->update([
                'thanh_toan_luc' => now(),
                'nguoi_xac_nhan_thanh_toan_id' => $admin->id,
            ]);

            return $order->fresh();
        }, 3);
    }

    public function reject(int $orderId, TaiKhoan $admin, ?string $reason = null): HoaDon
    {
        return DB::transaction(function () use ($orderId, $admin, $reason) {
            $order = HoaDon::whereKey($orderId)->lockForUpdate()->firstOrFail();
            $this->ensureQrPaymentMethod($order);
            $this->ensureOrderEligible($order);
            if ($order->trang_thai_thanh_toan !== HoaDon::CHO_XAC_NHAN_THANH_TOAN) {
                throw new RuntimeException('Chỉ có thể từ chối yêu cầu đang chờ xác nhận.');
            }

            $this->transition($order, HoaDon::CHUA_THANH_TOAN, $admin, 'Admin từ chối xác nhận', $reason);
            $order->update(['yeu_cau_xac_nhan_luc' => null]);

            return $order->fresh();
        }, 3);
    }

    private function ensureOrderEligible(HoaDon $order): void
    {
        if ($order->trang_thai === HoaDon::DA_HUY) {
            throw new RuntimeException('Không thể cập nhật thanh toán cho đơn hàng đã hủy.');
        }
    }

    private function ensureQrPaymentMethod(HoaDon $order): void
    {
        if ($order->phuong_thuc_thanh_toan !== 'Chuyển khoản QR') {
            throw new RuntimeException('Chỉ đơn hàng thanh toán bằng mã QR mới có thể xác nhận thanh toán thủ công.');
        }
    }

    private function transition(
        HoaDon $order,
        string $newStatus,
        TaiKhoan $actor,
        string $action,
        ?string $note = null,
    ): void {
        $oldStatus = $order->trang_thai_thanh_toan;
        $order->update(['trang_thai_thanh_toan' => $newStatus]);
        LichSuThanhToan::create([
            'hoa_don_id' => $order->id,
            'tai_khoan_id' => $actor->id,
            'trang_thai_cu' => $oldStatus,
            'trang_thai_moi' => $newStatus,
            'hanh_dong' => $action,
            'ghi_chu' => $note,
        ]);
    }
}
