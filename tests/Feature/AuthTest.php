<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('migrate', ['--path' => 'vendor/laravel/passport/database/migrations']);
        $this->artisan('passport:client', ['--personal' => true, '--name' => 'TestClient', '--provider' => 'users', '--no-interaction' => true]);
    }

    public function test_user_can_login_and_receive_passport_token(): void
    {
        $user = User::factory()->create([
            'email' => 'test_login@cbsua.edu.ph',
            'password' => bcrypt('secret123'),
        ]);

        $response = $this->postJson('/api/auth/login', [
            'email' => 'test_login@cbsua.edu.ph',
            'password' => 'secret123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user' => ['id', 'name', 'email'],
                    'token',
                    'token_type',
                ],
            ]);

        $token = $response->json('data.token');

        // Verify protected me endpoint
        $meResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/auth/me');

        $meResponse->assertStatus(200)
            ->assertJsonPath('data.email', 'test_login@cbsua.edu.ph');

        // Verify logout
        $logoutResponse = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/auth/logout');

        $logoutResponse->assertStatus(200)
            ->assertJsonPath('success', true);
    }

    public function test_user_can_register_and_receive_token(): void
    {
        $response = $this->postJson('/api/auth/register', [
            'name' => 'Juan Dela Cruz',
            'email' => 'juan@cbsua.edu.ph',
            'password' => 'secret12345',
            'role_id' => 2,
            'campus_id' => 1,
        ]);

        $response->assertStatus(201)
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.user.email', 'juan@cbsua.edu.ph')
            ->assertJsonStructure([
                'data' => [
                    'token',
                    'token_type',
                ],
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'juan@cbsua.edu.ph',
        ]);
    }

    public function test_login_fails_with_invalid_credentials(): void
    {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'nonexistent@cbsua.edu.ph',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}
