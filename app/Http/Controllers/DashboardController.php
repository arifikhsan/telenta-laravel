<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Role;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user()->load('role'); // Load the user's role

        if ($user->role->name === 'manager') {
            return redirect()->route('manager.dashboard');
        }

        return Inertia::render('Dashboard', [
            'user' => $user, // Pass the entire user data or just the role
        ]);
    }
}
