<?php

namespace App\Services;

use App\Models\GioHang;
use App\Models\HangThanhVien;
use App\Models\HoaDon;
use App\Models\ChiTietHoaDon;
use App\Models\KhachHang;
use App\Models\NhanVien;
use App\Models\Size;
use Illuminate\Support\Facades\DB;
use RuntimeException;

/**
 * Toàn bộ nghiệp vụ đơn hàng của Thành viên 3 nằm ở đây:
 * tạo đơn từ giỏ (trừ kho + chiết khấu hạng), đổi trạng thái, hủy đơn (hoàn kho), cộng điểm.
 */
class DonHangService
{
    /** Cứ mỗi 10.000đ tiền hàng (không tính ship) = 1 điểm tích lũy */
    public const VND_MOI_DIEM = 10000;

    /**
     * Tính tiền cho giỏ hàng: tạm tính (đã trừ Flash Sale của TV1), chiết khấu hạng (TV2), phí ship.
     */
    public function tinhTien($chiTiets, ?KhachHang $khachHang): array
    {
        $tamTinh = 0;
        foreach ($chiTiets as $ct) {
            $tamTinh += $ct->sanPham->tinhGiaBan($ct->so_luong); // TV1: đã trừ % Flash Sale
        }

        $phanTramHang = (int) optional($khachHang?->hangThanhVien)->phan_tram_chiet_khau;
        $giamHang     = (int) round($tamTinh * $phanTramHang / 100);
        $phiShip      = $tamTinh > 0 ? HoaDon::PHI_VAN_CHUYEN : 0;

        return [
            'tam_tinh'       => $tamTinh,
            'phan_tram_hang' => $phanTramHang,
            'giam_hang'      => $giamHang,
            'phi_ship'       => $phiShip,
            'thanh_toan'     => $tamTinh - $giamHang + $phiShip,
        ];
    }

    /**
     * Chốt đơn: kiểm tra kho -> tạo hóa đơn -> trừ kho -> xóa giỏ. Tất cả trong 1 transaction.
     */
    public function taoDonTuGioHang(KhachHang $khachHang, GioHang $gioHang, array $thongTin): HoaDon
    {
        return DB::transaction(function () use ($khachHang, $gioHang, $thongTin) {
            $chiTiets = $gioHang->chiTiets()->with(['sanPham', 'size'])->get();

            if ($chiTiets->isEmpty()) {
                throw new RuntimeException('Giỏ hàng trống!');
            }

            $tien = $this->tinhTien($chiTiets, $khachHang);

            $hoaDon = HoaDon::create([
                'khach_hang_id'          => $khachHang->id,
                'ngay_dat'               => now(),
                'trang_thai'             => HoaDon::CHO_XU_LY,
                'tong_tien'              => $tien['thanh_toan'],
                'phuong_thuc_thanh_toan' => $thongTin['phuong_thuc'] ?? 'COD',
                'dia_chi_giao_hang'      => $thongTin['dia_chi'],
                'ghi_chu'                => $thongTin['ghi_chu'] ?? null,
            ]);

            $hoaDon->update([
                'ma_don_hang' => 'DH' . now()->format('ymd') . str_pad($hoaDon->id, 5, '0', STR_PAD_LEFT),
            ]);

            foreach ($chiTiets as $ct) {
                // Khóa dòng size để 2 khách mua cùng lúc không làm tồn kho bị âm
                $size = Size::whereKey($ct->size_id)->lockForUpdate()->first();

                if (!$size || !$size->truKho($ct->so_luong)) { // TV1: Size::truKho()
                    throw new RuntimeException(
                        "Sản phẩm \"{$ct->sanPham->ten_sp}\" size {$ct->size->ten_size} không đủ hàng trong kho!"
                    );
                }

                ChiTietHoaDon::create([
                    'hoa_don_id'  => $hoaDon->id,
                    'san_pham_id' => $ct->san_pham_id,
                    'size_id'     => $ct->size_id,
                    'don_gia'     => $ct->sanPham->gia_khuyen_mai, // chốt giá tại thời điểm mua
                    'so_luong'    => $ct->so_luong,
                    'thanh_tien'  => $ct->sanPham->tinhGiaBan($ct->so_luong),
                ]);
            }

            $gioHang->chiTiets()->delete();

            return $hoaDon;
        });
    }

    /**
     * Đổi trạng thái đơn theo đúng luồng. Hủy => hoàn kho. Hoàn thành => cộng điểm + thăng hạng.
     */
    public function capNhatTrangThai(int $hoaDonId, string $trangThaiMoi, ?NhanVien $nhanVien = null): HoaDon
    {
        return DB::transaction(function () use ($hoaDonId, $trangThaiMoi, $nhanVien) {
            $hoaDon = HoaDon::whereKey($hoaDonId)->lockForUpdate()->firstOrFail();

            if (!in_array($trangThaiMoi, HoaDon::trangThaiCoTheChuyenTu($hoaDon->trang_thai), true)) {
                throw new RuntimeException(
                    "Không thể chuyển đơn từ \"{$hoaDon->trang_thai}\" sang \"{$trangThaiMoi}\"."
                );
            }

            $duLieu = ['trang_thai' => $trangThaiMoi];
            if ($nhanVien && !$hoaDon->nhan_vien_duyet_id) {
                $duLieu['nhan_vien_duyet_id'] = $nhanVien->id; // người duyệt đơn đầu tiên
            }
            $hoaDon->update($duLieu);

            if ($trangThaiMoi === HoaDon::DA_HUY) {
                $this->hoanKho($hoaDon);
            }
            if ($trangThaiMoi === HoaDon::HOAN_THANH) {
                $this->congDiemVaThangHang($hoaDon);
            }

            return $hoaDon;
        });
    }

    /** Khách tự hủy: chỉ khi đơn còn "Chờ xử lý" và là đơn của chính mình */
    public function khachHuyDon(int $hoaDonId, KhachHang $khachHang): HoaDon
    {
        $hoaDon = HoaDon::where('khach_hang_id', $khachHang->id)->findOrFail($hoaDonId);

        if ($hoaDon->trang_thai !== HoaDon::CHO_XU_LY) {
            throw new RuntimeException('Chỉ có thể hủy đơn khi đang ở trạng thái "Chờ xử lý".');
        }

        return $this->capNhatTrangThai($hoaDon->id, HoaDon::DA_HUY);
    }

    private function hoanKho(HoaDon $hoaDon): void
    {
        foreach ($hoaDon->chiTiets as $ct) {
            Size::whereKey($ct->size_id)->increment('so_luong_ton', $ct->so_luong);
        }
    }

    /**
     * Cộng điểm khi đơn hoàn tất, tự thăng hạng (không bao giờ hạ hạng).
     * Hàm này chỉ đọc bảng hang_thanh_viens của TV2 (cột diem_toi_thieu).
     */
    private function congDiemVaThangHang(HoaDon $hoaDon): void
    {
        $khachHang = KhachHang::whereKey($hoaDon->khach_hang_id)->lockForUpdate()->first();
        if (!$khachHang) {
            return;
        }

        $tienHang = max(0, (int) $hoaDon->tong_tien - HoaDon::PHI_VAN_CHUYEN);
        $diem     = intdiv($tienHang, self::VND_MOI_DIEM);

        $khachHang->diem_tich_luy = (int) $khachHang->diem_tich_luy + $diem;

        $hangMoi = HangThanhVien::where('diem_toi_thieu', '<=', $khachHang->diem_tich_luy)
            ->orderByDesc('diem_toi_thieu')
            ->first();
        $hangCu = $khachHang->hangThanhVien;

        if ($hangMoi && (!$hangCu || $hangMoi->diem_toi_thieu > $hangCu->diem_toi_thieu)) {
            $khachHang->hang_thanh_vien_id = $hangMoi->id;
        }

        $khachHang->save();
    }
}
