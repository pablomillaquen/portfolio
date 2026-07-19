<?php

namespace Tests\Feature\Models;

use Tests\TestCase;
use App\Models\SocialLink;
use Database\Factories\SocialLinkFactory;

class SocialLinkTest extends TestCase
{
    public function test_can_create_social_link_via_factory(): void
    {
        $socialLink = SocialLink::factory()->create();

        $this->assertDatabaseHas('social_links', [
            'id' => $socialLink->id,
            'platform' => $socialLink->platform,
        ]);
    }

    public function test_fillable_fields(): void
    {
        $socialLink = new SocialLink();

        $this->assertEquals(
            ['platform', 'label', 'url', 'icon', 'sort_order', 'active'],
            $socialLink->getFillable()
        );
    }

    public function test_label_is_cast_to_array(): void
    {
        $socialLink = SocialLink::factory()->create([
            'label' => ['es' => 'Mi perfil', 'en' => 'My profile'],
        ]);

        $this->assertIsArray($socialLink->label);
        $this->assertEquals('Mi perfil', $socialLink->label['es']);
        $this->assertEquals('My profile', $socialLink->label['en']);
    }

    public function test_active_is_cast_to_boolean(): void
    {
        $socialLink = SocialLink::factory()->create(['active' => true]);

        $this->assertIsBool($socialLink->active);
        $this->assertTrue($socialLink->active);
    }

    public function test_active_state(): void
    {
        $socialLink = SocialLink::factory()->create(['active' => true]);

        $this->assertTrue($socialLink->active);
    }

    public function test_inactive_state(): void
    {
        $socialLink = SocialLink::factory()->inactive()->create();

        $this->assertFalse($socialLink->active);
    }

    public function test_sort_order_is_numeric(): void
    {
        $socialLink = SocialLink::factory()->create(['sort_order' => 7]);

        $this->assertEquals(7, $socialLink->sort_order);
        $this->assertIsInt($socialLink->sort_order);
    }

    public function test_stores_url(): void
    {
        $url = 'https://github.com/testuser';
        $socialLink = SocialLink::factory()->create(['url' => $url]);

        $this->assertEquals($url, $socialLink->url);
    }

    public function test_stores_platform(): void
    {
        $socialLink = SocialLink::factory()->create(['platform' => 'github']);

        $this->assertEquals('github', $socialLink->platform);
    }
}
