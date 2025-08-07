<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Position::firstOrCreate(['name' => 'Java Developer']);
        Position::firstOrCreate(['name' => 'IT Support']);
        Position::firstOrCreate(['name' => 'Product Manager']);
    }
}
