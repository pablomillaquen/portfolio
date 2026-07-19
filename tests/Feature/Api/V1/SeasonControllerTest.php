<?php

namespace Tests\Feature\Api\V1;

use App\Models\Season;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SeasonControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_index_returns_all_seasons(): void
    {
        Season::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/seasons');

        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'slug', 'name', 'description'],
                ],
            ]);
    }

    public function test_index_returns_empty_when_no_seasons(): void
    {
        $response = $this->getJson('/api/v1/seasons');

        $response->assertOk()
            ->assertJsonCount(0, 'data');
    }

    public function test_index_orders_by_sort_order(): void
    {
        $second = Season::factory()->create(['sort_order' => 2]);
        $first = Season::factory()->create(['sort_order' => 1]);
        $third = Season::factory()->create(['sort_order' => 3]);

        $response = $this->getJson('/api/v1/seasons');

        $response->assertOk()
            ->assertJsonPath('data.0.id', $first->id)
            ->assertJsonPath('data.1.id', $second->id)
            ->assertJsonPath('data.2.id', $third->id);
    }

    public function test_index_filters_by_status(): void
    {
        Season::factory()->create(['status' => 'published']);
        Season::factory()->active()->create();
        Season::factory()->create(['status' => 'published']);

        $response = $this->getJson('/api/v1/seasons?status=active');

        $response->assertOk()
            ->assertJsonCount(1, 'data');
    }

    public function test_index_returns_empty_for_nonexistent_status(): void
    {
        Season::factory()->create(['status' => 'published']);

        $response = $this->getJson('/api/v1/seasons?status=archived');

        $response->assertOk()
            ->assertJsonCount(0, 'data');
    }

    public function test_index_returns_translated_name_by_locale(): void
    {
        Season::factory()->create([
            'name' => ['es' => 'Temporada ES', 'en' => 'Season EN'],
        ]);

        $response = $this->getJson('/api/v1/seasons?locale=en');

        $response->assertOk()
            ->assertJsonPath('data.0.name', 'Season EN');
    }

    public function test_index_returns_en_locale_by_default(): void
    {
        Season::factory()->create([
            'name' => ['es' => 'Temporada ES', 'en' => 'Season EN'],
        ]);

        $response = $this->getJson('/api/v1/seasons');

        $response->assertOk()
            ->assertJsonPath('data.0.name', 'Season EN');
    }
}
