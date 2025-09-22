<?php

namespace Database\Seeders;

use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();

        if ($users->isEmpty()) {
            echo "No users found. Please run UserSeeder first.\n";
            return;
        }

        $taskData = [
            [
                'title' => 'Setup Development Environment',
                'description' => 'Configure the local development environment with all necessary tools and dependencies.',
                'status' => 'done',
            ],
            [
                'title' => 'Design Database Schema',
                'description' => 'Create the database schema for the task management system.',
                'status' => 'done',
            ],
            [
                'title' => 'Implement User Authentication',
                'description' => 'Build login, register, and logout functionality with proper validation.',
                'status' => 'in_progress',
            ],
            [
                'title' => 'Create Task CRUD Operations',
                'description' => 'Implement create, read, update, and delete operations for tasks.',
                'status' => 'in_progress',
            ],
            [
                'title' => 'Build Frontend Components',
                'description' => 'Create Vue.js components for the user interface.',
                'status' => 'pending',
            ],
            [
                'title' => 'Add Task Filtering',
                'description' => 'Implement filtering by status and search functionality.',
                'status' => 'pending',
            ],
            [
                'title' => 'Write Unit Tests',
                'description' => 'Create comprehensive unit tests for all features.',
                'status' => 'pending',
            ],
            [
                'title' => 'Deploy to Production',
                'description' => 'Deploy the application to production environment.',
                'status' => 'pending',
            ],
        ];

        foreach ($users as $user) {
            foreach ($taskData as $index => $task) {
                Task::create([
                    'user_id' => $user->id,
                    'title' => $task['title'] . " (User: {$user->name})",
                    'description' => $task['description'],
                    'status' => $task['status'],
                    'created_at' => now()->subDays(rand(0, 30)),
                    'updated_at' => now()->subDays(rand(0, 10)),
                ]);
            }
        }

        echo "Created " . ($users->count() * count($taskData)) . " sample tasks.\n";
    }
}
