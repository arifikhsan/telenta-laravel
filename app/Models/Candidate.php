<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
