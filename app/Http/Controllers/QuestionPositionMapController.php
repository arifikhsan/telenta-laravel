<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\Question;
use App\Models\QuestionPositionMap;
use Illuminate\Http\Request;
use Inertia\Response;


use App\Helpers\DataTableHelper;
use App\Helpers\CommonHelper;

class QuestionPositionMapController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(int $id)
    {
        // $id = $request->query('id');

        $position = Position::findOrFail($id);

        // $questionPositionMap = QuestionPositionMap::with(['position', 'question'])
        // ->where('position_id', $id)
        // ->get(); 
        // return Inertia::render('QuestionPositionMaps', ['questionPositionMaps' => $questionPositionMap]);

        return view('question_position_map', ['position_name' => $position->name]);
    }

    public function fetch(Request $request, int $id)
    {
        $query = QuestionPositionMap::with(['position', 'question'])
        ->where('position_id', $id)
        ->get();

        $formatters = [
            fn($ls, $num) => $num,
            fn($ls) => $ls->question->question,
            fn($ls) => '
            <button type="button" class="btn btn-sm btn-outline-danger as-delete" title="Delete" onclick="deleteRow('.$ls->id.')">
            <i class="bx bx-trash me-0"></i>
            </button>',
        ];        

        return response()->json(
            DataTableHelper::fromCollectionWithFormatter($request, $query, $formatters)
        );
    }

    
    public function store(Request $request)
    {
        $code = 500;
        $message = "Internal Server Error (EXP)";

        $validated = $request->validate([
            'id' => 'required',
            'questions' => 'required|array',
            'questions.*' => 'exists:questions,id'
        ]);

        $positionId = $validated['id'];
        $questionIds = $validated['questions'];

        $insertData = [];

        foreach ($questionIds as $questionId) {
            $insertData[] = [
                'position_id' => $positionId,
                'question_id' => $questionId,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }


        $inserted = QuestionPositionMap::insert($insertData);

        if ($inserted) {
            $code = 200;
            $message = "Insert Data Success";
        } else {
            $code = 502;
            $message = "Invalid Insert Data";
        }

        $response = CommonHelper::setResponse($code, $message);
        return response()->json($response, $code);
    }


    public function destroy(Request $request)
    {
        $code = 500;
        $message = "Internal Server Error (EXP)";

        $validated = $request->validate([
            'id' => ['required']
        ]);

        $id = $request->input('id');

        $rowData = QuestionPositionMap::findOrFail($id);

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
}
