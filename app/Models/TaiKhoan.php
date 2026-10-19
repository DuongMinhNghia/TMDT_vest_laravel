<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class TaiKhoan extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'tai_khoans';

    protected $fillable = [
        'email',
        'password',
        'vai_tro',
        'trang_thai'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function khachHang()
    {
        return $this->hasOne(KhachHang::class, 'tai_khoan_id');
    }

    public function nhanVien()
    {
        return $this->hasOne(NhanVien::class, 'tai_khoan_id');
    }

    public function getNameAttribute()
    {
        if ($this->nhanVien && $this->nhanVien->ho_ten) {
            return $this->nhanVien->ho_ten;
        }
        if ($this->khachHang && $this->khachHang->ho_ten) {
            return $this->khachHang->ho_ten;
        }
        return explode('@', $this->email)[0];
    }

    public function getQuyenAttribute()
    {
        return $this->vai_tro === 'admin' ? 'Admin' : ($this->vai_tro === 'nhan_vien' ? 'NhanVien' : 'KhachHang');
    }

    public function isAdmin(): bool
    {
        return strtolower($this->vai_tro ?? '') === 'admin';
    }
}
