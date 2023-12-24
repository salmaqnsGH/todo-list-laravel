<?php

namespace Tests\Feature;

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
}
