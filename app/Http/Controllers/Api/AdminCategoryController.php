<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminCategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = Category::orderBy('dimension')->orderBy('slug')->get();

        return response()->json(['data' => $categories]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'slug' => 'required|string|unique:categories,slug',
            'dimension' => 'required|string|in:domain,capability,technology,methodology',
            'name' => 'required|array',
            'name.en' => 'required|string',
            'name.es' => 'required|string',
            'description' => 'nullable|array',
        ]);

        $category = Category::create($validated);

        return response()->json(['data' => $category], 201);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'slug' => 'required|string|unique:categories,slug,' . $category->id,
            'dimension' => 'required|string|in:domain,capability,technology,methodology',
            'name' => 'required|array',
            'name.en' => 'required|string',
            'name.es' => 'required|string',
            'description' => 'nullable|array',
        ]);

        $category->update($validated);

        return response()->json(['data' => $category]);
    }

    public function destroy(int $id): JsonResponse
    {
        $category = Category::findOrFail($id);

        if ($category->projects()->exists()) {
            return response()->json(['message' => 'Cannot delete category with projects'], 409);
        }

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }
}
