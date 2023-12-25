<?php

namespace Database\Seeders;

use App\Models\Todo;
use App\Models\Activity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TodoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activity = Activity::where('title', 'test')->first();

        Todo::create([
            'title' => 'test',
            'priority' => 'test',
            'is_active' => true,
            'activity_id' => $activity->id
        ]);
    }
}
