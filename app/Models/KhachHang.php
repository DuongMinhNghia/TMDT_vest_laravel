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
        'tinh_thanh_code',
        'phuong_xa_code',
        'ten_duong',
        'google_place_id',
        'dia_chi_chi_tiet',
        'ngay_sinh',
        'gioi_tinh',
        'diem_tich_luy'
    ];

    protected $casts = [
        'diem_tich_luy' => 'integer',
        'ngay_sinh'     => 'date',
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

    public function lichHens()
    {
        return $this->hasMany(LichHen::class, 'khach_hang_id');
    }

    public function hoaDons()
    {
        return $this->hasMany(HoaDon::class, 'khach_hang_id');
    }

    /**
     * Lấy số đo mặc định hoặc bộ số đo đầu tiên
     */
    public function soDoMacDinh(): ?SoDoKhachHang
    {
        return $this->soDos()->first();
    }

    /**
     * Tính toán tiến độ thăng hạng tiếp theo
     */
    public function tienDoThangHang(): array
    {
        $hangHienTai = $this->hangThanhVien ?? HangThanhVien::orderBy('diem_toi_thieu')->first();
        $hangTiepTheo = HangThanhVien::where('diem_toi_thieu', '>', (int)$this->diem_tich_luy)
            ->orderBy('diem_toi_thieu', 'asc')
            ->first();

        if (!$hangTiepTheo) {
            return [
                'la_cao_nhat'   => true,
                'hang_tiep'     => null,
                'diem_can_them' => 0,
                'phan_tram'     => 100,
            ];
        }

        $diemMocDuoi = $hangHienTai->diem_toi_thieu;
        $diemMocTren = $hangTiepTheo->diem_toi_thieu;
        $khoangDiem  = max(1, $diemMocTren - $diemMocDuoi);
        $diemDatDuoc = max(0, $this->diem_tich_luy - $diemMocDuoi);
        $phanTram    = min(100, round(($diemDatDuoc / $khoangDiem) * 100));

        return [
            'la_cao_nhat'   => false,
            'hang_tiep'     => $hangTiepTheo,
            'diem_can_them' => max(0, $diemMocTren - $this->diem_tich_luy),
            'phan_tram'     => $phanTram,
        ];
    }
}
