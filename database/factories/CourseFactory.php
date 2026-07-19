<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Course;

class CourseFactory extends Factory
{
    protected $model = Course::class;

    public function definition(): array
    {
        return [
            'slug' => $this->faker->unique()->slug(),
            'status' => 'published',
            'featured' => false,
            'sort_order' => $this->faker->numberBetween(1, 20),
            'name' => ['es' => $this->faker->words(3, true), 'en' => $this->faker->words(3, true)],
            'issuer' => $this->faker->company(),
            'issued_at' => $this->faker->optional(0.8)->dateTimeBetween('-2 years', 'now'),
            'credential_id' => $this->faker->optional(0.5)->uuid(),
            'url' => $this->faker->optional(0.7)->url(),
        ];
    }

    public function draft(): static
    {
        return $this->state(fn () => ['status' => 'draft']);
    }

    public function published(): static
    {
        return $this->state(fn () => ['status' => 'published']);
    }

    public function featured(): static
    {
        return $this->state(fn () => ['featured' => true]);
    }
}
