<?php

namespace Tests\Feature\Api\Contact;

use App\Mail\ContactMail;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
        Queue::fake();
        Mail::fake();
    }

    public function test_submit_contact_message_returns_201(): void
    {
        $data = [
            'name' => $this->faker->name(),
            'email' => $this->faker->safeEmail(),
            'message' => $this->faker->paragraph(),
        ];

        $response = $this->postJson('/api/contact', $data);

        $response->assertCreated()
            ->assertJson([
                'message' => 'Message sent.',
            ]);

        $this->assertDatabaseHas('contact_messages', [
            'name' => $data['name'],
            'email' => $data['email'],
            'message' => $data['message'],
        ]);

        Mail::assertSent(ContactMail::class, function ($mail) use ($data) {
            return $mail->hasTo(env('CONTACT_EMAIL'))
                && $mail->name === $data['name']
                && $mail->email === $data['email']
                && $mail->body === $data['message'];
        });
    }

    public function test_submit_with_missing_fields_returns_422(): void
    {
        $response = $this->postJson('/api/contact', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'message']);
    }

    public function test_submit_with_invalid_email_returns_422(): void
    {
        $data = [
            'name' => $this->faker->name(),
            'email' => 'not-an-email',
            'message' => $this->faker->paragraph(),
        ];

        $response = $this->postJson('/api/contact', $data);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_mail_is_sent_on_valid_submission(): void
    {
        $data = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'message' => 'Hello, this is a test message.',
        ];

        $this->postJson('/api/contact', $data);

        Mail::assertSent(ContactMail::class);
    }
}
