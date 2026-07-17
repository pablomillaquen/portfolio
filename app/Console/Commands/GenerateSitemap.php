<?php

namespace App\Console\Commands;

use App\Models\Course;
use App\Models\Post;
use App\Models\Project;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateSitemap extends Command
{
    protected $signature = 'seo:generate-sitemap';

    protected $description = 'Generate sitemap.xml with all published content';

    private const SITE_URL = 'https://pablomillaquen.com';

    public function handle(): int
    {
        $urls = $this->getStaticUrls();
        $urls = array_merge($urls, $this->getProjectUrls());
        $urls = array_merge($urls, $this->getPostUrls());
        $urls = array_merge($urls, $this->getCourseUrls());

        $xml = $this->buildXml($urls);

        $path = public_path('sitemap.xml');
        File::put($path, $xml);

        $this->info("Sitemap generated: {$path} (" . count($urls) . " URLs)");

        return self::SUCCESS;
    }

    private function getStaticUrls(): array
    {
        $now = now()->toDateString();

        return [
            [
                'loc' => self::SITE_URL,
                'lastmod' => $now,
                'changefreq' => 'weekly',
                'priority' => '1.0',
            ],
            [
                'loc' => self::SITE_URL . '/projects',
                'lastmod' => $now,
                'changefreq' => 'weekly',
                'priority' => '0.9',
            ],
            [
                'loc' => self::SITE_URL . '/posts',
                'lastmod' => $now,
                'changefreq' => 'weekly',
                'priority' => '0.9',
            ],
            [
                'loc' => self::SITE_URL . '/courses',
                'lastmod' => $now,
                'changefreq' => 'monthly',
                'priority' => '0.8',
            ],
            [
                'loc' => self::SITE_URL . '/contact',
                'lastmod' => $now,
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ],
        ];
    }

    private function getProjectUrls(): array
    {
        return Project::where('status', 'published')
            ->get()
            ->map(fn (Project $project) => [
                'loc' => self::SITE_URL . '/projects/' . $project->slug,
                'lastmod' => optional($project->updated_at)->toDateString() ?? now()->toDateString(),
                'changefreq' => 'monthly',
                'priority' => '0.8',
            ])
            ->toArray();
    }

    private function getPostUrls(): array
    {
        return Post::where('status', 'published')
            ->get()
            ->map(fn (Post $post) => [
                'loc' => self::SITE_URL . '/posts/' . $post->slug,
                'lastmod' => optional($post->published_at)->toDateString() ?? now()->toDateString(),
                'changefreq' => 'monthly',
                'priority' => '0.8',
            ])
            ->toArray();
    }

    private function getCourseUrls(): array
    {
        return Course::where('status', 'published')
            ->get()
            ->map(fn (Course $course) => [
                'loc' => self::SITE_URL . '/courses/' . $course->slug,
                'lastmod' => optional($course->updated_at)->toDateString() ?? now()->toDateString(),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ])
            ->toArray();
    }

    private function buildXml(array $urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $url) {
            $xml .= '  <url>' . "\n";
            $xml .= '    <loc>' . e($url['loc']) . '</loc>' . "\n";
            $xml .= '    <lastmod>' . e($url['lastmod']) . '</lastmod>' . "\n";
            $xml .= '    <changefreq>' . e($url['changefreq']) . '</changefreq>' . "\n";
            $xml .= '    <priority>' . e($url['priority']) . '</priority>' . "\n";
            $xml .= '  </url>' . "\n";
        }

        $xml .= '</urlset>';

        return $xml;
    }
}
