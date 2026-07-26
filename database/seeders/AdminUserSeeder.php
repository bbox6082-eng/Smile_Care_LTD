<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure there is always an admin user present after any migration/seed
        User::updateOrCreate(
            ['username' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('smilecareLTD'),
                'role' => 'admin',
                'status' => 'active',
            ]
        );
    }
}




