<?php

namespace App\Http\Controllers;

use App\Models\HoaDon;
use App\Services\DonHangService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use RuntimeException;

/**
 * Task 3.3 - phía khách hàng: lịch sử mua, chi tiết đơn, tự hủy đơn.
 */
class DonHangController extends Controller
{
    public function __construct(private DonHangService $donHangService)
    {
    }

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
        $donHang = HoaDon::with(['chiTiets.sanPham', 'chiTiets.size', 'khachHang.taiKhoan', 'nhanVienDuyet'])
            ->findOrFail($id);

        $user    = Auth::user();
        $laChuDon = $user->khachHang && $user->khachHang->id === $donHang->khach_hang_id;
        $laNhanSu = in_array($user->vai_tro, ['admin', 'nhan_vien'], true);

        abort_unless($laChuDon || $laNhanSu, 403, 'Bạn không có quyền xem đơn hàng này.');

        $tamTinh = $donHang->chiTiets->sum('thanh_tien');
        $phiShip = HoaDon::PHI_VAN_CHUYEN;
        $giamHang = max(0, $tamTinh + $phiShip - $donHang->tong_tien); // tong_tien = tạm tính - giảm hạng + ship

        $coTheHuy = $laChuDon && $donHang->trang_thai === HoaDon::CHO_XU_LY;
        $coTheXacNhanNhanHang = $laChuDon && $donHang->trang_thai === HoaDon::DANG_GIAO;

        return view('donhang_chitiet', compact('donHang', 'tamTinh', 'phiShip', 'giamHang', 'coTheHuy', 'coTheXacNhanNhanHang', 'laNhanSu'));
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
