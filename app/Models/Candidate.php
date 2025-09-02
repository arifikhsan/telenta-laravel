<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Candidate extends Model
{
    protected $fillable = [
        'name',
        'position_id',
        'manager_id',
        'status',
        'days_required',
        'proposed_date',
        'cv_review_date',
        'hr_interview_date',
        'cv_path',
    ];

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    protected $appends = ['cv_url'];

    public function getCvUrlAttribute()
    {
        return $this->cv_path
            ? Storage::url($this->cv_path)
            : null;
    }

    public function candidateRequestFill() {
        return $this->hasOne(CandidateRequestFill::class);
    }
}
