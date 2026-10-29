<?php

namespace App\Http\Controllers;

use App\Services\GooglePlacesService;
use App\Services\VietnamAddressService;
use Illuminate\Http\Request;
use RuntimeException;

class AddressLookupController extends Controller
{
    public function streets(
        Request $request,
        VietnamAddressService $addresses,
        GooglePlacesService $googlePlaces,
    ) {
        $data = $request->validate([
            'q' => ['required', 'string', 'min:2', 'max:100'],
            'tinh_thanh_code' => ['required', 'string', 'max:10'],
            'phuong_xa_code' => ['required', 'string', 'max:10'],
            'session_token' => ['required', 'uuid'],
        ]);

        $resolved = $addresses->resolve($data['tinh_thanh_code'], $data['phuong_xa_code']);
        if (!$resolved) {
            return response()->json(['message' => 'Phường/Xã không thuộc Tỉnh/Thành phố đã chọn.'], 422);
        }

        try {
            return response()->json(['data' => $googlePlaces->suggestStreets(
                trim($data['q']),
                $resolved['ward']['name'],
                $resolved['province']['name'],
                $data['session_token'],
            )]);
        } catch (RuntimeException $e) {
            return response()->json(['message' => $e->getMessage()], 503);
        }
    }
}
