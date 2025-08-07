<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if the 'admin' role exists, and create it if not
        $adminRole = Role::firstOrCreate(['name' => 'admin']);

        // Check if the 'manager' role exists, and create it if not
        $managerRole = Role::firstOrCreate(['name' => 'manager']);

        // Create 'admin' user if not exists and assign the 'admin' role
        User::firstOrCreate(
            ['email' => 'admin@telenta.com'],
            [
                'name' => 'Admin',
                'email' => 'admin@telenta.com',
                'password' => Hash::make('12345678'),
                'role_id' => $adminRole->id,  // Assign the 'admin' role
            ]
        );

        // Create 'manager' user if not exists and assign the 'manager' role
        User::firstOrCreate(
            ['email' => 'manager@telenta.com'],
            [
                'name' => 'Slamet',
                'email' => 'manager@telenta.com',
                'password' => Hash::make('12345678'),
                'role_id' => $managerRole->id,  // Assign the 'manager' role
            ]
        );
    }
}
