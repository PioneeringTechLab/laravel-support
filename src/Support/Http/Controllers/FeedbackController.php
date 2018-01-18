<?php

namespace CSUNMetaLab\Support\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

use CSUNMetaLab\Support\Http\Requests\FeedbackFormRequest;

class FeedbackController extends BaseController
{
	/**
	 * Displays the form to submit a feedback message.
	 *
	 * @return View
	 */
	public function create() {

	}

	/**
	 * Accepts the feedback submission and redirects back upon success.
	 *
	 * @return RedirectResponse
	 */
	public function store(FeedbackFormRequest $request) {

	}
}