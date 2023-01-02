<?php

namespace ImpressCMS\Core\Exceptions;

use RuntimeException;
use Throwable;

/**
 * Exception when is not possible to automatically create class instance
 */
class UnableToCreateInstanceException extends RuntimeException
{

	/**
	 * Constructor
	 *
	 * @param string $class Class name that was unable to create
	 * @param int $code Error code
	 * @param Throwable|null $previous
	 */
	public function __construct($class, $code = 0, Throwable $previous = null)
	{
		parent::__construct("Unable to create ${class} instance", $code, $previous);
	}

}