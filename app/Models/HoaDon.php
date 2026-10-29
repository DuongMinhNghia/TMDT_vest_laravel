<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HoaDon extends Model
{
    protected $table = 'hoa_dons';

    public const PHI_VAN_CHUYEN = 30000;

    public const CHO_XU_LY  = 'Chờ xử lý';
    public const DANG_MAY   = 'Đang may/gói';
    public const DANG_GIAO  = 'Đang giao';
    public const HOAN_THANH = 'Hoàn Thành';
    public const DA_HUY     = 'Đã hủy';

    public const CHUA_THANH_TOAN = 'Chưa thanh toán';
    public const CHO_XAC_NHAN_THANH_TOAN = 'Chờ xác nhận';
    public const DA_THANH_TOAN = 'Đã thanh toán';
    public const DA_HOAN_TIEN = 'Đã hoàn tiền';

    /** Luồng chuyển trạng thái hợp lệ: trạng thái hiện tại => các trạng thái được phép chuyển tới */
    public const LUONG_TRANG_THAI = [
        self::CHO_XU_LY  => [self::DANG_MAY, self::DA_HUY],
        self::DANG_MAY   => [self::DANG_GIAO, self::DA_HUY],
        self::DANG_GIAO  => [self::HOAN_THANH],
        self::HOAN_THANH => [],
        self::DA_HUY     => [],
    ];

    protected $fillable = [
        'khach_hang_id',
        'nhan_vien_duyet_id',
        'ma_don_hang',
        'ngay_dat',
        'trang_thai',
        'tong_tien',
        'phuong_thuc_thanh_toan',
        'trang_thai_thanh_toan',
        'yeu_cau_xac_nhan_luc',
        'thanh_toan_luc',
        'nguoi_xac_nhan_thanh_toan_id',
        'ten_nguoi_nhan',
        'so_dien_thoai_nhan',
        'tinh_thanh_code',
        'phuong_xa_code',
        'dia_chi_giao_hang',
        'ghi_chu'
    ];

    protected function casts(): array
    {
        return [
            'ngay_dat' => 'datetime',
            'yeu_cau_xac_nhan_luc' => 'datetime',
            'thanh_toan_luc' => 'datetime',
        ];
    }

    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'khach_hang_id');
    }

    public function nhanVienDuyet()
    {
        return $this->belongsTo(NhanVien::class, 'nhan_vien_duyet_id');
    }

    public function chiTiets()
    {
        return $this->hasMany(ChiTietHoaDon::class, 'hoa_don_id');
    }

    public function nguoiXacNhanThanhToan()
    {
        return $this->belongsTo(TaiKhoan::class, 'nguoi_xac_nhan_thanh_toan_id');
    }

    public function lichSuThanhToans()
    {
        return $this->hasMany(LichSuThanhToan::class, 'hoa_don_id');
    }

    public static function trangThaiCoTheChuyenTu(string $hienTai): array
    {
        return self::LUONG_TRANG_THAI[$hienTai] ?? [];
    }
}
