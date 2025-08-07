<?php

namespace Database\Seeders;

use App\Models\Client;
use App\Models\User;
use Illuminate\Database\Seeder;

class ManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $manager = User::where(['name' => 'Slamet'])->first();
        $client = Client::where(['name' => 'Telkomsel'])->first();
        $manager->client()->associate($client);
        $manager->save();
    }
}
