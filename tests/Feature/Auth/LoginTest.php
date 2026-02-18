<?php

namespace Tests\Feature\Auth;

use App\Domains\Users\Models\User;
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    // use RefreshDatabase; // Use if you want to reset DB. For now I'll just create unique users.

    public function test_user_can_login_with_valid_credentials()
    {
        $email = 'test_login_' . time() . '@example.com';
        $user = User::create([
            'name' => 'Test User',
            'email' => $email,
            'password' => bcrypt('password'),
            'active' => true,
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'email' => $email,
            'password' => 'password',
            'device_name' => 'test_device',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'message',
                'data' => [
                    'user' => ['id', 'email', 'name'],
                    'token',
                ],
            ]);
    }

    public function test_user_cannot_login_with_invalid_credentials()
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'wrongpassword',
            'device_name' => 'test_device',
        ]);

        $response->assertStatus(422) // ValidationException standard code is 422
            ->assertJson([
                'message' => 'Invalid credentials provided.',
            ]);
    }
}
