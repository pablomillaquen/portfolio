<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Capability;
use App\Models\Category;
use App\Models\Course;
use App\Models\Post;
use App\Models\Project;
use App\Models\Season;
use App\Models\SiteSetting;
use App\Models\SocialLink;
use App\Support\TranslatableContent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PublicContentController extends Controller
{
    public function home(Request $request): JsonResponse
    {
        $locale = $request->string('locale', 'en')->toString();

        return response()->json([
            'settings' => $this->settings($locale),
            'projects' => Project::query()
                ->where('status', 'published')
                ->orderByDesc('featured')
                ->orderBy('sort_order')
                ->with('media')
                ->limit(4)
                ->get()
                ->map(fn (Project $project) => $this->projectPayload($project, $locale, false)),
            'posts' => Post::query()
                ->where('status', 'published')
                ->orderByDesc('featured')
                ->orderByDesc('published_at')
                ->limit(4)
                ->get()
                ->map(fn (Post $post) => $this->postPayload($post, $locale, false)),
            'socialLinks' => SocialLink::query()
                ->where('active', true)
                ->orderBy('sort_order')
                ->get()
                ->map(fn (SocialLink $link) => $this->socialPayload($link, $locale)),
        ]);
    }

    public function projects(Request $request): JsonResponse
    {
        $locale = $request->string('locale', 'en')->toString();
        $category = $request->string('category');

        $query = Project::query()
            ->where('status', 'published')
            ->orderByDesc('featured')
            ->orderBy('sort_order')
            ->with('media');

        if ($category) {
            $categorySlugs = explode(',', $category);
            $query->whereHas('categories', function ($q) use ($categorySlugs) {
                $q->whereIn('slug', $categorySlugs);
            });
        }

        return response()->json(
            $query->get()
                ->map(fn (Project $project) => $this->projectPayload($project, $locale, false))
        );
    }

    public function project(Request $request, string $slug): JsonResponse
    {
        $locale = $request->string('locale', 'en')->toString();
        $project = Project::query()->where('status', 'published')->with('media')->where('slug', $slug)->firstOrFail();

        return response()->json($this->projectPayload($project, $locale, true));
    }

    public function posts(Request $request): JsonResponse
    {
        $locale = $request->string('locale', 'en')->toString();

        return response()->json(
            Post::query()
                ->where('status', 'published')
                ->orderByDesc('featured')
                ->orderByDesc('published_at')
                ->get()
                ->map(fn (Post $post) => $this->postPayload($post, $locale, false))
        );
    }

    public function post(Request $request, string $slug): JsonResponse
    {
        $locale = $request->string('locale', 'en')->toString();
        $post = Post::query()->where('status', 'published')->where('slug', $slug)->firstOrFail();

        return response()->json($this->postPayload($post, $locale, true));
    }

    public function courses(Request $request): JsonResponse
    {
        $locale = $request->string('locale', 'en')->toString();

        return response()->json(
            Course::query()
                ->where('status', 'published')
                ->orderByDesc('featured')
                ->orderByDesc('issued_at')
                ->get()
                ->map(fn (Course $course) => $this->coursePayload($course, $locale))
        );
    }

    public function course(Request $request, string $slug): JsonResponse
    {
        $locale = $request->string('locale', 'en')->toString();
        $course = Course::query()->where('status', 'published')->where('slug', $slug)->firstOrFail();

        return response()->json($this->coursePayload($course, $locale));
    }

    public function settingsOnly(Request $request): JsonResponse
    {
        $locale = $request->string('locale', 'en')->toString();

        return response()->json([
            'settings' => $this->settings($locale),
            'socialLinks' => SocialLink::query()
                ->where('active', true)
                ->orderBy('sort_order')
                ->get()
                ->map(fn (SocialLink $link) => $this->socialPayload($link, $locale)),
        ]);
    }

    public function capabilities(Request $request): JsonResponse
    {
        $locale = $request->string('locale', 'en')->toString();

        return response()->json([
            'data' => Capability::orderBy('sort_order')
                ->get()
                ->map(fn (Capability $capability) => [
                    'id' => $capability->id,
                    'slug' => $capability->slug,
                    'name' => TranslatableContent::text($capability->name, $locale),
                    'description' => TranslatableContent::text($capability->description, $locale),
                    'sortOrder' => $capability->sort_order,
                ]),
        ]);
    }

    public function categories(Request $request): JsonResponse
    {
        $locale = $request->string('locale', 'en')->toString();
        $dimension = $request->string('dimension');

        $query = Category::orderBy('dimension')->orderBy('slug');

        if ($dimension) {
            $query->where('dimension', $dimension);
        }

        return response()->json([
            'data' => $query->get()->map(fn (Category $category) => [
                'id' => $category->id,
                'slug' => $category->slug,
                'dimension' => $category->dimension,
                'name' => TranslatableContent::text($category->name, $locale),
                'description' => TranslatableContent::text($category->description, $locale),
            ]),
        ]);
    }

    public function seasons(Request $request): JsonResponse
    {
        $locale = $request->string('locale', 'en')->toString();
        $status = $request->string('status');

        $query = Season::orderBy('sort_order');

        if ($status) {
            $query->where('status', $status);
        }

        return response()->json([
            'data' => $query->get()->map(fn (Season $season) => [
                'id' => $season->id,
                'slug' => $season->slug,
                'status' => $season->status,
                'name' => TranslatableContent::text($season->name, $locale),
                'description' => TranslatableContent::text($season->description, $locale),
                'postsCount' => $season->posts()->count(),
                'sortOrder' => $season->sort_order,
            ]),
        ]);
    }

    private function settings(string $locale): array
    {
        return SiteSetting::query()
            ->get()
            ->mapWithKeys(fn (SiteSetting $setting) => [$setting->key => TranslatableContent::deep($setting->value, $locale)])
            ->all();
    }

    private function projectPayload(Project $project, string $locale, bool $full): array
    {
        $payload = [
            'id' => $project->id,
            'slug' => $project->slug,
            'status' => $project->status,
            'featured' => $project->featured,
            'sortOrder' => $project->sort_order,
            'coverImageUrl' => $project->cover_image_url,
            'demoUrl' => $project->demo_url,
            'repositoryUrl' => $project->repository_url,
            'title' => TranslatableContent::text($project->title, $locale),
            'summary' => TranslatableContent::text($project->summary, $locale),
            'description' => $full ? Str::markdown(TranslatableContent::text($project->description, $locale) ?? '') : TranslatableContent::text($project->description, $locale),
            'details' => TranslatableContent::deep($project->details ?? [], $locale),
            'stack' => $project->stack ?? [],
            'publishedAt' => optional($project->published_at)->toDateString(),
            'media' => $full ? $project->media->map(fn ($item) => [
                'id' => $item->id,
                'kind' => $item->kind,
                'url' => $item->url,
                'caption' => TranslatableContent::text($item->caption, $locale),
                'sortOrder' => $item->sort_order,
            ])->all() : $project->media->take(1)->map(fn ($item) => [
                'id' => $item->id,
                'kind' => $item->kind,
                'url' => $item->url,
                'caption' => TranslatableContent::text($item->caption, $locale),
                'sortOrder' => $item->sort_order,
            ])->all(),
        ];

        if ($full) {
            $payload['problem'] = Str::markdown(TranslatableContent::text($project->problem, $locale) ?? '');
            $payload['approach'] = Str::markdown(TranslatableContent::text($project->approach, $locale) ?? '');
            $payload['contribution'] = Str::markdown(TranslatableContent::text($project->contribution, $locale) ?? '');
            $payload['whatItDemonstrates'] = Str::markdown(TranslatableContent::text($project->what_it_demonstrates, $locale) ?? '');
            $payload['projectStatus'] = $project->project_status;

            $payload['categories'] = $project->categories->map(fn ($category) => [
                'slug' => $category->slug,
                'name' => TranslatableContent::text($category->name, $locale),
            ])->all();

            $payload['capabilities'] = $project->capabilities->map(fn ($capability) => [
                'slug' => $capability->slug,
                'name' => TranslatableContent::text($capability->name, $locale),
            ])->all();

            $payload['relatedPosts'] = $project->posts->map(fn ($post) => [
                'id' => $post->id,
                'slug' => $post->slug,
                'title' => TranslatableContent::text($post->title, $locale),
                'season' => $post->season ? [
                    'slug' => $post->season->slug,
                    'name' => TranslatableContent::text($post->season->name, $locale),
                ] : null,
                'episodeNumber' => $post->episode_number,
            ])->all();
        }

        return $payload;
    }

    private function postPayload(Post $post, string $locale, bool $full): array
    {
        $payload = [
            'id' => $post->id,
            'slug' => $post->slug,
            'type' => $post->type,
            'status' => $post->status,
            'featured' => $post->featured,
            'coverImageUrl' => $post->cover_image_url,
            'externalUrl' => $post->external_url,
            'shareEnabled' => $post->share_enabled,
            'title' => TranslatableContent::text($post->title, $locale),
            'excerpt' => TranslatableContent::text($post->excerpt, $locale),
            'content' => $full ? Str::markdown(TranslatableContent::text($post->content, $locale) ?? '') : null,
            'publishedAt' => optional($post->published_at)->toDateString(),
        ];

        if ($full) {
            if ($post->season) {
                $payload['season'] = [
                    'id' => $post->season->id,
                    'slug' => $post->season->slug,
                    'name' => TranslatableContent::text($post->season->name, $locale),
                ];
                $payload['episodeNumber'] = $post->episode_number;
            }

            if ($post->relatedProject) {
                $payload['relatedProject'] = [
                    'id' => $post->relatedProject->id,
                    'slug' => $post->relatedProject->slug,
                    'title' => TranslatableContent::text($post->relatedProject->title, $locale),
                ];
            }

            $previousPost = Post::where('season_id', $post->season_id)
                ->where('episode_number', '<', $post->episode_number)
                ->where('status', 'published')
                ->orderByDesc('episode_number')
                ->first();

            $nextPost = Post::where('season_id', $post->season_id)
                ->where('episode_number', '>', $post->episode_number)
                ->where('status', 'published')
                ->orderBy('episode_number')
                ->first();

            $payload['navigation'] = [
                'previous' => $previousPost ? [
                    'slug' => $previousPost->slug,
                    'title' => TranslatableContent::text($previousPost->title, $locale),
                ] : null,
                'next' => $nextPost ? [
                    'slug' => $nextPost->slug,
                    'title' => TranslatableContent::text($nextPost->title, $locale),
                ] : null,
            ];
        }

        return $payload;
    }

    private function coursePayload(Course $course, string $locale): array
    {
        return [
            'id' => $course->id,
            'slug' => $course->slug,
            'status' => $course->status,
            'featured' => $course->featured,
            'sortOrder' => $course->sort_order,
            'name' => TranslatableContent::text($course->name, $locale),
            'issuer' => $course->issuer,
            'issuedAt' => $course->issued_at?->toDateString(),
            'credentialId' => $course->credential_id,
            'url' => $course->url,
        ];
    }

    private function socialPayload(SocialLink $link, string $locale): array
    {
        return [
            'id' => $link->id,
            'platform' => $link->platform,
            'label' => TranslatableContent::text($link->label, $locale),
            'url' => $link->url,
            'icon' => $link->icon,
        ];
    }
}
