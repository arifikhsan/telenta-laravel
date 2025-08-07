<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    public function position() {
        return $this->belongsTo(Position::class);
    }

    public function manager() {
        return $this->belongsTo(User::class);
    }
}
