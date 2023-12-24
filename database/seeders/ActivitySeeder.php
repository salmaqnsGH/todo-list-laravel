<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('username', 'test')->first();

        Activity::create([
            'title' => 'test',
            'user_id' => $user->id,
            'email' => $user->username,
        ]);
    }
}
