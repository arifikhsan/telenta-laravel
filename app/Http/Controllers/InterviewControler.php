<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\CandidateRequest;
use App\Models\CandidateRequestFill;
use Illuminate\Http\Request;

use App\Models\Position;
use App\Models\User;
use App\Models\Interview;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Helpers\DataTableHelper;
use App\Helpers\CommonHelper;
class InterviewControler extends Controller
{
	public function index()
	{
		return view('interviews'); 
	}


	public function fetch(Request $request)
	{

		$query = CandidateRequestFill::with('candidate')->whereNotNull('interview_manager')->get();

		$formatters = [
			fn($ls, $num) => $num,
			fn($ls) => $ls->candidate->name,
			fn($ls) => CommonHelper::mappingStatus($ls->status),
			fn($ls) => '<a href="'.Storage::url($ls->candidate->cv_path).'" class="btn btn-secondary btn-sm">View CV</a>',
			fn($ls) => $ls->candidate->positions->pluck('name')->implode(', '),
			fn($ls) => $ls->date_filled,
			fn($ls) => $ls->interview_manager,
			fn($ls) => $ls->interview_client,
			fn($ls) => $ls->interview_hr,
			fn($ls) => CommonHelper::mappingSLA($ls->sla),
			fn($ls) => CommonHelper::candidateStatusInterviewCheck($ls->status, $ls->id),
		];

		return response()->json(
			DataTableHelper::fromCollectionWithFormatter($request, $query, $formatters)
		);
	}


	public function confirm(Request $request)
	{

		$code = 500;
		$message = "Internal Server Error (EXP)";

		$validated = $request->validate([
			'id' => 'required',
			'type' => 'required',
			'score' => 'required',
			'notes' => 'required',
			'result' => 'required',
			'interview_client' => 'nullable',
			'interview_hr' => 'nullable',
		]);

		$candidateRequestFillId = $validated['id'];
		$candidateRequest = CandidateRequestFill::findOrFail($candidateRequestFillId);

		$type = "";
		$status = "";
		$isInsert = true;

		$candidateRequestFillData;
		if ($validated['type'] == "1") {

			if ($validated['result'] == "approve") {
				$status = "user_interviewed";
			} else {
				$status = "rejected_internal";
			}

			$type = "internal_interview";
			$candidateRequestFillData  = [
				'interview_client' => $validated['interview_client'],
				'status' => $status,
			];

		} else if ($validated['type'] == "2") {

			if ($validated['result'] == "approve") {
				$status = "waiting_hr_interview";
			} else {
				$status = "rejected_user";
			}

			$type = "user_interview";
			$candidateRequestFillData  = [
				'status' => $status,
			];

		} else if ($validated['type'] == "3") {
			$type = "waiting_hr_interview";

			if ($validated['result'] == "approve") {
				$status = "hr_interviewed";
			}

			$candidateRequestFillData  = [
				'interview_hr' => $validated['interview_hr'],
				'status' => $status,
			];

			$isInsert = false;
		} else if ($validated['type'] == "4") {

			$query = CandidateRequestFill::with('candidateRequest')->findOrFail($candidateRequestFillId);
			$startDate = $query->candidateRequest->date_requested;
			$endDate = $query->interview_hr;

			$sla = CommonHelper::countDays($startDate, $endDate);

			$type = "hr_interview";

			if ($validated['result'] == "approve") {
				$status = "hired";
			} else {
				$status = "rejected_hr";
			}

			$candidateRequestFillData  = [
				'sla' => $sla,
				'status' => $status,
			];

			$candidateId = $candidateRequest->candidate_id;
			$candidate = Candidate::findOrFail($candidateId);
			$candidate->update(['status' => $status]);
		}


		if ($isInsert) {
			$fulfillData  = [
				'candidate_request_fill_id' => $validated['id'],
				'type' => $type,
				'score' => $validated['score'],
				'detail' => $validated['notes'],
			];

			$insert = Interview::create($fulfillData);

			$candidateRequest->update($candidateRequestFillData);

			$candidateId = $candidateRequest->candidate_id;
			$candidate = Candidate::findOrFail($candidateId);
			$candidate->update(['status' => $status]);


			if ($insert && $candidateRequest && $candidate) {
				$code = 200;
				$message = "Confirming Success";
			} else {
				$code = 502;
				$message = "Invalid Confirming";
			}
		} else {
			$candidateRequest->update($candidateRequestFillData);

			if ($candidateRequest) {
				$code = 200;
				$message = "Set Meeting Success";
			} else {
				$code = 502;
				$message = "Invalid Set Meeting";
			}
		}		

		$response = CommonHelper::setResponse($code, $message);
		return response()->json($response, $code);
	}
}
