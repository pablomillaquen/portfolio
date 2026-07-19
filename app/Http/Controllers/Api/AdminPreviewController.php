<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Capability;
use App\Models\Season;
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
        $problem = Str::markdown(TranslatableContent::text($data['problem'] ?? [], $locale) ?? '');
        $approach = Str::markdown(TranslatableContent::text($data['approach'] ?? [], $locale) ?? '');
        $contribution = Str::markdown(TranslatableContent::text($data['contribution'] ?? [], $locale) ?? '');
        $whatItDemonstrates = Str::markdown(TranslatableContent::text($data['what_it_demonstrates'] ?? [], $locale) ?? '');
        $details = TranslatableContent::deep($data['details'] ?? [], $locale) ?? [];
        $media = $data['media'] ?? [];
        $stack = $data['stack'] ?? [];
        $coverImageUrl = $data['cover_image_url'] ?? null;
        $demoUrl = $data['demo_url'] ?? null;
        $repositoryUrl = $data['repository_url'] ?? null;
        $categories = $data['categories'] ?? [];
        $capabilities = $data['capabilities'] ?? [];

        $categoryModels = Category::whereIn('id', $categories)->get();
        $capabilityModels = Capability::whereIn('id', $capabilities)->get();

        $html = $this->buildProjectHtml($title, $summary, $description, $problem, $approach, $contribution, $whatItDemonstrates, $details, $media, $stack, $coverImageUrl, $demoUrl, $repositoryUrl, $categoryModels, $capabilityModels, $locale);

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
        $publishedAt = $data['published_at'] ?? null;
        $seasonId = $data['season_id'] ?? null;
        $episodeNumber = $data['episode_number'] ?? null;

        $season = null;
        if ($seasonId) {
            $season = Season::find($seasonId);
        }

        $html = $this->buildPostHtml($title, $excerpt, $content, $coverImageUrl, $externalUrl, $publishedAt, $season, $episodeNumber, $locale);

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
        string $problem,
        string $approach,
        string $contribution,
        string $whatItDemonstrates,
        array $details,
        array $media,
        array $stack,
        ?string $coverImageUrl,
        ?string $demoUrl,
        ?string $repositoryUrl,
        $categories,
        $capabilities,
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
        if ($categories->isNotEmpty()) {
            $html .= '<div class="meta-tags">';
            foreach ($categories as $cat) {
                $name = TranslatableContent::text($cat->name, $locale);
                if ($name) {
                    $html .= '<span class="tag-category">'.e($name).'</span>';
                }
            }
            $html .= '</div>';
        }
        if ($capabilities->isNotEmpty()) {
            $html .= '<div class="meta-tags">';
            foreach ($capabilities as $cap) {
                $name = TranslatableContent::text($cap->name, $locale);
                if ($name) {
                    $html .= '<span class="tag-capability">'.e($name).'</span>';
                }
            }
            $html .= '</div>';
        }
        if ($demoUrl || $repositoryUrl) {
            $html .= '<hr>';
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

        // Case study sections
        $caseStudySections = [
            ['key' => 'problem', 'label_es' => 'Problema', 'label_en' => 'Problem', 'value' => $problem],
            ['key' => 'approach', 'label_es' => 'Enfoque', 'label_en' => 'Approach', 'value' => $approach],
            ['key' => 'contribution', 'label_es' => 'Aporte', 'label_en' => 'Contribution', 'value' => $contribution],
            ['key' => 'what_it_demonstrates', 'label_es' => 'Qué demuestra este trabajo', 'label_en' => 'What This Work Demonstrates', 'value' => $whatItDemonstrates],
        ];

        foreach ($caseStudySections as $section) {
            if ($section['value']) {
                $label = $locale === 'es' ? $section['label_es'] : $section['label_en'];
                $html .= '<section class="panel">';
                $html .= '<h2>'.e($label).'</h2>';
                $html .= '<div class="article-body">'.$section['value'].'</div>';
                $html .= '</section>';
            }
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
        ?string $publishedAt,
        ?Season $season,
        ?int $episodeNumber,
        string $locale = 'en'
    ): string {
        $html = '<div class="detail-layout">';
        $html .= '<section class="panel article-card">';

        if ($coverImageUrl) {
            $html .= '<img class="article-cover" src="'.e($coverImageUrl).'" alt="'.e($title).'">';
        }

        if ($publishedAt) {
            $date = \Carbon\Carbon::parse($publishedAt)->format('d M Y');
            $html .= '<p class="eyebrow">'.e($date).'</p>';
        }

        $html .= '<h1>'.e($title).'</h1>';

        if ($season) {
            $seasonName = TranslatableContent::text($season->name, $locale);
            $episodeLabel = $locale === 'es' ? 'Episodio' : 'Episode';
            $html .= '<p class="season-badge">'.e($seasonName).' - '.$episodeLabel.' '.e((string) $episodeNumber).'</p>';
        }

        if ($excerpt) {
            $html .= '<p class="lead">'.e($excerpt).'</p>';
        }

        if ($content) {
            $html .= '<div class="article-body">'.$content.'</div>';
        }

        $html .= '<div class="cta-row">';
        $shareLabel = $locale === 'es' ? 'Compartir en LinkedIn' : 'Share on LinkedIn';
        $html .= '<a class="secondary-button" href="https://www.linkedin.com/sharing/share-offsite/?url='.urlencode('#').'" target="_blank" rel="noreferrer">'.e($shareLabel).'</a>';
        $html .= '</div>';

        $html .= '</section>';
        $html .= '</div>';

        return $html;
    }
}
