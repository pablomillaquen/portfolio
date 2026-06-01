<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCourseController extends Controller
{
    public function index()
    {
        return response()->json(
            Course::query()->orderByDesc('featured')->orderBy('sort_order')->get()
        );
    }

    public function store(Request $request)
    {
        $course = Course::query()->create($this->validated($request));

        return response()->json($course, 201);
    }

    public function update(Request $request, Course $course)
    {
        $course->update($this->validated($request, $course->id));

        return response()->json($course);
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return response()->noContent();
    }

    private function validated(Request $request, ?int $courseId = null): array
    {
        $data = $request->validate([
            'slug' => ['nullable', 'string', 'max:255', 'unique:courses,slug'.($courseId ? ','.$courseId : '')],
            'status' => ['required', 'in:draft,published'],
            'featured' => ['boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'name' => ['required', 'array'],
            'name.es' => ['required', 'string'],
            'name.en' => ['required', 'string'],
            'issuer' => ['required', 'string', 'max:255'],
            'issued_at' => ['required', 'date'],
            'credential_id' => ['nullable', 'string', 'max:255'],
            'url' => ['nullable', 'url'],
        ]);

        $data['slug'] = $data['slug'] ?: Str::slug($data['name']['en'] ?? $data['name']['es']);
        $data['featured'] = $data['featured'] ?? false;
        $data['sort_order'] = $data['sort_order'] ?? 0;

        return $data;
    }
}
