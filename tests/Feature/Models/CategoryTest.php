<?php

namespace Tests\Feature\Models;

use Tests\TestCase;
use App\Models\Category;
use App\Models\Project;
use App\Models\Season;
use Illuminate\Support\Facades\Queue;

class CategoryTest extends TestCase
{
    public function test_can_create_category_via_factory(): void
    {
        $category = Category::factory()->create(['slug' => 'test-category']);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
            'dimension' => $category->dimension,
        ]);
    }

    public function test_fillable_fields(): void
    {
        $category = new Category();

        $this->assertEquals(
            ['slug', 'dimension', 'name', 'description'],
            $category->getFillable()
        );
    }

    public function test_name_is_cast_to_array(): void
    {
        $category = Category::factory()->create([
            'slug' => 'name-test',
            'name' => ['es' => 'Nombre', 'en' => 'Name'],
        ]);

        $this->assertIsArray($category->name);
        $this->assertEquals('Nombre', $category->name['es']);
        $this->assertEquals('Name', $category->name['en']);
    }

    public function test_description_is_cast_to_array(): void
    {
        $category = Category::factory()->create([
            'slug' => 'desc-test',
            'description' => ['es' => 'Descripción', 'en' => 'Description'],
        ]);

        $this->assertIsArray($category->description);
        $this->assertEquals('Descripción', $category->description['es']);
    }

    public function test_belongs_to_many_projects(): void
    {
        Queue::fake();

        $category = Category::factory()->create(['slug' => 'projects-test']);
        $projects = Project::factory()->count(3)->create();

        $category->projects()->attach($projects->pluck('id'));

        $this->assertCount(3, $category->projects);
        $this->assertInstanceOf(Project::class, $category->projects->first());
    }

    public function test_belongs_to_many_seasons(): void
    {
        $category = Category::factory()->create(['slug' => 'seasons-test']);
        $season1 = Season::factory()->create(['slug' => 'season-one']);
        $season2 = Season::factory()->create(['slug' => 'season-two']);
        $seasons = collect([$season1, $season2]);

        $category->seasons()->attach($seasons->pluck('id'));

        $this->assertCount(2, $category->seasons);
        $this->assertInstanceOf(Season::class, $category->seasons->first());
    }

    public function test_category_has_pivot_tables(): void
    {
        Queue::fake();

        $category = Category::factory()->create(['slug' => 'pivot-test']);
        $project = Project::factory()->create();
        $season = Season::factory()->create(['slug' => 'pivot-season']);

        $category->projects()->attach($project->id);
        $category->seasons()->attach($season->id);

        $this->assertDatabaseHas('category_project', [
            'category_id' => $category->id,
            'project_id' => $project->id,
        ]);

        $this->assertDatabaseHas('category_season', [
            'category_id' => $category->id,
            'season_id' => $season->id,
        ]);
    }
}
