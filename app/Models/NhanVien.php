<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NhanVien extends Model
{
    use HasFactory;

    protected $table = 'nhan_viens';

    protected $fillable = [
        'tai_khoan_id',
        'ma_nv',
        'ho_ten',
        'so_dien_thoai',
        'chuc_vu',
        'luong_co_ban',
        'ngay_vao_lam',
        'trang_thai'
    ];

    public function taiKhoan()
    {
        return $this->belongsTo(TaiKhoan::class, 'tai_khoan_id');
    }

    public function hoaDonsDuyet()
    {
        return $this->hasMany(HoaDon::class, 'nhan_vien_duyet_id');
    }
}
