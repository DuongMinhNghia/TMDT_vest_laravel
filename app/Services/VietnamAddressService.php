<?php

namespace App\Services;

use RuntimeException;

class VietnamAddressService
{
    private ?array $data = null;

    public function provinces(): array
    {
        return array_map(
            fn (array $province) => ['code' => $province['code'], 'name' => $province['name']],
            $this->data()['provinces']
        );
    }

    public function wards(string $provinceCode): array
    {
        $province = $this->province($provinceCode);

        return $province['wards'] ?? [];
    }

    public function resolve(string $provinceCode, string $wardCode): ?array
    {
        $province = $this->province($provinceCode);
        if (!$province) {
            return null;
        }

        foreach ($province['wards'] as $ward) {
            if ((string) $ward['code'] === $wardCode) {
                return ['province' => $province, 'ward' => $ward];
            }
        }

        return null;
    }

    public function provinceExists(string $provinceCode): bool
    {
        return $this->province($provinceCode) !== null;
    }

    private function province(string $provinceCode): ?array
    {
        foreach ($this->data()['provinces'] as $province) {
            if ((string) $province['code'] === $provinceCode) {
                return $province;
            }
        }

        return null;
    }

    private function data(): array
    {
        if ($this->data !== null) {
            return $this->data;
        }

        $path = resource_path('data/vietnam_admin_v2.json');
        if (!is_readable($path)) {
            throw new RuntimeException('Không thể tải danh sách địa chỉ. Vui lòng thử lại sau.');
        }

        try {
            $data = json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException) {
            throw new RuntimeException('Không thể tải danh sách địa chỉ. Vui lòng thử lại sau.');
        }

        if (!isset($data['provinces']) || !is_array($data['provinces'])) {
            throw new RuntimeException('Không thể tải danh sách địa chỉ. Vui lòng thử lại sau.');
        }

        return $this->data = $data;
    }
}
