<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;

class ManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $manager = User::where(['name' => 'manager'])->first();
        $department = Department::where(['name' => 'Java Developer'])->first();
        $manager->department()->associate($department);
        $manager->save();
    }
}
