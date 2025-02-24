<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthTest extends TestCase {
    use RefreshDatabase;

    public function test_user_can_register() {
        $response = $this->postJson('/api/register', [
            'name'     => 'Test User',
            'email'    => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('users', ['email' => 'test@example.com']);
    }

    public function test_user_can_login() {
        $user = User::factory()->create(['password' => bcrypt('password')]);

        $response = $this->postJson('/api/login', [
            'email'    => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['user', 'token']);
    }

    public function test_authenticated_user_can_logout() {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->postJson('/api/logout');
        $response->assertStatus(200);
    }
}
