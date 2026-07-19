<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoryDimensionsSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            // Capacidad
            ['slug' => 'analisis-datos', 'dimension' => 'capability', 'name' => ['es' => 'Análisis de Datos', 'en' => 'Data Analysis']],
            ['slug' => 'desarrollo-software', 'dimension' => 'capability', 'name' => ['es' => 'Desarrollo de Software', 'en' => 'Software Development']],
            ['slug' => 'diseno-sistemas', 'dimension' => 'capability', 'name' => ['es' => 'Diseño de Sistemas', 'en' => 'Systems Design']],
            ['slug' => 'gestion-proyectos', 'dimension' => 'capability', 'name' => ['es' => 'Gestión de Proyectos', 'en' => 'Project Management']],
            ['slug' => 'investigacion-aplicada', 'dimension' => 'capability', 'name' => ['es' => 'Investigación Aplicada', 'en' => 'Applied Research']],
            ['slug' => 'automatizacion', 'dimension' => 'capability', 'name' => ['es' => 'Automatización', 'en' => 'Automation']],
            ['slug' => 'integracion-sistemas', 'dimension' => 'capability', 'name' => ['es' => 'Integración de Sistemas', 'en' => 'Systems Integration']],
            ['slug' => 'analisis-requisitos', 'dimension' => 'capability', 'name' => ['es' => 'Análisis de Requisitos', 'en' => 'Requirements Analysis']],

            // Tecnología
            ['slug' => 'python', 'dimension' => 'technology', 'name' => ['es' => 'Python', 'en' => 'Python']],
            ['slug' => 'laravel', 'dimension' => 'technology', 'name' => ['es' => 'Laravel', 'en' => 'Laravel']],
            ['slug' => 'vue-js', 'dimension' => 'technology', 'name' => ['es' => 'Vue.js', 'en' => 'Vue.js']],
            ['slug' => 'mysql', 'dimension' => 'technology', 'name' => ['es' => 'MySQL', 'en' => 'MySQL']],
            ['slug' => 'wordpress', 'dimension' => 'technology', 'name' => ['es' => 'WordPress', 'en' => 'WordPress']],
            ['slug' => 'php', 'dimension' => 'technology', 'name' => ['es' => 'PHP', 'en' => 'PHP']],
            ['slug' => 'javascript', 'dimension' => 'technology', 'name' => ['es' => 'JavaScript', 'en' => 'JavaScript']],
            ['slug' => 'tailwind-css', 'dimension' => 'technology', 'name' => ['es' => 'Tailwind CSS', 'en' => 'Tailwind CSS']],
            ['slug' => 'vite', 'dimension' => 'technology', 'name' => ['es' => 'Vite', 'en' => 'Vite']],
            ['slug' => 'git', 'dimension' => 'technology', 'name' => ['es' => 'Git', 'en' => 'Git']],

            // Metodología
            ['slug' => 'edse', 'dimension' => 'methodology', 'name' => ['es' => 'EDSE', 'en' => 'EDSE']],
            ['slug' => 'sdd', 'dimension' => 'methodology', 'name' => ['es' => 'SDD', 'en' => 'SDD']],
            ['slug' => 'scrum', 'dimension' => 'methodology', 'name' => ['es' => 'Scrum', 'en' => 'Scrum']],
            ['slug' => 'metodologia-cientifica', 'dimension' => 'methodology', 'name' => ['es' => 'Metodología Científica', 'en' => 'Scientific Method']],
            ['slug' => 'design-thinking', 'dimension' => 'methodology', 'name' => ['es' => 'Design Thinking', 'en' => 'Design Thinking']],
            ['slug' => 'desarrollo-agil', 'dimension' => 'methodology', 'name' => ['es' => 'Desarrollo Ágil', 'en' => 'Agile Development']],
        ];

        foreach ($categories as $cat) {
            Category::updateOrCreate(
                ['slug' => $cat['slug']],
                $cat
            );
        }
    }
}
