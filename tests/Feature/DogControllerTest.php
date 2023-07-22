<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use App\Jobs\SaveDogJob;
use Tests\TestCase;
use App\Models\User;

class DogControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the create method.
     *
     * @return void
     */
    public function testCreateDog()
    {
        Queue::fake(); // We don't want to actually dispatch the job

        $user = User::factory()->create(); // Create a new user

        // Login as the user
        $response = $this->actingAs($user, 'api')
            ->postJson('/api/adddog', [
                'name' => 'Rover',
                'breed' => 'Bulldog',
                'age' => 3
            ]);

        // Assert the job was dispatched
        Queue::assertPushed(SaveDogJob::class);

        // Assert the response status and structure
        $response->assertStatus(202)
            ->assertJsonStructure([
                'data' => ['dog'],
                'message',
                'status'
            ]);
    }

    /**
     * Test the list method.
     *
     * @return void
     */
    public function testListDogs()
    {
        $user = User::factory()->create(); // Create a new user

        // Login as the user
        $response = $this->actingAs($user, 'api')
            ->getJson('/api/listdogs');

        // Assert the response status and structure
        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => ['dogs'],
                'message',
                'status'
            ]);
    }
}
