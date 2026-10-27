<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HangThanhVien extends Model
{
    use HasFactory;

    protected $table = 'hang_thanh_viens';

    protected $fillable = [
        'ten_hang',
        'diem_toi_thieu',
        'phan_tram_chiet_khau',
        'mo_ta'
    ];

    public function khachHangs()
    {
        return $this->hasMany(KhachHang::class, 'hang_thanh_vien_id');
    }

    /**
     * Lấy màu sắc nhận diện cho từng hạng
     */
    public function getMauSacAttribute(): string
    {
        return match (mb_strtolower($this->ten_hang, 'UTF-8')) {
            'đồng', 'dong' => '#CD7F32',
            'bạc', 'bac' => '#A0A0A0',
            'vàng', 'vang' => '#D4AF37',
            'kim cương (vip)', 'kim cương', 'kim cuong' => '#00D2FF',
            default => '#D4AF37'
        };
    }

    /**
     * Lấy biểu tượng huy hiệu cho từng hạng
     */
    public function getIconAttribute(): string
    {
        return match (mb_strtolower($this->ten_hang, 'UTF-8')) {
            'đồng', 'dong' => 'bi-coin',
            'bạc', 'bac' => 'bi-shield-check',
            'vàng', 'vang' => 'bi-award-fill',
            'kim cương (vip)', 'kim cương', 'kim cuong' => 'bi-gem',
            default => 'bi-patch-check-fill'
        };
    }
}
