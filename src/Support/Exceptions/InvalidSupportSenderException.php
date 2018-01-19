<?php

namespace CSUNMetaLab\Support\Exceptions;

use Exception;

class InvalidSupportSenderException extends Exception
{
	public function __construct($message="The support request sender is invalid") {
		parent::__construct($message);
	}
}