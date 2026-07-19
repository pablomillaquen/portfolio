<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        return [
            'slug' => $this->faker->unique()->slug(),
            'dimension' => $this->faker->randomElement(['technical', 'creative', 'management']),
            'name' => ['es' => $this->faker->words(2, true), 'en' => $this->faker->words(2, true)],
            'description' => ['es' => $this->faker->sentence(), 'en' => $this->faker->sentence()],
        ];
    }

    public function published(): static
    {
        return $this->state(fn () => []);
    }
}
