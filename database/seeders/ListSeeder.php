<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\User;
use App\Models\Todo;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
class ListSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::where('username', 'test')->first();

        
        for($i = 0; $i < 10; $i++){
            Activity::create([
                'title' => 'test' . $i,
                'user_id' => $user->id,
                'email' => $user->username,
            ]);
        }

        $activity = Activity::where('title', 'test')->first();
        
        for($i = 0; $i < 10; $i++){
            Todo::create([
                'title' => 'test',
                'priority' => 'test',
                'is_active' => true,
                'activity_id' => $activity->id
            ]);
        }
    }
}
