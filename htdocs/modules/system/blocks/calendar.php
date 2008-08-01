<?php
/**
* Java calendar
*
* System tool that allow's you to view the calendar in a block
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		core
* @since		1.2
* @author		Sina Asghari (AKA stranger) <stranger@impresscms.ir>
* @version		$Id$
*/

function b_icms_calendar(){
    global $xoopsUser, $xoopsConfig;
    $config_handler =& xoops_gethandler('config');
	$xoopsConfig =& $config_handler->getConfigsByCat(XOOPS_CONF);
        $block = array();
        $block['lang_name'] = $xoopsConfig['language'];
		if ( defined('_EXT_DATE_FUNC') && $xoopsConfig['use_ext_date'] == 1 && _EXT_DATE_FUNC ){
			$block['lcl_calendar'] = true;
        }
		if ( defined('_ADM_USE_RTL') && _ADM_USE_RTL ){
			$block['lcl_rtl_calendar'] = true;
        }
        $block['calendartype'] = _CALENDAR_TYPE;

return $block;
}
?>