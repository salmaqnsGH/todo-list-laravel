<?php

namespace Tests\Feature;

use App\Models\Activity;
use Database\Seeders\ActivitySeeder;
use Database\Seeders\ListSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\UserSeeder;
use Illuminate\Support\Facades\Log;

class ActivityTest extends TestCase
{
    public function testCreateActivitySuccess()
    {
        $this->seed([UserSeeder::class]);

        $this->post('/api/activities', [
            'title' => 'new activity 1'
        ],
        [
            'Authorization' => 'test'
        ])
        ->assertStatus(201)
        ->assertJson([
            'data' => [
                'title' => 'new activity 1'
            ]
        ]);
    }

    public function testGetSuccess()
    {
        $this->seed([UserSeeder::class, ActivitySeeder::class]);

        $activity = Activity::query()->limit(1)->first();

        $this->get("/api/activities/". $activity->id, 
        [
            'Authorization' => 'test'
        ])
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'title' => 'test'
            ]
        ]);
    }

    public function testGetNotFound()
    {
        $this->seed([UserSeeder::class, ActivitySeeder::class]);

        $activity = Activity::query()->limit(1)->first();

        $this->get("/api/activities/" . ($activity->id + 1), 
        [
            'Authorization' => 'test'
        ])
        ->assertStatus(404)
        ->assertJson([
            'errors' => [
                'message' => [
                    'not found'
                ]
            ]
        ]);
    }
    
    public function testGetOtherUserContact()
    {
        $this->seed([UserSeeder::class, ActivitySeeder::class]);

        $activity = Activity::query()->limit(1)->first();

        $this->get("/api/activities/" . $activity->id, 
        [
            'Authorization' => 'test2'
        ])
        ->assertStatus(404)
        ->assertJson([
            'errors' => [
                'message' => [
                    'not found'
                ]
            ]
        ]);
    }

    public function testUpdateSuccess()
    {
        $this->seed([UserSeeder::class, ActivitySeeder::class]);

        $activity = Activity::query()->limit(1)->first();

        $this->put("/api/activities/". $activity->id, 
        [
            'title' => 'test2'
        ],
        [
            'Authorization' => 'test'
        ])
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'title' => 'test2'
            ]
        ]);
    }

    public function testUpdateValidationError()
    {
        $this->seed([UserSeeder::class, ActivitySeeder::class]);

        $activity = Activity::query()->limit(1)->first();

        $this->put("/api/activities/". $activity->id, 
        [
            'title' => ''
        ],
        [
            'Authorization' => 'test'
        ])
        ->assertStatus(400)
        ->assertJson([
            'errors' => [
                'title' => [
                    'The title field is required.'
                ]
            ]
        ]);
    }

    public function testDeleteSuccess()
    {
        $this->seed([UserSeeder::class, ActivitySeeder::class]);

        $activity = Activity::query()->limit(1)->first();

        $this->delete("/api/activities/". $activity->id, [], 
        [
            'Authorization' => 'test'
        ])
        ->assertStatus(200)
        ->assertJson([
            'data' => true
        ]);
    }

    public function testDeleteNotFound()
    {
        $this->seed([UserSeeder::class, ActivitySeeder::class]);

        $activity = Activity::query()->limit(1)->first();

        $this->delete("/api/activities/". ($activity->id + 1), [], 
        [
            'Authorization' => 'test'
        ])
        ->assertStatus(404)
        ->assertJson([
            'errors' => [
                'message' => [
                    'not found'
                ]
            ]
        ]);
    }

    public function testGetListSuccess()
    {
        $this->seed([UserSeeder::class, ListSeeder::class]);

        $response = $this->get("/api/activities",
        [
            'Authorization' => 'test'
        ])
        ->assertStatus(200)
        ->json();

        Log::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(10, count($response['data']));
    }

    public function testGetListNotFound()
    {
        $this->seed([UserSeeder::class]);

        $response = $this->get("/api/activities",
        [
            'Authorization' => 'test'
        ])
        ->assertStatus(200)
        ->json();

        Log::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(0, count($response['data']));
    }

    public function testGetListPage()
    {
        $this->seed([UserSeeder::class, ListSeeder::class]);

        $response = $this->get("/api/activities?size=5&page=2",
        [
            'Authorization' => 'test'
        ])
        ->assertStatus(200)
        ->json();

        Log::info(json_encode($response, JSON_PRETTY_PRINT));

        self::assertEquals(5, count($response['data']));
        self::assertEquals(10, $response['meta']['total']);
        self::assertEquals(2, $response['meta']['current_page']);
    }
}
