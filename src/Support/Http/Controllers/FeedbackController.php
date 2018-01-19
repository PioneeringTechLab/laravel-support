<?php

namespace CSUNMetaLab\Support\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

use CSUNMetaLab\Support\Exceptions\FeedbackModelNotFoundException;

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
		$content = $request->input('content');

		// retrieve the name and email attributes dynamically
		$idAttr = config('support.submitter.id', 'id');
		$nameAttr = config('support.submitter.name', 'name');
		$emailAttr = config('support.submitter.email', 'email');
		$user_id = Auth::user()->$idAttr;
		$name = Auth::user()->$nameAttr;
		$email = Auth::user()->$emailAttr;

		// determine what to report as the application name
		$appName = config('app.name', 'Laravel');
		if(config('support.allow_application_name_override')) {
			if($request->input('application_name', null)) {
				$appName = $request->input('application_name');
			}
		}

		if(class_exists('Illuminate\Mail\Mailable')) {
			// use an instance of a custom Mailable instance that is also
			// queueable
		}
		else
		{
			// send the email using the Mail facade and the queue() method
		}

		// write the record to the database if database support is enabled
		if(config('support.database.enabled')) {
			$model = config('support.database.models.feedback');
			if(class_exists($model)) {
				$model::create([
					'application_name' => $appName,
					'user_id' => $user_id,
					'content' => $content,
				]);
			}
			else
			{
				// model could not be resolved, so log out the error and then
				// throw a catchable exception
				$msg = trans('support.errors.feedback.model_not_found', [
					'model' => $model
				]);
				Log::error($msg);
				throw new FeedbackModelNotFoundException($msg);
			}
		}

		// TODO: there was some kind of success, so re-direct back to the form
	}
}