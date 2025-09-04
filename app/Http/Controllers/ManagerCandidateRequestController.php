<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\ManagerCandidateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
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

    public function accept(int $id): RedirectResponse
    {
        $managerCandidateRequest = ManagerCandidateRequest::with(['manager'])->findOrFail($id);
        $managerCandidateRequest->update(['status' => 'accepted']);

        return redirect()->route('dashboard.manager-candidate-requests.fulfill', $managerCandidateRequest->id)
        ->with('success', 'Manager Candidate Request accepted successfully.');
    }

    public function reject(int $id): RedirectResponse
    {
        $managerCandidateRequest = ManagerCandidateRequest::with(['manager'])->findOrFail($id);
        $managerCandidateRequest->update(['status' => 'rejected']);

        return redirect()->route('dashboard.manager-candidate-requests.fulfill', $managerCandidateRequest->id)
        ->with('success', 'Manager Candidate Request rejected successfully.');
    }

    public function fulfill(int $id): Response
    {
        $managerCandidateRequest = ManagerCandidateRequest::with(['manager'])->findOrFail($id);
        $unassignedCandidates = Candidate::with(['position', 'manager'])->whereDoesntHave('candidateRequestFill')->orderBy('id', 'desc')->get();
        $assignedCandidates = Candidate::with(['position', 'manager'])->whereHas('candidateRequestFill', function ($query) use ($managerCandidateRequest) {
            $query->where('manager_candidate_request_id', $managerCandidateRequest->id);
        })->get();

        // dd($assignedCandidates);

        return Inertia::render('admin/manager-candidate-request/FulfillManagerCandidateRequest', [
            'managerCandidateRequest' => $managerCandidateRequest,
            'unassignedCandidates' => $unassignedCandidates,
            'assignedCandidates' => $assignedCandidates,
        ]);
    }
}
