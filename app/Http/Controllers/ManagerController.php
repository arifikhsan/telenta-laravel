<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;
use App\Helpers\DataTableHelper;
use App\Helpers\CommonHelper;

use Illuminate\Http\Request;

class ManagerController extends Controller
{
    // public function index(): Response
    // {
    //     $managers = User::with(['client', 'department'])->
    //     whereHas('role', function($query) {
    //         $query->where('name', 'manager');
    //     })->get();

    //     return Inertia::render('Managers', ['managers' => $managers]);
    // }

    public function index()
    {
        return view('managers');
    }

    public function fetch(Request $request)
    {

    	$managers = User::with(['client', 'department'])->
    	whereHas('role', function($query) {
    		$query->where('name', 'manager');
    	})->get();

    	// var_dump(json_encode($managers)); die;

        $formatters = [
            fn($ls, $num) => $num,
            fn($ls) => $ls->name,
            fn($ls) => $ls->client->name,
            fn($ls) => $ls->department->name,
            fn($ls) => CommonHelper::dateFormat($ls->created_at),
            fn($ls) => CommonHelper::dateFormat($ls->updated_at),
        ];

        return response()->json(
            DataTableHelper::fromCollectionWithFormatter($request, $managers, $formatters)
        );
    }
}
