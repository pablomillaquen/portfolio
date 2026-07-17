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
            Post::query()->with('season')->orderByDesc('featured')->orderByDesc('published_at')->get()
        );
    }

    public function store(Request $request)
    {
        $payload = $this->validated($request);
        $payload['status'] = $this->resolveStatus($payload);
        $post = Post::query()->create($payload);

        return response()->json($post, 201);
    }

    public function update(Request $request, Post $post)
    {
        $payload = $this->validated($request, $post->id);
        $payload['status'] = $this->resolveStatus($payload, $post);
        $post->update($payload);

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
            'season_id' => ['nullable', 'integer', 'exists:seasons,id'],
            'episode_number' => ['nullable', 'integer', 'min:1'],
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']['en'] ?? $data['title']['es']);
        $data['featured'] = $data['featured'] ?? false;
        $data['share_enabled'] = $data['share_enabled'] ?? true;
        $data['season_id'] = $data['season_id'] ?? null;
        $data['episode_number'] = $data['episode_number'] ?? null;

        return $data;
    }

    private function resolveStatus(array $data, ?Post $existing = null): string
    {
        $publishedAt = $data['published_at'] ?? null;

        if ($publishedAt === null) {
            return $existing?->status ?? ($data['status'] ?? 'draft');
        }

        if (is_string($publishedAt) && $publishedAt !== '') {
            $date = \Carbon\Carbon::parse($publishedAt);

            if ($date->isPast()) {
                return 'published';
            }

            if ($date->isFuture() && $existing && $existing->status === 'published') {
                return 'draft';
            }
        }

        return $data['status'] ?? 'draft';
    }
}
