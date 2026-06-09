<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AppSetting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AppSettingController extends Controller
{
    public function index(): JsonResponse
    {
        $settings = AppSetting::all();
        return response()->json($settings);
    }

    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'settings'        => 'required|array',
            'settings.*.key'  => 'required|string',
            'settings.*.value' => 'nullable|string',
        ]);

        foreach ($request->settings as $item) {
            AppSetting::set($item['key'], $item['value']);
        }

        return response()->json(['message' => 'Configuracion actualizada.']);
    }
}
