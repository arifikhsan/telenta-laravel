<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionPositionMap extends Model
{
    public function position() {
        return $this->belongsTo(Position::class);
    }

    public function question() {
        return $this->belongsTo(Question::class);
    }
}
