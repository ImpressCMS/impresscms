<?php

/**
 * includeq Smarty compiler plug-in
 *
 * See the enclosed file LICENSE for licensing information.
 * If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
 *
 * @copyright   The XOOPS project http://www.xoops.org/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author		Skalpa Keo <skalpa@xoops.org>
 * @package		xos_opal
 * @subpackage	xos_opal_Smarty
 * @since       2.0.14
 */

namespace ImpressCMS\Core\Extensions\Smarty\Compiler;

use ImpressCMS\Core\Extensions\Smarty\SmartyCompilerExtensionInterface;

/**
 * includeq Smarty compiler plug-in
 *
 * See the enclosed file LICENSE for licensing information.
 * If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
 *
 * @copyright   The XOOPS project http://www.xoops.org/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author		Skalpa Keo <skalpa@xoops.org>
 * @package		xos_opal
 * @subpackage	xos_opal_Smarty
 * @since       2.0.14
 * @deprecated
 */
class IncludeqCompiler implements SmartyCompilerExtensionInterface
{

	/**
	 * @inheritDoc
	 */
	public function getName(): string
	{
		return 'includeq';
	}

	/**
	 * @inheritDoc
	 */
	public function execute($args, \Smarty_Internal_SmartyTemplateCompiler &$compiler)
	{
		$arg_list = array();

		if (empty($args['file'])) {
			$compiler->trigger_template_error("missing 'file' attribute in includeq tag", __LINE__, __FILE__ );
		}

		foreach ($args as $arg_name => $arg_value) {
			if ($arg_name == 'file') {
				$include_file = $arg_value;
				continue;
			} elseif ($arg_name == 'assign') {
				$assign_var = $arg_value;
				continue;
			}
			if (is_bool($arg_value)) {
				$arg_value = $arg_value ? 'true' : 'false';
			}
			$arg_list[] = "'$arg_name' => $arg_value";
		}

		$output = '';

		if (isset($assign_var)) {
			$output .= "ob_start();\n";
		}

		//$output .= "\$_smarty_tpl_vars = \$this->_tpl_vars;\n";
		$output .= 'include ' . $include_file . ';';
		//"\$this->_tpl_vars = \$_smarty_tpl_vars;\n" .
		//"unset(\$_smarty_tpl_vars);\n";

		if (isset($assign_var)) {
			$output .= "\$_smarty_tpl->assign(" . $assign_var . ", ob_get_contents()); ob_end_clean();\n";
		}
		//$output .= '';
		return '<?php ' . $output . ' ?>';
	}
}