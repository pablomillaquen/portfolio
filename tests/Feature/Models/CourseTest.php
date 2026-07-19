<?php

namespace Tests\Feature\Models;

use App\Models\Course;
use Database\Factories\CourseFactory;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CourseTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
    }

    public function test_can_create_via_factory(): void
    {
        $course = Course::factory()->create(['issued_at' => now()]);

        $this->assertNotNull($course);
        $this->assertDatabaseHas('courses', ['id' => $course->id]);
    }

    public function test_fillable_fields(): void
    {
        $data = [
            'slug' => ['es' => 'curso-laravel', 'en' => 'laravel-course'],
            'status' => 'draft',
            'featured' => true,
            'sort_order' => 7,
            'name' => ['es' => 'Curso de Laravel', 'en' => 'Laravel Course'],
            'issuer' => 'Udemy',
            'issued_at' => now()->subMonths(3),
            'credential_id' => 'ABC-123-XYZ',
            'url' => 'https://udemy.com/course/example',
        ];

        $course = Course::factory()->create($data);

        $this->assertEquals($data['slug'], $course->slug);
        $this->assertEquals($data['status'], $course->status);
        $this->assertTrue($course->featured);
        $this->assertEquals($data['sort_order'], $course->sort_order);
        $this->assertEquals($data['name'], $course->name);
        $this->assertEquals($data['issuer'], $course->issuer);
        $this->assertEquals($data['credential_id'], $course->credential_id);
        $this->assertEquals($data['url'], $course->url);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $course->issued_at);
    }

    public function test_casts(): void
    {
        $course = Course::factory()->create([
            'featured' => true,
            'name' => ['es' => 'Curso', 'en' => 'Course'],
            'issued_at' => now()->subYear(),
        ]);

        $this->assertIsArray($course->slug);
        $this->assertIsBool($course->featured);
        $this->assertTrue($course->featured);
        $this->assertIsArray($course->name);
        $this->assertInstanceOf(\Illuminate\Support\Carbon::class, $course->issued_at);
    }

    public function test_state_draft(): void
    {
        $course = Course::factory()->draft()->create(['issued_at' => now()]);

        $this->assertEquals('draft', $course->status);
    }

    public function test_state_published(): void
    {
        $course = Course::factory()->published()->create(['issued_at' => now()]);

        $this->assertEquals('published', $course->status);
    }

    public function test_state_featured(): void
    {
        $course = Course::factory()->featured()->create(['issued_at' => now()]);

        $this->assertTrue($course->featured);
    }
}
