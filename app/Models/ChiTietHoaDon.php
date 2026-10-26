<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChiTietHoaDon extends Model
{
    protected $table = 'chi_tiet_hoa_dons';

    protected $fillable = [
        'hoa_don_id', 'san_pham_id', 'size_id', 'ten_san_pham', 'ten_size',
        'hinh_anh_san_pham', 'don_gia', 'so_luong', 'thanh_tien',
    ];

    public function hoaDon()
    {
        return $this->belongsTo(HoaDon::class, 'hoa_don_id');
    }

    public function sanPham()
    {
        return $this->belongsTo(SanPham::class, 'san_pham_id')->withTrashed();
    }

    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id');
    }
}
