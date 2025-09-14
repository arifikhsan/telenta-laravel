<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ManagerCandidateRequestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $manager = User::where(['name' => 'Slamet'])->first();
        $manager->candidateRequests()->create([
            'status' => 'pending',
            'requested_count' => 5,
            'fulfilled_count' => 0,
            'position_id' => 1,
            'note' => 'mau yang good looking',
            'level' => 'junior',
            'hiring_type' => 'new',
            'date_requested' => now(),
        ]);
    }
}
