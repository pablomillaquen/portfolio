<?php

namespace App\Http\Resources;

use App\Support\TranslatableContent;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $locale = $request->string('locale', 'en')->toString();

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => TranslatableContent::text($this->resource->title, $locale),
            'summary' => TranslatableContent::text($this->resource->summary, $locale),
            'cover_image_url' => $this->cover_image_url,
            'featured' => (bool) $this->featured,
            'categories' => CategoryResource::collection($this->whenLoaded('categories')),
            'published_at' => $this->published_at?->toISOString(),
        ];
    }
}
