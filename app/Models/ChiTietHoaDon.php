<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ChiTietHoaDon extends Model
{
    protected $table = 'chi_tiet_hoa_dons';
    protected $fillable = ['hoa_don_id', 'san_pham_id', 'so_luong', 'don_gia', 'tong_tien','size'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}