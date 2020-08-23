<?php

namespace ImpressCMS\Core\Extensions\Smarty\Compiler;
use ImpressCMS\Core\Extensions\Smarty\SmartyExtensionInterface;
use Smarty_Internal_Compile_Include;

/**
 * Because this is very similar function to internal smarty include, differences were removed.
 * Try to not use it!
 *
 * @deprecated
 */
class IncludeqCompiler extends Smarty_Internal_Compile_Include implements SmartyExtensionInterface
{

	/**
	 * @inheritDoc
	 */
	public function getName(): string
	{
		return 'includeq';
	}
}