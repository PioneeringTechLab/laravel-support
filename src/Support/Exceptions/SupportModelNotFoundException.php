<?php

namespace CSUNMetaLab\Support\Exceptions;

use Exception;

class SupportModelNotFoundException extends Exception
{
	public function __construct($message="The specified support submission model could not be resolved") {
		parent::__construct($message);
	}
}