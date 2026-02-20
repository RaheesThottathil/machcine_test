<?php

namespace Database\Seeders;

use App\Models\User;
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
        User::updateOrCreate([
            'email' => 'admin@example.com'
        ], [
            'name' => 'Admin User',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        User::updateOrCreate([
            'email' => 'staff@example.com'
        ], [
            'name' => 'Staff User',
            'password' => bcrypt('password'),
            'role' => 'staff',
        ]);

        User::updateOrCreate([
            'email' => 'client@example.com'
        ], [
            'name' => 'Client User',
            'password' => bcrypt('password'),
            'role' => 'client',
        ]);
    }
}
