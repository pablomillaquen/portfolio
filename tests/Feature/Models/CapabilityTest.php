<?php

namespace Tests\Feature\Models;

use Tests\TestCase;
use App\Models\Capability;
use App\Models\Project;
use Illuminate\Support\Facades\Queue;

class CapabilityTest extends TestCase
{
    public function test_can_create_capability_via_factory(): void
    {
        $capability = Capability::factory()->create();

        $this->assertDatabaseHas('capabilities', [
            'id' => $capability->id,
            'slug' => $capability->slug,
        ]);
    }

    public function test_fillable_fields(): void
    {
        $capability = new Capability();

        $this->assertEquals(
            ['slug', 'name', 'description', 'sort_order'],
            $capability->getFillable()
        );
    }

    public function test_name_is_cast_to_array(): void
    {
        $capability = Capability::factory()->create([
            'name' => ['es' => 'Capacidad', 'en' => 'Capability'],
        ]);

        $this->assertIsArray($capability->name);
        $this->assertEquals('Capacidad', $capability->name['es']);
        $this->assertEquals('Capability', $capability->name['en']);
    }

    public function test_description_is_cast_to_array(): void
    {
        $capability = Capability::factory()->create([
            'description' => ['es' => 'Descripción', 'en' => 'Description'],
        ]);

        $this->assertIsArray($capability->description);
        $this->assertEquals('Descripción', $capability->description['es']);
    }

    public function test_sort_order_is_cast(): void
    {
        $capability = Capability::factory()->create(['sort_order' => 10]);

        $this->assertEquals(10, $capability->sort_order);
        $this->assertIsInt($capability->sort_order);
    }

    public function test_belongs_to_many_projects(): void
    {
        Queue::fake();

        $capability = Capability::factory()->create();
        $projects = Project::factory()->count(4)->create();

        $capability->projects()->attach($projects->pluck('id'));

        $this->assertCount(4, $capability->projects);
        $this->assertInstanceOf(Project::class, $capability->projects->first());
    }

    public function test_capability_without_projects(): void
    {
        $capability = Capability::factory()->create();

        $this->assertCount(0, $capability->projects);
    }

    public function test_pivot_table_data(): void
    {
        Queue::fake();

        $capability = Capability::factory()->create();
        $project = Project::factory()->create();

        $capability->projects()->attach($project->id);

        $this->assertDatabaseHas('capability_project', [
            'capability_id' => $capability->id,
            'project_id' => $project->id,
        ]);
    }
}
