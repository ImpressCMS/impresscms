<?php

namespace ImpressCMS\Core\Extensions\Smarty\Compiler;
use Smarty_Internal_Compile_Foreach;

/**
 * Because foreachq was so similar to foreach this works as standard foreach
 * just to make work. Try to not use it!
 *
 * @deprecated
 */
class ForeachqCompiler extends Smarty_Internal_Compile_Foreach
{

}