<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SanPham extends Model
{
    use HasFactory;

    protected $table = 'san_phams';

    protected $fillable = [
        'danh_muc_id',
        'ma_sp',
        'ten_sp',
        'chat_lieu',
        'xuat_xu',
        'form_dang',
        'gia_ban',
        'hinh_anh',
        'mo_ta'
    ];

    /**
     * Tương thích ngược: Nếu code cũ gọi ->gia thì vẫn trả về ->gia_ban
     */
    public function getGiaAttribute()
    {
        return $this->gia_ban;
    }

    // ==========================================
    // CÁC QUAN HỆ (RELATIONSHIPS)
    // ==========================================

    /**
     * Quan hệ: Thuộc về 1 danh mục
     */
    public function danhMuc()
    {
        return $this->belongsTo(DanhMuc::class, 'danh_muc_id');
    }

    /**
     * Quan hệ: Một sản phẩm có nhiều size và tồn kho theo size
     */
    public function sizes()
    {
        return $this->hasMany(Size::class, 'san_pham_id');
    }

    /**
     * Quan hệ: Nằm trong các bộ sưu tập thời trang marketing
     */
    public function boSuuTaps()
    {
        return $this->belongsToMany(
            BoSuuTap::class,
            'bo_suu_tap_san_phams',
            'san_pham_id',
            'bo_suu_tap_id'
        );
    }

    /**
     * Quan hệ: Các chương trình giảm giá lẻ gắn riêng cho sản phẩm này
     */
    public function giamGias()
    {
        return $this->belongsToMany(
            GiamGia::class,
            'giam_gia_san_phams',
            'san_pham_id',
            'giam_gia_id'
        );
    }

    /**
     * Quan hệ: Các đánh giá phản hồi từ khách hàng
     */
    public function danhGias()
    {
        return $this->hasMany(DanhGia::class, 'san_pham_id');
    }

    /**
     * Quan hệ: Các lịch hẹn khách hàng chọn thử sản phẩm này
     */
    public function lichHens()
    {
        return $this->hasMany(LichHen::class, 'san_pham_quan_tam_id');
    }

    // ==========================================
    // HÀM NGHIỆP VỤ & TỰ ĐỘNG TÍNH GIÁ SALE
    // ==========================================

    /**
     * Tự động kiểm tra % giảm giá lớn nhất đang áp dụng cho sản phẩm này
     * (Kiểm tra cả đợt sale toàn bộ shop và sale riêng sản phẩm này)
     */
    public function getPhanTramGiamAttribute()
    {
        // 1. Kiểm tra có chương trình sale áp dụng toàn shop đang chạy không
        $saleToanShop = GiamGia::dangChay()->where('ap_dung_tat_ca', true)->max('phan_tram_giam');

        // 2. Kiểm tra có chương trình sale riêng cho sản phẩm này đang chạy không
        $saleRieng = $this->giamGias()->dangChay()->max('phan_tram_giam');

        $maxSale = max($saleToanShop ?? 0, $saleRieng ?? 0);

        return $maxSale > 0 ? (int)$maxSale : 0;
    }

    /**
     * Tự động tính giá bán thực tế sau khi trừ khuyến mãi (Đơn vị VNĐ)
     * Ví dụ: Giá gốc 3.000.000đ, sale 20% -> trả về 2.400.000đ
     */
    public function getGiaKhuyenMaiAttribute()
    {
        $phanTram = $this->phan_tram_giam;

        if ($phanTram > 0 && $this->gia_ban > 0) {
            return round($this->gia_ban * (100 - $phanTram) / 100);
        }

        return $this->gia_ban;
    }

    /**
     * Tính tổng số lượng tồn kho của tất cả các size gộp lại
     */
    public function tongTonKho()
    {
        return $this->sizes()->sum('so_luong_ton');
    }

    /**
     * Lấy danh sách các size còn hàng (so_luong_ton > 0)
     */
    public function sizesConHang()
    {
        return $this->sizes()->where('so_luong_ton', '>', 0)->get();
    }

    /**
     * Helper tính giá bán thực tế cho Giỏ hàng (Bàn giao cho Thành viên 3)
     * Trả về thành tiền sau khi trừ % khuyến mãi (nếu có)
     */
    public function tinhGiaBan($soLuong = 1): int
    {
        return (int)($this->gia_khuyen_mai * max(1, (int)$soLuong));
    }

    /**
     * Helper kiểm tra tồn kho cho 1 size cụ thể (Bàn giao cho Thành viên 3)
     */
    public function kiemTraTonKhoSize($tenSize, $soLuongCanMua = 1): bool
    {
        $size = $this->sizes()->where('ten_size', $tenSize)->first();
        if (!$size) return false;
        return $size->so_luong_ton >= max(1, (int)$soLuongCanMua);
    }

    /**
     * Helper trừ tồn kho của 1 size khi chốt đơn thành công (Bàn giao cho Thành viên 3)
     */
    public function truTonKhoSize($tenSize, $soLuong = 1): bool
    {
        $size = $this->sizes()->where('ten_size', $tenSize)->first();
        if (!$size || $size->so_luong_ton < $soLuong) return false;
        $size->decrement('so_luong_ton', $soLuong);
        return true;
    }
}
