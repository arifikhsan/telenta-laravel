<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateRequestFill extends Model
{
    protected $fillable = [
        'manager_candidate_request_id',
        'candidate_id',
        'date_filled',
    ];

    public function managerCandidateRequest()
    {
        return $this->belongsTo(ManagerCandidateRequest::class);
    }

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }
}
