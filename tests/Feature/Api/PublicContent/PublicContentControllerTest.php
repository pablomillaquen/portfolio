<?php

namespace Tests\Feature\Api\PublicContent;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class PublicContentControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
        Queue::fake();
    }

    public function test_get_home_content_returns_200(): void
    {
        $response = $this->getJson('/api/home');

        $response->assertOk()
            ->assertJsonStructure([
                'settings',
                'projects',
                'posts',
                'socialLinks',
            ]);
    }

    public function test_get_projects_returns_200(): void
    {
        $response = $this->getJson('/api/projects');

        $response->assertOk()
            ->assertJsonStructure([
                '*' => ['id', 'slug', 'status', 'title'],
            ]);
    }

    public function test_get_posts_returns_200(): void
    {
        $response = $this->getJson('/api/posts');

        $response->assertOk();
    }

    public function test_get_courses_returns_200(): void
    {
        $response = $this->getJson('/api/courses');

        $response->assertOk();
    }

    public function test_get_settings_returns_200(): void
    {
        $response = $this->getJson('/api/settings');

        $response->assertOk()
            ->assertJsonStructure([
                'settings',
                'socialLinks',
            ]);
    }

    public function test_get_capabilities_returns_200(): void
    {
        $response = $this->getJson('/api/capabilities');

        $response->assertOk()
            ->assertJsonStructure([
                'data',
            ]);
    }

    public function test_get_categories_returns_200(): void
    {
        $response = $this->getJson('/api/categories');

        $response->assertOk()
            ->assertJsonStructure([
                'data',
            ]);
    }

    public function test_get_seasons_returns_200(): void
    {
        $response = $this->getJson('/api/seasons');

        $response->assertOk()
            ->assertJsonStructure([
                'data',
            ]);
    }
}
