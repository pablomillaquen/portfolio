<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\PostDetailResource;
use App\Http\Resources\PostResource;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PostController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $locale = $request->string('locale', 'en')->toString();
        $season = $request->string('season');

        $query = Post::query()
            ->where('status', 'published')
            ->orderByDesc('featured')
            ->orderByDesc('published_at')
            ->with('season');

        if ($season->isNotEmpty()) {
            $query->whereHas('season', function ($q) use ($season) {
                $q->where('slug', $season);
            });
        }

        $posts = $query->paginate($request->integer('per_page', 15));

        return PostResource::collection($posts);
    }

    public function show(string $slug): PostDetailResource|JsonResponse
    {
        $post = Post::query()
            ->where('status', 'published')
            ->where('slug', $slug)
            ->with(['season', 'project'])
            ->first();

        if (! $post) {
            return response()->json(['message' => 'Post not found'], 404);
        }

        return new PostDetailResource($post);
    }
}
