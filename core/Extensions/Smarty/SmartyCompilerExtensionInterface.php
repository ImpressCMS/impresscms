<?php

namespace ImpressCMS\Core\Extensions\Smarty;

/**
 * Interface that lets to define smarty compiler extension (that is not internal function based)
 *
 * @package ImpressCMS\Core\Extensions\Smarty
 */
interface SmartyCompilerExtensionInterface extends SmartyExtensionInterface
{

	/**
	 * Execute compiler
	 *
	 * @param $args
	 * @param \Smarty_Internal_SmartyTemplateCompiler $compiler
	 *
	 * @return string|void
	 */
	public function execute($args, \Smarty_Internal_SmartyTemplateCompiler &$compiler);

}