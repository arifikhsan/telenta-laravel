<?php

use App\Http\Controllers\CandidateController;
use App\Http\Controllers\CandidateRequestFillController;
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
use App\Http\Controllers\ManagerManagerCandidateRequestController;

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
    Route::post('dashboard/candidates/{id}/update', [CandidateController::class, 'update'])->name('dashboard.candidates.update');

    Route::get('dashboard/manager-candidate-requests', [ManagerCandidateRequestController::class, 'index'])->name('dashboard.manager-candidate-requests');
    Route::get('dashboard/manager-candidate-requests/{id}/fulfill', [ManagerCandidateRequestController::class, 'fulfill'])->name('dashboard.manager-candidate-requests.fulfill');
    Route::post('dashboard/manager-candidate-requests/{id}/accept', [ManagerCandidateRequestController::class, 'accept'])->name('dashboard.manager-candidate-requests.accept');
    Route::post('dashboard/manager-candidate-requests/{id}/reject', [ManagerCandidateRequestController::class, 'reject'])->name('dashboard.manager-candidate-requests.reject');
    Route::post('dashboard/candidate-request-fills/add-candidate', [CandidateRequestFillController::class, 'addCandidate'])->name('dashboard.candidate-request-fills.add-candidate');
    Route::post('dashboard/candidate-request-fills/remove-candidate', [CandidateRequestFillController::class, 'removeCandidate'])->name('dashboard.candidate-request-fills.remove-candidate');

    Route::get('dashboard/candidates/create', [CandidateController::class, 'create'])->name('dashboard.candidates.create');
    Route::post('dashboard/candidates/store', [CandidateController::class, 'store'])->name('dashboard.candidates.store');

    Route::get('dashboard/positions', [PositionController::class, 'index'])->name('dashboard.positions');
    Route::get('dashboard/positions/questions', [QuestionPositionMapController::class, 'index'])->name('dashboard.positions.questions');
    Route::get('dashboard/questions', [QuestionController::class, 'index'])->name('dashboard.questions');

    Route::get('manager/dashboard', [ManagerDashboardController::class, 'index'])->name('manager.dashboard');
    Route::get('manager/dashboard/candidates', [ManagerCandidateController::class, 'index'])->name('manager.dashboard.candidates');
    Route::get('manager/dashboard/manager-candidate-requests', [ManagerManagerCandidateRequestController::class, 'index'])->name('manager.dashboard.manager-candidate-requests');
    Route::get('manager/dashboard/manager-candidate-requests/create', [ManagerManagerCandidateRequestController::class, 'create'])->name('manager.dashboard.manager-candidate-requests.create');
    Route::post('manager/dashboard/manager-candidate-requests/store', [ManagerManagerCandidateRequestController::class, 'store'])->name('manager.dashboard.manager-candidate-requests.store');
    Route::post('manager/dashboard/manager-candidate-requests/{id}/mark-as-cancelled', [ManagerManagerCandidateRequestController::class, 'markAsCancelled'])->name('manager.dashboard.manager-candidate-requests.mark-as-cancelled');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
