<?php

use App\Http\Controllers\CandidateController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\ManagerCandidateController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\ManagerDashboardController;
use App\Http\Controllers\PositionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\QuestionPositionMapController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ManagerCandidateRequestController;

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
    Route::get('dashboard/clients', [ClientController::class, 'index'])->name('dashboard.clients');
    Route::get('dashboard/managers', [ManagerController::class, 'index'])->name('dashboard.managers');
    Route::get('dashboard/candidates', [CandidateController::class, 'index'])->name('dashboard.candidates');
    Route::get('dashboard/candidates/{id}/edit', [CandidateController::class, 'edit'])->name('dashboard.candidates.edit');
    Route::get('dashboard/manager-candidate-requests', [ManagerCandidateRequestController::class, 'index'])->name('dashboard.manager-candidate-requests');
    Route::get('dashboard/candidates/create', [CandidateController::class, 'create'])->name('dashboard.candidates.create');
    Route::post('dashboard/candidates/store', [CandidateController::class, 'store'])->name('dashboard.candidates.store');

    Route::get('dashboard/positions', [PositionController::class, 'index'])->name('dashboard.positions');
    Route::get('dashboard/positions/questions', [QuestionPositionMapController::class, 'index'])->name('dashboard.positions.questions');
    Route::get('dashboard/questions', [QuestionController::class, 'index'])->name('dashboard.questions');

    Route::get('manager/dashboard', [ManagerDashboardController::class, 'index'])->name('manager.dashboard');
    Route::get('manager/dashboard/candidates', [ManagerCandidateController::class, 'index'])->name('manager.dashboard.candidates');

});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
