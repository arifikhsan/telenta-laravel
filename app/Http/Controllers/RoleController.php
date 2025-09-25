<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

use App\Helpers\DataTableHelper;
use App\Helpers\CommonHelper;


class RoleController extends Controller
{
    // public function index(): Response
    // {
    //     $roles = Role::all();

    //     return Inertia::render('Roles', ['roles' => $roles]);
    // }

    public function index()
    {
        return view('roles');
    }

    public function getData(Request $request)
    {

    	$roles = Role::all();

        $formatters = [
            fn($ls, $num) => $num,
            fn($ls) => $ls->name,
            fn($ls) => CommonHelper::dateFormat($ls->created_at),
            fn($ls) => CommonHelper::dateFormat($ls->updated_at),
        ];

        return response()->json(
            DataTableHelper::fromCollectionWithFormatter($request, $roles, $formatters)
        );
    }

}
