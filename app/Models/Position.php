<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    protected $fillable = [
		'name'
	];

	public function candidates()
    {
        return $this->belongsToMany(
            Candidate::class,
            'candidate_position_maps',
            'position_id',
            'candidate_id'
        );
    }
}
