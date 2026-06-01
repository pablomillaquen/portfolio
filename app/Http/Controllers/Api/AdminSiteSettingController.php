<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class AdminSiteSettingController extends Controller
{
    public function index()
    {
        return response()->json(
            SiteSetting::query()->get()->mapWithKeys(fn (SiteSetting $setting) => [$setting->key => $setting->value])
        );
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'settings' => ['required', 'array'],
        ]);

        foreach ($validated['settings'] as $key => $value) {
            SiteSetting::query()->updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return response()->json(
            SiteSetting::query()->get()->mapWithKeys(fn (SiteSetting $setting) => [$setting->key => $setting->value])
        );
    }
}
