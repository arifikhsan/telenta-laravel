<?php

namespace Database\Seeders;

use App\Models\Candidate;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Seeder;

class CandidateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch the positions
        $javaDeveloperPosition = Position::where('name', 'Java Developer')->first();
        $itSupportPosition = Position::where('name', 'IT Support')->first();
        $productManagerPosition = Position::where('name', 'Product Manager')->first();

        // Fetch the manager by name
        $manager = User::where('name', 'Slamet')->first();

        // Use firstOrCreate to ensure candidates are not duplicated based on the 'name' field
        Candidate::firstOrCreate(
            ['name' => 'Mulyadi'], // Check if candidate with this name already exists
            [
                'position_id' => $javaDeveloperPosition->id,
                'manager_id' => $manager->id,
                'status' => 'CV Reviewed',
                'days_required' => 10,
                'proposed_date' => now()->toDateString(),
                'cv_review_date' => now()->toDateString(),
                'hr_interview_date' => now()->toDateString(),
            ]
        );

        Candidate::firstOrCreate(
            ['name' => 'Karto'], // Check if candidate with this name already exists
            [
                'position_id' => $itSupportPosition->id,
                'manager_id' => $manager->id,
                'status' => 'HR Interviewed',
                'days_required' => 7,
                'proposed_date' => now()->toDateString(),
                'cv_review_date' => now()->toDateString(),
                'hr_interview_date' => now()->toDateString(),
            ]
        );

        Candidate::firstOrCreate(
            ['name' => 'Sutrisno'], // Check if candidate with this name already exists
            [
                'position_id' => $productManagerPosition->id,
                'manager_id' => $manager->id,
                'status' => 'Hired',
                'days_required' => 14,
                'proposed_date' => now()->toDateString(),
                'cv_review_date' => now()->toDateString(),
                'hr_interview_date' => now()->toDateString(),
            ]
        );
    }
}
