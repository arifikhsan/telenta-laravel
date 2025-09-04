<?php

namespace Database\Seeders;

use App\Models\Candidate;
use App\Models\Position;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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

        $cvFileName = 'cv-template.pdf';
        $filePath = 'cv/' . Str::random(10) . '-' . $cvFileName;

        // Store the file in storage/app/cv
        Storage::disk('public')->put($filePath, file_get_contents(database_path('seeders/files/' . $cvFileName)));


        // Use firstOrCreate to ensure candidates are not duplicated based on the 'name' field
        Candidate::firstOrCreate(
            ['name' => 'Mulyadi'], // Check if candidate with this name already exists
            [
                'position_id' => $javaDeveloperPosition->id,
                'manager_id' => $manager->id,
                'status' => 'cv_reviewed',
                'days_required' => 10,
                'proposed_date' => now()->toDateString(),
                'cv_review_date' => now()->toDateString(),
                'hr_interview_date' => now()->toDateString(),
                'cv_path' => $filePath,
            ]
        );

        Candidate::firstOrCreate(
            ['name' => 'Karto'], // Check if candidate with this name already exists
            [
                'position_id' => $itSupportPosition->id,
                'manager_id' => $manager->id,
                'status' => 'hr_interviewed',
                'days_required' => 7,
                'proposed_date' => now()->toDateString(),
                'cv_review_date' => now()->toDateString(),
                'hr_interview_date' => now()->toDateString(),
                'cv_path' => $filePath,
            ]
        );

        Candidate::firstOrCreate(
            ['name' => 'Sutrisno'], // Check if candidate with this name already exists
            [
                'position_id' => $productManagerPosition->id,
                'manager_id' => $manager->id,
                'status' => 'hired',
                'days_required' => 14,
                'proposed_date' => now()->toDateString(),
                'cv_review_date' => now()->toDateString(),
                'hr_interview_date' => now()->toDateString(),
                'internal_interview_date' => now()->toDateString(),
                'user_interview_date' => now()->toDateString(),
                'cv_path' => $filePath,
            ]
        );
    }
}
