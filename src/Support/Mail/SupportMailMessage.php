<?php

namespace CSUNMetaLab\Support\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use Illuminate\Contracts\Queue\ShouldQueue;

class SupportMailMessage extends Mailable implements ShouldQueue
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
	 * Application name from where the support request message is being sent.
	 *
	 * @var string
	 */
	public $application_name;

	/**
	 * Impact of the issue that caused the support request.
	 *
	 * @var string
	 */
	public $impact;

	/**
	 * Content of the support request message.
	 *
	 * @var string
	 */
	public $content;

	/**
	 * Type of the message, either "text" or "html".
	 *
	 * @var string
	 */
	public $type;

	/**
	 * Constructs a new SupportMailMessage instance.
	 *
	 * @param string $submitter_name Name of the submitter
	 * @param string $submitter_email Email address of the submitter
	 * @param string $impact Impact of the issue that caused the support request
	 * @param string $content Content of the support request message
	 * @param string $application_name Optional application name from where the
	 * message is being sent
	 */
	public function __construct($submitter_name, $submitter_email, $impact,
		$content, $application_name="Laravel", $type="text") {
		$this->submitter_name = $submitter_name;
		$this->submitter_email = $submitter_email;
		$this->impact = $impact;
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
				config('support.senders.support.address'),
				config('support.senders.support.name')
			)
			->subject(config('support.titles.support'));

		if($this->type == "text") {
			$msg->text("support::emails.support");
		}
		else
		{
			$msg->view("support::emails.support");
		}
		
		return $msg;
	}
}