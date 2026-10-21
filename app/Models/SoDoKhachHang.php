<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoDoKhachHang extends Model
{
    use HasFactory;

    protected $table = 'so_do_khach_hangs';

    protected $fillable = [
        'khach_hang_id',
        'ten_ho_so',
        'vong_co',
        'rong_vai',
        'vong_nguc',
        'vong_eo',
        'vong_mong',
        'dai_tay_ao',
        'dai_ao_vest',
        'dai_quan',
        'vong_dui',
        'ghi_chu'
    ];

    protected $casts = [
        'vong_co'     => 'float',
        'rong_vai'    => 'float',
        'vong_nguc'   => 'float',
        'vong_eo'     => 'float',
        'vong_mong'   => 'float',
        'dai_tay_ao'  => 'float',
        'dai_ao_vest' => 'float',
        'dai_quan'    => 'float',
        'vong_dui'    => 'float',
    ];

    public function khachHang()
    {
        return $this->belongsTo(KhachHang::class, 'khach_hang_id');
    }
}
