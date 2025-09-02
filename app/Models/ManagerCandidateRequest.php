<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ManagerCandidateRequest extends Model
{
    protected $fillable = [
        'manager_id',
        'status',
        'requested_count',
        'fulfilled_count',
        'date_requested',
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function candidateRequestFills()
    {
        return $this->hasMany(CandidateRequestFill::class);
    }
}
