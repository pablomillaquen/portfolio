<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Capability;

class CapabilityFactory extends Factory
{
    protected $model = Capability::class;

    public function definition(): array
    {
        return [
            'slug' => $this->faker->unique()->slug(),
            'name' => ['es' => $this->faker->words(2, true), 'en' => $this->faker->words(2, true)],
            'description' => ['es' => $this->faker->sentence(), 'en' => $this->faker->sentence()],
            'sort_order' => $this->faker->numberBetween(1, 20),
        ];
    }
}
