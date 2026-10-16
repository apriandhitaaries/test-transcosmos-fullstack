<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Task;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::factory(5)->create();

        $tasks = Task::factory(15)->create([
            'created_by' => $users->random()->id,
            'assigned_user_id' => $users->random()->id,
        ]);

        foreach ($tasks->random(10) as $task) {
            \App\Models\TaskComment::factory()->create([
                'task_id' => $task->id,
                'user_id' => $users->random()->id,
            ]);
        }
    }
}
