<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the login endpoint.
     *
     * @return void
     */
    public function testLogin()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'user',
                    'authorisation' => ['token', 'type'],
                ],
                'message',
                'status',
            ]);
    }

    /**
     * Test the register endpoint.
     *
     * @return void
     */
    public function testRegister()
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['user'],
                'message',
                'status',
            ]);
    }

    /**
     * Test the logout endpoint.
     *
     * @return void
     */
    public function testLogout()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        // Log in the user to get the token
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);

        $token = $response->json()['data']['authorisation']['token'];

        // Use the token for the logout request
        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/logout');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data',
                'message',
                'status',
            ]);
    }

    /**
     * Test the refresh endpoint.
     *
     * @return void
     */
    public function testRefresh()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password'),
        ]);

        // Log in the user to get the token
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertStatus(200);

        $token = $response->json()['data']['authorisation']['token'];

        // Use the token for the refresh request
        $response = $this->withHeader('Authorization', "Bearer $token")
            ->postJson('/api/refresh');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'user',
                    'authorisation' => ['token', 'type'],
                ],
                'message',
                'status',
            ]);
    }
}
