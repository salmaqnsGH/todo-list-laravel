<?php

namespace Tests\Feature;

use App\Models\Activity;
use App\Models\Todo;
use Database\Seeders\ActivitySeeder;
use Database\Seeders\TodoSeeder;
use Database\Seeders\UserSeeder;
use Database\Seeders\ListSeeder;
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

    public function testUpdateTodoSuccess()
    {
        $this->seed([UserSeeder::class, ActivitySeeder::class, TodoSeeder::class]);

        $activity = Activity::query()->limit(1)->first();
        $todo = Todo::query()->limit(1)->first();

        $this->put("/api/activities/".$activity->id."/todos/" . $todo->id, 
        [
            'title' => 'test 2',
            'priority' => 'test 2',
            'is_active' => true,
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

    public function testUpdateTodoNotFound()
    {
        $this->seed([UserSeeder::class, ActivitySeeder::class, TodoSeeder::class]);

        $activity = Activity::query()->limit(1)->first();
        $todo = Todo::query()->limit(1)->first();

        $this->put("/api/activities/".($activity->id + 1)."/todos/" .( $todo->id +1 ), 
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

    public function testDeleteTodoSuccess()
    {
        $this->seed([UserSeeder::class, ActivitySeeder::class, TodoSeeder::class]);

        $todo = Todo::query()->limit(1)->first();

        $this->delete("/api/activities/todos/" . $todo->id, 
        [],
        [
            'Authorization' => 'test'
        ])
        ->assertStatus(200)
        ->assertJson([
            'data' => true
        ]);
    }

    public function testDeleteTodoNotFound()
    {
        $this->seed([UserSeeder::class, ActivitySeeder::class, TodoSeeder::class]);

        $todo = Todo::query()->limit(1)->first();

        $this->delete("/api/activities/todos/" . ($todo->id + 1), 
        [],
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

    public function testGetListTodo()
    {
        $this->seed([UserSeeder::class, ActivitySeeder::class, ListSeeder::class]);

        $activity = Activity::query()->limit(1)->first();

        $response = $this->get("/api/activities/".$activity->id."/todos",
        [
            'Authorization' => 'test'
        ])
        ->assertStatus(200)
        ->json();

        self::assertEquals(10, count($response['data']));
    }
}
