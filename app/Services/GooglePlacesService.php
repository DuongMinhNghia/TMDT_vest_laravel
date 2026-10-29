<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;

class GooglePlacesService
{
    public function suggestStreets(
        string $input,
        string $wardName,
        string $provinceName,
        string $sessionToken
    ): array {
        $apiKey = trim((string) config('services.google_maps.places_api_key'));
        if ($apiKey === '') {
            throw new RuntimeException('Dịch vụ gợi ý tên đường chưa được cấu hình.');
        }

        $response = Http::timeout(8)
            ->withHeaders([
                'X-Goog-Api-Key' => $apiKey,
                'X-Goog-FieldMask' => 'suggestions.placePrediction.placeId,suggestions.placePrediction.text,suggestions.placePrediction.structuredFormat',
            ])
            ->post('https://places.googleapis.com/v1/places:autocomplete', [
                'input' => implode(', ', [$input, $wardName, $provinceName, 'Việt Nam']),
                'includedRegionCodes' => ['vn'],
                'languageCode' => 'vi',
                'regionCode' => 'VN',
                'sessionToken' => $sessionToken,
            ]);

        if (!$response->successful()) {
            throw new RuntimeException('Không thể tải gợi ý tên đường. Vui lòng nhập thủ công hoặc thử lại sau.');
        }

        return collect($response->json('suggestions', []))
            ->map(fn (array $suggestion) => $suggestion['placePrediction'] ?? null)
            ->filter()
            ->map(fn (array $prediction) => [
                'place_id' => $prediction['placeId'] ?? null,
                'text' => $prediction['text']['text'] ?? '',
                'main_text' => $prediction['structuredFormat']['mainText']['text']
                    ?? $prediction['text']['text'] ?? '',
                'secondary_text' => $prediction['structuredFormat']['secondaryText']['text'] ?? '',
            ])
            ->filter(fn (array $prediction) => $prediction['place_id'] && $prediction['main_text'])
            ->filter(function (array $prediction) use ($wardName, $provinceName) {
                $fullText = Str::lower(Str::ascii($prediction['text']));
                $ward = $this->administrativeKeyword($wardName);
                $province = $this->administrativeKeyword($provinceName);

                return str_contains($fullText, $ward) && str_contains($fullText, $province);
            })
            ->take(8)
            ->values()
            ->all();
    }

    private function administrativeKeyword(string $name): string
    {
        $normalized = Str::lower(Str::ascii($name));

        return preg_replace('/^(phuong|xa|dac khu|tinh|thanh pho)\s+/u', '', $normalized);
    }
}
