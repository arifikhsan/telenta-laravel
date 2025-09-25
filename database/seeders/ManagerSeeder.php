<?php

namespace Database\Seeders;

use App\Models\Client;
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
        $manager = User::where(['name' => 'Slamet'])->first();
        $client = Client::where(['name' => 'Telkomsel'])->first();
        $manager->client()->associate($client);
        $manager->save();

        $department = Department::where(['name' => 'Infrastructure Delivery - Maintenance'])->first();
        $manager->department()->associate($department);
        $manager->save();

        $financeManager = User::where(['name' => 'Bambang'])->first();
        $financeDepartment = Department::where(['name' => 'Microsoft Delivery & Solution'])->first();
        $komdigiClient = Client::where(['name' => 'Komdigi'])->first();

        $financeManager->client()->associate($komdigiClient);
        $financeManager->save();

        $financeManager->department()->associate($financeDepartment);
        $financeManager->save();
    }
}
