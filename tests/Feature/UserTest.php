<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\UserSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserTest extends TestCase
{
    public function testRegisterSuccess()
    {
        $this->post('/api/users', [
            'username' => 'salmamail',
            'password' => 'password',
            'name' => 'salma'
        ])
        ->assertStatus(201)
        ->assertJson([
            'data' => [
                'username' => 'salmamail',
                'name' => 'salma'
            ]
        ]);
    }

    public function testRegisterFailed()
    {
        $this->post('/api/users', [
            'username' => '',
            'password' => '',
            'name' => ''
        ])
        ->assertStatus(400)
        ->assertJson([
            'errors' => [
                'username' => [
                    'The username field is required.'
                ],
                'password' => [
                    'The password field is required.'
                ],
                'name' => [
                    'The name field is required.'
                ]
            ]
        ]);
    }

    public function testRegisterUsernameAlreadyExists()
    {
        $this->testRegisterSuccess();
        $this->post('/api/users', [
            'username' => 'salmamail',
            'password' => 'password',
            'name' => 'salma'
        ])
        ->assertStatus(400)
        ->assertJson([
            'errors' => [
                'username' => [
                    'username already registered'
                ]
            ]
        ]);
    }

    public function testLoginSuccess()
    {
        $this->seed([UserSeeder::class]);
        $this->post('/api/users/login', [
            'username' => 'test',
            'password' => 'test'
        ])
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'username' => 'test',
                'name' => 'test'
            ]
        ]);

        $user = User::where('username', 'test')->first();

        self::assertNotNull($user->token);
    }

    public function testLoginFailed()
    {
        $this->post('/api/users/login', [
            'username' => 'test',
            'password' => 'test'
        ])
        ->assertStatus(401)
        ->assertJson([
            'errors' => [
                'message' => [
                    'invalid username/password'
                ]
            ]
        ]);
    }

    public function testLoginWrongPassword()
    {
        $this->seed([UserSeeder::class]);
        $this->post('/api/users/login', [
            'username' => 'test',
            'password' => 'test123'
        ])
        ->assertStatus(401)
        ->assertJson([
            'errors' => [
                'message' => [
                    'invalid username/password'
                ]
            ]
        ]);
    }

    public function testGetSuccess()
    {
        $this->seed([UserSeeder::class]);

        $this->get('/api/users/current', [
            'Authorization' => 'test'
        ])
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'username' => 'test',
                'name' => 'test'
            ]
        ]);
    }

    public function testGetUnauthorized()
    {
        $this->seed([UserSeeder::class]);

        $this->get('/api/users/current')
        ->assertStatus(401)
        ->assertJson([
            'errors' => [
                'message' => [
                    'unauthorized'
                ]
            ]
        ]);
    } 

    public function testGetInvalidToken()
    {
        $this->seed([UserSeeder::class]);

        $this->get('/api/users/current', [
            'Authorization' => 'test123'
        ])
        ->assertStatus(401)
        ->assertJson([
            'errors' => [
                'message' => [
                    'unauthorized'
                ]
            ]
        ]);
    } 

    public function testUpdatePasswordSuccess()
    {
        $this->seed([UserSeeder::class]);
        $oldUser = User::where('username', 'test')->first();

        $this->patch('/api/users/current', 
        [
            'password' => 'password'
        ],
        [
            'Authorization' => 'test'
        ])
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'username' => 'test',
                'name' => 'test'
            ]
        ]);

        $newUser = User::where('username', 'test')->first();

        self::assertNotEquals($oldUser->password, $newUser->password);
    }

    public function testUpdateNameSuccess()
    {
        $this->seed([UserSeeder::class]);
        $oldUser = User::where('username', 'test')->first();

        $this->patch('/api/users/current', 
        [
            'name' => 'name'
        ],
        [
            'Authorization' => 'test'
        ])
        ->assertStatus(200)
        ->assertJson([
            'data' => [
                'username' => 'test',
                'name' => 'name'
            ]
        ]);

        $newUser = User::where('username', 'test')->first();

        self::assertNotEquals($oldUser->name, $newUser->name);
    }
}
