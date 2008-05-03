<?php
/**
 * Initial functions
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	XOOPS_copyrights.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		installer
 * @since		XOOPS
 * @author		http://www.xoops.org/ The XOOPS Project
 * @author		Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @author		modified by Sina Asghari <stranger@impresscms.ir>
 * @version		$Id:$
*/

if (!defined("CAPTCHA_FUNCTIONS_INI")):
define("CAPTCHA_FUNCTIONS_INI", true);

define("CAPTCHA_ROOT_PATH", XOOPS_ROOT_PATH."/libraries/captcha");

global $xoops;
/**
 * Load a collective functions of Frameworks
 *
 * @param	string	$group		name of  the collective functions, empty for functions.php
 * @return	bool
 */
function load_functions($group = "", $dirname = "captcha")
{
	$dirname = ("" == $dirname ) ? "captcha" : $dirname;
	$constant = strtoupper( "frameworks_{$dirname}_functions" . (($group) ? "_{$group}" : ""));
	if (defined($constant)) return true;
	return @include_once CAPTCHA_ROOT_PATH."/{$dirname}/functions.{$group}" . (empty($group) ? "" : "." ) . "php";
}


/**
 * Load a collective functions of a module
 *
 * The function file should be located in /modules/MODULE/functions.{$group}.php
 * To avoid slowdown caused by include_once, a constant is suggested in the corresponding file: capitalized {$dirname}_{functions}[_{$group}]
 *
 * The function is going to be formulated to use xos_kernel_Xoops2::loadService() in XOOPS 2.3+
 *
 * @param	string	$group		name of  the collective functions, empty for functions.php
 * @param	string	$dirname	module dirname, optional
 * @return	bool
 */
function mod_loadFunctions($group = "", $dirname = "")
{
	$dirname = !empty($dirname) ? $dirname : $GLOBALS["xoopsModule"]->getVar("dirname", "n");
	$constant = strtoupper( "{$dirname}_functions" . ( ($group) ? "_{$group}" : "" ) . "_loaded" );
	if (defined($constant)) return true;
	$filename = XOOPS_ROOT_PATH."/modules/{$dirname}/include/functions.{$group}" . (empty($group) ? "" : "." ) . "php";
	return include_once $filename;
}

?>