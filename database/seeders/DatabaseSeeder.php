<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Post;
use App\Models\Project;
use App\Models\SiteSetting;
use App\Models\SocialLink;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        foreach ([
            [
                'key' => 'home',
                'value' => [
                    'brand' => 'PM',
                    'profileImage' => 'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=900&q=80',
                    'headline' => [
                        'es' => 'Hola, soy Pablo.',
                        'en' => "Hi, I'm Pablo.",
                    ],
                    'bio' => [
                        'es' => 'Ingeniero de software en Valparaiso, Chile. Me enfoco en Laravel, Vue y productos digitales bien diseñados.',
                        'en' => 'Software engineer based in Valparaiso, Chile. I focus on Laravel, Vue, and thoughtfully designed digital products.',
                    ],
                ],
            ],
            [
                'key' => 'stack',
                'value' => [
                    ['title' => ['es' => 'Lenguajes', 'en' => 'Languages'], 'items' => ['PHP', 'JavaScript', 'TypeScript', 'Python', 'Kotlin']],
                    ['title' => ['es' => 'Frameworks', 'en' => 'Frameworks'], 'items' => ['Laravel', 'Vue', 'Nuxt', 'Angular', 'Ionic']],
                    ['title' => ['es' => 'Bases de datos', 'en' => 'Databases'], 'items' => ['MySQL', 'PostgreSQL', 'SQLite']],
                    ['title' => ['es' => 'Otros', 'en' => 'Others'], 'items' => ['AWS', 'REST APIs', 'Docker', 'Git']],
                ],
            ],
            [
                'key' => 'experience',
                'value' => [
                    [
                        'role' => ['es' => 'Senior Full Stack Developer', 'en' => 'Senior Full Stack Developer'],
                        'company' => 'ML Tecnologias',
                        'period' => ['es' => 'Abr 2019 - Actualidad', 'en' => 'Apr 2019 - Present'],
                        'bullets' => [
                            ['es' => 'Desarrollo de aplicaciones web y móviles con Laravel, Angular e Ionic.', 'en' => 'Development of web and mobile applications with Laravel, Angular, and Ionic.'],
                            ['es' => 'Diseño e implementación de APIs REST con autenticación JWT.', 'en' => 'Design and implementation of REST APIs with JWT authentication.'],
                        ],
                    ],
                ],
            ],
            [
                'key' => 'contact',
                'value' => [
                    'title' => ['es' => 'Hablemos de tu proyecto', 'en' => "Let's talk about your project"],
                    'subtitle' => ['es' => 'Cuéntame tu idea y te responderé pronto.', 'en' => 'Tell me about your idea and I will get back to you soon.'],
                    'email' => 'hola@pablomillaquen.dev',
                ],
            ],
            [
                'key' => 'footer',
                'value' => [
                    'copyright' => [
                        'es' => 'Todos los derechos reservados.',
                        'en' => 'All rights reserved.',
                    ],
                ],
            ],
        ] as $setting) {
            SiteSetting::query()->updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value']]
            );
        }

        foreach ([
            ['platform' => 'instagram', 'label' => ['es' => 'Instagram', 'en' => 'Instagram'], 'url' => 'https://instagram.com', 'icon' => 'instagram', 'sort_order' => 1, 'active' => true],
            ['platform' => 'linkedin', 'label' => ['es' => 'LinkedIn', 'en' => 'LinkedIn'], 'url' => 'https://linkedin.com', 'icon' => 'linkedin', 'sort_order' => 2, 'active' => true],
            ['platform' => 'github', 'label' => ['es' => 'GitHub', 'en' => 'GitHub'], 'url' => 'https://github.com', 'icon' => 'github', 'sort_order' => 3, 'active' => true],
            ['platform' => 'youtube', 'label' => ['es' => 'YouTube', 'en' => 'YouTube'], 'url' => 'https://youtube.com', 'icon' => 'youtube', 'sort_order' => 4, 'active' => true],
        ] as $link) {
            SocialLink::query()->updateOrCreate(
                ['platform' => $link['platform']],
                $link
            );
        }

        $project = Project::query()->updateOrCreate([
            'slug' => 'maintenance-platform',
        ], [
            'status' => 'published',
            'featured' => true,
            'sort_order' => 1,
            'cover_image_url' => 'https://images.unsplash.com/photo-1516321318423-f06f85e504b3?auto=format&fit=crop&w=1600&q=80',
            'demo_url' => 'https://example.com/demo',
            'repository_url' => 'https://github.com/example/demo',
            'title' => ['es' => 'Plataforma de mantenimiento', 'en' => 'Maintenance Platform'],
            'summary' => ['es' => 'Sistema para gestionar órdenes de trabajo, activos y trazabilidad.', 'en' => 'System to manage work orders, assets, and traceability.'],
            'description' => ['es' => 'Proyecto full stack con enfoque en productividad, panel operativo y experiencia móvil.', 'en' => 'Full stack project focused on productivity, operational dashboards, and mobile experience.'],
            'details' => [
                ['label' => ['es' => 'Cliente', 'en' => 'Client'], 'value' => ['es' => 'Sector salud', 'en' => 'Healthcare sector']],
                ['label' => ['es' => 'Rol', 'en' => 'Role'], 'value' => ['es' => 'Lider tecnico y desarrollo', 'en' => 'Technical lead and development']],
            ],
            'stack' => ['Laravel', 'Vue', 'MySQL', 'Tailwind'],
            'published_at' => Carbon::parse('2026-03-01'),
        ]);

        $project->media()->delete();
        $project->media()->createMany([
            ['kind' => 'image', 'url' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?auto=format&fit=crop&w=1400&q=80', 'caption' => ['es' => 'Vista general del panel', 'en' => 'Dashboard overview'], 'sort_order' => 1],
            ['kind' => 'video', 'url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ', 'caption' => ['es' => 'Demo en video', 'en' => 'Video demo'], 'sort_order' => 2],
        ]);

        Post::query()->updateOrCreate([
            'slug' => 'building-bilingual-portfolios',
        ], [
            'type' => 'internal',
            'status' => 'published',
            'featured' => true,
            'cover_image_url' => 'https://images.unsplash.com/photo-1498050108023-c5249f4df085?auto=format&fit=crop&w=1600&q=80',
            'share_enabled' => true,
            'title' => ['es' => 'Como diseno portafolios bilingues', 'en' => 'How I design bilingual portfolios'],
            'excerpt' => ['es' => 'Una guia practica para estructurar contenido editable y coherente en dos idiomas.', 'en' => 'A practical guide to structuring editable, consistent content in two languages.'],
            'content' => ['es' => "## Enfoque\n\nTrabajo con contenido estructurado y componentes reutilizables.", 'en' => "## Approach\n\nI work with structured content and reusable components."],
            'published_at' => Carbon::parse('2026-03-03'),
        ]);

        foreach ([
            [
                'slug' => 'aws-cloud-practitioner',
                'status' => 'published',
                'featured' => true,
                'sort_order' => 1,
                'name' => ['es' => 'AWS Cloud Practitioner', 'en' => 'AWS Cloud Practitioner'],
                'issuer' => 'Amazon Web Services',
                'issued_at' => '2024-06-15',
                'credential_id' => 'AWS-CP-12345',
                'url' => 'https://aws.amazon.com/verify',
            ],
            [
                'slug' => 'laravel-certification',
                'status' => 'published',
                'featured' => true,
                'sort_order' => 2,
                'name' => ['es' => 'Certificacion Laravel', 'en' => 'Laravel Certification'],
                'issuer' => 'Laravel Certification Board',
                'issued_at' => '2023-11-20',
                'credential_id' => null,
                'url' => 'https://laravel.com/certification',
            ],
        ] as $course) {
            Course::query()->updateOrCreate(
                ['slug' => $course['slug']],
                $course
            );
        }

        Post::query()->updateOrCreate([
            'slug' => 'laravel-vue-in-production',
        ], [
            'type' => 'external',
            'status' => 'published',
            'featured' => true,
            'external_url' => 'https://example.com/article',
            'share_enabled' => true,
            'title' => ['es' => 'Laravel + Vue en produccion', 'en' => 'Laravel + Vue in production'],
            'excerpt' => ['es' => 'Notas sobre arquitectura, despliegue y rendimiento.', 'en' => 'Notes on architecture, deployment, and performance.'],
            'content' => ['es' => '', 'en' => ''],
            'published_at' => Carbon::parse('2026-03-05'),
        ]);
    }
}
