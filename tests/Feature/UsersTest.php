<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function test_it_can_register_successfully()
    {
        $payload = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => '12345678'
        ];

        $response = $this->postJson('/api/v1/register', $payload)
            ->assertSee([
                $payload['name'],
                $payload['email']
            ]);

        $this->assertDatabaseHas('users', [
            'email' => $payload['email'],
        ]);

        $response->assertStatus(201);
    }

    /** @test */
    public function test_it_can_login_user_successfully()
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('12345678')
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => '12345678'
        ])
            ->assertSee([
                $user->email,
                'token'
            ]);

        $response->assertStatus(200);
    }

    /** @test */
    public function test_it_cannot_login_with_incorrect_credentials()
    {
        $user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('12345678')
        ]);

        $response = $this->postJson('/api/v1/login', [
            'email' => $user->email,
            'password' => 'password'
        ])
            ->assertSee([
                'Incorrect credentials'
            ]);

        $response->assertStatus(401);
    }
}
