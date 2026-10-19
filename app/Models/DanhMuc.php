<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DanhMuc extends Model
{
    protected $table = 'danh_mucs';

    protected $fillable = [
        'ten_danh_muc',
        'mo_ta'
    ];

    /**
     * Một danh mục có nhiều sản phẩm
     */
    public function sanPhams()
    {
        return $this->hasMany(SanPham::class, 'danh_muc_id');
    }

    /** Hàm Đếm số lượng sản phẩm trong danh mục
     */
    public function demSanPham()
    {
        return $this->sanPhams()->count();
    }
}
