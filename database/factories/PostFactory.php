<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Post;

class PostFactory extends Factory
{
    protected $model = Post::class;

    public function definition(): array
    {
        return [
            'type' => $this->faker->randomElement(['article', 'video']),
            'slug' => $this->faker->unique()->slug(),
            'status' => 'published',
            'featured' => false,
            'cover_image_url' => $this->faker->imageUrl(640, 480),
            'external_url' => $this->faker->optional(0.3)->url(),
            'share_enabled' => true,
            'title' => ['es' => $this->faker->words(4, true), 'en' => $this->faker->words(4, true)],
            'excerpt' => ['es' => $this->faker->sentence(), 'en' => $this->faker->sentence()],
            'content' => ['es' => $this->faker->paragraphs(3, true), 'en' => $this->faker->paragraphs(3, true)],
            'episode_number' => $this->faker->optional(0.3)->numberBetween(1, 50),
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

    public function scheduled(): static
    {
        return $this->state(fn () => [
            'status' => 'scheduled',
            'published_at' => $this->faker->dateTimeBetween('now', '+1 year'),
        ]);
    }

    public function featured(): static
    {
        return $this->state(fn () => ['featured' => true]);
    }
}
