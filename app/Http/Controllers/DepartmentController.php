<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

use App\Helpers\DataTableHelper;
use App\Helpers\CommonHelper;

class DepartmentController extends Controller
{
    // public function index(): Response
    // {
    //     $departments = Department::all();

    //     return Inertia::render('Departments', ['departments' => $departments]);
    // }

    public function index()
    {
        return view('departments');
    }

    public function fetch(Request $request)
    {

        $departments = Department::all();

        $formatters = [
            fn($ls, $num) => $num,
            fn($ls) => $ls->name,
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
            DataTableHelper::fromCollectionWithFormatter($request, $departments, $formatters)
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
        $insert = Department::create($data);

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

        $rowData = Department::findOrFail($id);

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

        $rowData = Department::findOrFail($id);

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

        $rowData = Department::findOrFail($id);

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
