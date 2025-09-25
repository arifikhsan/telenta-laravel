<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CandidatePositionMap extends Model
{
	public function candidate() {
        return $this->belongsTo(Candidate::class);
    }

    public function position() {
        return $this->belongsTo(Position::class);
    }

}
