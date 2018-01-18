<?php

namespace CSUNMetaLab\Support\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FeedbackFormRequest extends FormRequest
{
	public function authorize() {
		return true;
	}

	public function rules() {
		return [
			'content' => 'required|string',
		];
	}

	public function messages() {
		return [
			'content.required' => trans('support.errors.v.feedback.content.required'),
		];
	}
}