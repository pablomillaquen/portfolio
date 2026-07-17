<?php

namespace Database\Seeders;

use App\Models\Capability;
use App\Models\Category;
use Illuminate\Database\Seeder;

class KnowledgePlatformSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['slug' => 'arquitectura', 'dimension' => 'domain', 'name' => ['en' => 'Architecture', 'es' => 'Arquitectura']],
            ['slug' => 'investigacion', 'dimension' => 'domain', 'name' => ['en' => 'Research', 'es' => 'Investigación']],
            ['slug' => 'gestion', 'dimension' => 'domain', 'name' => ['en' => 'Management', 'es' => 'Gestión']],
            ['slug' => 'salud', 'dimension' => 'domain', 'name' => ['en' => 'Healthcare', 'es' => 'Salud']],
            ['slug' => 'logistica', 'dimension' => 'domain', 'name' => ['en' => 'Logistics', 'es' => 'Logística']],
            ['slug' => 'ia', 'dimension' => 'domain', 'name' => ['en' => 'AI', 'es' => 'IA']],
            ['slug' => 'cumplimiento', 'dimension' => 'domain', 'name' => ['en' => 'Compliance', 'es' => 'Cumplimiento']],
            ['slug' => 'educacion', 'dimension' => 'domain', 'name' => ['en' => 'Education', 'es' => 'Educación']],
            ['slug' => 'wordpress', 'dimension' => 'domain', 'name' => ['en' => 'WordPress', 'es' => 'WordPress']],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }

        $capabilities = [
            ['slug' => 'arquitectura-software', 'name' => ['en' => 'Software Architecture', 'es' => 'Arquitectura de Software'], 'sort_order' => 1],
            ['slug' => 'investigacion-aplicada', 'name' => ['en' => 'Applied Research', 'es' => 'Investigación Aplicada'], 'sort_order' => 2],
            ['slug' => 'ingenieria-evidencia', 'name' => ['en' => 'Evidence-Based Engineering', 'es' => 'Ingeniería Basada en Evidencia'], 'sort_order' => 3],
            ['slug' => 'evolucion-sistemas', 'name' => ['en' => 'System Evolution', 'es' => 'Evolución de Sistemas'], 'sort_order' => 4],
            ['slug' => 'cumplimiento-normativo', 'name' => ['en' => 'Regulatory Compliance', 'es' => 'Cumplimiento Normativo'], 'sort_order' => 5],
        ];

        foreach ($capabilities as $capability) {
            Capability::create($capability);
        }
    }
}
