<?php

namespace Tests\Feature\Api\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class AdminUploadControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
        Queue::fake();
        $this->admin = User::factory()->admin()->create();
    }

    public function test_store_uploads_image_and_returns_url(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('cover.png');

        $response = $this->actingAs($this->admin)->post('/api/admin/uploads', [
            'file' => $file,
        ]);

        $response->assertCreated()
            ->assertJsonStructure(['url', 'path', 'filename']);

        $this->assertStringStartsWith('/storage/uploads/', $response->json('url'));

        $path = $response->json('path');
        Storage::disk('public')->assertExists($path);
    }

    public function test_store_rejects_non_image(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->create('document.txt', 100);

        $response = $this->actingAs($this->admin)->postJson('/api/admin/uploads', [
            'file' => $file,
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['file']);
    }

    public function test_store_requires_file(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->admin)->postJson('/api/admin/uploads', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['file']);
    }

    public function test_unauthenticated_access_returns_401(): void
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('cover.png');

        $response = $this->post('/api/admin/uploads', [
            'file' => $file,
        ]);

        $response->assertUnauthorized();
    }

    public function test_non_admin_user_returns_401(): void
    {
        $user = User::factory()->create(['is_admin' => false]);

        $file = UploadedFile::fake()->image('cover.png');

        $response = $this->actingAs($user)->post('/api/admin/uploads', [
            'file' => $file,
        ]);

        $response->assertUnauthorized();
    }
}
