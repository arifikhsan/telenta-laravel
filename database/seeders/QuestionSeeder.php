<?php

namespace Database\Seeders;

use App\Models\Question;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuestionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Question::firstOrCreate(
        	[
        		'question' => "Berapa lama pengalaman di bidang ini?",
        		'status' => "active"
        	]

        );

        Question::firstOrCreate(
        	[
        		'question' => "Darimana anda mendapat info lowongan pekerjaan ini?",
        		'status' => "active"
        	]

        );

        Question::firstOrCreate(
        	[
        		'question' => "Skill apa saja yang anda kuasai?",
        		'status' => "active"
        	]

        );
    }
}
