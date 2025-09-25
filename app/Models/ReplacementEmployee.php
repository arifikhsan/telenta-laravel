<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReplacementEmployee extends Model
{

	protected $fillable = [
		'candidate_request_id',
		'name'
	];

	public function candidateRequest() {
		return $this->belongsTo(CandidateRequest::class);
	}
}
