<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Support\TranslatableContent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminPreviewController extends Controller
{
    public function preview(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => ['required', 'in:project,post'],
            'locale' => ['required', 'in:en,es'],
            'data' => ['required', 'array'],
        ]);

        $type = $validated['type'];
        $locale = $validated['locale'];
        // Use the raw data array directly from the request to bypass validated elements filtering
        $data = $request->input('data');

        if ($type === 'project') {
            return response()->json($this->previewProject($data, $locale));
        }

        return response()->json($this->previewPost($data, $locale));
    }

    private function previewProject(array $data, string $locale): array
    {
        $title = TranslatableContent::text($data['title'] ?? [], $locale) ?? '';
        $summary = TranslatableContent::text($data['summary'] ?? [], $locale) ?? '';
        $description = Str::markdown(TranslatableContent::text($data['description'] ?? [], $locale) ?? '');
        $details = TranslatableContent::deep($data['details'] ?? [], $locale) ?? [];
        $media = $data['media'] ?? [];
        $stack = $data['stack'] ?? [];
        $coverImageUrl = $data['cover_image_url'] ?? null;
        $demoUrl = $data['demo_url'] ?? null;
        $repositoryUrl = $data['repository_url'] ?? null;

        $html = $this->buildProjectHtml($title, $summary, $description, $details, $media, $stack, $coverImageUrl, $demoUrl, $repositoryUrl, $locale);

        return [
            'html' => $html,
            'title' => $title,
            'locale' => $locale,
        ];
    }

    private function previewPost(array $data, string $locale): array
    {
        $title = TranslatableContent::text($data['title'] ?? [], $locale) ?? '';
        $excerpt = TranslatableContent::text($data['excerpt'] ?? [], $locale) ?? '';
        $content = Str::markdown(TranslatableContent::text($data['content'] ?? [], $locale) ?? '');
        $coverImageUrl = $data['cover_image_url'] ?? null;
        $externalUrl = $data['external_url'] ?? null;

        $html = $this->buildPostHtml($title, $excerpt, $content, $coverImageUrl, $externalUrl, $locale);

        return [
            'html' => $html,
            'title' => $title,
            'locale' => $locale,
        ];
    }

    private function buildProjectHtml(
        string $title,
        string $summary,
        string $description,
        array $details,
        array $media,
        array $stack,
        ?string $coverImageUrl,
        ?string $demoUrl,
        ?string $repositoryUrl,
        string $locale = 'en'
    ): string {
        $html = '<div class="detail-layout">';

        // Hero banner section
        $html .= '<section class="hero-banner panel detail-hero">';
        if ($coverImageUrl) {
            $html .= '<img src="'.e($coverImageUrl).'" alt="'.e($title).'">';
        }
        $html .= '<div>';
        $html .= '<h1>'.e($title).'</h1>';
        if ($summary) {
            $html .= '<p class="lead">'.e($summary).'</p>';
        }
        if (! empty($stack)) {
            $html .= '<div class="meta-tags">';
            foreach ($stack as $item) {
                $html .= '<span>'.e($item).'</span>';
            }
            $html .= '</div>';
        }
        if ($demoUrl || $repositoryUrl) {
            $html .= '<div class="cta-row">';
            if ($demoUrl) {
                $html .= '<a class="primary-button" href="'.e($demoUrl).'" target="_blank" rel="noreferrer">Demo</a>';
            }
            if ($repositoryUrl) {
                $html .= '<a class="secondary-button" href="'.e($repositoryUrl).'" target="_blank" rel="noreferrer">Repo</a>';
            }
            $html .= '</div>';
        }
        $html .= '</div>';
        $html .= '</section>';

        // Details section
        if (! empty($details)) {
            $detailLabel = $locale === 'es' ? 'Detalles' : 'Details';
            $html .= '<section class="panel">';
            $html .= '<h2>'.e($detailLabel).'</h2>';
            $html .= '<div class="detail-grid">';
            foreach ($details as $detail) {
                $label = is_array($detail['label'] ?? null) ? TranslatableContent::text($detail['label'], $locale) : ($detail['label'] ?? '');
                $value = is_array($detail['value'] ?? null) ? TranslatableContent::text($detail['value'], $locale) : ($detail['value'] ?? '');
                $html .= '<article class="detail-card">';
                $html .= '<p class="eyebrow">'.e($label).'</p>';
                $html .= '<h3>'.e($value).'</h3>';
                $html .= '</article>';
            }
            $html .= '</div>';
            $html .= '</section>';
        }

        // Description section
        if ($description) {
            $descLabel = $locale === 'es' ? 'Descripción' : 'Description';
            $html .= '<section class="panel">';
            $html .= '<h2>'.e($descLabel).'</h2>';
            $html .= '<div class="article-body">'.$description.'</div>';
            $html .= '</section>';
        }

        // Gallery/Media section
        if (! empty($media)) {
            $galleryLabel = $locale === 'es' ? 'Galeria' : 'Gallery';
            $html .= '<section class="panel">';
            $html .= '<h2>'.e($galleryLabel).'</h2>';
            $html .= '<div class="media-grid">';
            foreach ($media as $item) {
                $caption = is_array($item['caption'] ?? null) ? TranslatableContent::text($item['caption'], $locale) : ($item['caption'] ?? '');
                if ($item['kind'] === 'video') {
                    $html .= '<iframe src="'.e($item['url']).'" title="'.e($caption ?: $title).'" frameborder="0" allowfullscreen></iframe>';
                } else {
                    $html .= '<img src="'.e($item['url']).'" alt="'.e($caption ?: $title).'">';
                }
            }
            $html .= '</div>';
            $html .= '</section>';
        }

        $html .= '</div>';

        return $html;
    }

    private function buildPostHtml(
        string $title,
        string $excerpt,
        string $content,
        ?string $coverImageUrl,
        ?string $externalUrl,
        string $locale = 'en'
    ): string {
        $html = '<div class="detail-layout">';
        $html .= '<section class="panel article-card">';

        if ($coverImageUrl) {
            $html .= '<img class="article-cover" src="'.e($coverImageUrl).'" alt="'.e($title).'">';
        }

        $publishedLabel = $locale === 'es' ? 'Vista previa' : 'Preview';
        $html .= '<p class="eyebrow">'.e($publishedLabel).'</p>';
        
        $html .= '<h1>'.e($title).'</h1>';

        if ($excerpt) {
            $html .= '<p class="lead">'.e($excerpt).'</p>';
        }

        if ($content) {
            $html .= '<div class="article-body">'.$content.'</div>';
        }

        if ($externalUrl) {
            $readMoreLabel = $locale === 'es' ? 'Leer más' : 'Read more';
            $html .= '<div class="cta-row">';
            $html .= '<a class="secondary-button" href="'.e($externalUrl).'" target="_blank" rel="noreferrer">'.e($readMoreLabel).'</a>';
            $html .= '</div>';
        }

        $html .= '</section>';
        $html .= '</div>';

        return $html;
    }
}
