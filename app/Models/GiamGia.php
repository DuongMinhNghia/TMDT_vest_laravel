<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GiamGia extends Model
{
    use HasFactory;

    protected $table = 'giam_gias';

    protected $fillable = [
        'tieu_de',
        'phan_tram_giam',
        'ap_dung_tat_ca',
        'thoi_gian_bat_dau',
        'thoi_gian_ket_thuc',
        'trang_thai'
    ];

    protected $casts = [
        'thoi_gian_bat_dau' => 'datetime',
        'thoi_gian_ket_thuc' => 'datetime',
        'ap_dung_tat_ca' => 'boolean',
        'trang_thai' => 'boolean',
    ];

    /**
     * Quan hệ Nhiều - Nhiều: Các sản phẩm lẻ được chỉ định giảm giá
     */
    public function sanPhams()
    {
        return $this->belongsToMany(
            SanPham::class,
            'giam_gia_san_phams',
            'giam_gia_id',
            'san_pham_id'
        );
    }

    /**
     * Kiểm tra chương trình giảm giá cụ thể có đang trong thời gian hiệu lực hay không
     */
    public function dangHieuLuc()
    {
        $now = now();
        return $this->trang_thai &&
               $this->thoi_gian_bat_dau <= $now &&
               $this->thoi_gian_ket_thuc >= $now;
    }

    /**
     * Lọc các chương trình giảm giá đang chạy có hiệu lực
     */
    public function scopeDangChay($query)
    {
        $now = now();
        return $query->where('trang_thai', true)
                     ->where('thoi_gian_bat_dau', '<=', $now)
                     ->where('thoi_gian_ket_thuc', '>=', $now);
    }
}
