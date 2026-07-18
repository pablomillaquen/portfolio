<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProjectDetailResource;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProjectController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $locale = $request->string('locale', 'en')->toString();
        $category = $request->string('category');

        $query = Project::query()
            ->where('status', 'published')
            ->orderByDesc('featured')
            ->orderBy('sort_order')
            ->with(['categories', 'media']);

        if ($category->isNotEmpty()) {
            $categorySlugs = explode(',', $category);
            $query->whereHas('categories', function ($q) use ($categorySlugs) {
                $q->whereIn('slug', $categorySlugs);
            });
        }

        $projects = $query->paginate($request->integer('per_page', 15));

        return ProjectResource::collection($projects);
    }

    public function show(string $slug): ProjectDetailResource|JsonResponse
    {
        $project = Project::query()
            ->where('status', 'published')
            ->where('slug', $slug)
            ->with(['categories', 'capabilities', 'media', 'posts'])
            ->first();

        if (! $project) {
            return response()->json(['message' => 'Project not found'], 404);
        }

        return new ProjectDetailResource($project);
    }
}
