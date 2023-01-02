<?php
namespace ImpressCMS\Core\Exceptions;

use RuntimeException;

/**
 * Thrown when is not possible to detect public documents root path
 */
class PublicPathResolveException extends RuntimeException
{

	public $message = 'You need to define relative "public_path" in .env file before using this tool';

}