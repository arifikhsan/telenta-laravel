<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    protected $fillable = [
        'candidate_request_fill_id',
        'type',
        'score',
        'detail',
    ];

    public function candidateRequestFill()
    {
        return $this->belongsTo(CandidateRequestFill::class);
    }
}
