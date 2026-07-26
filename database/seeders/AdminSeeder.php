<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Check if admin user already exists
        $existingAdmin = User::where('username', 'admin@gmail.com')->first();
        
        if ($existingAdmin) {
            // Update existing admin user instead of deleting
            $existingAdmin->update([
                'name' => 'Administrator',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('smilecareLTD'),
                'role' => 'admin',
                'status' => 'active'
            ]);
        } else {
            // Create fresh admin user if doesn't exist
            User::create([
                'name' => 'Administrator',
                'username' => 'admin@gmail.com',
                'email' => 'admin@gmail.com',
                'password' => Hash::make('smilecareLTD'),
                'role' => 'admin',
                'status' => 'active'
            ]);
        }
    }
}
