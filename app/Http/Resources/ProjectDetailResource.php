<?php

namespace App\Http\Resources;

use App\Support\TranslatableContent;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $locale = $request->string('locale', 'en')->toString();

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => TranslatableContent::text($this->resource->title, $locale),
            'summary' => TranslatableContent::text($this->resource->summary, $locale),
            'description' => TranslatableContent::text($this->resource->description, $locale),
            'problem' => TranslatableContent::text($this->resource->problem, $locale),
            'approach' => TranslatableContent::text($this->resource->approach, $locale),
            'contribution' => TranslatableContent::text($this->resource->contribution, $locale),
            'what_it_demonstrates' => TranslatableContent::text($this->resource->what_it_demonstrates, $locale),
            'stack' => $this->stack,
            'demo_url' => $this->demo_url,
            'repository_url' => $this->repository_url,
            'cover_image_url' => $this->cover_image_url,
            'featured' => (bool) $this->featured,
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'capabilities' => CapabilityResource::collection($this->whenLoaded('capabilities')),
            'media' => ProjectMediaResource::collection($this->whenLoaded('media')),
            'related_posts' => PostResource::collection($this->whenLoaded('posts')),
            'published_at' => $this->published_at?->toISOString(),
        ];
    }
}
