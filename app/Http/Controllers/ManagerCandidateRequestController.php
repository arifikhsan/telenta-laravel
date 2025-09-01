<?php

namespace App\Http\Controllers;

use App\Models\ManagerCandidateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ManagerCandidateRequestController extends Controller
{
    public function index(): Response
    {
        $managerCandidateRequests = ManagerCandidateRequest::with(['manager'])->get();

        return Inertia::render('admin/manager-candidate-request/ManagerCandidateRequests', ['managerCandidateRequests' => $managerCandidateRequests]);
    }
}
