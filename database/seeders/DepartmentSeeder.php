<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Department;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Department::firstOrCreate(['name' => 'Java Developer']);
        Department::firstOrCreate(['name' => 'IT Support']);
        Department::firstOrCreate(['name' => 'Product Manager']);
    }
}
