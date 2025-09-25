<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    	Department::firstOrCreate(['name' => 'Infrastructure Delivery - Maintenance']);
    	Department::firstOrCreate(['name' => 'Infrastructure Delivery - System Management']);
    	Department::firstOrCreate(['name' => 'Data Center and Infrastructure - Data Center']);
    	Department::firstOrCreate(['name' => 'Data Center and Infrastructure - Seat Management']);
    	Department::firstOrCreate(['name' => 'Microsoft Delivery & Solution']);
    	Department::firstOrCreate(['name' => 'IT Operation Services 1']);
    	Department::firstOrCreate(['name' => 'IT Operation Services 2']);
    	Department::firstOrCreate(['name' => 'Cloud Solution & Services Delivery']);
    	Department::firstOrCreate(['name' => 'IT Managed Services 1']);
    	Department::firstOrCreate(['name' => 'IT Managed Services 2']);
    	Department::firstOrCreate(['name' => 'ERP Operation Services']);
    	Department::firstOrCreate(['name' => 'Application Development Services']);
    	Department::firstOrCreate(['name' => 'Cyber Security Services']);
    	Department::firstOrCreate(['name' => 'Human Resource and Development']);

    }
}
