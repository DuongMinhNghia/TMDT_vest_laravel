import os

models_dir = r"c:\Laragon\www\demo\app\Models"

# Mapping table name to Model class name
models = {
    "hang_thanh_viens": "HangThanhVien",
    "so_do_khach_hangs": "SoDoKhachHang",
    "bo_suu_taps": "BoSuuTap",
    "bo_suu_tap_san_phams": "BoSuuTapSanPham",
    "giam_gias": "GiamGia",
    "giam_gia_san_phams": "GiamGiaSanPham",
    "gio_hangs": "GioHang",
    "chi_tiet_gio_hangs": "ChiTietGioHang",
    "danh_gias": "DanhGia",
    "lich_hens": "LichHen",
}

template = """<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class {class_name} extends Model
{{
    use HasFactory;

    protected $table = '{table_name}';

    // TODO: Thành viên phụ trách bổ sung $fillable và các relationships tại đây
}}
"""

for tbl, cls in models.items():
    file_path = os.path.join(models_dir, f"{cls}.php")
    content = template.format(class_name=cls, table_name=tbl)
    with open(file_path, "w", encoding="utf-8") as f:
        f.write(content)
    print(f"Created Model: {cls}.php (table: {tbl})")

print("Finished generating skeleton models!")
