<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\CourseResource;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CourseController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $courses = Course::query()
            ->where('status', 'published')
            ->orderByDesc('featured')
            ->orderBy('sort_order')
            ->paginate($request->integer('per_page', 15));

        return CourseResource::collection($courses);
    }

    public function show(string $slug): CourseResource|JsonResponse
    {
        $course = Course::query()
            ->where('status', 'published')
            ->where('slug', $slug)
            ->first();

        if (! $course) {
            return response()->json(['message' => 'Course not found'], 404);
        }

        return new CourseResource($course);
    }
}
