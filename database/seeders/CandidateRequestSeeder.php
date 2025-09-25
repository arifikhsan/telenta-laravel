<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CandidateRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $manager = User::where(['name' => 'Slamet'])->first();
        $manager->candidateRequests()->create([
            'position_id' => 1,
            'status' => 'pending',
            'requested_count' => 3,
            'fulfilled_count' => 0,
            'date_requested' => now(),
            'level' => 'senior',
            'detail' => 'S1, Python, Fullstack',
            'salary_min' => '8000000',
            'salary_max' => '14000000',
            'category'  => 'replacement'
        ]);

        $manager->candidateRequests()->create([
            'position_id' => 3,
            'status' => 'pending',
            'requested_count' => 5,
            'fulfilled_count' => 0,
            'date_requested' => now(),
            'level' => 'senior',
            'detail' => 'S1, HTML, MySQL',
            'salary_min' => '5000000',
            'salary_max' => '6000000',
            'category'  => 'new'
        ]);
    }
}
