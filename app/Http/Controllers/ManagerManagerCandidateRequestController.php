<?php

namespace App\Http\Controllers;

use App\Models\ManagerCandidateRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ManagerManagerCandidateRequestController extends Controller
{
    public function index()
    {
        // Fetch manager candidate requests for the authenticated manager
        $managerCandidateRequests = ManagerCandidateRequest::where('manager_id', auth()->id())->get();

        return Inertia::render('manager/manager-candidate-request/ManagerCandidateRequests', [
            'managerCandidateRequests' => $managerCandidateRequests,
        ]);
    }

    public function create()
    {
        return Inertia::render('manager/manager-candidate-request/CreateManagerCandidateRequest');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'requested_count' => 'required|integer|min:1',
            'date_requested' => 'required|date',
        ]);

        ManagerCandidateRequest::create([
            'manager_id' => auth()->id(),
            'status' => 'pending',
            'requested_count' => $validated['requested_count'],
            'fulfilled_count' => 0,
            'date_requested' => $validated['date_requested'],
        ]);

        return redirect()->route('manager.dashboard.manager-candidate-requests')
            ->with('success', 'Manager Candidate Request created successfully.');
    }
}
