<?php

namespace Tests\Feature\Api\V1;

use App\Models\Capability;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CapabilityControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_index_returns_all_capabilities(): void
    {
        Capability::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/capabilities');

        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'slug', 'name', 'description'],
                ],
            ]);
    }

    public function test_index_returns_empty_when_no_capabilities(): void
    {
        $response = $this->getJson('/api/v1/capabilities');

        $response->assertOk()
            ->assertJsonCount(0, 'data');
    }

    public function test_index_orders_by_sort_order(): void
    {
        $second = Capability::factory()->create(['sort_order' => 2]);
        $first = Capability::factory()->create(['sort_order' => 1]);
        $third = Capability::factory()->create(['sort_order' => 3]);

        $response = $this->getJson('/api/v1/capabilities');

        $response->assertOk()
            ->assertJsonPath('data.0.id', $first->id)
            ->assertJsonPath('data.1.id', $second->id)
            ->assertJsonPath('data.2.id', $third->id);
    }

    public function test_index_returns_translated_name_by_locale(): void
    {
        Capability::factory()->create([
            'name' => ['es' => 'Capacidad ES', 'en' => 'Capability EN'],
        ]);

        $response = $this->getJson('/api/v1/capabilities?locale=en');

        $response->assertOk()
            ->assertJsonPath('data.0.name', 'Capability EN');
    }

    public function test_index_returns_en_locale_by_default(): void
    {
        Capability::factory()->create([
            'name' => ['es' => 'Capacidad ES', 'en' => 'Capability EN'],
        ]);

        $response = $this->getJson('/api/v1/capabilities');

        $response->assertOk()
            ->assertJsonPath('data.0.name', 'Capability EN');
    }
}
