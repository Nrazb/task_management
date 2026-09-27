<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Naura',
                'password' => bcrypt('password'),
                'status' => 'active',
            ]
        );

        $tasks = [
            [
                'title' => 'Build dashboard',
                'description' => 'Create responsive task management dashboard.',
                'status' => 'in_progress',
                'priority' => 'high',
            ],
            [
                'title' => 'Create REST API',
                'description' => 'Implement CRUD task API.',
                'status' => 'completed',
                'priority' => 'high',
            ],
            [
                'title' => 'Write documentation',
                'description' => 'Create README documentation.',
                'status' => 'pending',
                'priority' => 'medium',
            ],
            [
                'title' => 'Test application',
                'description' => 'Test API and responsive dashboard.',
                'status' => 'pending',
                'priority' => 'low',
            ],
        ];

        foreach ($tasks as $task) {
            Task::create([
                ...$task,
                'user_id' => $user->id,
            ]);
        }
    }
}
