<?php

namespace App\Http\Controllers;

use App\Models\Interview;
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

class InterviewDetailControler extends Controller
{

    public function index(int $id)
    {
        return view('interview_detail'); 
    }


    public function fetch(Request $request, int $id)
    {
        $query = Interview::where('candidate_request_fill_id', $id)->get();

        $formatters = [
            fn($ls, $num) => $num,
            fn($ls) => CommonHelper::mappingInterviewType($ls->type),
            fn($ls) => $ls->score,
            fn($ls) => $ls->detail,
        ];

        return response()->json(
            DataTableHelper::fromCollectionWithFormatter($request, $query, $formatters)
        );
    }
}
