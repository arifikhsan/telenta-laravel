<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateRequestFill extends Model
{
    protected $fillable = [
        'candidate_request_id',
        'candidate_id',
        'date_filled',
        'interview_manager',
        'interview_client',
        'interview_hr',
        'sla',
        'status',
    ];

    public function candidateRequest()
    {
        return $this->belongsTo(CandidateRequest::class);
    }

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }


    public function interview()
    {
        return $this->hasMany(Interview::class);
    }
}