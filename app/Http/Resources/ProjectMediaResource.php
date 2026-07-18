<?php

namespace App\Http\Resources;

use App\Support\TranslatableContent;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectMediaResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $locale = $request->string('locale', 'en')->toString();

        return [
            'id' => $this->id,
            'kind' => $this->kind,
            'url' => $this->url,
            'caption' => TranslatableContent::text($this->resource->caption, $locale),
        ];
    }
}
