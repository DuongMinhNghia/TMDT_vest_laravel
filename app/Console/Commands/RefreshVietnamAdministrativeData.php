<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class RefreshVietnamAdministrativeData extends Command
{
    protected $signature = 'address:refresh-vietnam';

    protected $description = 'Download and validate the post-July-2025 Vietnam province/ward dataset';

    private const SOURCE = 'https://provinces.open-api.vn/api/v2/?depth=2';

    public function handle(): int
    {
        try {
            $source = Http::timeout(45)->retry(2, 500)->get(self::SOURCE)->throw()->json();
            $normalized = $this->normalize($source);
            $json = json_encode(
                $normalized,
                JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_THROW_ON_ERROR
            ) . PHP_EOL;

            $target = resource_path('data/vietnam_admin_v2.json');
            $temporary = $target . '.tmp';
            if (file_put_contents($temporary, $json, LOCK_EX) === false || !rename($temporary, $target)) {
                throw new RuntimeException('Không thể ghi tệp dữ liệu địa chỉ cục bộ.');
            }

            $wardCount = array_sum(array_map(fn ($province) => count($province['wards']), $normalized['provinces']));
            $this->info('Đã cập nhật ' . count($normalized['provinces']) . " tỉnh/thành và {$wardCount} phường/xã.");

            return self::SUCCESS;
        } catch (\Throwable $e) {
            @unlink(resource_path('data/vietnam_admin_v2.json.tmp'));
            $this->error('Không thể cập nhật dữ liệu: ' . $e->getMessage());

            return self::FAILURE;
        }
    }

    private function normalize(mixed $source): array
    {
        if (!is_array($source) || count($source) < 1) {
            throw new RuntimeException('API không trả về danh sách tỉnh/thành hợp lệ.');
        }

        $provinceCodes = [];
        $wardCodes = [];
        $provinces = [];

        foreach ($source as $province) {
            $code = (string) ($province['code'] ?? '');
            $name = trim((string) ($province['name'] ?? ''));
            if ($code === '' || $name === '' || isset($provinceCodes[$code]) || !is_array($province['wards'] ?? null)) {
                throw new RuntimeException('Dữ liệu tỉnh/thành bị thiếu, trùng hoặc sai cấu trúc.');
            }
            $provinceCodes[$code] = true;

            $wards = [];
            foreach ($province['wards'] as $ward) {
                $wardCode = (string) ($ward['code'] ?? '');
                $wardName = trim((string) ($ward['name'] ?? ''));
                if ($wardCode === '' || $wardName === '' || isset($wardCodes[$wardCode])) {
                    throw new RuntimeException('Dữ liệu phường/xã bị thiếu hoặc trùng mã.');
                }
                $wardCodes[$wardCode] = true;
                $wards[] = [
                    'code' => $wardCode,
                    'name' => $wardName,
                    'division_type' => (string) ($ward['division_type'] ?? ''),
                ];
            }

            $provinces[] = [
                'code' => $code,
                'name' => $name,
                'division_type' => (string) ($province['division_type'] ?? ''),
                'wards' => $wards,
            ];
        }

        if (count($provinces) < 30 || count($wardCodes) < 3000) {
            throw new RuntimeException('Dataset không đạt số lượng kỳ vọng; tệp hiện tại được giữ nguyên.');
        }

        return [
            'source' => self::SOURCE,
            'retrieved_at' => now()->toDateString(),
            'version' => 2,
            'provinces' => $provinces,
        ];
    }
}
