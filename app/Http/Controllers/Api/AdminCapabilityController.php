<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Capability;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminCapabilityController extends Controller
{
    public function index(): JsonResponse
    {
        $capabilities = Capability::orderBy('sort_order')->get();

        return response()->json(['data' => $capabilities]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'slug' => 'required|string|unique:capabilities,slug',
            'name' => 'required|array',
            'name.en' => 'required|string',
            'name.es' => 'required|string',
            'description' => 'nullable|array',
            'sort_order' => 'nullable|integer',
        ]);

        $capability = Capability::create($validated);

        return response()->json(['data' => $capability], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $capability = Capability::findOrFail($id);

        $validated = $request->validate([
            'slug' => 'required|string|unique:capabilities,slug,' . $capability->id,
            'name' => 'required|array',
            'name.en' => 'required|string',
            'name.es' => 'required|string',
            'description' => 'nullable|array',
            'sort_order' => 'nullable|integer',
        ]);

        $capability->update($validated);

        return response()->json(['data' => $capability]);
    }

    public function destroy(int $id): JsonResponse
    {
        $capability = Capability::findOrFail($id);

        if ($capability->projects()->exists()) {
            return response()->json(['message' => 'Cannot delete capability with projects'], 409);
        }

        $capability->delete();

        return response()->json(['message' => 'Capability deleted successfully']);
    }
}
