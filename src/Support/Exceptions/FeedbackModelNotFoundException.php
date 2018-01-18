<?php

namespace CSUNMetaLab\Support\Exceptions;

use Exception;

class FeedbackModelNotFoundException extends Exception
{
	public function __construct($message="The specified feedback submission model could not be resolved") {
		parent::__construct($message);
	}
}