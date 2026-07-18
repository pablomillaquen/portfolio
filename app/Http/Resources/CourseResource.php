<?php

namespace App\Http\Resources;

use App\Support\TranslatableContent;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $locale = $request->string('locale', 'en')->toString();

        return [
            'id' => $this->id,
            'slug' => $this->slug,
            'name' => TranslatableContent::text($this->resource->name, $locale),
            'issuer' => $this->issuer,
            'credential_id' => $this->credential_id,
            'url' => $this->url,
            'issued_at' => $this->issued_at?->toISOString(),
        ];
    }
}
