<?php

namespace App\Http\Controllers;

use App\Models\User;
use Inertia\Inertia;
use Inertia\Response;

class ManagerController extends Controller
{
    public function index(): Response
    {
        $managers = User::with('department')->
        whereHas('role', function($query) {
            $query->where('name', 'manager');
        })->get();

        return Inertia::render('Managers', ['managers' => $managers]);
    }
}
