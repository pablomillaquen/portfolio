<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\SiteSetting;

class SiteSettingFactory extends Factory
{
    protected $model = SiteSetting::class;

    public function definition(): array
    {
        return [
            'key' => $this->faker->unique()->uuid(),
            'value' => ['es' => $this->faker->sentence(), 'en' => $this->faker->sentence()],
        ];
    }

    public function defaults(): static
    {
        return $this->state(fn () => [
            'key' => 'site_description',
            'value' => [
                'es' => $this->faker->paragraph(),
                'en' => $this->faker->paragraph(),
            ],
        ]);
    }
}
