<?php

use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }

    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard/roles', [RoleController::class, 'index'])->name('dashboard.roles');
    Route::get('dashboard/departments', [DepartmentController::class, 'index'])->name('dashboard.departments');
    Route::get('dashboard/managers', [ManagerController::class, 'index'])->name('dashboard.managers');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
