<?php

namespace Database\Seeders;


use App\Models\QuestionPositionMap;
use App\Models\Position;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionPositionMapSeeder extends Seeder
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

        QuestionPositionMap::firstOrCreate(
        	[
        		'position_id' => $javaDeveloperPosition,
        		'question_id' => 1
        	]

        );

        QuestionPositionMap::firstOrCreate(
        	[
        		'position_id' => $javaDeveloperPosition,
        		'question_id' => 2
        	]

        );

        QuestionPositionMap::firstOrCreate(
        	[
        		'position_id' => $javaDeveloperPosition,
        		'question_id' => 3
        	]

        );

        QuestionPositionMap::firstOrCreate(
        	[
        		'position_id' => $itSupportPosition,
        		'question_id' => 1
        	]

        );

        QuestionPositionMap::firstOrCreate(
        	[
        		'position_id' => $itSupportPosition,
        		'question_id' => 3
        	]

        );

        QuestionPositionMap::firstOrCreate(
        	[
        		'position_id' => $productManagerPosition,
        		'question_id' => 1
        	]

        );

        QuestionPositionMap::firstOrCreate(
        	[
        		'position_id' => $productManagerPosition,
        		'question_id' => 2
        	]

        );


    }
}
