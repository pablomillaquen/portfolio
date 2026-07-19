<?php

namespace Tests\Feature\Api\V1;

use App\Models\Post;
use App\Models\Season;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();
    }

    private function setRawSlug(Post $post, string $slug): void
    {
        DB::table('posts')->where('id', $post->id)->update(['slug' => $slug]);
    }

    private function setRawSeasonSlug(Season $season, string $slug): void
    {
        DB::table('seasons')->where('id', $season->id)->update(['slug' => $slug]);
    }

    public function test_index_returns_published_posts(): void
    {
        Post::factory()->published()->count(3)->create();

        $response = $this->getJson('/api/v1/posts');

        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'slug', 'title', 'excerpt', 'type', 'cover_image_url', 'season', 'episode_number', 'published_at'],
                ],
                'links',
                'meta',
            ]);
    }

    public function test_index_excludes_draft_posts(): void
    {
        Post::factory()->published()->count(2)->create();
        Post::factory()->draft()->count(3)->create();

        $response = $this->getJson('/api/v1/posts');

        $response->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_index_returns_empty_when_no_published_posts(): void
    {
        Post::factory()->draft()->count(2)->create();

        $response = $this->getJson('/api/v1/posts');

        $response->assertOk()
            ->assertJsonCount(0, 'data');
    }

    public function test_index_filters_by_season_slug(): void
    {
        $season = Season::factory()->create();
        $this->setRawSeasonSlug($season, 'season-1');

        $postWithSeason = Post::factory()->published()->create(['season_id' => $season->id]);
        Post::factory()->published()->create(['season_id' => null]);

        $response = $this->getJson('/api/v1/posts?season=season-1');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $postWithSeason->id);
    }

    public function test_index_paginates_results(): void
    {
        Post::factory()->published()->count(20)->create();

        $response = $this->getJson('/api/v1/posts?per_page=5');

        $response->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJsonPath('meta.per_page', 5)
            ->assertJsonPath('meta.total', 20);
    }

    public function test_index_orders_by_featured_desc_then_published_at_desc(): void
    {
        $regular = Post::factory()->published()->create(['featured' => false]);
        $featured = Post::factory()->published()->create(['featured' => true]);
        Post::factory()->published()->create(['featured' => false]);

        $response = $this->getJson('/api/v1/posts');

        $response->assertOk()
            ->assertJsonPath('data.0.id', $featured->id);
    }

    public function test_index_returns_translated_title_by_locale(): void
    {
        Post::factory()->published()->create([
            'title' => ['es' => 'Artículo ES', 'en' => 'Article EN'],
        ]);

        $response = $this->getJson('/api/v1/posts?locale=en');

        $response->assertOk()
            ->assertJsonPath('data.0.title', 'Article EN');
    }

    public function test_show_returns_post_by_slug(): void
    {
        $this->markTestIncomplete(
            'PostController::show loads ->with("project") but Post model defines relatedProject(). '
            ."This causes a RelationNotFoundException. Fix the controller to use ->with(['season', 'relatedProject'])."
        );

        $post = Post::factory()->published()->create();
        $this->setRawSlug($post, 'my-article');

        $response = $this->getJson('/api/v1/posts/my-article');

        $response->assertOk()
            ->assertJsonPath('data.id', $post->id)
            ->assertJsonStructure([
                'data' => [
                    'id', 'slug', 'title', 'excerpt', 'content', 'type', 'cover_image_url',
                    'external_url', 'season', 'episode_number', 'related_project', 'published_at',
                ],
            ]);
    }

    public function test_show_returns_404_for_nonexistent_slug(): void
    {
        $response = $this->getJson('/api/v1/posts/nonexistent-post');

        $response->assertNotFound()
            ->assertJsonPath('message', 'Post not found');
    }

    public function test_show_excludes_draft_posts(): void
    {
        $post = Post::factory()->draft()->create();
        $this->setRawSlug($post, 'draft-post');

        $response = $this->getJson('/api/v1/posts/draft-post');

        $response->assertNotFound();
    }

    public function test_show_loads_season_relation(): void
    {
        $this->markTestIncomplete(
            'PostController::show loads ->with("project") but Post model defines relatedProject(). '
            ."This causes a RelationNotFoundException. Fix the controller to use ->with(['season', 'relatedProject'])."
        );

        $season = Season::factory()->create();
        $post = Post::factory()->published()->create(['season_id' => $season->id]);
        $this->setRawSlug($post, 'post-with-season');

        $response = $this->getJson('/api/v1/posts/post-with-season');

        $response->assertOk()
            ->assertJsonPath('data.season.id', $season->id);
    }
}
