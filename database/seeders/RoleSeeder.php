<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 'admin' role if it doesn't exist
        Role::firstOrCreate(['name' => 'admin']);

        // Create 'manager' role if it doesn't exist
        Role::firstOrCreate(['name' => 'manager']);
    }
}
