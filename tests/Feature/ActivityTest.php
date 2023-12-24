<?php

namespace Tests\Feature;

use App\Models\Activity;
use Database\Seeders\ActivitySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Database\Seeders\UserSeeder;


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
}
