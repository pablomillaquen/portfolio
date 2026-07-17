<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Post;
use App\Models\Project;
use App\Support\TranslatableContent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SeoController extends Controller
{
    private const SITE_NAME = 'Pablo Millaquen';
    private const SITE_URL = 'https://pablomillaquen.com';
    private const DEFAULT_IMAGE = 'https://pablomillaquen.com/img/og_image.png';

    public function home(Request $request): JsonResponse
    {
        $locale = $request->string('locale', 'es')->toString();

        $payload = [
            'title' => self::SITE_NAME . ' — Desarrollador & Investigador',
            'description' => 'Portfolio profesional de Pablo Millaquen. Desarrollador de software e investigador especializado en logística, IA y arquitectura de software.',
            'image' => self::DEFAULT_IMAGE,
            'url' => self::SITE_URL,
            'type' => 'website',
            'locale' => $locale,
            'alternates' => [
                'es' => self::SITE_URL . '?locale=es',
                'en' => self::SITE_URL . '?locale=en',
            ],
        ];

        return response()->json($payload);
    }

    public function project(Request $request, string $slug): JsonResponse
    {
        $locale = $request->string('locale', 'es')->toString();

        $project = Project::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if (! $project) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $title = TranslatableContent::text($project->title, $locale);
        $description = TranslatableContent::text($project->summary, $locale)
            ?? Str::limit(strip_tags(TranslatableContent::text($project->description, $locale) ?? ''), 160);

        $payload = [
            'title' => $title . ' | ' . self::SITE_NAME,
            'description' => $description,
            'image' => $project->cover_image_url ?: self::DEFAULT_IMAGE,
            'url' => self::SITE_URL . '/projects/' . $project->slug,
            'type' => 'article',
            'locale' => $locale,
            'alternates' => [
                'es' => self::SITE_URL . '/projects/' . $project->slug . '?locale=es',
                'en' => self::SITE_URL . '/projects/' . $project->slug . '?locale=en',
            ],
        ];

        return response()->json($payload);
    }

    public function post(Request $request, string $slug): JsonResponse
    {
        $locale = $request->string('locale', 'es')->toString();

        $post = Post::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if (! $post) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $title = TranslatableContent::text($post->title, $locale);
        $description = TranslatableContent::text($post->excerpt, $locale)
            ?? Str::limit(strip_tags(TranslatableContent::text($post->content, $locale) ?? ''), 160);

        $payload = [
            'title' => $title . ' | ' . self::SITE_NAME,
            'description' => $description,
            'image' => $post->cover_image_url ?: self::DEFAULT_IMAGE,
            'url' => self::SITE_URL . '/posts/' . $post->slug,
            'type' => 'article',
            'locale' => $locale,
            'publishedAt' => optional($post->published_at)->toDateString(),
            'alternates' => [
                'es' => self::SITE_URL . '/posts/' . $post->slug . '?locale=es',
                'en' => self::SITE_URL . '/posts/' . $post->slug . '?locale=en',
            ],
        ];

        return response()->json($payload);
    }

    public function course(Request $request, string $slug): JsonResponse
    {
        $locale = $request->string('locale', 'es')->toString();

        $course = Course::query()
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();

        if (! $course) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $title = TranslatableContent::text($course->name, $locale);
        $description = 'Certificación: ' . $title . ' — ' . ($course->issuer ?? '');

        $payload = [
            'title' => $title . ' | ' . self::SITE_NAME,
            'description' => $description,
            'image' => self::DEFAULT_IMAGE,
            'url' => self::SITE_URL . '/courses/' . $course->slug,
            'type' => 'article',
            'locale' => $locale,
            'alternates' => [
                'es' => self::SITE_URL . '/courses/' . $course->slug . '?locale=es',
                'en' => self::SITE_URL . '/courses/' . $course->slug . '?locale=en',
            ],
        ];

        return response()->json($payload);
    }
}
