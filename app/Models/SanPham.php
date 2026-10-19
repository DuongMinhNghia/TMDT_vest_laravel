<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SanPham extends Model
{
    protected $table = 'san_phams';

    protected $fillable = [
        'ten_sp',
        'xuat_xu',
        'chat_lieu',
        'gia',
        'hinh_anh',
        'mo_ta',
        'danh_muc_id'
    ];

    // Quan hệ với DanhMuc
    public function danhMuc()
    {
        return $this->belongsTo(DanhMuc::class, 'danh_muc_id');
    }
}
