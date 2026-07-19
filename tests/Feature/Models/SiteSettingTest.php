<?php

namespace Tests\Feature\Models;

use Tests\TestCase;
use App\Models\SiteSetting;
use Database\Factories\SiteSettingFactory;

class SiteSettingTest extends TestCase
{
    public function test_can_create_site_setting_via_factory(): void
    {
        $setting = SiteSetting::factory()->create();

        $this->assertDatabaseHas('site_settings', [
            'id' => $setting->id,
            'key' => $setting->key,
        ]);
    }

    public function test_fillable_fields(): void
    {
        $setting = new SiteSetting();

        $this->assertEquals(
            ['key', 'value'],
            $setting->getFillable()
        );
    }

    public function test_value_is_cast_to_array(): void
    {
        $setting = SiteSetting::factory()->create([
            'value' => ['es' => 'Valor', 'en' => 'Value'],
        ]);

        $this->assertIsArray($setting->value);
        $this->assertEquals('Valor', $setting->value['es']);
        $this->assertEquals('Value', $setting->value['en']);
    }

    public function test_has_no_timestamps(): void
    {
        $setting = SiteSetting::factory()->create();

        $this->assertNull($setting->created_at);
        $this->assertNull($setting->updated_at);
    }

    public function test_key_value_storage(): void
    {
        $setting = SiteSetting::factory()->create([
            'key' => 'site_title',
            'value' => ['es' => 'Mi Sitio', 'en' => 'My Site'],
        ]);

        $this->assertEquals('site_title', $setting->key);
        $this->assertEquals(['es' => 'Mi Sitio', 'en' => 'My Site'], $setting->value);
    }

    public function test_defaults_factory_state(): void
    {
        $setting = SiteSetting::factory()->defaults()->create();

        $this->assertEquals('site_description', $setting->key);
        $this->assertArrayHasKey('es', $setting->value);
        $this->assertArrayHasKey('en', $setting->value);
    }

    public function test_can_update_value(): void
    {
        $setting = SiteSetting::factory()->create([
            'key' => 'site_footer',
            'value' => ['es' => 'Original', 'en' => 'Original'],
        ]);

        $setting->update([
            'value' => ['es' => 'Actualizado', 'en' => 'Updated'],
        ]);

        $this->assertEquals(['es' => 'Actualizado', 'en' => 'Updated'], $setting->fresh()->value);
    }
}
