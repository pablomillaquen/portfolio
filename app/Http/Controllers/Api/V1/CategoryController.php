<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Category::query()->orderBy('slug');

        if ($request->has('dimension')) {
            $query->where('dimension', $request->string('dimension'));
        }

        return CategoryResource::collection($query->get());
    }
}
