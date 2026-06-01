<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SocialLink;
use Illuminate\Http\Request;

class AdminSocialLinkController extends Controller
{
    public function index()
    {
        return response()->json(SocialLink::query()->orderBy('sort_order')->get());
    }

    public function save(Request $request)
    {
        $validated = $request->validate([
            'links' => ['required', 'array'],
            'links.*.platform' => ['required', 'string'],
            'links.*.label' => ['required', 'array'],
            'links.*.label.es' => ['required', 'string'],
            'links.*.label.en' => ['required', 'string'],
            'links.*.url' => ['required', 'url'],
            'links.*.icon' => ['nullable', 'string'],
            'links.*.sort_order' => ['nullable', 'integer', 'min:0'],
            'links.*.active' => ['boolean'],
        ]);

        SocialLink::query()->delete();

        foreach ($validated['links'] as $index => $link) {
            SocialLink::query()->create([
                'platform' => $link['platform'],
                'label' => $link['label'],
                'url' => $link['url'],
                'icon' => $link['icon'] ?? $link['platform'],
                'sort_order' => $link['sort_order'] ?? $index,
                'active' => $link['active'] ?? true,
            ]);
        }

        return response()->json(SocialLink::query()->orderBy('sort_order')->get());
    }
}
