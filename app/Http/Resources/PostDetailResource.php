<?php

namespace App\Http\Resources;

use App\Support\TranslatableContent;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostDetailResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $locale = $request->string('locale', 'en')->toString();

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => TranslatableContent::text($this->resource->title, $locale),
            'excerpt' => TranslatableContent::text($this->resource->excerpt, $locale),
            'content' => TranslatableContent::text($this->resource->content, $locale),
            'type' => $this->type,
            'cover_image_url' => $this->cover_image_url,
            'external_url' => $this->external_url,
            'season' => new SeasonResource($this->whenLoaded('season')),
            'episode_number' => $this->episode_number,
            'related_project' => new ProjectResource($this->whenLoaded('project')),
            'published_at' => $this->published_at?->toISOString(),
        ];
    }
}
