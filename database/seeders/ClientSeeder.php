<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Client;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Client::firstOrCreate(['name' => 'Telkomsel']);
        Client::firstOrCreate(['name' => 'Komdigi']);
        Client::firstOrCreate(['name' => 'Koperasi Merah Putih']);
    }
}
