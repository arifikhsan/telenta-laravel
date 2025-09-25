<?php

namespace Database\Seeders;

use App\Models\CandidatePositionMap;
use App\Models\Position;
use App\Models\Candidate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CandidatePositionMapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch the positions
        $javaDeveloperPosition = Position::where('name', 'Java Developer')->first()->id;
        $itSupportPosition = Position::where('name', 'IT Support')->first()->id;
        $productManagerPosition = Position::where('name', 'Product Manager')->first()->id;

        $mulyadi = Candidate::where('name', 'Mulyadi')->first()->id;
        $karto = Candidate::where('name', 'Karto')->first()->id;
        $sutrisno = Candidate::where('name', 'Sutrisno')->first()->id;

        CandidatePositionMap::firstOrCreate(
        	[
                'candidate_id' => $mulyadi,
        		'position_id' => $javaDeveloperPosition
        	]

        );

        CandidatePositionMap::firstOrCreate(
        	[
                'candidate_id' => $karto,
        		'position_id' => $javaDeveloperPosition
        	]

        );

        CandidatePositionMap::firstOrCreate(
        	[
                'candidate_id' => $sutrisno,
        		'position_id' => $javaDeveloperPosition
        	]

        );

        CandidatePositionMap::firstOrCreate(
            [
                'candidate_id' => $mulyadi,
                'position_id' => $itSupportPosition
            ]

        );

        CandidatePositionMap::firstOrCreate(
            [
                'candidate_id' => $karto,
                'position_id' => $itSupportPosition
            ]

        );

        CandidatePositionMap::firstOrCreate(
            [
                'candidate_id' => $sutrisno,
                'position_id' => $productManagerPosition
            ]

        );
    }
}
