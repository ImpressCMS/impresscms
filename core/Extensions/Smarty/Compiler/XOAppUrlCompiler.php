<?php
/**
 * xoAppUrl Smarty compiler plug-in
 *
 * See the enclosed file LICENSE for licensing information.
 * If you did not receive this file, get it at https://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * @copyright   The XOOPS project http://www.xoops.org/
 * @license     https://www.gnu.org/licenses/old-licenses/gpl-2.0.html GPLv2 or later license
 * @author		Skalpa Keo <skalpa@xoops.org>
 * @package		xos_opal
 * @subpackage	xos_opal_Smarty
 * @since       2.0.14
 */

namespace ImpressCMS\Core\Extensions\Smarty\Compiler;

use icms;
use ImpressCMS\Core\Extensions\Smarty\SmartyCompilerExtensionInterface;

/**
 * Inserts the URL of an application page
 *
 * This plug-in allows you to generate a module location URL. It uses any URL rewriting
 * mechanism and rules you'll have configured for the system.
 *
 * To ensure this can be as optimized as possible, it accepts 2 modes of operation:
 *
 * <b>Static address generation</b>:
 * This is the default mode and fastest mode. When used, the URL is generated during
 * the template compilation, and statically written in the compiled template file.
 * To use it, you just need to provide a location in a format XOOPS understands.
 *
 * <code>
 * // Generate an URL using a physical path
 * ([xoAppUrl modules/something/yourpage.php])
 * // Generate an URL using a module+location identifier (2.3+)
 * ([xoAppUrl mod_xoops_Identification#logout])
 * </code>
 *
 * <b>Dynamic address generation</b>:
 * The is the slowest mode, and its use should be prevented unless necessary. Here,
 * the URL is generated dynamically each time the template is displayed, thus allowing
 * you to use the value of a template variable in the location string. To use it, you
 * must surround your location with double-quotes ("), and use the Smarty quoted strings
 * syntax to insert variables values.
 *
 * <code>
 * // Use the value of the $sortby template variable in the URL
 * ([xoAppUrl "modules/something/yourpage.php?order=`$sortby`"])
 * </code>
 */
class XOAppUrlCompiler implements SmartyCompilerExtensionInterface
{
	/**
	 * @inheritDoc
	 */
	public function execute($args, \Smarty_Internal_SmartyTemplateCompiler &$compiler)
	{
		$url = trim($args[0]);
		$params = array_slice($args, 1);

		if (strpos($url, '/') === 0) {
			$url = 'www' . $url;
		}
		// Static URL generation
		if ($url !== '.' && strpos($url[0], '$') === false) {
			if (isset($params)) {
				foreach ($params as $k => $v) {
					if (in_array(substr($v, 0, 1), array('"', "'"))) {
						$params[$k] = substr($v, 1, -1);
					}
				}
				$url = \icms::buildUrl($url, $params);
			}
			$url = icms::path($url, true);
			return htmlspecialchars($url);
		}
		// Dynamic URL generation
		if ($url == '.') {
			$str = "\$_SERVER['REQUEST_URI']";
		} else {
			$str = "\\icms::path( '$url', true )";
		}
		if (isset($params)) {
			$str = "\\icms::buildUrl( $str, array(\n";
			foreach ($params as $k => $v) {
				$str .= var_export($k, true) . " => $v,\n";
			}
			$str .= ') )';
		}
		return "<?php echo htmlspecialchars( $str ); ?" . '>';
	}

	/**
	 * @inheritDoc
	 */
	public function getName(): string
	{
		return 'xoAppUrl';
	}
}