<?php

namespace App\Http\Controllers;

use App\Models\HoaDon;
use App\Services\DonHangService;
use App\Services\PaymentStatusService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

/**
 * Task 3.3 - phía khách hàng: lịch sử mua, chi tiết đơn, tự hủy đơn.
 */
class DonHangController extends Controller
{
    public function __construct(
        private DonHangService $donHangService,
        private PaymentStatusService $paymentStatusService,
    ) {}

    public function lichSu()
    {
        $khachHang = Auth::user()->khachHang;
        if (!$khachHang) {
            return redirect()->route('sanpham.index')->with('error', 'Chỉ tài khoản khách hàng mới có lịch sử mua.');
        }

        $donHangs = HoaDon::where('khach_hang_id', $khachHang->id)
            ->withCount('chiTiets')
            ->latest('ngay_dat')
            ->get();

        return view('donhang_lichsu', compact('donHangs'));
    }

    public function chiTiet($id)
    {
        $donHang = HoaDon::with([
            'chiTiets.sanPham', 'chiTiets.size', 'khachHang.taiKhoan', 'nhanVienDuyet', 'nguoiXacNhanThanhToan',
            'lichSuThanhToans' => fn ($query) => $query->with('taiKhoan')->latest(),
        ])
            ->findOrFail($id);

        $user    = Auth::user();
        $laChuDon = $user->khachHang && $user->khachHang->id === $donHang->khach_hang_id;
        $laNhanSu = in_array($user->vai_tro, ['admin', 'nhan_vien'], true);

        abort_unless($laChuDon || $laNhanSu, 403, 'Bạn không có quyền xem đơn hàng này.');

        $tamTinh = $donHang->chiTiets->sum('thanh_tien');
        $phiShip = HoaDon::PHI_VAN_CHUYEN;
        $giamHang = max(0, $tamTinh + $phiShip - $donHang->tong_tien); // tong_tien = tạm tính - giảm hạng + ship

        $coTheHuy = $laChuDon && $donHang->trang_thai === HoaDon::CHO_XU_LY
            && $donHang->trang_thai_thanh_toan !== HoaDon::DA_THANH_TOAN;
        $coTheXacNhanNhanHang = $laChuDon && $donHang->trang_thai === HoaDon::DANG_GIAO;

        return view('donhang_chitiet', compact('donHang', 'tamTinh', 'phiShip', 'giamHang', 'coTheHuy', 'coTheXacNhanNhanHang', 'laNhanSu'));
    }

    /** Hiển thị hướng dẫn chuyển khoản QR thủ công cho chính chủ đơn hàng. */
    public function thanhToan($id)
    {
        $khachHang = Auth::user()?->khachHang;
        abort_unless($khachHang, 403);

        $donHang = HoaDon::where('khach_hang_id', $khachHang->id)->findOrFail($id);
        abort_unless($donHang->phuong_thuc_thanh_toan === 'Chuyển khoản QR', 404);
        abort_unless(
            $donHang->trang_thai === HoaDon::CHO_XU_LY,
            409,
            'Đơn hàng này không còn ở trạng thái chờ thanh toán.'
        );

        $bankId = trim((string) config('services.manual_transfer.bank_id', ''));
        $bankName = trim((string) config('services.manual_transfer.bank_name', ''));
        $accountNumber = trim((string) config('services.manual_transfer.account_number', ''));
        $accountName = trim((string) config('services.manual_transfer.account_name', ''));
        $divisorConfig = config('services.manual_transfer.demo_divisor', 1);
        $divisorValid = filter_var($divisorConfig, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
        $paymentConfigValid = $bankId !== '' && $bankName !== '' && $accountNumber !== ''
            && $accountName !== '' && $divisorValid !== false;

        $heSoDemo = $divisorValid === false ? 1 : (int) $divisorValid;
        if (app()->environment('production')
            && !config('services.manual_transfer.allow_demo_in_production', false)) {
            $heSoDemo = 1;
        }

        $soTienDon = (int) $donHang->tong_tien;
        $soTien = (int) ceil($soTienDon / $heSoDemo);
        $qrUrl = null;

        if ($paymentConfigValid) {
            $query = http_build_query([
                'amount' => $soTien,
                'addInfo' => $donHang->ma_don_hang,
                'accountName' => $accountName,
            ], '', '&', PHP_QUERY_RFC3986);
            $qrUrl = 'https://img.vietqr.io/image/' . rawurlencode($bankId)
                . '-' . rawurlencode($accountNumber) . '-compact2.png?' . $query;
        }

        return view('thanhtoan_qr', compact(
            'donHang', 'bankId', 'bankName', 'accountNumber', 'accountName',
            'soTienDon', 'soTien', 'heSoDemo', 'qrUrl', 'paymentConfigValid'
        ));
    }

    public function huy($id)
    {
        $khachHang = Auth::user()?->khachHang;
        abort_unless($khachHang, 403);

        try {
            $this->donHangService->khachHuyDon((int) $id, $khachHang);
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('donhang.chitiet', $id)->with('success', 'Đã hủy đơn hàng, số lượng đã được hoàn lại kho.');
    }

    public function yeuCauXacNhanThanhToan($id)
    {
        $khachHang = Auth::user()?->khachHang;
        abort_unless($khachHang, 403);

        try {
            $this->paymentStatusService->requestConfirmation((int) $id, $khachHang->id, Auth::user());
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Yêu cầu xác nhận thanh toán đã được ghi nhận. KINGSMAN sẽ kiểm tra giao dịch và cập nhật trạng thái đơn hàng.');
    }

    public function xacNhanNhanHang($id)
    {
        $khachHang = Auth::user()?->khachHang;
        abort_unless($khachHang, 403);

        try {
            $this->donHangService->khachXacNhanNhanHang((int) $id, $khachHang);
        } catch (ModelNotFoundException $e) {
            throw $e;
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return redirect()->route('donhang.chitiet', $id)->with('success', 'Đã xác nhận nhận hàng. Đơn hàng đã hoàn thành.');
    }
}
