<?php

namespace Database\Seeders;

use App\Models\Activity;
use App\Models\User;
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
    }
}
