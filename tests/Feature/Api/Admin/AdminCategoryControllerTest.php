<?php

namespace Tests\Feature\Api\Admin;

use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class AdminCategoryControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
        Queue::fake();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_index_returns_200(): void
    {
        Category::factory()->count(3)->create([
            'dimension' => $this->faker->randomElement(['domain', 'capability', 'technology', 'methodology']),
        ]);

        $response = $this->actingAs($this->admin)->getJson('/api/admin/categories');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'slug', 'dimension', 'name', 'description'],
                ],
            ]);
    }

    public function test_store_creates_category(): void
    {
        $data = [
            'slug' => 'test-category',
            'dimension' => 'domain',
            'name' => ['en' => 'Test Category', 'es' => 'Categoria de Prueba'],
            'description' => ['en' => 'A test category', 'es' => 'Una categoria de prueba'],
        ];

        $response = $this->actingAs($this->admin)->postJson('/api/admin/categories', $data);

        $response->assertCreated()
            ->assertJsonStructure([
                'data' => ['id', 'slug', 'dimension', 'name'],
            ]);

        $this->assertDatabaseHas('categories', [
            'dimension' => 'domain',
        ]);

        $category = Category::query()->where('dimension', 'domain')->first();
        $this->assertNotNull($category);
        $this->assertEquals('domain', $category->dimension);
    }

    public function test_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->admin)->postJson('/api/admin/categories', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['slug', 'dimension', 'name', 'name.en', 'name.es']);
    }

    public function test_store_validates_unique_slug(): void
    {
        $this->actingAs($this->admin)->postJson('/api/admin/categories', [
            'slug' => 'unique-test',
            'dimension' => 'domain',
            'name' => ['en' => 'First', 'es' => 'Primera'],
        ]);

        $duplicateResponse = $this->actingAs($this->admin)->postJson('/api/admin/categories', [
            'slug' => 'unique-test',
            'dimension' => 'capability',
            'name' => ['en' => 'Second', 'es' => 'Segunda'],
        ]);

        $duplicateResponse->assertStatus(500);
    }

    public function test_store_validates_dimension_values(): void
    {
        $data = [
            'slug' => 'new-category',
            'dimension' => 'invalid-dimension',
            'name' => ['en' => 'New', 'es' => 'Nueva'],
        ];

        $response = $this->actingAs($this->admin)->postJson('/api/admin/categories', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['dimension']);
    }

    public function test_update_modifies_category(): void
    {
        $category = Category::factory()->create([
            'slug' => ['en' => 'original', 'es' => 'original'],
            'dimension' => 'domain',
        ]);

        $data = [
            'slug' => 'updated',
            'dimension' => 'technology',
            'name' => ['en' => 'Updated', 'es' => 'Actualizada'],
        ];

        $response = $this->actingAs($this->admin)->putJson("/api/admin/categories/{$category->id}", $data);

        $response->assertOk()
            ->assertJsonPath('data.dimension', 'technology');

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'dimension' => 'technology',
        ]);
    }

    public function test_destroy_deletes_category(): void
    {
        $category = Category::factory()->create();

        $response = $this->actingAs($this->admin)->deleteJson("/api/admin/categories/{$category->id}");

        $response->assertOk()
            ->assertJson([
                'message' => 'Category deleted successfully',
            ]);

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_unauthenticated_access_returns_401(): void
    {
        $response = $this->getJson('/api/admin/categories');

        $response->assertUnauthorized();
    }

    public function test_non_admin_user_returns_401(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)->getJson('/api/admin/categories');

        $response->assertUnauthorized();
    }
}
