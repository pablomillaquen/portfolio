<?php

namespace Tests\Feature\Api\Seo;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class SeoControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
        Queue::fake();
    }

    public function test_get_seo_home_returns_200(): void
    {
        $response = $this->getJson('/api/seo/home');

        $response->assertOk()
            ->assertJsonStructure([
                'title',
                'description',
                'image',
                'url',
                'type',
                'locale',
                'alternates',
            ]);
    }

    public function test_get_seo_home_includes_locale(): void
    {
        $response = $this->getJson('/api/seo/home?locale=en');

        $response->assertOk()
            ->assertJsonPath('locale', 'en');
    }

    public function test_get_seo_home_defaults_to_es_locale(): void
    {
        $response = $this->getJson('/api/seo/home');

        $response->assertOk()
            ->assertJsonPath('locale', 'es');
    }
}
