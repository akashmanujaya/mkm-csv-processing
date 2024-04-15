<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'status_code',
                     'message',
                     'data' => [
                         'token'
                     ]
                 ])
                 ->assertJson([
                     'status' => true,
                     'status_code' => 200,
                     'message' => 'Registration successful.'
                 ]);
    }

    public function test_login()
    {
        // First, create a user to login with
        $this->postJson('/api/register', [
            'name' => 'Jane Doe',
            'email' => 'janedoe@example.com',
            'password' => 'password123'
        ]);

        // Attempt to login
        $response = $this->postJson('/api/login', [
            'email' => 'janedoe@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'status_code',
                     'message',
                     'data' => [
                         'token'
                     ]
                 ])
                 ->assertJson([
                     'status' => true,
                     'status_code' => 200,
                     'message' => 'Login successful.'
                 ]);
    }
}
