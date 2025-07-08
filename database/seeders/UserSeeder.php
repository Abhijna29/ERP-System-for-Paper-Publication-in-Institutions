<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'], // Prevent duplicates
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'mobile_number' => '9876543210',
                'password' => Hash::make('admin123'), // Secure hash
                'role' => 'admin',
            ]
        );
    }
}
