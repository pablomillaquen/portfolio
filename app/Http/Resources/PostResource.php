<?php

namespace App\Http\Resources;

use App\Support\TranslatableContent;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $locale = $request->string('locale', 'en')->toString();

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'title' => TranslatableContent::text($this->resource->title, $locale),
            'excerpt' => TranslatableContent::text($this->resource->excerpt, $locale),
            'type' => $this->type,
            'cover_image_url' => $this->cover_image_url,
            'season' => new SeasonResource($this->whenLoaded('season')),
            'episode_number' => $this->episode_number,
            'published_at' => $this->published_at?->toISOString(),
        ];
    }
}
