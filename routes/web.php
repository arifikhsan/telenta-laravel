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
use App\Http\Controllers\AcmRoleMenuController;
use App\Http\Controllers\CandidateRequestController;
use App\Http\Controllers\InterviewControler;
use App\Http\Controllers\InterviewDetailControler;
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
    // return Inertia::render('Welcome');
    return view('login');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('dashboard/summary/fulfilled', [DashboardController::class, 'fulfilled'])->name('dashboard.summary.fulfilled');
    Route::get('dashboard/summary/approved', [DashboardController::class, 'approved'])->name('dashboard.summary.approved');
    Route::get('dashboard/summary/internal', [DashboardController::class, 'internal'])->name('dashboard.summary.internal');
    Route::get('dashboard/summary/user', [DashboardController::class, 'user'])->name('dashboard.summary.user');
    Route::get('dashboard/summary/comparison', [DashboardController::class, 'comparison'])->name('dashboard.summary.comparison');
    Route::get('dashboard/summary/applicant', [DashboardController::class, 'applicant'])->name('dashboard.summary.applicant');
    // Route::get('dashboard/candidates/{id}/edit', [CandidateController::class, 'edit'])->name('dashboard.candidates.edit');
    // Route::post('dashboard/candidates/{id}/update', [CandidateController::class, 'update'])->name('dashboard.candidates.update');

    Route::get('dashboard/manager-candidate-requests', [ManagerCandidateRequestController::class, 'index'])->name('dashboard.manager-candidate-requests');
    Route::get('dashboard/manager-candidate-requests/{id}/fulfill', [ManagerCandidateRequestController::class, 'fulfill'])->name('dashboard.manager-candidate-requests.fulfill');
    Route::post('dashboard/manager-candidate-requests/{id}/accept', [ManagerCandidateRequestController::class, 'accept'])->name('dashboard.manager-candidate-requests.accept');
    Route::post('dashboard/manager-candidate-requests/{id}/reject', [ManagerCandidateRequestController::class, 'reject'])->name('dashboard.manager-candidate-requests.reject');
    Route::post('dashboard/candidate-request-fills/add-candidate', [CandidateRequestFillController::class, 'addCandidate'])->name('dashboard.candidate-request-fills.add-candidate');
    Route::post('dashboard/candidate-request-fills/remove-candidate', [CandidateRequestFillController::class, 'removeCandidate'])->name('dashboard.candidate-request-fills.remove-candidate');


    Route::get('dashboard/departments', [DepartmentController::class, 'index'])->name('dashboard.departments');
    Route::post('dashboard/departments/fetch', [DepartmentController::class, 'fetch'])->name('dashboard.departments.fetch');
    Route::post('dashboard/departments/store', [DepartmentController::class, 'store'])->name('dashboard.departments.store');
    Route::post('dashboard/departments/show', [DepartmentController::class, 'show'])->name('dashboard.departments.show');
    Route::post('dashboard/departments/update', [DepartmentController::class, 'update'])->name('dashboard.departments.update');
    Route::post('dashboard/departments/destroy', [DepartmentController::class, 'destroy'])->name('dashboard.departments.destroy');

    Route::get('dashboard/candidates', [CandidateController::class, 'index'])->name('dashboard.candidates');
    Route::post('dashboard/candidates/fetch', [CandidateController::class, 'fetch'])->name('dashboard.candidates.fetch');
    Route::get('dashboard/candidates/create', [CandidateController::class, 'create'])->name('dashboard.candidates.create');
    Route::post('dashboard/candidates/store', [CandidateController::class, 'store'])->name('dashboard.candidates.store');
    Route::post('dashboard/candidates/destroy', [CandidateController::class, 'destroy'])->name('dashboard.candidates.destroy');
    Route::post('dashboard/candidates/idle', [CandidateController::class, 'idle'])->name('dashboard.candidates.idle');
    Route::post('dashboard/candidates/show', [CandidateController::class, 'show'])->name('dashboard.candidates.show');


    Route::get('dashboard/managers', [ManagerController::class, 'index'])->name('dashboard.managers');
    Route::post('dashboard/managers/fetch', [ManagerController::class, 'fetch'])->name('dashboard.managers.fetch');

    Route::get('dashboard/roles', [RoleController::class, 'index'])->name('dashboard.roles');
    Route::post('dashboard/roles/getData', [RoleController::class, 'getData'])->name('dashboard.roles.getData');


    Route::get('dashboard/clients', [ClientController::class, 'index'])->name('dashboard.clients');
    Route::post('dashboard/clients/fetch', [ClientController::class, 'fetch'])->name('dashboard.clients.fetch');
    Route::post('dashboard/clients/store', [ClientController::class, 'store'])->name('dashboard.clients.store');
    Route::post('dashboard/clients/show', [ClientController::class, 'show'])->name('dashboard.clients.show');
    Route::post('dashboard/clients/update', [ClientController::class, 'update'])->name('dashboard.clients.update');
    Route::post('dashboard/clients/destroy', [ClientController::class, 'destroy'])->name('dashboard.clients.destroy');

    Route::get('dashboard/positions', [PositionController::class, 'index'])->name('dashboard.positions');
    Route::post('dashboard/positions/fetch', [PositionController::class, 'fetch'])->name('dashboard.positions.fetch');
    Route::post('dashboard/positions/store', [PositionController::class, 'store'])->name('dashboard.positions.store');
    Route::post('dashboard/positions/show', [PositionController::class, 'show'])->name('dashboard.positions.show');
    Route::post('dashboard/positions/update', [PositionController::class, 'update'])->name('dashboard.positions.update');
    Route::post('dashboard/positions/destroy', [PositionController::class, 'destroy'])->name('dashboard.positions.destroy');
    Route::get('dashboard/positions/list', [PositionController::class, 'list'])->name('dashboard.positions.list');

    Route::get('dashboard/positions/questions/{id}', [QuestionPositionMapController::class, 'index'])->name('dashboard.positions.questions');
    Route::post('dashboard/positions/questions/fetch/{id}', [QuestionPositionMapController::class, 'fetch'])->name('dashboard.positions.questions.fetch');
    Route::post('dashboard/positions/questions/store', [QuestionPositionMapController::class, 'store'])->name('dashboard.positions.questions.store');
    Route::post('dashboard/positions/questions/destroy', [QuestionPositionMapController::class, 'destroy'])->name('dashboard.positions.questions.destroy'); 

    Route::get('dashboard/questions', [QuestionController::class, 'index'])->name('dashboard.questions');
    Route::post('dashboard/questions/fetch', [QuestionController::class, 'fetch'])->name('dashboard.questions.fetch');
    Route::post('dashboard/questions/store', [QuestionController::class, 'store'])->name('dashboard.questions.store');
    Route::post('dashboard/questions/show', [QuestionController::class, 'show'])->name('dashboard.questions.show');
    Route::post('dashboard/questions/update', [QuestionController::class, 'update'])->name('dashboard.questions.update');
    Route::post('dashboard/questions/destroy', [QuestionController::class, 'destroy'])->name('dashboard.questions.destroy');
    Route::get('dashboard/questions/list/{id}', [QuestionController::class, 'list'])->name('dashboard.questions.list');

    Route::get('dashboard/candidate-requests', [CandidateRequestController::class, 'index'])->name('dashboard.candidate-request');
    Route::post('dashboard/candidate-requests/fetch', [CandidateRequestController::class, 'fetch'])->name('dashboard.candidate-request.fetch');
    Route::post('dashboard/candidate-requests/store', [CandidateRequestController::class, 'store'])->name('dashboard.candidate-requests.store');
    Route::post('dashboard/candidate-requests/reject', [CandidateRequestController::class, 'reject'])->name('dashboard.candidate-requests.reject');
    Route::post('dashboard/candidate-requests/show', [CandidateRequestController::class, 'show'])->name('dashboard.candidate-request.show');
    Route::post('dashboard/candidate-requests/push', [CandidateRequestController::class, 'push'])->name('dashboard.candidate-request.push');

    Route::get('dashboard/candidate-requests/fulfill/{id}', [CandidateRequestFillController::class, 'index'])->name('dashboard.candidate-requests.fulfill');
    Route::post('dashboard/candidate-requests/fulfill/fetch/{id}', [CandidateRequestFillController::class, 'fetch'])->name('dashboard.candidate-requests.fulfill.fetch');
    Route::post('dashboard/candidate-requests/fulfill/store', [CandidateRequestFillController::class, 'store'])->name('dashboard.candidate-requests.fulfill.store');
    Route::post('dashboard/candidate-requests/fulfill/approve', [CandidateRequestFillController::class, 'approve'])->name('dashboard.candidate-requests.fulfill.approve');
    Route::post('dashboard/candidate-requests/fulfill/reject', [CandidateRequestFillController::class, 'reject'])->name('dashboard.candidate-requests.fulfill.reject');

    Route::get('dashboard/interviews', [InterviewControler::class, 'index'])->name('dashboard.interviews');
    Route::post('dashboard/interviews/fetch', [InterviewControler::class, 'fetch'])->name('dashboard.interviews.fetch');
    Route::post('dashboard/interviews/confirm', [InterviewControler::class, 'confirm'])->name('dashboard.interview.confirm');

    Route::get('dashboard/interviews/detail/{id}', [InterviewDetailControler::class, 'index'])->name('dashboard.interview.detail');
    Route::post('dashboard/interviews/detail/fetch/{id}', [InterviewDetailControler::class, 'fetch'])->name('dashboard.interview.detail.fetch');

    Route::get('manager/dashboard', [ManagerDashboardController::class, 'index'])->name('manager.dashboard');
    Route::get('manager/dashboard/candidates', [ManagerCandidateController::class, 'index'])->name('manager.dashboard.candidates');
    Route::get('manager/dashboard/manager-candidate-requests', [ManagerManagerCandidateRequestController::class, 'index'])->name('manager.dashboard.manager-candidate-requests');
    Route::get('manager/dashboard/manager-candidate-requests/create', [ManagerManagerCandidateRequestController::class, 'create'])->name('manager.dashboard.manager-candidate-requests.create');
    Route::post('manager/dashboard/manager-candidate-requests/store', [ManagerManagerCandidateRequestController::class, 'store'])->name('manager.dashboard.manager-candidate-requests.store');
    Route::post('manager/dashboard/manager-candidate-requests/{id}/mark-as-cancelled', [ManagerManagerCandidateRequestController::class, 'markAsCancelled'])->name('manager.dashboard.manager-candidate-requests.mark-as-cancelled');


    Route::post('dashboard/checkaccess', [AcmRoleMenuController::class, 'checkaccess'])->name('dashboard.checkaccess');
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
