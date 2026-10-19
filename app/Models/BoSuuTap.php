<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoSuuTap extends Model
{
    use HasFactory;

    protected $table = 'bo_suu_taps';

    protected $fillable = [
        'ten_bo_suu_tap',
        'hinh_banner',
        'mo_ta'
    ];

    /**
     * Quan hệ Nhiều - Nhiều: Một bộ sưu tập chứa nhiều sản phẩm vest
     */
    public function sanPhams()
    {
        return $this->belongsToMany(
            SanPham::class,
            'bo_suu_tap_san_phams',
            'bo_suu_tap_id',
            'san_pham_id'
        );
    }

    /**
     * Chỉ lấy các bộ sưu tập đã có sản phẩm (tránh hiển thị BST rỗng)
     */
    public function scopeCoSanPham($query)
    {
        return $query->has('sanPhams');
    }
}
