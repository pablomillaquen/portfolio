<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Season;

class SeasonFactory extends Factory
{
    protected $model = Season::class;

    public function definition(): array
    {
        return [
            'slug' => $this->faker->unique()->slug(),
            'status' => 'published',
            'name' => ['es' => $this->faker->words(2, true), 'en' => $this->faker->words(2, true)],
            'description' => ['es' => $this->faker->sentence(), 'en' => $this->faker->sentence()],
            'sort_order' => $this->faker->numberBetween(1, 10),
        ];
    }

    public function active(): static
    {
        return $this->state(fn () => ['status' => 'active']);
    }
}
