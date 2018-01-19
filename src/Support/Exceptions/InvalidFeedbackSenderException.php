<?php

namespace CSUNMetaLab\Support\Exceptions;

use Exception;

class InvalidFeedbackSenderException extends Exception
{
	public function __construct($message="The feedback sender is invalid") {
		parent::__construct($message);
	}
}