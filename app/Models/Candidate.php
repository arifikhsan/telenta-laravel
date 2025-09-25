<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Candidate extends Model
{
    protected $fillable = [
        'name',
        'status',
        'cv_path'
    ];


    protected $appends = ['cv_url'];

    public function getCvUrlAttribute()
    {
        return $this->cv_path
            ? Storage::url($this->cv_path)
            : null;
    }


    public function positions()
    {
        return $this->belongsToMany(
            Position::class,
            'candidate_position_maps',
            'candidate_id',
            'position_id'
        );
    }

    public function candidateRequestFill() {
        return $this->hasOne(CandidateRequestFill::class);
    }
}
