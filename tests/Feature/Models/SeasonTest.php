<?php

namespace Tests\Feature\Models;

use Tests\TestCase;
use App\Models\Season;
use App\Models\Post;
use Illuminate\Support\Facades\Queue;

class SeasonTest extends TestCase
{
    public function test_can_create_season_via_factory(): void
    {
        $season = Season::factory()->create(['slug' => 'test-season']);

        $this->assertDatabaseHas('seasons', [
            'id' => $season->id,
            'status' => $season->status,
        ]);
    }

    public function test_fillable_fields(): void
    {
        $season = new Season();

        $this->assertEquals(
            ['slug', 'status', 'name', 'description', 'sort_order'],
            $season->getFillable()
        );
    }

    public function test_name_is_cast_to_array(): void
    {
        $season = Season::factory()->create([
            'slug' => 'name-test',
            'name' => ['es' => 'Temporada', 'en' => 'Season'],
        ]);

        $this->assertIsArray($season->name);
        $this->assertEquals('Temporada', $season->name['es']);
        $this->assertEquals('Season', $season->name['en']);
    }

    public function test_description_is_cast_to_array(): void
    {
        $season = Season::factory()->create([
            'slug' => 'desc-test',
            'description' => ['es' => 'Descripción', 'en' => 'Description'],
        ]);

        $this->assertIsArray($season->description);
        $this->assertEquals('Descripción', $season->description['es']);
    }

    public function test_has_many_posts(): void
    {
        Queue::fake();

        $season = Season::factory()->create(['slug' => 'posts-test']);
        Post::factory()->count(3)->create(['season_id' => $season->id]);

        $this->assertCount(3, $season->posts);
        $this->assertInstanceOf(Post::class, $season->posts->first());
        $this->assertEquals($season->id, $season->posts->first()->season_id);
    }

    public function test_season_without_posts(): void
    {
        $season = Season::factory()->create(['slug' => 'empty-test']);

        $this->assertCount(0, $season->posts);
    }

    public function test_sort_order_is_cast(): void
    {
        $season = Season::factory()->create(['slug' => 'sort-test', 'sort_order' => 5]);

        $this->assertEquals(5, $season->sort_order);
        $this->assertIsInt($season->sort_order);
    }

    public function test_active_state(): void
    {
        $season = Season::factory()->active()->create(['slug' => 'active-test']);

        $this->assertEquals('active', $season->status);
    }
}
