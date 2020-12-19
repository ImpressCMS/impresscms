<?php

namespace ImpressCMS\Core\Exceptions;

use Exception;
use Throwable;

/**
 * This exception will be thrown when needed variables is not found
 *
 * @package ImpressCMS\Core\Exceptions
 */
class UndefinedVariableException extends Exception
{

	/**
	 * UndefinedVariableException constructor.
	 *
	 * @param string $variable Variable name
	 * @param int $code Exception code (if any)
	 * @param Throwable|null $previous Previous exception
	 */
	public function __construct($variable, $code = 0, Throwable $previous = null)
	{
		parent::__construct($variable . ' is not defined but required', $code, $previous);
	}

}