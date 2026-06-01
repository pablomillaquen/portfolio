<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminPostController extends Controller
{
    public function index()
    {
        return response()->json(
            Post::query()->orderByDesc('featured')->orderByDesc('published_at')->get()
        );
    }

    public function store(Request $request)
    {
        $post = Post::query()->create($this->validated($request));

        return response()->json($post, 201);
    }

    public function update(Request $request, Post $post)
    {
        $post->update($this->validated($request, $post->id));

        return response()->json($post);
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return response()->noContent();
    }

    private function validated(Request $request, ?int $postId = null): array
    {
        $data = $request->validate([
            'type' => ['required', 'in:internal,external'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:posts,slug'.($postId ? ','.$postId : '')],
            'status' => ['required', 'in:draft,published'],
            'featured' => ['boolean'],
            'cover_image_url' => ['nullable', 'url'],
            'external_url' => ['nullable', 'url'],
            'share_enabled' => ['boolean'],
            'title' => ['required', 'array'],
            'title.es' => ['required', 'string'],
            'title.en' => ['required', 'string'],
            'excerpt' => ['required', 'array'],
            'excerpt.es' => ['required', 'string'],
            'excerpt.en' => ['required', 'string'],
            'content' => ['nullable', 'array'],
            'content.es' => ['nullable', 'string'],
            'content.en' => ['nullable', 'string'],
            'published_at' => ['nullable', 'date'],
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']['en'] ?? $data['title']['es']);
        $data['featured'] = $data['featured'] ?? false;
        $data['share_enabled'] = $data['share_enabled'] ?? true;

        return $data;
    }
}
