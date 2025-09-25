<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AcmRoleMenu;

class AcmRoleMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AcmRoleMenu::firstOrCreate(['role_id' => 1, 'acm_menu_id' => 1, 'create' => 1, 'read' => 1, 'update' => 1, 'delete' => 1, 'export' => 1]);
        AcmRoleMenu::firstOrCreate(['role_id' => 1, 'acm_menu_id' => 2, 'create' => 1, 'read' => 1, 'update' => 1, 'delete' => 1, 'export' => 1]);
        AcmRoleMenu::firstOrCreate(['role_id' => 1, 'acm_menu_id' => 3, 'create' => 1, 'read' => 1, 'update' => 1, 'delete' => 1, 'export' => 1]);
        AcmRoleMenu::firstOrCreate(['role_id' => 1, 'acm_menu_id' => 4, 'create' => 1, 'read' => 1, 'update' => 1, 'delete' => 1, 'export' => 1]);
        AcmRoleMenu::firstOrCreate(['role_id' => 1, 'acm_menu_id' => 5, 'create' => 1, 'read' => 1, 'update' => 1, 'delete' => 1, 'export' => 1]);
        AcmRoleMenu::firstOrCreate(['role_id' => 1, 'acm_menu_id' => 6, 'create' => 1, 'read' => 1, 'update' => 1, 'delete' => 1, 'export' => 1]);
        AcmRoleMenu::firstOrCreate(['role_id' => 1, 'acm_menu_id' => 7, 'create' => 1, 'read' => 1, 'update' => 1, 'delete' => 1, 'export' => 1]);
        AcmRoleMenu::firstOrCreate(['role_id' => 1, 'acm_menu_id' => 8, 'create' => 1, 'read' => 1, 'update' => 1, 'delete' => 1, 'export' => 1]);
        AcmRoleMenu::firstOrCreate(['role_id' => 1, 'acm_menu_id' => 9, 'create' => 0, 'read' => 1, 'update' => 1, 'delete' => 1, 'export' => 1]);
        AcmRoleMenu::firstOrCreate(['role_id' => 1, 'acm_menu_id' => 10, 'create' => 0, 'read' => 1, 'update' => 1, 'delete' => 1, 'export' => 1]);

        AcmRoleMenu::firstOrCreate(['role_id' => 2, 'acm_menu_id' => 1, 'create' => 1, 'read' => 1, 'update' => 1, 'delete' => 1, 'export' => 1]);
        AcmRoleMenu::firstOrCreate(['role_id' => 2, 'acm_menu_id' => 5, 'create' => 1, 'read' => 1, 'update' => 1, 'delete' => 1, 'export' => 1]);
        AcmRoleMenu::firstOrCreate(['role_id' => 2, 'acm_menu_id' => 8, 'create' => 1, 'read' => 1, 'update' => 1, 'delete' => 1, 'export' => 1]);
        AcmRoleMenu::firstOrCreate(['role_id' => 2, 'acm_menu_id' => 9, 'create' => 1, 'read' => 1, 'update' => 1, 'delete' => 1, 'export' => 1]);
        AcmRoleMenu::firstOrCreate(['role_id' => 2, 'acm_menu_id' => 10, 'create' => 1, 'read' => 1, 'update' => 1, 'delete' => 1, 'export' => 1]);
    }
}
