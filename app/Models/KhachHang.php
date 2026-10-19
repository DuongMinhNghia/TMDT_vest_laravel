<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KhachHang extends Model
{
    use HasFactory;

    protected $table = 'khach_hangs';

    protected $fillable = [
        'tai_khoan_id',
        'hang_thanh_vien_id',
        'ma_kh',
        'ho_ten',
        'so_dien_thoai',
        'dia_chi',
        'ngay_sinh',
        'gioi_tinh',
        'diem_tich_luy'
    ];

    public function taiKhoan()
    {
        return $this->belongsTo(TaiKhoan::class, 'tai_khoan_id');
    }

    public function hangThanhVien()
    {
        return $this->belongsTo(HangThanhVien::class, 'hang_thanh_vien_id');
    }

    public function soDos()
    {
        return $this->hasMany(SoDoKhachHang::class, 'khach_hang_id');
    }

    public function hoaDons()
    {
        return $this->hasMany(HoaDon::class, 'khach_hang_id');
    }
}
