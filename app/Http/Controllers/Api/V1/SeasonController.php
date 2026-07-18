<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\SeasonResource;
use App\Models\Season;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SeasonController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Season::query()->orderBy('sort_order');

        if ($request->has('status')) {
            $query->where('status', $request->string('status'));
        }

        return SeasonResource::collection($query->get());
    }
}
