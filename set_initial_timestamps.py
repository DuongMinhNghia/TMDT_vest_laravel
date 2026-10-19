import os
import datetime
import subprocess

root_dir = r"c:\Laragon\www\demo"
target_dt = datetime.datetime(2026, 10, 9, 8, 30, 0)
ts = target_dt.timestamp()

# 1. Danh sách các file/thư mục mặc định khi tạo mới một dự án Laravel:
default_laravel_rel_paths = [
    # Thư mục gốc
    "artisan",
    "composer.json",
    "composer.lock",
    "package.json",
    "phpunit.xml",
    "vite.config.js",
    ".env",
    ".env.example",
    ".gitignore",
    "README.md",
    
    # Bootstrap
    "bootstrap/app.php",
    "bootstrap/providers.php",
    
    # Config
    "config/app.php",
    "config/auth.php",
    "config/cache.php",
    "config/database.php",
    "config/filesystems.php",
    "config/logging.php",
    "config/mail.php",
    "config/queue.php",
    "config/services.php",
    "config/session.php",
    
    # Public
    "public/index.php",
    "public/.htaccess",
    "public/robots.txt",
    
    # Routes
    "routes/console.php",
    
    # App core
    "app/Http/Controllers/Controller.php",
    "app/Providers/AppServiceProvider.php",
    
    # Database
    "database/seeders/DatabaseSeeder.php",
    "database/factories/UserFactory.php",
    "database/migrations/0001_01_01_000000_create_tai_khoans_table.php",
    "database/migrations/0001_01_01_000001_create_cache_table.php",
    "database/migrations/0001_01_01_000002_create_jobs_table.php",
    
    # Resources
    "resources/css/app.css",
    "resources/js/app.js",
    "resources/js/bootstrap.js",
]

# Set os.utime (Last-Modified & Last-Access)
modified_files = []
for rel in default_laravel_rel_paths:
    full_path = os.path.join(root_dir, rel.replace("/", os.sep))
    if os.path.exists(full_path):
        os.utime(full_path, (ts, ts))
        modified_files.append(full_path)

# Also set CreationTime and LastWriteTime via PowerShell script file
ps_script_path = os.path.join(root_dir, "temp_set_time.ps1")
with open(ps_script_path, "w", encoding="utf-8") as f:
    f.write("""$target = [DateTime]"2026-10-09 08:30:00"\n""")
    for p in modified_files:
        escaped_p = p.replace("'", "''")
        f.write(f"""Get-Item -LiteralPath '{escaped_p}' | ForEach-Object {{ $_.CreationTime = $target; $_.LastWriteTime = $target }}\n""")

subprocess.run(["powershell", "-ExecutionPolicy", "Bypass", "-File", ps_script_path], check=True)
os.remove(ps_script_path)

print(f"Successfully updated timestamps for {len(modified_files)} core Laravel files to: 2026-10-09 08:30:00")
