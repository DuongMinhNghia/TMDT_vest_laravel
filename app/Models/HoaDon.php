<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HoaDon extends Model
{
    protected $table = 'hoa_dons';
    protected $fillable = [
        'khach_hang_id',
        'nhan_vien_duyet_id',
        'ma_don_hang',
        'ngay_dat',
        'trang_thai',
        'tong_tien',
        'phuong_thuc_thanh_toan',
        'dia_chi_giao_hang',
        'ghi_chu'
    ];

    public function khachHang() {
        return $this->belongsTo(KhachHang::class, 'khach_hang_id');
    }

    public function nhanVienDuyet() {
        return $this->belongsTo(NhanVien::class, 'nhan_vien_duyet_id');
    }

    public function chiTiets() {
        return $this->hasMany(ChiTietHoaDon::class, 'hoa_don_id');
    }
}