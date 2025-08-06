<?php

namespace App\Http\Controllers;

use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ManagerController extends Controller
{
    public function index(): Response
    {
        $managers = User::whereHas('role', function($query) {
            $query->where('name', 'manager');
        })->get();

        return Inertia::render('Managers', ['managers' => $managers]);
    }
}
