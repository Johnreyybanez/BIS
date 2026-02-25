<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            ['role_name' => 'admin', 'description' => 'System Administrator'],
            ['role_name' => 'secretary', 'description' => 'Barangay Secretary'],
            ['role_name' => 'treasurer', 'description' => 'Barangay Treasurer'],
            ['role_name' => 'staff', 'description' => 'Barangay Staff'],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}