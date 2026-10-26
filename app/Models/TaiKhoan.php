<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @property int $id
 * @property string $email
 * @property string $password
 * @property string $vai_tro
 * @property bool $trang_thai
 * @property string $name
 * @property string $quyen
 * @property string|null $phone
 * @property string|null $address
 * @property \App\Models\KhachHang|null $khachHang
 * @property \App\Models\NhanVien|null $nhanVien
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class TaiKhoan extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_ADMIN = 'admin';
    public const ROLE_STAFF = 'nhan_vien';
    public const ROLE_CUSTOMER = 'khach_hang';

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
            'trang_thai' => 'boolean',
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
        if ($this->isAdmin()) {
            return 'Admin';
        }
        if ($this->isStaff()) {
            return 'Nhân viên';
        }
        return 'Khách hàng';
    }

    public function getPhoneAttribute()
    {
        return $this->nhanVien?->so_dien_thoai ?? $this->khachHang?->so_dien_thoai;
    }

    public function getAddressAttribute()
    {
        return $this->khachHang?->dia_chi;
    }

    public function isAdmin(): bool
    {
        return $this->vai_tro === self::ROLE_ADMIN;
    }

    public function isStaff(): bool
    {
        return $this->vai_tro === self::ROLE_STAFF;
    }

    public function isCustomer(): bool
    {
        return $this->vai_tro === self::ROLE_CUSTOMER;
    }

    public function hasAnyRole(array $roles): bool
    {
        return in_array($this->vai_tro, $roles, true);
    }
}
