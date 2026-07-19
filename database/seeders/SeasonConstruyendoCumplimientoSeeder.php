<?php

namespace Database\Seeders;

use App\Models\Season;
use App\Models\Category;
use App\Models\Capability;
use App\Models\Project;
use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SeasonConstruyendoCumplimientoSeeder extends Seeder
{
    private string $basePath = '/Users/pablomillaquen/Proyectos/Publicaciones/1. Ley 21719';

    public function run(): void
    {
        $season = $this->createSeason();
        $categories = $this->createCategories();
        $capabilities = $this->createCapabilities();
        $projects = $this->createProjects($categories, $capabilities);
        $this->createPosts($season, $projects, $categories);
    }

    private function createSeason(): Season
    {
        return Season::updateOrCreate(
            ['slug' => 'construyendo-cumplimiento'],
            [
                'status' => 'draft',
                'name' => [
                    'en' => 'Building Compliance: Software Engineering for the New Data Protection Law',
                    'es' => 'Construyendo cumplimiento: Ingeniería de Software para la nueva Ley de Protección de Datos',
                ],
                'description' => [
                    'en' => 'A series on how to transform a regulation into architecture, evidence, and software.',
                    'es' => 'Una serie sobre cómo transformar una regulación en arquitectura, evidencia y software.',
                ],
                'sort_order' => 2,
            ]
        );
    }

    private function createCategories(): array
    {
        $cats = [];
        $cats['proteccion-datos'] = Category::updateOrCreate(
            ['slug' => 'proteccion-datos'],
            [
                'dimension' => 'domain',
                'name' => ['en' => 'Data Protection', 'es' => 'Protección de Datos'],
                'description' => ['en' => 'Personal data protection, privacy, and regulatory compliance.', 'es' => 'Protección de datos personales, privacidad y cumplimiento regulatorio.'],
            ]
        );
        $cats['ingenieria-software'] = Category::updateOrCreate(
            ['slug' => 'ingenieria-software'],
            [
                'dimension' => 'domain',
                'name' => ['en' => 'Software Engineering', 'es' => 'Ingeniería de Software'],
                'description' => ['en' => 'Software engineering practices, methodology, and process.', 'es' => 'Prácticas, metodología y proceso de ingeniería de software.'],
            ]
        );
        $cats['gobierno-datos'] = Category::updateOrCreate(
            ['slug' => 'gobierno-datos'],
            [
                'dimension' => 'domain',
                'name' => ['en' => 'Data Governance', 'es' => 'Gobierno de Datos'],
                'description' => ['en' => 'Data governance, classification, lifecycle management.', 'es' => 'Gobierno de datos, clasificación, gestión del ciclo de vida.'],
            ]
        );
        return $cats;
    }

    private function createCapabilities(): array
    {
        $caps = [];
        $caps['analisis-sistemas'] = Capability::updateOrCreate(
            ['slug' => 'analisis-sistemas'],
            [
                'name' => ['en' => 'System Analysis', 'es' => 'Análisis de Sistemas'],
                'description' => ['en' => 'Analysis of existing systems to understand data flow and architecture.', 'es' => 'Análisis de sistemas existentes para comprender el flujo de datos y la arquitectura.'],
                'sort_order' => 11,
            ]
        );
        $caps['proteccion-datos-cap'] = Capability::updateOrCreate(
            ['slug' => 'proteccion-datos-cap'],
            [
                'name' => ['en' => 'Data Protection Engineering', 'es' => 'Ingeniería de Protección de Datos'],
                'description' => ['en' => 'Engineering personal data protection into software systems.', 'es' => 'Ingeniería de protección de datos personales en sistemas de software.'],
                'sort_order' => 12,
            ]
        );
        $caps['diseno-arquitectonico'] = Capability::updateOrCreate(
            ['slug' => 'diseno-arquitectonico'],
            [
                'name' => ['en' => 'Architectural Design', 'es' => 'Diseño Arquitectónico'],
                'description' => ['en' => 'Designing software architecture to meet regulatory and technical requirements.', 'es' => 'Diseño de arquitectura de software para cumplir requisitos regulatorios y técnicos.'],
                'sort_order' => 13,
            ]
        );
        $caps['gobierno-datos-cap'] = Capability::updateOrCreate(
            ['slug' => 'gobierno-datos-cap'],
            [
                'name' => ['en' => 'Data Governance', 'es' => 'Gobierno de Datos'],
                'description' => ['en' => 'Data governance strategies, classification, and lifecycle management.', 'es' => 'Estrategias de gobierno de datos, clasificación y gestión del ciclo de vida.'],
                'sort_order' => 14,
            ]
        );
        return $caps;
    }

    private function createProjects(array $categories, array $capabilities): array
    {
        $projects = [];
        $baseDate = now()->startOfWeek()->addWeek();

        $projectDefs = [
            [
                'key' => 'adaptacion-ley',
                'slug' => 'adaptacion-ley-21719-ingenieria-evidencia',
                'dir' => 'articulo_1',
                'sort_order' => 1,
                'featured' => true,
                'title_en' => 'Adapting Ley 21719: Software Engineering as Evidence of Compliance',
                'title_es' => 'Adaptando la Ley 21719: Ingeniería de Software como Evidencia de Cumplimiento',
                'week_offset' => 0,
                'caps' => ['analisis-sistemas', 'proteccion-datos-cap'],
            ],
            [
                'key' => 'investigacion-evidencia',
                'slug' => 'investigacion-evidencia-ley-21719',
                'dir' => 'articulo_2',
                'sort_order' => 2,
                'featured' => true,
                'title_en' => 'Evidence-Based Research for Ley 21719 Compliance',
                'title_es' => 'Investigación Basada en Evidencia para el Cumplimiento de la Ley 21719',
                'week_offset' => 1,
                'caps' => ['analisis-sistemas'],
            ],
            [
                'key' => 'analisis-recorrido',
                'slug' => 'analisis-recorrido-datos-personales',
                'dir' => 'articulo_3',
                'sort_order' => 3,
                'featured' => false,
                'title_en' => 'Mapping the Journey of Personal Data',
                'title_es' => 'Mapeando el Recorrido de los Datos Personales',
                'week_offset' => 2,
                'caps' => ['proteccion-datos-cap', 'gobierno-datos-cap'],
            ],
            [
                'key' => 'transformacion-requisitos',
                'slug' => 'transformacion-requisitos-regulatorios-arquitectura',
                'dir' => 'articulo_4',
                'sort_order' => 4,
                'featured' => false,
                'title_en' => 'From Regulatory Requirements to Software Architecture',
                'title_es' => 'De Requisitos Regulatorios a Arquitectura de Software',
                'week_offset' => 3,
                'caps' => ['diseno-arquitectonico', 'analisis-sistemas'],
            ],
            [
                'key' => 'diseno-capacidades',
                'slug' => 'diseno-capacidades-cumplimiento',
                'dir' => 'articulo_5',
                'sort_order' => 5,
                'featured' => false,
                'title_en' => 'Designing Compliance Capabilities',
                'title_es' => 'Diseño de Capacidades de Cumplimiento',
                'week_offset' => 4,
                'caps' => ['diseno-arquitectonico', 'proteccion-datos-cap'],
            ],
        ];

        foreach ($projectDefs as $def) {
            $portafolio = $this->readFile($def['dir'] . '/portafolio.md');
            $sections = $this->parsePortafolio($portafolio);
            $summary = $this->extractBeforeSeparator($portafolio);
            $description = $this->extractIntroBeforeSections($portafolio);
            $publishedAt = $baseDate->copy()->addWeeks($def['week_offset']);

            $project = Project::updateOrCreate(
                ['slug' => $def['slug']],
                [
                    'status' => 'draft',
                    'featured' => $def['featured'],
                    'sort_order' => $def['sort_order'],
                    'cover_image_url' => '/images/projects/' . $def['slug'] . '-cover.webp',
                    'title' => ['en' => $def['title_en'], 'es' => $def['title_es']],
                    'summary' => ['en' => $summary, 'es' => $summary],
                    'description' => ['en' => $description, 'es' => $description],
                    'problem' => ['en' => $sections['problema'] ?? '', 'es' => $sections['problema'] ?? ''],
                    'approach' => ['en' => $sections['enfoque'] ?? '', 'es' => $sections['enfoque'] ?? ''],
                    'contribution' => ['en' => $sections['aporte'] ?? '', 'es' => $sections['aporte'] ?? ''],
                    'what_it_demonstrates' => ['en' => $sections['que_demuestra'] ?? '', 'es' => $sections['que_demuestra'] ?? ''],
                    'project_status' => 'completed',
                    'published_at' => $publishedAt,
                ]
            );

            $project->categories()->syncWithoutDetaching([
                $categories['proteccion-datos']->id,
                $categories['ingenieria-software']->id,
            ]);

            foreach ($def['caps'] as $capSlug) {
                $project->capabilities()->syncWithoutDetaching([$capabilities[$capSlug]->id]);
            }

            $projects[$def['key']] = $project;
        }

        return $projects;
    }

    private function parsePortafolio(string $content): array
    {
        $sections = [];
        $lines = explode("\n", $content);
        $currentSection = null;
        $sectionBuffer = [];

        foreach ($lines as $line) {
            $trimmed = trim($line);

            if (preg_match('/^##\s+Problema/i', $trimmed)) {
                $currentSection = 'problema';
                $sectionBuffer = [];
                continue;
            }
            if (preg_match('/^##\s+Enfoque/i', $trimmed)) {
                $this->flushSection($sections, $currentSection, $sectionBuffer);
                $currentSection = 'enfoque';
                $sectionBuffer = [];
                continue;
            }
            if (preg_match('/^##\s+Aporte/i', $trimmed)) {
                $this->flushSection($sections, $currentSection, $sectionBuffer);
                $currentSection = 'aporte';
                $sectionBuffer = [];
                continue;
            }
            if (preg_match('/^##\s+Qué demuestra/i', $trimmed)) {
                $this->flushSection($sections, $currentSection, $sectionBuffer);
                $currentSection = 'que_demuestra';
                $sectionBuffer = [];
                continue;
            }
            if (preg_match('/^##\s+Estado/i', $trimmed)) {
                $this->flushSection($sections, $currentSection, $sectionBuffer);
                $currentSection = null;
                $sectionBuffer = [];
                continue;
            }
            if ($currentSection !== null) {
                $sectionBuffer[] = $line;
            }
        }

        $this->flushSection($sections, $currentSection, $sectionBuffer);
        return $sections;
    }

    private function flushSection(array &$sections, ?string $key, array &$buffer): void
    {
        if ($key !== null) {
            $sections[$key] = trim(implode("\n", $buffer));
        }
        $buffer = [];
    }

    private function extractBeforeSeparator(string $content): string
    {
        $parts = preg_split('/_{5,}/', $content);
        if ($parts === false) {
            $parts = [$content];
        }
        return trim($parts[0] ?? '');
    }

    private function extractAfterSeparator(string $content): string
    {
        $parts = preg_split('/_{5,}|_{50,}|_{30,}/', $content);
        if ($parts === false || count($parts) < 2) {
            $parts = preg_split('/-{5,}/', $content);
        }
        if ($parts === false || count($parts) < 2) {
            return '';
        }
        return trim($parts[1] ?? '');
    }

    private function extractIntroBeforeSections(string $content): string
    {
        $afterSep = $this->extractAfterSeparator($content);
        if ($afterSep === '') {
            return '';
        }
        $lines = explode("\n", $afterSep);
        $intro = [];
        foreach ($lines as $line) {
            if (preg_match('/^#{1,2}\s/', trim($line))) {
                break;
            }
            $intro[] = $line;
        }
        return trim(implode("\n", $intro));
    }

    private function createPosts(Season $season, array $projects, array $categories): void
    {
        $baseDate = now()->startOfWeek()->addWeek();

        $postDefs = [
            [
                'slug' => 'ley-21719-proyecto-ingenieria',
                'episode' => 1,
                'project' => 'adaptacion-ley',
                'dir' => 'articulo_1',
                'title_en' => 'Ley 21719 as a Software Engineering Project',
                'title_es' => 'La Ley 21719 como Proyecto de Ingeniería de Software',
                'week_offset' => 0,
            ],
            [
                'slug' => 'edse-estudiar-ley-21719',
                'episode' => 2,
                'project' => 'investigacion-evidencia',
                'dir' => 'articulo_2',
                'title_en' => 'Using EDSE to Study Ley 21719',
                'title_es' => 'Usando EDSE para Estudiar la Ley 21719',
                'week_offset' => 1,
            ],
            [
                'slug' => 'trazabilidad-datos-ley-21719',
                'episode' => 3,
                'project' => 'analisis-recorrido',
                'dir' => 'articulo_3',
                'title_en' => 'Data Traceability in Ley 21719',
                'title_es' => 'Trazabilidad de Datos en la Ley 21719',
                'week_offset' => 2,
            ],
            [
                'slug' => 'requisitos-regulatorios-a-arquitectura',
                'episode' => 4,
                'project' => 'transformacion-requisitos',
                'dir' => 'articulo_4',
                'title_en' => 'From Regulatory Requirements to Architecture',
                'title_es' => 'De Requisitos Regulatorios a Arquitectura',
                'week_offset' => 3,
            ],
            [
                'slug' => 'diseno-capacidades-cumplimiento',
                'episode' => 5,
                'project' => 'diseno-capacidades',
                'dir' => 'articulo_5',
                'title_en' => 'Designing Compliance Capabilities',
                'title_es' => 'Diseño de Capacidades de Cumplimiento',
                'week_offset' => 4,
            ],
            [
                'slug' => 'herramientas-razonamiento-cumplimiento',
                'episode' => 6,
                'project' => null,
                'dir' => 'articulo_6',
                'title_en' => 'Tools for Compliance Reasoning',
                'title_es' => 'Herramientas de Razonamiento para el Cumplimiento',
                'week_offset' => 5,
            ],
            [
                'slug' => 'procesos-segundo-orden',
                'episode' => 7,
                'project' => null,
                'dir' => 'articulo_7',
                'title_en' => 'Second-Order Processes',
                'title_es' => 'Procesos de Segundo Orden',
                'week_offset' => 6,
            ],
            [
                'slug' => 'ingenieria-datos-transformacion-regular',
                'episode' => 8,
                'project' => null,
                'dir' => 'articulo_8',
                'title_en' => 'Data Engineering as Regular Transformation',
                'title_es' => 'Ingeniería de Datos como Transformación Regular',
                'week_offset' => 7,
            ],
        ];

        foreach ($postDefs as $def) {
            $resumen = $this->readFile($def['dir'] . '/resumen.md');
            $publicacion = $this->readFile($def['dir'] . '/publicacion.md');
            $body = $this->extractAfterSeparator($publicacion);
            $publishedAt = $baseDate->copy()->addWeeks($def['week_offset']);

            $project = $def['project'] ? $projects[$def['project']] : null;

            $post = Post::updateOrCreate(
                ['slug' => $def['slug']],
                [
                    'type' => 'internal',
                    'status' => 'draft',
                    'featured' => false,
                    'share_enabled' => true,
                    'title' => ['en' => $def['title_en'], 'es' => $def['title_es']],
                    'excerpt' => ['en' => $resumen, 'es' => $resumen],
                    'content' => ['en' => $body, 'es' => $body],
                    'cover_image_url' => '/images/posts/' . $def['slug'] . '-cover.webp',
                    'season_id' => $season->id,
                    'episode_number' => $def['episode'],
                    'related_project_id' => $project?->id,
                    'published_at' => $publishedAt,
                ]
            );

            if ($project) {
                $post->projects()->syncWithoutDetaching([$project->id]);
            }
        }
    }

    private function readFile(string $relativePath): string
    {
        $fullPath = $this->basePath . '/' . $relativePath;
        if (! file_exists($fullPath)) {
            return '';
        }
        return trim(file_get_contents($fullPath));
    }
}
