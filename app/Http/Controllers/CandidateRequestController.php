<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Position;
use App\Models\User;
use App\Models\CandidateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Helpers\DataTableHelper;
use App\Helpers\CommonHelper;

use Illuminate\Support\Facades\Session;

class CandidateRequestController extends Controller
{
    public function index()
    {
        return view('candidate_requests');
    }

    public function fetch(Request $request)
    {

        $role = Session::get('role');
        $query;
        if ($role == "admin") {
            $query = CandidateRequest::with(['manager.department'])->get();
        } else if ($role == "manager") {

            $managers = User::with(['client', 'department'])->findOrFail(auth()->id());
            $departmentId = $managers->department_id;
            $query = CandidateRequest::with(['manager.department'])
            ->where('manager_id', auth()->id())
            ->whereHas('manager.department', function ($q) use ($departmentId) {
                $q->where('id', $departmentId);
            })->get();
        }

        $formatters = [
            fn($ls, $num) => $num,
            fn($ls) => $ls->position->name,
            fn($ls) => $ls->manager->department->name != null ? $ls->manager->department->name : '',
            fn($ls) => ucfirst($ls->level),
            fn($ls) => CommonHelper::formatRupiah($ls->salary_min). " - ". CommonHelper::formatRupiah($ls->salary_max),
            fn($ls) => CommonHelper::mappingStatusCandidateRequest($ls->status),
            fn($ls) => $ls->requested_count,
            fn($ls) => $ls->fulfilled_count,
            fn($ls) => CommonHelper::dateFormat($ls->date_requested),
            fn($ls) => CommonHelper::replacementCheck($ls->category, $ls->replacementEmployee->pluck('name')->implode(', ')),
            fn($ls) => $ls->detail,
            fn($ls) => CommonHelper::statusRequestCheck($ls->status, $ls->id),
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
            'position'         => 'required',

            'level'             => 'required|in:junior,middle,senior',
            'detail'            => 'nullable|string|max:1000',

            'salary_min'        => 'required|numeric|min:0',
            'salary_max'        => 'required|numeric|gte:salary_min',

            'count'             => 'required|integer|min:1|max:20',
            'category'          => 'required',

            'replacement_employees'   => 'required_if:category,replacement|array',
            'replacement_employees.*' => 'required_if:category,replacement|string|min:1|max:20',
        ]);

        $data = [
            'manager_id' => auth()->id(),
            'position_id' => $validated['position'],
            'status' => 'pending',
            'requested_count' => $validated['count'],
            'fulfilled_count' => 0,
            'date_requested' => now(),

            'level' => $validated['level'],
            'detail' => $validated['detail'],
            'salary_min' => $validated['salary_min'],
            'salary_max' => $validated['salary_max'],
            'category' => $validated['category'],
        ];



        $insert = CandidateRequest::create($data);

        if ($validated['category'] == 'replacement') {
            $employees = collect($validated['replacement_employees'])
            ->map(fn($name) => ['name' => $name])
            ->toArray();

            $insert->replacementEmployee()->createMany($employees);
        }

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

    public function reject(Request $request)
    {

        $code = 500;
        $message = "Internal Server Error (EXP)";


        $validated = $request->validate([
            'id' => ['required']
        ]);

        $id = $request->input('id');

        $rowData = CandidateRequest::with(['manager'])->findOrFail($id);
        $rowData->update(['status' => 'rejected']);

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

    public function show(Request $request)
    {

        $code = 500;
        $message = "Internal Server Error (EXP)";


        $validated = $request->validate([
            'request_id' => ['required']
        ]);

        $id = $request->input('request_id');

        $rowData = CandidateRequest::with(['manager.department'])->where('id', $id)->first();
        $body;
        // var_dump(json_encode($rowData)); die;
        if($rowData) {
            $code = 200;
            $message = "Success";

            $body = "Department " . $rowData->manager->department->name . " requested " . $rowData->requested_count . " candidates for " . CommonHelper::replacementCheck($rowData->category, $rowData->replacementEmployee->pluck('name')->implode(', ')) . " in position " . $rowData->position->name . " level " . ucfirst($rowData->level) . "<br>" . 
            " range salary " . CommonHelper::formatRupiah($rowData->salary_min) . " - ". CommonHelper::formatRupiah($rowData->salary_max) . ". The detail is " . $rowData->detail;
        } else {
            $code = 404;
            $message = "Data Not Found";
        }

        $response = CommonHelper::setResponseBody($code, $message, $body);
        return response()->json($response, $code);
    }

    public function push(Request $request)
    {

        $code = 500;
        $message = "Internal Server Error (EXP)";


        $validated = $request->validate([
            'id' => ['required']
        ]);

        $id = $request->input('id');

        $rowData = CandidateRequest::with(['manager'])->findOrFail($id);


        if($rowData) {
            $code = 200;
            $message = "Success";

            if($rowData->fulfilled_count == 0) {
                $code = 400;
                $message = "Please Fulfill candidates first";
            } else {
                $rowData->update(['status' => 'fulfilled']);
            }


        } else {
            $code = 404;
            $message = "Data Not Found";
        }

        $response = CommonHelper::setResponseBody($code, $message, $rowData);
        return response()->json($response, $code);
    }


}
