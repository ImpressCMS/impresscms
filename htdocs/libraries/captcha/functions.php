<?php
/**
 * common functions
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

if (!defined("CAPTCHA_FUNCTIONS")):
define("CAPTCHA_FUNCTIONS", true);

defined("CAPTCHA_FUNCTIONS_INI") || include_once (dirname(__FILE__)."/functions.ini.php");
load_functions("cache");


/**
 * get MySQL server version
 * 
 * In some cases mysql_get_client_info is required instead
 *
 * @return 	string
 */
function mod_getMysqlVersion($conn = null)
{
    static $mysql_version;
    if (isset($mysql_version)) return $mysql_version;
    if (!is_null($conn)) {
	    $version = mysql_get_server_info($conn);
    } else {
	    $version = mysql_get_server_info();
    }
    return $mysql_version;
}

endif;
?>