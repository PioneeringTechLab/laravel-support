<?php

namespace CSUNMetaLab\Support\Http\Requests;

use Illuminate\Support\Facades\Config;
use Illuminate\Foundation\Http\FormRequest;

class SupportFormRequest extends FormRequest
{
	public function authorize() {
		return true;
	}

	public function rules() {
		$choicesArr = unserialize(Config::get('support.impact'));
		$choices = implode(",", array_keys($choicesArr));
		return [
			'impact' => 'required|in:' . $choices,
			'content' => 'required|string',
		];
	}

	public function messages() {
		return [
			'impact.required' => trans('support.errors.v.support.impact.required'),
			'impact.in' => trans('support.errors.v.support.impact.in'),
			'content.required' => trans('support.errors.v.support.content.required'),
		];
	}
}