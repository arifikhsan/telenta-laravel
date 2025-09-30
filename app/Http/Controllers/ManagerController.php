<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use Inertia\Inertia;
use Inertia\Response;
use App\Helpers\DataTableHelper;
use App\Helpers\CommonHelper;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class ManagerController extends Controller
{
    // public function index(): Response
    // {
    //     $managers = User::with(['client', 'department'])->
    //     whereHas('role', function($query) {
    //         $query->where('name', 'manager');
    //     })->get();

    //     return Inertia::render('Managers', ['managers' => $managers]);
    // }

    public function index()
    {
        return view('managers');
    }

    public function fetch(Request $request)
    {

    	$managers = User::with(['client', 'department'])->
    	whereHas('role', function($query) {
    		$query->where('name', 'manager');
    	})->get();

    	// var_dump(json_encode($managers)); die;

        $formatters = [
            fn($ls, $num) => $num,
            fn($ls) => $ls->name,
            fn($ls) => $ls->email,
            fn($ls) => $ls->client != null ? $ls->client->name : "",
            fn($ls) => $ls->department != null ? $ls->department->name : "",
            fn($ls) => CommonHelper::dateFormat($ls->created_at),
            fn($ls) => CommonHelper::dateFormat($ls->updated_at),
            fn($ls) => $ls->email_verified_at != null ? CommonHelper::dateFormat($ls->email_verified_at) : "",
            fn($ls) => $ls->email_verified_at == null ? '<button type="button" class="btn btn-sm btn-outline-success as-update" title="Verified" onclick="approve('.$ls->id.')">
            Verified Access</button>' : "",
        ];

        return response()->json(
            DataTableHelper::fromCollectionWithFormatter($request, $managers, $formatters)
        );
    }

    public function approve(Request $request)
    {

        $code = 500;
        $message = "Internal Server Error (EXP)";


        $validated = $request->validate([
            'id' => ['required']
        ]);

        $id = $validated['id'];

        $updated = User::findOrFail($id);
        $updated->update(['email_verified_at' => now()]);


        if($updated) {
            $code = 200;
            $message = "Success";
        } else {
            $code = 404;
            $message = "Data Not Found";
        }

        $response = CommonHelper::setResponse($code, $message);
        return response()->json($response, $code);
    }

    public function verify() 
    {

        // $role = Session::get('role');

        $code = 500;
        $message = "Internal Server Error (EXP)";

        if(auth()->user()->client_id != null && auth()->user()->department_id != null) {
            $code = 200;
            $message = "Success";
        } else {
            $code = 404;
            $message = "Data Not Found";
        }

        $response = CommonHelper::setResponse($code, $message);
        return response()->json($response, $code);

    }

    public function update(Request $request) 
    {
        $code = 500;
        $message = "Internal Server Error (EXP)";

        $validated = $request->validate([
            'department_id' => ['required', 'integer'],
            'client_id'     => ['required_without:client', 'nullable', 'integer'],
            'client'        => ['required_without:client_id', 'nullable', 'string', 'max:255'],
        ]);

        $user = User::findOrFail(auth()->id());

        try {
            if (!empty($validated['client_id'])) {
                $clientId = $validated['client_id'];
            } elseif (!empty($validated['client'])) {
                $client = Client::create([
                    'name' => $validated['client']
                ]);
                $clientId = $client->id;
            } else {
                $clientId = null;
            }
            $user->update([
                'department_id' => $validated['department_id'],
                'client_id'     => $clientId,
            ]);

            $code = 200;
            $message = "Update Data Success";

        } catch (\Exception $e) {
            $code = 500;
            $message = "Internal Server Error: " . $e->getMessage();
        }

        $response = CommonHelper::setResponse($code, $message);
        return response()->json($response, $code);
    }

    public function show() 
    {
        $code = 200;
        $message = "Success";
        
        $response = CommonHelper::setResponseBody($code, $message, auth()->user());
        return response()->json($response, $code);

    }

}
