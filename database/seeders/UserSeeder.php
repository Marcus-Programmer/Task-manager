<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Create test user
        User::create([
            'name' => 'Test User',
            'email' => 'test@taskmanager.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create demo user
        User::create([
            'name' => 'Demo User',
            'email' => 'demo@taskmanager.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@taskmanager.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        echo "Created 3 test users:\n";
        echo "- test@taskmanager.com / password\n";
        echo "- demo@taskmanager.com / password\n";
        echo "- admin@taskmanager.com / password\n";
    }
}