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
        $cvFileName = 'cv-template.pdf';
        $filePath = 'cv/' . Str::random(10) . '-' . $cvFileName;

        // Store the file in storage/app/cv
        Storage::disk('public')->put($filePath, file_get_contents(database_path('seeders/files/' . $cvFileName)));


        // Use firstOrCreate to ensure candidates are not duplicated based on the 'name' field
        Candidate::firstOrCreate(
            ['name' => 'Mulyadi'], // Check if candidate with this name already exists
            [
                'status' => 'idle',
                'cv_path' => $filePath,
            ]
        );

        Candidate::firstOrCreate(
            ['name' => 'Karto'], // Check if candidate with this name already exists
            [
                'status' => 'idle',
                'cv_path' => $filePath,
            ]
        );

        Candidate::firstOrCreate(
            ['name' => 'Sutrisno'], // Check if candidate with this name already exists
            [
                'status' => 'idle',
                'cv_path' => $filePath,
            ]
        );
    }
}
