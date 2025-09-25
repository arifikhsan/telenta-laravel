<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\QuestionPositionMap;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

use App\Helpers\DataTableHelper;
use App\Helpers\CommonHelper;


class QuestionController extends Controller
{
    // public function index(): Response
    // {
    //     $questions = Question::all();
    //     return Inertia::render('Questions', ['questions' => $questions]);
    // }

	public function index()
	{
		return view('questions');
	}

	public function fetch(Request $request)
	{

		$questions = Question::all();

		$formatters = [
			fn($ls, $num) => $num,
			fn($ls) => $ls->question,
			fn($ls) => CommonHelper::dateFormat($ls->created_at),
			fn($ls) => CommonHelper::dateFormat($ls->updated_at),
			fn($ls) => '
			<button type="button" class="btn btn-sm btn-outline-dark as-update" title="Edit" onclick="edit('.$ls->id.')">
			<i class="bx bx-edit me-0"></i>
			</button>
			<button type="button" class="btn btn-sm btn-outline-danger as-delete" title="Delete" onclick="deleteRow('.$ls->id.')">
			<i class="bx bx-trash me-0"></i>
			</button>',
		];

		return response()->json(
			DataTableHelper::fromCollectionWithFormatter($request, $questions, $formatters)
		);
	}


	public function store(Request $request)
	{
		$code = 500;
		$message = "Internal Server Error (EXP)";


		$validated = $request->validate([
			'question' => ['required']
		]);

		$data = [
			'question' => $validated['question'],
			'status' => 'active'
		];

        // Insert the candidate data
		$insert = Question::create($data);

		if ($insert) {
			$code = 200;
			$message = "Insert Data Success";
		} else {
			$code = 502;
			$message = "Invalid Insert Data";
		}

		$response = CommonHelper::setResponse($code, $message);
		return response()->json($response, $code);
	}



	public function show(Request $request)
	{
		$code = 500;
		$message = "Internal Server Error (EXP)";


		$validated = $request->validate([
			'id' => ['required']
		]);

		$id = $request->input('id');

		$rowData = Question::findOrFail($id);

		if($rowData) {
			$code = 200;
			$message = "Success";
		} else {
			$code = 404;
			$message = "Data Not Found";
		}

		$response = CommonHelper::setResponseBody($code, $message, $rowData);
		return response()->json($response, $code);
	}


	public function update(Request $request)
	{
		$code = 500;
		$message = "Internal Server Error (EXP)";

		$validated = $request->validate([
			'id' => ['required'],
			'question' => ['required']
		]);

		$id = $request->input('id');

		$rowData = Question::findOrFail($id);

		if ($rowData) {
			$updated = $rowData->update($validated);
			if ($updated) {
				$code = 200;
				$message = "Update Data Success";
			} else {
				$code = 502;
				$message = "Invalid Update Data";
			}
		}

		$response = CommonHelper::setResponse($code, $message);
		return response()->json($response, $code);

		$candidate->update($validated);
	}


	public function destroy(Request $request)
	{
		$code = 500;
		$message = "Internal Server Error (EXP)";

		$validated = $request->validate([
			'id' => ['required']
		]);

		$id = $request->input('id');

		$rowData = Question::findOrFail($id);

		if($rowData) {
			$code = 200;
			$message = "Success";
			$rowData->delete();
		} else {
			$code = 404;
			$message = "Data Not Found";
		}

		$response = CommonHelper::setResponseBody($code, $message, $rowData);
		return response()->json($response, $code);
	}

	public function list(int $id)
	{
		$mappedQuestionIds = QuestionPositionMap::where('position_id', $id)
		->pluck('question_id')
		->toArray();

		$questions = Question::select('id', 'question')
		->whereNotIn('id', $mappedQuestionIds)
		->orderBy('question')
		->get();

		// $questions = Question::select('id', 'question')->orderBy('question')->get();
		return response()->json($questions);
	}
}
