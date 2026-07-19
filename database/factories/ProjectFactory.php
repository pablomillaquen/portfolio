<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Project;

class ProjectFactory extends Factory
{
    protected $model = Project::class;

    public function definition(): array
    {
        return [
            'slug' => $this->faker->unique()->slug(),
            'status' => 'published',
            'featured' => false,
            'sort_order' => $this->faker->numberBetween(1, 20),
            'cover_image_url' => $this->faker->imageUrl(640, 480),
            'demo_url' => $this->faker->optional(0.5)->url(),
            'repository_url' => $this->faker->optional(0.5)->url(),
            'title' => ['es' => $this->faker->words(3, true), 'en' => $this->faker->words(3, true)],
            'summary' => ['es' => $this->faker->sentence(), 'en' => $this->faker->sentence()],
            'description' => ['es' => $this->faker->paragraphs(2, true), 'en' => $this->faker->paragraphs(2, true)],
            'details' => ['es' => $this->faker->paragraphs(3, true), 'en' => $this->faker->paragraphs(3, true)],
            'stack' => ['es' => $this->faker->words(5, true), 'en' => $this->faker->words(5, true)],
            'problem' => ['es' => $this->faker->paragraph(), 'en' => $this->faker->paragraph()],
            'approach' => ['es' => $this->faker->paragraph(), 'en' => $this->faker->paragraph()],
            'contribution' => ['es' => $this->faker->paragraph(), 'en' => $this->faker->paragraph()],
            'what_it_demonstrates' => ['es' => $this->faker->paragraph(), 'en' => $this->faker->paragraph()],
            'project_status' => $this->faker->randomElement(['active', 'completed', 'archived']),
            'published_at' => $this->faker->optional(0.8)->dateTimeBetween('-1 year', 'now'),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => [
            'status' => 'draft',
            'published_at' => null,
        ]);
    }

    public function published(): static
    {
        return $this->state(fn () => [
            'status' => 'published',
            'published_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn () => ['featured' => true]);
    }
}
