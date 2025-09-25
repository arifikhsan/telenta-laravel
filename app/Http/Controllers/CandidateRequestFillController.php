<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\CandidateRequest;
use App\Models\CandidateRequestFill;
use Illuminate\Http\Request;

use App\Models\Position;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Helpers\DataTableHelper;
use App\Helpers\CommonHelper;

class CandidateRequestFillController extends Controller
{

    public function index(int $id)
    {
        return view('candidate_request_fulfill'); 
    }


    public function fetch(Request $request, int $id)
    {
        $candidateRequest = CandidateRequest::with(['manager'])->findOrFail($id);
        $query;
        $formatters;
        if ($candidateRequest->status == "pending") {
            $query = Candidate::where('status', 'idle')->get()
            ->map(function ($candidate) {
                $candidate->cv_url = $candidate->cv_path
                ? Storage::url($candidate->cv_path)
                : null;

                return $candidate;
            });

            $formatters = [
                fn($ls, $num) => $num,
                fn($ls) => $ls->name,
                fn($ls) => CommonHelper::mappingStatus($ls->status),
                fn($ls) => '<a href="'.Storage::url($ls->cv_path).'" class="btn btn-secondary btn-sm">View CV</a>',
                fn($ls) => $ls->positions->pluck('name')->implode(', '),
                fn($ls) => '',
                fn($ls) => CommonHelper::candidateStatusRequestCheck($ls->status, $ls->id),
            ];
        } else {
            $query = CandidateRequestFill::with('candidate')->where('candidate_request_id', $id)->get();

            $formatters = [
                fn($ls, $num) => $num,
                fn($ls) => $ls->candidate->name,
                fn($ls) => CommonHelper::mappingStatus($ls->status),
                fn($ls) => '<a href="'.Storage::url($ls->candidate->cv_path).'" class="btn btn-secondary btn-sm">View CV</a>',
                fn($ls) => $ls->candidate->positions->pluck('name')->implode(', '),
                fn($ls) => $ls->date_filled,
                fn($ls) => CommonHelper::candidateStatusRequestCheck($ls->status, $ls->id),
            ];

            // $query = Candidate::all()
            // ->map(function ($candidate) {
            //     $candidate->cv_url = $candidate->cv_path
            //     ? Storage::url($candidate->cv_path)
            //     : null;

            //     return $candidate;
            // });
        }

        

        return response()->json(
            DataTableHelper::fromCollectionWithFormatter($request, $query, $formatters)
        );
    }

    public function store(Request $request)
    {

        $code = 500;
        $message = "Internal Server Error (EXP)";

        $validated = $request->validate([
            'candidate_id' => 'required',
            'request_id' => 'required',
        ]);

        
        $fulfillData  = [
            'candidate_id' => $validated['candidate_id'],
            'candidate_request_id' => $validated['request_id'],
            'date_filled' => now(),
            'status' => 'cv_reviewed'
        ];

        $insert = CandidateRequestFill::create($fulfillData);

        $candidateRequestId = $validated['request_id'];
        $candidateRequest = CandidateRequest::findOrFail($candidateRequestId);
        $candidateRequest->update(['fulfilled_count' => $candidateRequest->fulfilled_count + 1]);

        $candidateId = $validated['candidate_id'];
        $candidate = Candidate::findOrFail($candidateId);
        $candidate->update(['status' => 'cv_reviewed']);


        if ($insert && $candidateRequest && $candidate) {
            $code = 200;
            $message = "Push Candidate Success";
        } else {
            $code = 502;
            $message = "Invalid Push Candidate";
        }

        $response = CommonHelper::setResponse($code, $message);
        return response()->json($response, $code);
    }

    public function approve(Request $request)
    {

        $code = 500;
        $message = "Internal Server Error (EXP)";


        $validated = $request->validate([
            'id' => ['required'],
            'internal_interview' => ['required']
        ]);

        $id = $validated['id'];


        $interviewData  = [
            'interview_manager' => $validated['internal_interview'],
            'status' => 'internal_interviewed'
        ];

        $rowData = CandidateRequestFill::with(['candidate'])->findOrFail($id);
        $rowData->update($interviewData);
        $rowData->candidate->update(['status' => 'internal_interviewed']);

        if($rowData) {
            $code = 200;
            $message = "Success";
        } else {
            $code = 404;
            $message = "Data Not Found";
        }

        $response = CommonHelper::setResponse($code, $message);
        return response()->json($response, $code);
    }

    public function reject(Request $request)
    {

        $code = 500;
        $message = "Internal Server Error (EXP)";


        $validated = $request->validate([
            'id' => ['required']
        ]);

        $id = $request->input('id');

        $rowData = CandidateRequestFill::with(['candidate'])->findOrFail($id);
        $rowData->update(['status' => 'rejected_by_manager']);
        $rowData->candidate->update(['status' => 'rejected_by_manager']);

        if($rowData) {
            $code = 200;
            $message = "Success";
        } else {
            $code = 404;
            $message = "Data Not Found";
        }

        $response = CommonHelper::setResponse($code, $message);
        return response()->json($response, $code);
    }



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
