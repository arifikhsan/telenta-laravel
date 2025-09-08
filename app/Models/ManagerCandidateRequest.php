<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManagerCandidateRequest extends Model
{
    protected $fillable = [
        'manager_id',
        'position_id',
        'status',
        'level',
        'note',
        'requested_count',
        'fulfilled_count',
        'date_requested',
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function position()
    {
        return $this->belongsTo(Position::class, 'position_id');
    }

    public function candidateRequestFills()
    {
        return $this->hasMany(CandidateRequestFill::class);
    }
}
