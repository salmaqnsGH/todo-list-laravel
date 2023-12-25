<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Todo;
use Database\Seeders\ActivitySeeder;
use Database\Seeders\TodoSeeder;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TodoTest extends TestCase
{
    public function testCreateTodoSuccess()
    {
        $this->seed([UserSeeder::class, ActivitySeeder::class]);

        $activity = Activity::query()->limit(1)->first();

        $this->post('/api/activities/'. $activity->id . '/todos' , 
        [
            'title' => 'new todo 1',
            'is_active' => true,
            'priority' => 'high'
        ],
        [
            'Authorization' => 'test'
        ])
        ->assertStatus(201)
        ->assertJson([
            'data' => [
                'title' => 'new todo 1',
                'is_active' => true,
                'priority' => 'high',
            ],
        ]);
    }

    public function testCreateTodoNotFound()
    {
        $this->seed([UserSeeder::class, ActivitySeeder::class]);

        $activity = Activity::query()->limit(1)->first();

        $this->post('/api/activities/'. ($activity->id + 1) . '/todos' , 
        [
            'title' => 'new todo 1',
            'is_active' => true,
            'priority' => 'high'
        ],
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

    public function testGetTodoSuccess()
    {
        $this->seed([UserSeeder::class, ActivitySeeder::class, TodoSeeder::class]);

        $todo = Todo::query()->limit(1)->first();

        $this->get("/api/activities/todos/" . $todo->id, 
        [
            'Authorization' => 'test'
        ])
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'title' => 'test',
                'priority' => 'test',
                'is_active' => true
            ]
        ]);
    }

    public function testGetTodoNotFound()
    {
        $this->seed([UserSeeder::class, ActivitySeeder::class, TodoSeeder::class]);

        $todo = Todo::query()->limit(1)->first();

        $this->get("/api/activities/todos/" . ($todo->id+1), 
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

    public function updateTodoSuccess()
    {
        $this->seed([UserSeeder::class, ActivitySeeder::class, TodoSeeder::class]);

        $todo = Todo::query()->limit(1)->first();

        $this->put("/api/activities/todos/" . $todo->id, 
        [
            'title' => 'test 2',
            'priority' => 'test 2',
            'is_active' => true
        ],
        [
            'Authorization' => 'test'
        ])
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'title' => 'test 2',
                'priority' => 'test 2',
                'is_active' => true
            ]
        ]);
    }

    public function updateTodoNotFound()
    {
        $this->seed([UserSeeder::class, ActivitySeeder::class, TodoSeeder::class]);

        $todo = Todo::query()->limit(1)->first();

        $this->put("/api/activities/todos/" . ($todo->id + 1), 
        [
            'title' => 'test 2',
            'priority' => 'test 2',
            'is_active' => true
        ],
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
}
