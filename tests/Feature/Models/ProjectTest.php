<?php

namespace Tests\Feature\Models;

use App\Models\Capability;
use App\Models\Category;
use App\Models\Post;
use App\Models\Project;
use App\Models\ProjectMedia;
use Database\Factories\CapabilityFactory;
use Database\Factories\CategoryFactory;
use Database\Factories\PostFactory;
use Database\Factories\ProjectFactory;
use Database\Factories\ProjectMediaFactory;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class ProjectTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
    }

    public function test_can_create_via_factory(): void
    {
        $project = Project::factory()->create();

        $this->assertNotNull($project);
        $this->assertDatabaseHas('projects', ['id' => $project->id]);
    }

    public function test_fillable_fields(): void
    {
        $data = [
            'slug' => 'test-slug',
            'status' => 'draft',
            'featured' => true,
            'sort_order' => 5,
            'cover_image_url' => 'https://example.com/image.jpg',
            'demo_url' => 'https://demo.example.com',
            'repository_url' => 'https://github.com/example',
            'title' => ['es' => 'Título', 'en' => 'Title'],
            'summary' => ['es' => 'Resumen', 'en' => 'Summary'],
            'description' => ['es' => 'Descripción', 'en' => 'Description'],
            'details' => ['es' => 'Detalles', 'en' => 'Details'],
            'stack' => ['es' => 'Laravel, Vue', 'en' => 'Laravel, Vue'],
            'problem' => ['es' => 'Problema', 'en' => 'Problem'],
            'approach' => ['es' => 'Enfoque', 'en' => 'Approach'],
            'contribution' => ['es' => 'Contribución', 'en' => 'Contribution'],
            'what_it_demonstrates' => ['es' => 'Demo', 'en' => 'Demo'],
            'project_status' => 'active',
            'published_at' => now(),
        ];

        $project = Project::factory()->create($data);

        $this->assertEquals($data['slug'], $project->slug);
        $this->assertEquals($data['status'], $project->status);
        $this->assertTrue($project->featured);
        $this->assertEquals($data['sort_order'], $project->sort_order);
        $this->assertEquals($data['cover_image_url'], $project->cover_image_url);
        $this->assertEquals($data['demo_url'], $project->demo_url);
        $this->assertEquals($data['repository_url'], $project->repository_url);
        $this->assertEquals($data['title'], $project->title);
        $this->assertEquals($data['summary'], $project->summary);
        $this->assertEquals($data['description'], $project->description);
        $this->assertEquals($data['details'], $project->details);
        $this->assertEquals($data['stack'], $project->stack);
        $this->assertEquals($data['problem'], $project->problem);
        $this->assertEquals($data['approach'], $project->approach);
        $this->assertEquals($data['contribution'], $project->contribution);
        $this->assertEquals($data['what_it_demonstrates'], $project->what_it_demonstrates);
        $this->assertEquals($data['project_status'], $project->project_status);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $project->published_at);
    }

    public function test_casts(): void
    {
        $project = Project::factory()->create([
            'title' => ['es' => 'Título', 'en' => 'Title'],
            'featured' => true,
            'published_at' => now(),
        ]);

        $this->assertIsString($project->slug);
        $this->assertIsArray($project->title);
        $this->assertIsArray($project->summary);
        $this->assertIsArray($project->description);
        $this->assertIsArray($project->details);
        $this->assertIsArray($project->stack);
        $this->assertIsArray($project->problem);
        $this->assertIsArray($project->approach);
        $this->assertIsArray($project->contribution);
        $this->assertIsArray($project->what_it_demonstrates);
        $this->assertIsBool($project->featured);
        $this->assertTrue($project->featured);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $project->published_at);
    }

    public function test_relationship_media(): void
    {
        $project = Project::factory()->create();
        ProjectMediaFactory::new()->count(3)->create(['project_id' => $project->id]);

        $this->assertCount(3, $project->media);
        $this->assertInstanceOf(ProjectMedia::class, $project->media->first());
    }

    public function test_relationship_posts(): void
    {
        $project = Project::factory()->create();
        $posts = PostFactory::new()->count(2)->create();
        $project->posts()->attach($posts->pluck('id'));

        $this->assertCount(2, $project->posts);
        $this->assertInstanceOf(Post::class, $project->posts->first());
    }

    public function test_relationship_categories(): void
    {
        $project = Project::factory()->create();
        $categories = CategoryFactory::new()->count(2)->create();
        $project->categories()->attach($categories->pluck('id'));

        $this->assertCount(2, $project->categories);
        $this->assertInstanceOf(Category::class, $project->categories->first());
    }

    public function test_relationship_capabilities(): void
    {
        $project = Project::factory()->create();
        $capabilities = CapabilityFactory::new()->count(2)->create();
        $project->capabilities()->attach($capabilities->pluck('id'));

        $this->assertCount(2, $project->capabilities);
        $this->assertInstanceOf(Capability::class, $project->capabilities->first());
    }

    public function test_state_draft(): void
    {
        $project = Project::factory()->draft()->create();

        $this->assertEquals('draft', $project->status);
        $this->assertNull($project->published_at);
    }

    public function test_state_published(): void
    {
        $project = Project::factory()->published()->create();

        $this->assertEquals('published', $project->status);
        $this->assertNotNull($project->published_at);
    }

    public function test_state_featured(): void
    {
        $project = Project::factory()->featured()->create();

        $this->assertTrue($project->featured);
    }
}
