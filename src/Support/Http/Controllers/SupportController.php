<?php

namespace CSUNMetaLab\Support\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

use CSUNMetaLab\Support\Http\Requests\SupportFormRequest;

class SupportController extends BaseController
{
	/**
	 * Displays the form to submit a support request message.
	 *
	 * @return View
	 */
	public function create() {

	}

	/**
	 * Accepts the support request submission and redirects back upon success.
	 *
	 * @return RedirectResponse
	 */
	public function store(SupportFormRequest $request) {

	}
}