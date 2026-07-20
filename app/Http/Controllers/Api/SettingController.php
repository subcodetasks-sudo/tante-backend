<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;

class SettingController extends Controller
{
    public function index(): JsonResponse
    {
        $setting = Setting::query()->latest()->first();

        if (! $setting) {
            return response()->json([
                'message' => 'No settings found',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'data' => [
                'id' => $setting->id,
                'restaurant_name' => $setting->restaurant_name,
                'logo' => $setting->logo_url,
                'description' => $setting->description,
                'social' => [
                    'facebook' => $setting->facebook,
                    'instagram' => $setting->instagram,
                    'twitter' => $setting->twitter,
                    'youtube' => $setting->youtube,
                    'tiktok' => $setting->tiktok,
                    'whatsapp' => $setting->whatsapp,
                ],
            ],
        ]);
    }
}
