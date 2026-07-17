<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminProjectController extends Controller
{
    public function index()
    {
        return response()->json(
            Project::query()->with('media')->orderByDesc('featured')->orderBy('sort_order')->get()
        );
    }

    public function store(Request $request)
    {
        $payload = $this->validated($request);
        $payload['status'] = $this->resolveStatus($payload);
        $project = Project::query()->create($payload);
        $this->syncMedia($project, $request->input('media', []));
        $project->categories()->sync($request->input('categories', []));
        $project->capabilities()->sync($request->input('capabilities', []));

        return response()->json($project->load(['media', 'categories', 'capabilities']), 201);
    }

    public function update(Request $request, Project $project)
    {
        $payload = $this->validated($request, $project->id);
        $payload['status'] = $this->resolveStatus($payload, $project);
        $project->update($payload);
        $this->syncMedia($project, $request->input('media', []));
        $project->categories()->sync($request->input('categories', []));
        $project->capabilities()->sync($request->input('capabilities', []));

        return response()->json($project->load(['media', 'categories', 'capabilities']));
    }

    public function destroy(Project $project)
    {
        $project->delete();

        return response()->noContent();
    }

    private function validated(Request $request, ?int $projectId = null): array
    {
        $data = $request->validate([
            'slug' => ['nullable', 'string', 'max:255', 'unique:projects,slug'.($projectId ? ','.$projectId : '')],
            'status' => ['required', 'in:draft,published'],
            'featured' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'cover_image_url' => ['nullable', 'url'],
            'demo_url' => ['nullable', 'url'],
            'repository_url' => ['nullable', 'url'],
            'title' => ['required', 'array'],
            'title.es' => ['required', 'string'],
            'title.en' => ['required', 'string'],
            'summary' => ['required', 'array'],
            'summary.es' => ['required', 'string'],
            'summary.en' => ['required', 'string'],
            'description' => ['required', 'array'],
            'description.es' => ['required', 'string'],
            'description.en' => ['required', 'string'],
            'problem' => ['nullable', 'array'],
            'approach' => ['nullable', 'array'],
            'contribution' => ['nullable', 'array'],
            'what_it_demonstrates' => ['nullable', 'array'],
            'details' => ['nullable', 'array'],
            'stack' => ['nullable', 'array'],
            'stack.*' => ['string'],
            'published_at' => ['nullable', 'date'],
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['title']['en'] ?? $data['title']['es']);
        $data['sort_order'] = $data['sort_order'] ?? 0;
        $data['featured'] = $data['featured'] ?? false;

        return $data;
    }

    private function syncMedia(Project $project, array $items): void
    {
        $project->media()->delete();

        foreach ($items as $index => $item) {
            if (empty($item['url'])) {
                continue;
            }

            $project->media()->create([
                'kind' => $item['kind'] ?? 'image',
                'url' => $item['url'],
                'caption' => $item['caption'] ?? ['es' => '', 'en' => ''],
                'sort_order' => $item['sort_order'] ?? $index,
            ]);
        }
    }

    private function resolveStatus(array $data, ?Project $existing = null): string
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
