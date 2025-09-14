<?php

namespace App\Http\Controllers;

use App\Models\ManagerCandidateRequest;
use App\Models\Position;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ManagerManagerCandidateRequestController extends Controller
{
    public function index(): Response
    {
        $managerCandidateRequests = ManagerCandidateRequest::with('position')
            ->where('manager_id', auth()->id())
            ->get();

        return Inertia::render('manager/manager-candidate-request/ManagerCandidateRequests', [
            'managerCandidateRequests' => $managerCandidateRequests,
        ]);
    }

    public function create(): Response
    {
        $positions = Position::all();
        return Inertia::render('manager/manager-candidate-request/CreateManagerCandidateRequest', [
            'positions' => $positions,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'position_id' => 'required|exists:positions,id',
            'requested_count' => 'required|integer|min:1',
            'date_requested' => 'required|date',
            'level' => 'required|string',
            'note' => 'nullable|string',
            'hiring_type' => 'required|string',
        ]);

        ManagerCandidateRequest::create([
            'position_id' => $validated['position_id'],
            'manager_id' => auth()->id(),
            'status' => 'pending',
            'requested_count' => $validated['requested_count'],
            'fulfilled_count' => 0,
            'level' => $validated['level'],
            'note' => $validated['note'],
            'hiring_type' => $validated['hiring_type'],
            'date_requested' => $validated['date_requested'],
        ]);

        return redirect()->route('manager.dashboard.manager-candidate-requests')
            ->with('success', 'Manager Candidate Request created successfully.');
    }

    public function markAsCancelled(string $id): RedirectResponse
    {
        $request = ManagerCandidateRequest::findOrFail($id);
        $request->update(['status' => 'cancelled']);

        return redirect()->route('manager.dashboard.manager-candidate-requests')
            ->with('success', 'Manager Candidate Request marked as cancelled.');
    }
}
