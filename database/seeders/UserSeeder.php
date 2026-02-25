<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run()
    {
        User::create([
            'role_id' => 1,
            'full_name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@barangay.com',
            'password' => Hash::make('password'),
            'contact_number' => '09123456789',
            'status' => 'active'
        ]);
    }
}