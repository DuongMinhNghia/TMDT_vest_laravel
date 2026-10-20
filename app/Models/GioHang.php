<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GioHang extends Model
{
    use HasFactory;

    protected $table = 'gio_hangs';

    protected $fillable = ['khach_hang_id'];

    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'khach_hang_id');
    }

    public function chiTiets()
    {
        return $this->hasMany(ChiTietGioHang::class, 'gio_hang_id');
    }
}
