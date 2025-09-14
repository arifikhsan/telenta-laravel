<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ManagerCandidateRequest extends Model
{
    protected $fillable = [
        'manager_id',
        'position_id',
        'status',
        'level',
        'note',
        'hiring_type',
        'requested_count',
        'fulfilled_count',
        'date_requested',
    ];

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function candidateRequestFills()
    {
        return $this->hasMany(CandidateRequestFill::class);
    }
}
