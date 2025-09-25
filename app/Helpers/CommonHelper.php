<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class CommonHelper
{

    public static function mappingStatus($status) {
        $response  = "";

        if ($status == 'cv_reviewed') {
            $response = 'Waiting CV Review';
        } else if ($status == 'internal_interviewed') {
            $response = 'Internal Interviewed';
        } else if ($status == 'user_interviewed') {
            $response = 'User Interviewed';
        } else if ($status == 'waiting_hr_interview') {
            $response = 'Waiting HR Interview';
        } else if ($status == 'hr_interviewed') {
            $response = 'HR Interviewed';
        } else if ($status == 'hired') {
            $response = 'Hired';
        } else if ($status == 'rejected_internal') {
            $response = 'Rejected Interview Manager';
        } else if ($status == 'rejected_user') {
            $response = 'Rejected Interview User';
        } else if ($status == 'rejected_hr') {
            $response = 'Rejected Interview HR';
        } else if ($status == 'rejected_by_manager') {
            $response = 'Rejected by Manager';
        } else if ($status == 'idle') {
            $response = 'Idle';
        }

        return $response;
    }

    public static function mappingStatusCandidateRequest($status) {
        $response  = "";

        if ($status == 'pending') {
            $response = '<strong class="text-warning">Pending</strong>';
        } else if ($status == 'fulfilled') {
            $response = '<strong class="text-primary">Candidate Fulfilled</strong>';
        } else if ($status == 'user_interviewed') {
            $response = 'User Interviewed';
        } else if ($status == 'rejected') {
            $response = '<strong class="text-danger">Request Rejected by Manager</strong>';
        }
        return $response;
    }

    public static function mappingInterviewType($type) {
        $response  = "";

        if ($type == 'internal_interview') {
            $response = 'Interviewed with Manager';
        } else if ($type == 'user_interview') {
            $response = 'Interviewed with User';
        } else if ($type == 'hr_interview') {
            $response = 'Interviewed with HR';
        } 

        return $response;
    }

    public static function mappingSLA($sla) {
        $response  = "";

        if ($sla > 10) {
            $response = '<span class="text-danger">'. $sla .'</span>';
        } else {
            $response = '<span class="text-success">'. $sla .'</span>';
        }
        return $response;
    }

    public static function setActionCandidate($status, $id)  {
        $response = "";

        if ($status == 'idle')  {
            $response = '
            <button type="button" class="btn btn-sm btn-outline-dark as-update" title="Edit" onclick="edit('.$id.')">
            <i class="bx bx-edit me-0"></i>
            </button>
            <button type="button" class="btn btn-sm btn-outline-danger as-delete" title="Delete" onclick="deleteRow('.$id.')">
            <i class="bx bx-trash me-0"></i>
            </button>';
        } else if ($status == 'rejected_by_manager' || $status == "rejected_internal" || $status == "rejected_user" || $status == "rejected_hr") {
            $response = '
            <button type="button" class="btn btn-sm btn-outline-dark as-update" title="Set Idle" onclick="updateIdle('.$id.')">
            Set Idle
            </button>';
        }

        return $response;
    }


    public static function replacementCheck($isReplacement, $employee) {
        $response  = "";

        if($isReplacement == 'replacement') {
            $response = 'Replacing ' . $employee;
        } else {
            $response = 'New Recruitment';
        }

        return $response;
    }


    public static function dateFormat($date) {
        return date("d-m-Y H:m:s", strtotime($date));
    }

    public static function setResponse($code, $message){
        /* this function used for set response */
        $data = array(
            'code' => $code,
            'message' => $message
        );

        return $data;
    }

    public static function setResponseBody($code, $message, $body){
        /* this function used for set response */
        $data = array(
            'code' => $code,
            'message' => $message,
            'body' => $body
        );

        return $data;
    }

    public static function formatRupiah($angka, $withPrefix = true) {
        $hasil = number_format($angka, 0, ',', '.');
        return $withPrefix ? 'Rp ' . $hasil : $hasil;
    }

    public static function statusRequestCheck($status, $id) {
        $role = Session::get('role');

        $response  = "";

        if ($role == "admin" && $status == "pending") {
            $response = '<button type="button" class="btn btn-sm btn-outline-dark as-update" title="Pick Candidate" onclick="detail('.$id.')">
            Pick Candidate</button>
            <button type="button" class="btn btn-sm btn-outline-primary as-update" title="Release" onclick="processAssest('.$id.')">
            Release</button>';
        } else if ($role == "admin" && $status == "fulfilled") {
            $response = '<button type="button" class="btn btn-sm btn-outline-dark as-update" title="Detail" onclick="detail('.$id.')">
            Detail</button>';
        } else if ($role == "manager" && $status == "pending") {
            $response = '<button type="button" class="btn btn-sm btn-outline-danger as-update" title="Abort" onclick="processAbort('.$id.')">
            Reject</button>';
        }  else if ($role == "manager" && $status == "fulfilled") {
            $response = '<button type="button" class="btn btn-sm btn-outline-dark as-update" title="Detail" onclick="detail('.$id.')">
            Detail</button>';
        }

        return $response;
    }

    public static function candidateStatusRequestCheck($status, $id) {
        $role = Session::get('role');

        $response  = "";

        if ($role == "admin" && $status == "idle") {
            $response = '<button type="button" class="btn btn-sm btn-outline-dark as-update" title="Push" onclick="pushCandidate('.$id.')">
            Push</button>';
        } else if ($role == "manager" && $status == "cv_reviewed") {
            $response = '<button type="button" class="btn btn-sm btn-outline-primary as-update" title="Approve" onclick="approve('.$id.')">
            Approve</button>
            <button type="button" class="btn btn-sm btn-outline-danger as-update" title="Reject" onclick="reject('.$id.')">
            Reject</button>';
        } 
        return $response;
    }

    public static function candidateStatusInterviewCheck($status, $id) {
        $role = Session::get('role');

        $response  = "";

        if ($role == "manager" && $status == "internal_interviewed") {
            $response = '<button type="button" class="btn btn-sm btn-outline-dark as-update" title="Detail" onclick="detail('.$id.')">
            Detail</button>
            <button type="button" class="btn btn-sm btn-outline-primary as-update" title="Confirmation" onclick="confirmation('.$id.', 1)">
            Confirmation</button>';
        } else if ($role == "manager" && $status == "user_interviewed") {
            $response = '<button type="button" class="btn btn-sm btn-outline-dark as-update" title="Detail" onclick="detail('.$id.')">
            Detail</button>
            <button type="button" class="btn btn-sm btn-outline-primary as-update" title="Confirmation" onclick="confirmation('.$id.', 2)">
            Confirmation</button>';
        } else if ($role == "manager" && $status == "waiting_hr_interview") {
            $response = '<button type="button" class="btn btn-sm btn-outline-dark as-update" title="Detail" onclick="detail('.$id.')">
            Detail</button>';
        } else if ($role == "manager" && $status == "hr_interviewed") {
            $response = '<button type="button" class="btn btn-sm btn-outline-dark as-update" title="Detail" onclick="detail('.$id.')">
            Detail</button>';
        } else if ($role == "admin" && $status == "internal_interviewed") {
            $response = '<button type="button" class="btn btn-sm btn-outline-dark as-update" title="Detail" onclick="detail('.$id.')">
            Detail</button>';
        } else if ($role == "admin" && $status == "user_interviewed") {
            $response = '<button type="button" class="btn btn-sm btn-outline-dark as-update" title="Detail" onclick="detail('.$id.')">
            Detail</button>';
        } else if ($role == "admin" && $status == "waiting_hr_interview") {
            $response = '<button type="button" class="btn btn-sm btn-outline-dark as-update" title="Detail" onclick="detail('.$id.')">
            Detail</button>
            <button type="button" class="btn btn-sm btn-outline-primary as-update" title="Set HR Interview" onclick="setMeeting('.$id.', 3)">
            Set HR Interview</button>';
        } else if ($role == "admin" && $status == "hr_interviewed") {
            $response = '<button type="button" class="btn btn-sm btn-outline-dark as-update" title="Detail" onclick="detail('.$id.')">
            Detail</button>
            <button type="button" class="btn btn-sm btn-outline-primary as-update" title="Confirmation" onclick="hrConfirmation('.$id.', 4)">
            Confirmation</button>';
        }

        return $response;
    }

    public static function countDays(string $startDate, string $endDate): int
    {
        $start = Carbon::parse($startDate);
        $end   = Carbon::parse($endDate);

        return $start->diffInDays($end);
    }

    public static function countLatestScore($candidates)
    {


        // var_dump(json_encode($candidates)); die;
        $response = "";
        if ($candidates->status == "rejected_internal" || $candidates->status == "rejected_user" || $candidates->status == "rejected_hr" || $candidates->status == "hired") {

            if ($candidates->candidateRequestFill->interview) {
                $interviews = $candidates->candidateRequestFill->interview;
                $internalInterivew = 0;
                $userInterivew = 0;
                foreach ($interviews as $interview) {
                    if ($interview->type == "internal_interview") {
                        $internalInterivew = $interview->score;
                    } else if ($interview->type == "user_interview") {
                        $userInterivew = $interview->score;
                    } 
                }

                if ($internalInterivew != 0 && $userInterivew != 0) {
                    $response = ($internalInterivew + $userInterivew) / 2;
                } else if ($internalInterivew != 0) {
                    $response = $internalInterivew;
                } else if ($userInterivew != 0) {
                    $response = $userInterivew;
                }
            }
        }
        
        

        return $response;
    }
}