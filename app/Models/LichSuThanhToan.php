<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LichSuThanhToan extends Model
{
    protected $table = 'lich_su_thanh_toans';

    protected $fillable = [
        'hoa_don_id', 'tai_khoan_id', 'trang_thai_cu', 'trang_thai_moi', 'hanh_dong', 'ghi_chu',
    ];

    public function hoaDon()
    {
        return $this->belongsTo(HoaDon::class, 'hoa_don_id');
    }

    public function taiKhoan()
    {
        return $this->belongsTo(TaiKhoan::class, 'tai_khoan_id');
    }
}
