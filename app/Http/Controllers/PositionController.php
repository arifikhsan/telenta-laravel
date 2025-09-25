<?php

namespace App\Http\Controllers;

use App\Models\Position;
use App\Models\QuestionPositionMap;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

use App\Helpers\DataTableHelper;
use App\Helpers\CommonHelper;

class PositionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index(): Response
    // {
    //     $positions = Position::all();
    //     return Inertia::render('Positions', ['positions' => $positions]);
    // }

    public function index()
    {
        return view('positions');
    }

    public function fetch(Request $request)
    {

        $positions = Position::all();

        $formatters = [
            fn($ls, $num) => $num,
            fn($ls) => $ls->name,
            fn($ls) => '<a href="' . url("dashboard/positions/questions/" . $ls->id) . '" class="btn btn-secondary btn-sm">Show Questions</a>',
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
            DataTableHelper::fromCollectionWithFormatter($request, $positions, $formatters)
        );
    }

    
    public function store(Request $request)
    {
        $code = 500;
        $message = "Internal Server Error (EXP)";


        $validated = $request->validate([
            'name' => ['required']
        ]);

        $data = [
            'name' => $validated['name']
        ];

        // Insert the candidate data
        $insert = Position::create($data);

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

        $rowData = Position::findOrFail($id);

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
            'name' => ['required']
        ]);

        $id = $request->input('id');

        $rowData = Position::findOrFail($id);

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
    }


    public function destroy(Request $request)
    {
        $code = 500;
        $message = "Internal Server Error (EXP)";

        $validated = $request->validate([
            'id' => ['required']
        ]);

        $id = $request->input('id');

        $rowData = Position::findOrFail($id);

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

    public function list()
    {
        $positions = Position::select('id', 'name')->orderBy('name')->get();

       return response()->json($positions);
    }
}
