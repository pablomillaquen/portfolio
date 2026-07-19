<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\ProjectMedia;

class ProjectMediaFactory extends Factory
{
    protected $model = ProjectMedia::class;

    public function definition(): array
    {
        return [
            'kind' => $this->faker->randomElement(['image', 'video', 'document']),
            'url' => $this->faker->url(),
            'caption' => ['es' => $this->faker->sentence(), 'en' => $this->faker->sentence()],
            'sort_order' => $this->faker->numberBetween(1, 10),
        ];
    }

    public function image(): static
    {
        return $this->state(fn () => ['kind' => 'image']);
    }

    public function video(): static
    {
        return $this->state(fn () => ['kind' => 'video']);
    }

    public function document(): static
    {
        return $this->state(fn () => ['kind' => 'document']);
    }
}
