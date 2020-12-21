<?php


namespace ImpressCMS\Core\Extensions\Smarty;

/**
 * Interface that must be implement all function based smarty extensions
 *
 * @package ImpressCMS\Core\Extensions\Smarty
 */
interface SmartyExtensionInterface
{

	/**
	 * Gets name how to register this function
	 *
	 * @return string
	 */
	public function getName(): string;

}