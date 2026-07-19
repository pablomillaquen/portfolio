<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Season;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminSeasonController extends Controller
{
    public function index(): JsonResponse
    {
        $seasons = Season::with('categories')->orderBy('sort_order')->get();

        return response()->json(['data' => $seasons]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'slug' => 'required|string|unique:seasons,slug',
            'status' => 'required|string|in:draft,active,completed,upcoming',
            'name' => 'required|array',
            'name.en' => 'required|string',
            'name.es' => 'required|string',
            'description' => 'nullable|array',
            'sort_order' => 'nullable|integer',
        ]);

        $season = Season::create($validated);
        $season->categories()->sync($request->input('categories', []));

        return response()->json(['data' => $season->load('categories')], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $season = Season::findOrFail($id);

        $validated = $request->validate([
            'slug' => 'required|string|unique:seasons,slug,' . $season->id,
            'status' => 'required|string|in:draft,active,completed,upcoming',
            'name' => 'required|array',
            'name.en' => 'required|string',
            'name.es' => 'required|string',
            'description' => 'nullable|array',
            'sort_order' => 'nullable|integer',
        ]);

        $season->update($validated);
        $season->categories()->sync($request->input('categories', []));

        return response()->json(['data' => $season->load('categories')]);
    }

    public function destroy(int $id): JsonResponse
    {
        $season = Season::findOrFail($id);

        if ($season->posts()->exists()) {
            return response()->json(['message' => 'Cannot delete season with posts'], 409);
        }

        $season->delete();

        return response()->json(['message' => 'Season deleted successfully']);
    }
}
