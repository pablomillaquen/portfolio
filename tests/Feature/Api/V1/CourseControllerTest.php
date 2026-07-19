<?php

namespace Tests\Feature\Api\V1;

use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CourseControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();
    }

    private function setRawSlug(Course $course, string $slug): void
    {
        DB::table('courses')->where('id', $course->id)->update(['slug' => $slug]);
    }

    public function test_index_returns_published_courses(): void
    {
        Course::factory()->published()->count(3)->create(['issued_at' => now()]);

        $response = $this->getJson('/api/v1/courses');

        $response->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'slug', 'name', 'issuer', 'credential_id', 'url', 'issued_at'],
                ],
                'links',
                'meta',
            ]);
    }

    public function test_index_excludes_draft_courses(): void
    {
        Course::factory()->published()->count(2)->create(['issued_at' => now()]);
        Course::factory()->draft()->count(3)->create(['issued_at' => now()]);

        $response = $this->getJson('/api/v1/courses');

        $response->assertOk()
            ->assertJsonCount(2, 'data');
    }

    public function test_index_returns_empty_when_no_published_courses(): void
    {
        Course::factory()->draft()->count(2)->create(['issued_at' => now()]);

        $response = $this->getJson('/api/v1/courses');

        $response->assertOk()
            ->assertJsonCount(0, 'data');
    }

    public function test_index_orders_by_featured_desc_then_sort_order(): void
    {
        $regular = Course::factory()->published()->create(['featured' => false, 'sort_order' => 1, 'issued_at' => now()]);
        $featured = Course::factory()->published()->create(['featured' => true, 'sort_order' => 5, 'issued_at' => now()]);
        $anotherRegular = Course::factory()->published()->create(['featured' => false, 'sort_order' => 2, 'issued_at' => now()]);

        $response = $this->getJson('/api/v1/courses');

        $response->assertOk()
            ->assertJsonPath('data.0.id', $featured->id)
            ->assertJsonPath('data.1.id', $regular->id)
            ->assertJsonPath('data.2.id', $anotherRegular->id);
    }

    public function test_index_paginates_results(): void
    {
        Course::factory()->published()->count(20)->create(['issued_at' => now()]);

        $response = $this->getJson('/api/v1/courses?per_page=5');

        $response->assertOk()
            ->assertJsonCount(5, 'data')
            ->assertJsonPath('meta.per_page', 5)
            ->assertJsonPath('meta.total', 20);
    }

    public function test_index_returns_translated_name_by_locale(): void
    {
        Course::factory()->published()->create([
            'name' => ['es' => 'Curso ES', 'en' => 'Course EN'],
            'issued_at' => now(),
        ]);

        $response = $this->getJson('/api/v1/courses?locale=en');

        $response->assertOk()
            ->assertJsonPath('data.0.name', 'Course EN');
    }

    public function test_show_returns_course_by_slug(): void
    {
        $course = Course::factory()->published()->create(['issued_at' => now()]);
        $this->setRawSlug($course, 'my-course');

        $response = $this->getJson('/api/v1/courses/my-course');

        $response->assertOk()
            ->assertJsonPath('data.id', $course->id)
            ->assertJsonStructure([
                'data' => ['id', 'slug', 'name', 'issuer', 'credential_id', 'url', 'issued_at'],
            ]);
    }

    public function test_show_returns_404_for_nonexistent_slug(): void
    {
        $response = $this->getJson('/api/v1/courses/nonexistent-course');

        $response->assertNotFound()
            ->assertJsonPath('message', 'Course not found');
    }

    public function test_show_excludes_draft_courses(): void
    {
        $course = Course::factory()->draft()->create(['issued_at' => now()]);
        $this->setRawSlug($course, 'draft-course');

        $response = $this->getJson('/api/v1/courses/draft-course');

        $response->assertNotFound();
    }
}
