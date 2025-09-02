<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\CandidateRequestFill;
use App\Models\ManagerCandidateRequest;
use Illuminate\Http\Request;

class CandidateRequestFillController extends Controller
{
    public function addCandidate(Request $request)
    {
        // Validate and process the request
        // it has candidate_id and manager_request_id
        $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'manager_candidate_request_id' => 'required|exists:manager_candidate_requests,id',
        ]);

        // Fulfill the request logic here
        CandidateRequestFill::create([
            'candidate_id' => $request->input('candidate_id'),
            'manager_candidate_request_id' => $request->input('manager_candidate_request_id'),
            'date_filled' => now(),
        ]);

        $managerCandidateRequestId = $request->input('manager_candidate_request_id');
        $managerCandidateRequest = ManagerCandidateRequest::findOrFail($managerCandidateRequestId);
        $managerCandidateRequest->update(['fulfilled_count' => $managerCandidateRequest->fulfilled_count + 1]);

        // redirect to ManagerCandidateRequestController on function fulfill
        return redirect()->route('dashboard.manager-candidate-requests.fulfill', $request->input('manager_candidate_request_id'));
    }

    public function removeCandidate(Request $request)
    {
        // Validate and process the request
        $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
            'manager_candidate_request_id' => 'required|exists:manager_candidate_requests,id',
        ]);

        // Remove the candidate from the request logic here
        CandidateRequestFill::where([
            'candidate_id' => $request->input('candidate_id'),
            'manager_candidate_request_id' => $request->input('manager_candidate_request_id'),
        ])->delete();

        $managerCandidateRequestId = $request->input('manager_candidate_request_id');
        $managerCandidateRequest = ManagerCandidateRequest::findOrFail($managerCandidateRequestId);
        $managerCandidateRequest->update(['fulfilled_count' => $managerCandidateRequest->fulfilled_count - 1]);

        // redirect to ManagerCandidateRequestController on function fulfill
        return redirect()->route('dashboard.manager-candidate-requests.fulfill', $request->input('manager_candidate_request_id'));
    }
}
