<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_create_user()
    {
        // Create an admin user
        $admin = User::factory()->create(['role' => 'admin']);

        // Login as admin
        $token = $admin->createToken('test')->plainTextToken;

        // User data to create
        $userData = [
            'full_name' => 'Test Citizen',
            'phonenumber' => '9999999999',
            'role' => 'citizen',
            'address' => 'Test Address',
            'password' => 'password123'
        ];

        // Make API request
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->postJson('/api/users', $userData);

        // Assert response
        $response->assertStatus(201)
                 ->assertJson([
                     'code' => 201,
                     'message' => 'User created successfully'
                 ])
                 ->assertJsonStructure([
                     'code',
                     'data' => [
                         'id', 'full_name', 'phonenumber', 'role', 'address'
                     ],
                     'message'
                 ]);

        // Assert user was created in database
        $this->assertDatabaseHas('users', [
            'full_name' => 'Test Citizen',
            'phonenumber' => '9999999999',
            'role' => 'citizen',
            'address' => 'Test Address'
        ]);
    }

    public function test_non_admin_cannot_create_user()
    {
        // Create a citizen user
        $citizen = User::factory()->create(['role' => 'citizen']);

        // Login as citizen
        $token = $citizen->createToken('test')->plainTextToken;

        // User data to create
        $userData = [
            'full_name' => 'Test Citizen',
            'phonenumber' => '9999999999',
            'role' => 'citizen',
            'address' => 'Test Address',
            'password' => 'password123'
        ];

        // Make API request
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json'
        ])->postJson('/api/users', $userData);

        // Assert forbidden response
        $response->assertStatus(403);
    }
}
