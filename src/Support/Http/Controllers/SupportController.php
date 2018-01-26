<?php

namespace CSUNMetaLab\Support\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

use Illuminate\Support\Facades\Mail;

use CSUNMetaLab\Support\Exceptions\InvalidSupportSenderException;
use CSUNMetaLab\Support\Exceptions\SupportModelNotFoundException;

use CSUNMetaLab\Support\Mail\SupportMailMessage;

use CSUNMetaLab\Support\Http\Requests\SupportFormRequest;

class SupportController extends BaseController
{
	/**
	 * Displays the form to submit a support request message.
	 *
	 * @return View
	 */
	public function create() {
		// retrieve the name and email attributes dynamically
		$nameAttr = config('support.submitter.name', 'name');
		$emailAttr = config('support.submitter.email', 'email');
		$submitter_name = auth()->user()->$nameAttr;
		$submitter_email = auth()->user()->$emailAttr;

		// resolve the impact array
		$impact = ["" => ""] + unserialize(config('support.impact'));

		// determine what to report as the application name
		$application_name = config('app.name', 'Laravel');

		return view("support::forms.support", compact(
			'submitter_name', 'submitter_email', 'impact', 'application_name'
		));
	}

	/**
	 * Accepts the support request submission and redirects back upon success.
	 *
	 * @return RedirectResponse
	 *
	 * @throws InvalidSupportSenderException
	 * @throws SupportModelNotFoundException
	 */
	public function store(SupportFormRequest $request) {
		// ensure we have a valid sender address before we go any further
		if(!config('support.senders.support.address')) {
			$msg = trans('support.errors.support.invalid_sender');
			logger()->error($msg);
			throw new InvalidSupportSenderException($msg);
		}

		$content = $request->input('content');

		// retrieve the user attributes dynamically
		$idAttr = config('support.submitter.id', 'id');
		$nameAttr = config('support.submitter.name', 'name');
		$emailAttr = config('support.submitter.email', 'email');
		$user_id = auth()->user()->$idAttr;
		$name = auth()->user()->$nameAttr;
		$email = auth()->user()->$emailAttr;

		// resolve the impact key and value
		$impactArr = unserialize(config('support.impact'));
		$impactKey = $request->input('impact');
		$impactVal = $impactArr[$impactKey];

		// determine what to report as the application name
		$appName = config('app.name', 'Laravel');
		if(config('support.allow_application_name_override')) {
			if($request->input('application_name', null)) {
				$appName = $request->input('application_name');
			}
		}

		// resolve some more metadata about the email going out
		$recipients = explode("|", config('support.recipients.support'));
		$shouldCCSubmitter = config('support.send_copy_to_submitter');
		$mailType = config('support.types.support');
		if(!in_array($mailType, ["text", "html"])) {
			$mailType = "text";
		}

		if(class_exists('Illuminate\Mail\Mailable')) {
			// use an instance of a custom Mailable instance that is also
			// queueable
			$mailable = new SupportMailMessage(
				$name, $email, $impactVal, $content, $appName, $mailType
			);
			$msg = Mail::to($recipients);
			if($shouldCCSubmitter) {
				$msg->cc($email);
			}
			$msg->send($mailable);
		}
		else
		{
			// send the email using the Mail facade and the queue() method
			$data = [
				'submitter_name' => $name,
				'submitter_email' => $email,
				'application_name' => $appName,
				'impact' => $impactVal,
				'content' => $content,
			];
			Mail::queue([$mailType => 'support::emails.support'], $data,
				function($message) use ($recipients, $shouldCCSubmitter) {
					$message->from(config('support.senders.support.address'),
						config('support.senders.support.name'))
						->to($recipients)
						->subject(config('support.titles.support'));
					if($shouldCCSubmitter) {
						$message->cc($email);
					}
				}
			);
		}

		// write the record to the database if database support is enabled
		if(config('support.database.enabled')) {
			$model = config('support.database.models.support');
			if(class_exists($model)) {
				$model::create([
					'application_name' => $appName,
					'user_id' => $user_id,
					'impact' => $impactKey,
					'content' => $content,
				]);
			}
			else
			{
				// model could not be resolved, so log out the error and then
				// throw a catchable exception
				$msg = trans('support.errors.support.model_not_found', [
					'model' => $model
				]);
				logger()->error($msg);
				throw new SupportModelNotFoundException($msg);
			}
		}

		// there was some kind of success, so re-direct back to the form
		return redirect()->back()->with('message',
			trans('support.success.support'));
	}
}