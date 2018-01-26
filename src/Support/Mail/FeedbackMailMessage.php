<?php

namespace CSUNMetaLab\Support\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use Illuminate\Contracts\Queue\ShouldQueue;

class FeedbackMailMessage extends Mailable implements ShouldQueue
{
	use Queueable, SerializesModels;

	/**
	 * Name of the submitter.
	 *
	 * @var string
	 */
	public $submitter_name;

	/**
	 * Email address of the submitter.
	 *
	 * @var string
	 */
	public $submitter_email;

	/**
	 * Application name from where the feedback message is being sent.
	 *
	 * @var string
	 */
	public $application_name;

	/**
	 * Content of the feedback message.
	 *
	 * @var string
	 */
	public $content;

	/**
	 * Type of the message, either "text" or "html"
	 *
	 * @var string
	 */
	public $type;

	/**
	 * Constructs a new FeedbackMailMessage instance.
	 *
	 * @param string $submitter_name Name of the submitter
	 * @param string $submitter_email Email address of the submitter
	 * @param string $content Content of the feedback message
	 * @param string $application_name Optional application name from where the
	 * message is being sent
	 */
	public function __construct($submitter_name, $submitter_email, $content,
		$application_name="Laravel", $type="text") {
		$this->submitter_name = $submitter_name;
		$this->submitter_email = $submitter_email;
		$this->content = $content;
		$this->application_name = $application_name;
		$this->type = $type;
	}

	/**
	 * Build the message.
	 *
	 * @return $this
	 */
	public function build() {
		$msg = $this->from(
				config('support.senders.feedback.address'),
				config('support.senders.feedback.name')
			)
			->subject(config('support.titles.feedback'));

		if($this->type == "text") {
			$msg->text("support::emails.feedback");
		}
		else
		{
			$msg->view("support::emails.feedback");
		}

		return $msg;
	}
}