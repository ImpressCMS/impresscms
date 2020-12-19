<?php

namespace ImpressCMS\Core\Extensions\Smarty;

/**
 * Interface that lets to define smarty function extension
 *
 * @package ImpressCMS\Core\Extensions\Smarty
 */
interface SmartyFunctionExtensionInterface extends SmartyExtensionInterface
{

	/**
	 * Execute function
	 *
	 * @param $params
	 * @param $smarty
	 * @return string|void
	 */
	public function execute($params, &$smarty);

}