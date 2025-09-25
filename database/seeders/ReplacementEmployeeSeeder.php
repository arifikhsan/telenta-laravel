<?php

namespace Database\Seeders;

use App\Models\ReplacementEmployee;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReplacementEmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ReplacementEmployee::firstOrCreate(
        	[
        		'candidate_request_id' => 1,
        		'name' => "John Doe"
        	]

        );

        ReplacementEmployee::firstOrCreate(
        	[
        		'candidate_request_id' => 1,
        		'name' => "John Mills"
        	]

        );

        ReplacementEmployee::firstOrCreate(
        	[
        		'candidate_request_id' => 1,
        		'name' => "John Phoe"
        	]

        );
    }
}
