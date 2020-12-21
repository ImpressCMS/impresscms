<?php


namespace ImpressCMS\Core\Extensions\Smarty\Compiler;

/**
 * Foreachq closing tag
 *
 * @package ImpressCMS\Core\Extensions\Smarty\Compiler
 * @deprecated
 */
class ForeachqCloseCompiler implements \ImpressCMS\Core\Extensions\Smarty\SmartyCompilerExtensionInterface
{

    /**
     * @inheritDoc
     */
    public function execute($args, \Smarty_Internal_SmartyTemplateCompiler &$compiler)
    {
        return 'endforeach;';
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return 'foreachqclose';
    }
}