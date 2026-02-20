<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
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
