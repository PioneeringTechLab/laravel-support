<?php

namespace CSUNMetaLab\Support\Models;

use Illuminate\Database\Eloquent\Model;

class FeedbackSubmission extends Model
{
	protected $table = "feedback_submissions";

	protected $primaryKey = "submission_id";

	protected $fillable = [
		'application_name',
		'user_id',
		'content',
	];
}