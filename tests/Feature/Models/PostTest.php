<?php

namespace Tests\Feature\Models;

use App\Models\Post;
use App\Models\Project;
use App\Models\Season;
use Database\Factories\PostFactory;
use Database\Factories\ProjectFactory;
use Database\Factories\SeasonFactory;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class PostTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
    }

    public function test_can_create_via_factory(): void
    {
        $post = Post::factory()->create();

        $this->assertNotNull($post);
        $this->assertDatabaseHas('posts', ['id' => $post->id]);
    }

    public function test_fillable_fields(): void
    {
        $data = [
            'type' => 'video',
            'slug' => ['es' => 'test-post', 'en' => 'test-post'],
            'status' => 'published',
            'featured' => true,
            'cover_image_url' => 'https://example.com/cover.jpg',
            'external_url' => 'https://youtube.com/watch?v=123',
            'share_enabled' => false,
            'title' => ['es' => 'Título', 'en' => 'Title'],
            'excerpt' => ['es' => 'Extracto', 'en' => 'Excerpt'],
            'content' => ['es' => 'Contenido', 'en' => 'Content'],
            'episode_number' => 3,
            'published_at' => now(),
        ];

        $post = Post::factory()->create($data);

        $this->assertEquals($data['type'], $post->type);
        $this->assertEquals($data['slug'], $post->slug);
        $this->assertEquals($data['status'], $post->status);
        $this->assertTrue($post->featured);
        $this->assertEquals($data['cover_image_url'], $post->cover_image_url);
        $this->assertEquals($data['external_url'], $post->external_url);
        $this->assertFalse($post->share_enabled);
        $this->assertEquals($data['title'], $post->title);
        $this->assertEquals($data['excerpt'], $post->excerpt);
        $this->assertEquals($data['content'], $post->content);
        $this->assertEquals($data['episode_number'], $post->episode_number);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $post->published_at);
    }

    public function test_casts(): void
    {
        $post = Post::factory()->create([
            'featured' => true,
            'share_enabled' => false,
            'title' => ['es' => 'Título', 'en' => 'Title'],
            'excerpt' => ['es' => 'Extracto', 'en' => 'Excerpt'],
            'content' => ['es' => 'Contenido', 'en' => 'Content'],
            'published_at' => now(),
        ]);

        $this->assertIsArray($post->slug);
        $this->assertIsBool($post->featured);
        $this->assertTrue($post->featured);
        $this->assertIsBool($post->share_enabled);
        $this->assertFalse($post->share_enabled);
        $this->assertIsArray($post->title);
        $this->assertIsArray($post->excerpt);
        $this->assertIsArray($post->content);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $post->published_at);
    }

    public function test_relationship_season(): void
    {
        $season = SeasonFactory::new()->create();
        $post = Post::factory()->create(['season_id' => $season->id]);

        $this->assertNotNull($post->season);
        $this->assertInstanceOf(Season::class, $post->season);
        $this->assertEquals($season->id, $post->season->id);
    }

    public function test_relationship_related_project(): void
    {
        $project = ProjectFactory::new()->create();
        $post = Post::factory()->create(['related_project_id' => $project->id]);

        $this->assertNotNull($post->relatedProject);
        $this->assertInstanceOf(Project::class, $post->relatedProject);
        $this->assertEquals($project->id, $post->relatedProject->id);
    }

    public function test_relationship_projects(): void
    {
        $post = Post::factory()->create();
        $projects = ProjectFactory::new()->count(2)->create();
        $post->projects()->attach($projects->pluck('id'));

        $this->assertCount(2, $post->projects);
        $this->assertInstanceOf(Project::class, $post->projects->first());
    }

    public function test_state_draft(): void
    {
        $post = Post::factory()->draft()->create();

        $this->assertEquals('draft', $post->status);
        $this->assertNull($post->published_at);
    }

    public function test_state_published(): void
    {
        $post = Post::factory()->published()->create();

        $this->assertEquals('published', $post->status);
        $this->assertNotNull($post->published_at);
    }

    public function test_state_scheduled(): void
    {
        $post = Post::factory()->scheduled()->create();

        $this->assertEquals('scheduled', $post->status);
        $this->assertNotNull($post->published_at);
        $this->assertTrue($post->published_at->isFuture());
    }

    public function test_state_featured(): void
    {
        $post = Post::factory()->featured()->create();

        $this->assertTrue($post->featured);
    }
}
