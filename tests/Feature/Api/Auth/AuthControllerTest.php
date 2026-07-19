<?php

namespace Tests\Feature\Api\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        Event::fake();
        Queue::fake();
    }

    public function test_login_with_valid_credentials(): void
    {
        $password = 'password123';
        $user = User::factory()->create([
            'password' => bcrypt($password),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => $password,
        ]);

        $response->assertOk()
            ->assertJsonStructure([
                'user' => ['id', 'name', 'email'],
            ]);
    }

    public function test_login_with_invalid_credentials_returns_422(): void
    {
        $user = User::factory()->create();

        $response = $this->postJson('/api/auth/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'message' => 'Invalid credentials.',
            ]);
    }

    public function test_logout_returns_ok_even_without_auth(): void
    {
        $response = $this->postJson('/api/auth/logout');

        $response->assertOk()
            ->assertJson([
                'ok' => true,
            ]);
    }

    public function test_logout_succeeds_when_authenticated(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/auth/logout');

        $response->assertOk()
            ->assertJson([
                'ok' => true,
            ]);
    }

    public function test_current_user_returns_authenticated_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->getJson('/api/auth/me');

        $response->assertOk()
            ->assertJsonPath('user.id', $user->id)
            ->assertJsonPath('user.email', $user->email);
    }

    public function test_current_user_returns_null_when_unauthenticated(): void
    {
        $response = $this->getJson('/api/auth/me');

        $response->assertOk()
            ->assertJsonPath('user', null);
    }
}
