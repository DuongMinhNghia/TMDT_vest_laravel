<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;

    protected $table = 'sizes';

    protected $fillable = [
        'san_pham_id',
        'ten_size',
        'so_luong_ton',
        'chieu_cao_goi_y',
        'can_nang_goi_y'
    ];

    /**
     * Quan hệ: Kích thước này thuộc về một sản phẩm cụ thể
     */
    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'san_pham_id');
    }

    /**
     * Hàm Kiểm tra size này còn hàng trong kho hay không
     */
    public function conHang()
    {
        return $this->so_luong_ton > 0;
    }

    /**
     * Hàm Trừ số lượng tồn kho khi có khách mua
     */
    public function truKho($soLuong = 1)
    {
        if ($this->so_luong_ton >= $soLuong) {
            $this->decrement('so_luong_ton', $soLuong);
            return true;
        }
        return false;
    }
}
