<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Role;
use App\Models\AcmMenu;
use App\Models\Candidate;
use App\Models\CandidateRequestFill;

use App\Helpers\CommonHelper;

use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    // public function index()
    // {
    //     $user = auth()->user()->load('role'); // Load the user's role

    //     if ($user->role->name === 'manager') {
    //         return redirect()->route('manager.dashboard');
    //     }

    //     return Inertia::render('Dashboard', [
    //         'user' => $user, // Pass the entire user data or just the role
    //     ]);
    // }

    public function index()
    {
        $user = auth()->user()->load('role'); 
        // $menu = AcmMenu::all();// Load the user's role

        // if ($user->role->name === 'manager') {
        //     return redirect()->route('manager.dashboard');
        // }

        // var_dump(json_encode($menu));
        // die;

        return view('dashboard');

    }

    public function fulfilled() {
        $user = auth()->user()->load('role');

        // $query = CandidateRequestFill::all();

        $year = now()->year; 

        $query = CandidateRequestFill::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
        ->whereYear('created_at', $year)
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        // $response = [];

        $code = 500;
        $message = "Internal Server Error (EXP)";

        if($query) {
            $code = 200;
            $message = "Success";
            // var_dump(json_encode($query)); die;
            // $response = [
            //     "total" => count($query)
            // ];
        } else {
            $code = 404;
            $message = "Data Not Found";
        }

        $response = CommonHelper::setResponseBody($code, $message, $query);
        return response()->json($response, $code);

    }

    public function approved() {
        $user = auth()->user()->load('role');

        $year = now()->year; 

        // $query = CandidateRequestFill::whereNotIn('status', ['rejected_by_manager'])->get();
        $query = CandidateRequestFill::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
        ->whereNotIn('status', ['rejected_by_manager'])
        ->whereYear('created_at', $year)
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        $response = [];

        $code = 500;
        $message = "Internal Server Error (EXP)";

        if($query) {
            $code = 200;
            $message = "Success";
            $response = [
                "total" => count($query)
            ];
        } else {
            $code = 404;
            $message = "Data Not Found";
        }

        $response = CommonHelper::setResponseBody($code, $message, $query);
        return response()->json($response, $code);

    }

    public function internal() {
        $user = auth()->user()->load('role');
        $year = now()->year;

        // $query = CandidateRequestFill::where('status', 'internal_interviewed')->get();
        $query = CandidateRequestFill::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
        ->where('status', 'internal_interviewed')
        ->whereYear('created_at', $year)
        ->groupBy('month')
        ->orderBy('month')
        ->get();

        $response = [];

        $code = 500;
        $message = "Internal Server Error (EXP)";

        if($query) {
            $code = 200;
            $message = "Success";
            $response = [
                "total" => count($query)
            ];
        } else {
            $code = 404;
            $message = "Data Not Found";
        }

        $response = CommonHelper::setResponseBody($code, $message, $query);
        return response()->json($response, $code);

    }

    public function user() {
        $user = auth()->user()->load('role');
        $year = now()->year;

        // $query = CandidateRequestFill::where('status', 'user_interviewed')->get();

        $query = CandidateRequestFill::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
        ->where('status', 'user_interviewed')
        ->whereYear('created_at', $year)
        ->groupBy('month')
        ->orderBy('month')
        ->get();
        $response = [];

        $code = 500;
        $message = "Internal Server Error (EXP)";

        if($query) {
            $code = 200;
            $message = "Success";
            $response = [
                "total" => count($query)
            ];
        } else {
            $code = 404;
            $message = "Data Not Found";
        }

        $response = CommonHelper::setResponseBody($code, $message, $query);
        return response()->json($response, $code);

    }

    public function applicant() {
        $user = auth()->user()->load('role');
        $year = now()->year;

        $now = Carbon::now(); 
        $year = $now->year;
        $month = $now->month;


        $rawData = Candidate::selectRaw('DAY(created_at) as day, COUNT(*) as total')
        ->whereYear('created_at', $year)
        ->whereMonth('created_at', $month)
        ->groupBy('day')
        ->orderBy('day')
        ->pluck('total', 'day'); 


        $dailyData = [];

        $code = 500;
        $message = "Internal Server Error (EXP)";

        if($rawData) {
            $code = 200;
            $message = "Success";
            for ($d = 1; $d <= $now->daysInMonth; $d++) {
                $dailyData[$d] = $rawData[$d] ?? 0;
            }
        } else {
            $code = 404;
            $message = "Data Not Found";
        }

        $response = CommonHelper::setResponseBody($code, $message, $dailyData);
        return response()->json($response, $code);

    }
}
