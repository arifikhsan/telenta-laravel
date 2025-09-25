<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidateRequest extends Model
{
    protected $fillable = [
        'manager_id',
        'position_id',
        'status',
        'requested_count',
        'fulfilled_count',
        'date_requested',
        'level',
        'detail',
        'salary_min',
        'salary_max',
        'category',
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function position() {
        return $this->belongsTo(Position::class);
    }

    public function candidateRequestFills()
    {
        return $this->hasMany(CandidateRequestFill::class);
    }

    public function replacementEmployee()
    {
        return $this->hasMany(ReplacementEmployee::class);
    }
}
