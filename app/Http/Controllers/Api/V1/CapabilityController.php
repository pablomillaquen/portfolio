<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CapabilityResource;
use App\Models\Capability;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CapabilityController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return CapabilityResource::collection(
            Capability::query()->orderBy('sort_order')->get()
        );
    }
}
