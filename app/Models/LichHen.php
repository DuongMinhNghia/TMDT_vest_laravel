<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LichHen extends Model
{
    use HasFactory;

    protected $table = 'lich_hens';

    public const CHO_XAC_NHAN = 'Chờ xác nhận';
    public const DA_XAC_NHAN  = 'Đã xác nhận';
    public const DANG_TIEP_DON = 'Đang tiếp đón';
    public const HOAN_THANH    = 'Hoàn thành';
    public const DA_HUY        = 'Đã hủy';

    protected $fillable = [
        'khach_hang_id',
        'nhan_vien_tiep_don_id',
        'san_pham_quan_tam_id',
        'ma_lich_hen',
        'thoi_gian_hen',
        'loai_dich_vu',
        'so_nguoi_di_cung',
        'trang_thai',
        'ghi_chu'
    ];

    protected $casts = [
        'thoi_gian_hen' => 'datetime',
        'so_nguoi_di_cung' => 'integer',
    ];

    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'khach_hang_id');
    }

    public function nhanVienTiepDon()
    {
        return $this->belongsTo(NhanVien::class, 'nhan_vien_tiep_don_id');
    }

    public function sanPhamQuanTam()
    {
        return $this->belongsTo(SanPham::class, 'san_pham_quan_tam_id');
    }

    /**
     * Badge màu cho trạng thái lịch hẹn
     */
    public function getBadgeClassAttribute(): string
    {
        return match ($this->trang_thai) {
            self::CHO_XAC_NHAN => 'bg-warning text-dark',
            self::DA_XAC_NHAN  => 'bg-info text-dark',
            self::DANG_TIEP_DON => 'bg-primary text-white',
            self::HOAN_THANH    => 'bg-success text-white',
            self::DA_HUY        => 'bg-danger text-white',
            default            => 'bg-secondary text-white',
        };
    }
}
