<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SocialLink;

class SocialLinkFactory extends Factory
{
    protected $model = SocialLink::class;

    public function definition(): array
    {
        return [
            'platform' => $this->faker->randomElement(['github', 'linkedin', 'twitter', 'email']),
            'label' => ['es' => $this->faker->word(), 'en' => $this->faker->word()],
            'url' => $this->faker->url(),
            'icon' => $this->faker->word(),
            'sort_order' => $this->faker->numberBetween(1, 10),
            'active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['active' => false]);
    }
}
