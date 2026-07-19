<?php

namespace Tests\Feature\Api\V1;

use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();
    }

    public function test_index_returns_all_categories(): void
    {
        Category::factory()->count(3)->create();

        $response = $this->getJson('/api/v1/categories');

        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'slug', 'name', 'description'],
                ],
            ]);
    }

    public function test_index_returns_empty_when_no_categories(): void
    {
        $response = $this->getJson('/api/v1/categories');

        $response->assertOk()
            ->assertJsonCount(0, 'data');
    }

    public function test_index_filters_by_dimension(): void
    {
        Category::factory()->create(['dimension' => 'technical']);
        Category::factory()->create(['dimension' => 'creative']);
        Category::factory()->create(['dimension' => 'technical']);

        $response = $this->getJson('/api/v1/categories?dimension=technical');

        $response->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_index_returns_empty_for_nonexistent_dimension(): void
    {
        Category::factory()->create(['dimension' => 'technical']);

        $response = $this->getJson('/api/v1/categories?dimension=management');

        $response->assertOk()
            ->assertJsonCount(0, 'data');
    }

    public function test_index_returns_translated_name_by_locale(): void
    {
        Category::factory()->create([
            'name' => ['es' => 'Nombre ES', 'en' => 'Name EN'],
        ]);

        $response = $this->getJson('/api/v1/categories?locale=en');

        $response->assertOk()
            ->assertJsonPath('data.0.name', 'Name EN');
    }

    public function test_index_returns_en_locale_by_default(): void
    {
        Category::factory()->create([
            'name' => ['es' => 'Nombre ES', 'en' => 'Name EN'],
        ]);

        $response = $this->getJson('/api/v1/categories');

        $response->assertOk()
            ->assertJsonPath('data.0.name', 'Name EN');
    }
}
