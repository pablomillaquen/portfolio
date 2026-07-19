<?php

namespace Tests\Feature\Api\V1;

use App\Models\Category;
use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ProjectControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();
    }

    private function setRawSlug(Project $project, string $slug): void
    {
        DB::table('projects')->where('id', $project->id)->update(['slug' => $slug]);
    }

    public function test_index_returns_published_projects(): void
    {
        Project::factory()->published()->count(3)->create();

        $response = $this->getJson('/api/v1/projects');

        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'slug', 'title', 'summary', 'cover_image_url', 'featured', 'categories', 'published_at'],
                ],
                'links',
                'meta',
            ]);
    }

    public function test_index_excludes_draft_projects(): void
    {
        Project::factory()->published()->count(2)->create();
        Project::factory()->draft()->count(3)->create();

        $response = $this->getJson('/api/v1/projects');

        $response->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_index_returns_empty_when_no_published_projects(): void
    {
        Project::factory()->draft()->count(2)->create();

        $response = $this->getJson('/api/v1/projects');

        $response->assertOk()
            ->assertJsonCount(0, 'data');
    }

    public function test_index_orders_by_featured_desc_then_sort_order(): void
    {
        $regular = Project::factory()->published()->create(['featured' => false, 'sort_order' => 1]);
        $featured = Project::factory()->published()->create(['featured' => true, 'sort_order' => 5]);
        $anotherRegular = Project::factory()->published()->create(['featured' => false, 'sort_order' => 2]);

        $response = $this->getJson('/api/v1/projects');

        $response->assertOk()
            ->assertJsonPath('data.0.id', $featured->id)
            ->assertJsonPath('data.1.id', $regular->id)
            ->assertJsonPath('data.2.id', $anotherRegular->id);
    }

    public function test_index_filters_by_category_slug(): void
    {
        $category = Category::factory()->create();
        DB::table('categories')->where('id', $category->id)->update(['slug' => 'laravel']);

        $projectWithCategory = Project::factory()->published()->create();
        $projectWithCategory->categories()->attach($category->id);

        Project::factory()->published()->create();

        $response = $this->getJson('/api/v1/projects?category=laravel');

        $response->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $projectWithCategory->id);
    }

    public function test_index_filters_by_multiple_category_slugs(): void
    {
        $cat1 = Category::factory()->create();
        DB::table('categories')->where('id', $cat1->id)->update(['slug' => 'laravel']);

        $cat2 = Category::factory()->create();
        DB::table('categories')->where('id', $cat2->id)->update(['slug' => 'vue']);

        $project1 = Project::factory()->published()->create();
        $project1->categories()->attach($cat1->id);

        $project2 = Project::factory()->published()->create();
        $project2->categories()->attach($cat2->id);

        Project::factory()->published()->create();

        $response = $this->getJson('/api/v1/projects?category=laravel,vue');

        $response->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_index_paginates_results(): void
    {
        Project::factory()->published()->count(20)->create();

        $response = $this->getJson('/api/v1/projects?per_page=5');

        $response->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJsonPath('meta.per_page', 5)
            ->assertJsonPath('meta.total', 20);
    }

    public function test_index_returns_translated_title_by_locale(): void
    {
        Project::factory()->published()->create([
            'title' => ['es' => 'Proyecto ES', 'en' => 'Project EN'],
        ]);

        $response = $this->getJson('/api/v1/projects?locale=en');

        $response->assertOk()
            ->assertJsonPath('data.0.title', 'Project EN');
    }

    public function test_show_returns_project_by_slug(): void
    {
        $project = Project::factory()->published()->create();
        $this->setRawSlug($project, 'my-project');

        $response = $this->getJson('/api/v1/projects/my-project');

        $response->assertOk()
            ->assertJsonPath('data.id', $project->id)
            ->assertJsonStructure([
                'data' => [
                    'id', 'slug', 'title', 'summary', 'description', 'problem', 'approach',
                    'contribution', 'what_it_demonstrates', 'stack', 'demo_url', 'repository_url',
                    'cover_image_url', 'featured', 'categories', 'capabilities', 'media',
                    'related_posts', 'published_at',
                ],
            ]);
    }

    public function test_show_returns_404_for_nonexistent_slug(): void
    {
        $response = $this->getJson('/api/v1/projects/nonexistent-project');

        $response->assertNotFound()
            ->assertJsonPath('message', 'Project not found');
    }

    public function test_show_excludes_draft_projects(): void
    {
        $project = Project::factory()->draft()->create();
        $this->setRawSlug($project, 'draft-project');

        $response = $this->getJson('/api/v1/projects/draft-project');

        $response->assertNotFound();
    }

    public function test_show_loads_categories_and_capabilities(): void
    {
        $category = Category::factory()->create();
        $project = Project::factory()->published()->create();
        $this->setRawSlug($project, 'project-with-categories');
        $project->categories()->attach($category->id);

        $response = $this->getJson('/api/v1/projects/project-with-categories');

        $response->assertOk()
            ->assertJsonCount(1, 'data.categories');
    }
}
