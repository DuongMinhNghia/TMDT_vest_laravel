import os
import shutil

migrations_dir = r"c:\Laragon\www\demo\database\migrations"

# 1. Clean up old migration files (except cache and jobs)
files_to_remove = [
    "0001_01_01_000000_create_users_table.php",
    "2026_06_04_010940_create_danh_mucs_table.php",
    "2026_06_04_010955_create_san_phams_table.php",
    "2026_06_04_011002_create_hoa_dons_table.php",
    "2026_06_04_011008_create_chi_tiet_hoa_dons_table.php",
    "2026_06_04_015513_create_sizes_table.php"
]

for f in files_to_remove:
    p = os.path.join(migrations_dir, f)
    if os.path.exists(p):
        os.remove(p)
        print(f"Removed: {f}")

# 2. Migration definitions
migrations = {
    # 01. tai_khoans, password_reset_tokens, sessions
    "0001_01_01_000000_create_tai_khoans_table.php": """<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tai_khoans', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('vai_tro', ['khach_hang', 'nhan_vien', 'admin'])->default('khach_hang');
            $table->boolean('trang_thai')->default(true);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tai_khoans');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
""",

    # 04. hang_thanh_viens
    "2026_06_04_000001_create_hang_thanh_viens_table.php": """<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hang_thanh_viens', function (Blueprint $table) {
            $table->id();
            $table->string('ten_hang', 50)->unique();
            $table->integer('diem_toi_thieu')->default(0);
            $table->integer('phan_tram_chiet_khau')->default(0);
            $table->text('mo_ta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hang_thanh_viens');
    }
};
""",

    # 05. khach_hangs
    "2026_06_04_000002_create_khach_hangs_table.php": """<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('khach_hangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tai_khoan_id')->unique()->constrained('tai_khoans')->onDelete('cascade');
            $table->foreignId('hang_thanh_vien_id')->nullable()->constrained('hang_thanh_viens')->nullOnDelete();
            $table->string('ma_kh', 20)->unique()->nullable();
            $table->string('ho_ten', 100);
            $table->string('so_dien_thoai', 15)->nullable();
            $table->string('dia_chi', 255)->nullable();
            $table->date('ngay_sinh')->nullable();
            $table->enum('gioi_tinh', ['Nam', 'Nu', 'Khac'])->default('Nam');
            $table->integer('diem_tich_luy')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('khach_hangs');
    }
};
""",

    # 06. nhan_viens
    "2026_06_04_000003_create_nhan_viens_table.php": """<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nhan_viens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tai_khoan_id')->unique()->constrained('tai_khoans')->onDelete('cascade');
            $table->string('ma_nv', 20)->unique();
            $table->string('ho_ten', 100);
            $table->string('so_dien_thoai', 15)->nullable();
            $table->string('chuc_vu', 50)->default('Nhân viên tư vấn');
            $table->decimal('luong_co_ban', 18, 0)->nullable();
            $table->date('ngay_vao_lam')->nullable();
            $table->string('trang_thai', 30)->default('Đang làm việc');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nhan_viens');
    }
};
""",

    # 07. so_do_khach_hangs
    "2026_06_04_000004_create_so_do_khach_hangs_table.php": """<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('so_do_khach_hangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('khach_hang_id')->constrained('khach_hangs')->onDelete('cascade');
            $table->string('ten_ho_so', 50)->default('Số đo mặc định');
            $table->decimal('vong_co', 5, 1)->nullable();
            $table->decimal('rong_vai', 5, 1)->nullable();
            $table->decimal('vong_nguc', 5, 1)->nullable();
            $table->decimal('vong_eo', 5, 1)->nullable();
            $table->decimal('vong_mong', 5, 1)->nullable();
            $table->decimal('dai_tay_ao', 5, 1)->nullable();
            $table->decimal('dai_ao_vest', 5, 1)->nullable();
            $table->decimal('dai_quan', 5, 1)->nullable();
            $table->decimal('vong_dui', 5, 1)->nullable();
            $table->text('ghi_chu')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('so_do_khach_hangs');
    }
};
""",

    # 08. danh_mucs
    "2026_06_04_000005_create_danh_mucs_table.php": """<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('danh_mucs', function (Blueprint $table) {
            $table->id();
            $table->string('ten_danh_muc', 50);
            $table->text('mo_ta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('danh_mucs');
    }
};
""",

    # 09. bo_suu_taps
    "2026_06_04_000006_create_bo_suu_taps_table.php": """<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bo_suu_taps', function (Blueprint $table) {
            $table->id();
            $table->string('ten_bo_suu_tap', 100);
            $table->string('hinh_banner', 255)->nullable();
            $table->text('mo_ta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bo_suu_taps');
    }
};
""",

    # 10. san_phams
    "2026_06_04_000007_create_san_phams_table.php": """<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('san_phams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('danh_muc_id')->constrained('danh_mucs')->onDelete('cascade');
            $table->string('ma_sp', 50)->unique()->nullable();
            $table->string('ten_sp', 100);
            $table->string('chat_lieu', 50)->nullable();
            $table->string('xuat_xu', 50)->nullable();
            $table->string('form_dang', 50)->nullable();
            $table->decimal('gia_ban', 18, 0)->nullable();
            $table->string('hinh_anh', 255)->nullable();
            $table->text('mo_ta')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('san_phams');
    }
};
""",

    # 11. sizes
    "2026_06_04_000008_create_sizes_table.php": """<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sizes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('san_pham_id')->constrained('san_phams')->onDelete('cascade');
            $table->string('ten_size', 10);
            $table->integer('so_luong_ton')->default(0);
            $table->string('chieu_cao_goi_y', 40)->nullable();
            $table->string('can_nang_goi_y', 40)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sizes');
    }
};
""",

    # 12. bo_suu_tap_san_phams
    "2026_06_04_000009_create_bo_suu_tap_san_phams_table.php": """<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bo_suu_tap_san_phams', function (Blueprint $table) {
            $table->foreignId('bo_suu_tap_id')->constrained('bo_suu_taps')->onDelete('cascade');
            $table->foreignId('san_pham_id')->constrained('san_phams')->onDelete('cascade');
            $table->primary(['bo_suu_tap_id', 'san_pham_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bo_suu_tap_san_phams');
    }
};
""",

    # 13. giam_gias
    "2026_06_04_000010_create_giam_gias_table.php": """<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('giam_gias', function (Blueprint $table) {
            $table->id();
            $table->string('tieu_de', 150);
            $table->integer('phan_tram_giam');
            $table->boolean('ap_dung_tat_ca')->default(false);
            $table->dateTime('thoi_gian_bat_dau');
            $table->dateTime('thoi_gian_ket_thuc');
            $table->boolean('trang_thai')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('giam_gias');
    }
};
""",

    # 14. giam_gia_san_phams
    "2026_06_04_000011_create_giam_gia_san_phams_table.php": """<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('giam_gia_san_phams', function (Blueprint $table) {
            $table->foreignId('giam_gia_id')->constrained('giam_gias')->onDelete('cascade');
            $table->foreignId('san_pham_id')->constrained('san_phams')->onDelete('cascade');
            $table->primary(['giam_gia_id', 'san_pham_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('giam_gia_san_phams');
    }
};
""",

    # 15. gio_hangs
    "2026_06_04_000012_create_gio_hangs_table.php": """<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gio_hangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('khach_hang_id')->unique()->constrained('khach_hangs')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gio_hangs');
    }
};
""",

    # 16. chi_tiet_gio_hangs
    "2026_06_04_000013_create_chi_tiet_gio_hangs_table.php": """<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chi_tiet_gio_hangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gio_hang_id')->constrained('gio_hangs')->onDelete('cascade');
            $table->foreignId('san_pham_id')->constrained('san_phams')->onDelete('cascade');
            $table->foreignId('size_id')->constrained('sizes')->onDelete('cascade');
            $table->integer('so_luong')->default(1);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chi_tiet_gio_hangs');
    }
};
""",

    # 17. hoa_dons
    "2026_06_04_000014_create_hoa_dons_table.php": """<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hoa_dons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('khach_hang_id')->constrained('khach_hangs')->onDelete('cascade');
            $table->foreignId('nhan_vien_duyet_id')->nullable()->constrained('nhan_viens')->nullOnDelete();
            $table->string('ma_don_hang', 30)->unique()->nullable();
            $table->dateTime('ngay_dat')->useCurrent();
            $table->string('trang_thai', 50)->default('Chờ xử lý');
            $table->decimal('tong_tien', 18, 0)->default(0);
            $table->string('phuong_thuc_thanh_toan', 50)->default('COD');
            $table->text('dia_chi_giao_hang')->nullable();
            $table->text('ghi_chu')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hoa_dons');
    }
};
""",

    # 18. chi_tiet_hoa_dons
    "2026_06_04_000015_create_chi_tiet_hoa_dons_table.php": """<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chi_tiet_hoa_dons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hoa_don_id')->constrained('hoa_dons')->onDelete('cascade');
            $table->foreignId('san_pham_id')->constrained('san_phams')->onDelete('cascade');
            $table->foreignId('size_id')->constrained('sizes')->onDelete('cascade');
            $table->decimal('don_gia', 18, 0)->default(0);
            $table->integer('so_luong')->default(1);
            $table->decimal('thanh_tien', 18, 0)->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chi_tiet_hoa_dons');
    }
};
""",

    # 19. danh_gias
    "2026_06_04_000016_create_danh_gias_table.php": """<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('danh_gias', function (Blueprint $table) {
            $table->id();
            $table->foreignId('san_pham_id')->constrained('san_phams')->onDelete('cascade');
            $table->foreignId('khach_hang_id')->constrained('khach_hangs')->onDelete('cascade');
            $table->integer('so_sao')->default(5);
            $table->text('noi_dung')->nullable();
            $table->string('hinh_anh', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('danh_gias');
    }
};
""",

    # 20. lich_hens
    "2026_06_04_000017_create_lich_hens_table.php": """<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lich_hens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('khach_hang_id')->constrained('khach_hangs')->onDelete('cascade');
            $table->foreignId('nhan_vien_tiep_don_id')->nullable()->constrained('nhan_viens')->nullOnDelete();
            $table->foreignId('san_pham_quan_tam_id')->nullable()->constrained('san_phams')->nullOnDelete();
            $table->string('ma_lich_hen', 30)->unique()->nullable();
            $table->dateTime('thoi_gian_hen');
            $table->string('loai_dich_vu', 50)->default('Thử vest có sẵn');
            $table->integer('so_nguoi_di_cung')->default(1);
            $table->string('trang_thai', 50)->default('Chờ xác nhận');
            $table->text('ghi_chu')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lich_hens');
    }
};
"""
}

for fname, content in migrations.items():
    fpath = os.path.join(migrations_dir, fname)
    with open(fpath, "w", encoding="utf-8") as f:
        f.write(content.strip() + "\n")
    print(f"Created: {fname}")

print("All migrations generated successfully!")
