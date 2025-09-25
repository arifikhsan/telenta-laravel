<?php

namespace Database\Seeders;

use App\Models\AcmMenu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AcmMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
    	AcmMenu::firstOrCreate(
    		[
    			'name' => "Dashboard",
    			'url' => "/dashboard",
    			'icon' => "bx bx-home",
    			'sorting' => 1
    		]

    	);

        AcmMenu::firstOrCreate(
            [
                'name' => "Departments",
                'url' => "/dashboard/departments",
                'icon' => "bx bx-building-house",
                'sorting' => 2
            ]

        );

    	AcmMenu::firstOrCreate(
    		[
    			'name' => "Candidates",
    			'url' => "/dashboard/candidates",
    			'icon' => "bx bx-user",
    			'sorting' => 3
    		]

    	);

    	AcmMenu::firstOrCreate(
    		[
    			'name' => "Managers",
    			'url' => "/dashboard/managers",
    			'icon' => "bx bx-user-voice",
    			'sorting' => 4
    		]

    	);

    	AcmMenu::firstOrCreate(
    		[
    			'name' => "Positions",
    			'url' => "/dashboard/positions",
    			'icon' => "bx bx-briefcase",
    			'sorting' => 5
    		]

    	);

    	AcmMenu::firstOrCreate(
    		[
    			'name' => "Roles",
    			'url' => "/dashboard/roles",
    			'icon' => "bx bx-key",
    			'sorting' => 6
    		]

    	);

    	AcmMenu::firstOrCreate(
    		[
    			'name' => "Clients",
    			'url' => "/dashboard/clients",
    			'icon' => "bx bx-buildings",
    			'sorting' => 7
    		]

    	);

    	AcmMenu::firstOrCreate(
    		[
    			'name' => "Questions",
    			'url' => "/dashboard/questions",
    			'icon' => "bx bx-question-mark",
    			'sorting' => 8
    		]

    	);

        AcmMenu::firstOrCreate(
            [
                'name' => "Candidate Requests",
                'url' => "/dashboard/candidate-requests",
                'icon' => "bx bx-git-pull-request",
                'sorting' => 9
            ]

        );

        AcmMenu::firstOrCreate(
            [
                'name' => "Interviews",
                'url' => "/dashboard/interviews",
                'icon' => "bx bx-calendar-event",
                'sorting' => 10
            ]

        );
    }
}
