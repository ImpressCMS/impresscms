<?php
/**
* Class to introduce timepicker
*
* @copyright	http://www.xoops.org/ The XOOPS Project
* @copyright	XOOPS_copyrights.txt
* @copyright	http://www.impresscms.org/ The ImpressCMS Project
* @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package	core
* @since	XOOPS
* @author	http://www.xoops.org The XOOPS Project
* @author	   Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
* @version	$Id$
**/
if (!defined('XOOPS_ROOT_PATH')) {
	die("XOOPS root path not defined");
}

/**
 * @package     kernel
 * @subpackage  form
 * 
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */

/**
 * A text field with calendar popup
 * 
 * @package     kernel
 * @subpackage  form
 * 
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */

class XoopsFormTextDateSelect extends XoopsFormText
{


	/**
	 * Constructor
	 */
	function XoopsFormTextDateSelect($caption, $name, $size = 15, $value= 0)
	{
		$value = !is_numeric($value) ? time() : intval($value);
		$this->XoopsFormText($caption, $name, $size, 25, $value);
	}


	/**
	 * Render the Date Select
	 */
	function render()
	{
	   	$ele_name = $this->getName();
		$ele_value = $this->getValue(false);
		$jstime = formatTimestamp( $ele_value, 'Y-m-d' );
		$config_handler =& xoops_gethandler('config');
		$xoopsConfigPersona =& $config_handler->getConfigsByCat(XOOPS_CONF_PERSONA);
        	if($xoopsConfigPersona['use_jsjalali'] == 1)
		{
		include_once XOOPS_ROOT_PATH.'/include/calendarjalalijs.php';
//		return "<input type='text' name='".$ele_name."' id='".$ele_name."' size='".$this->getSize()."' maxlength='".$this->getMaxlength()."' value='".date("Y-m-d", $ele_value)."'".$this->getExtra()." /><input type='reset' value=' ... ' onclick='return showCalendar(\"".$ele_name."\");'>";
// Now it is time to let users use their own calendars.
		return "<input id='tmp_".$ele_name."' readonly='readonly' size='".$this->getSize()."' maxlength='".$this->getMaxlength()."' value='".(_CALENDAR_TYPE=='jalali' ? Convertnumber2farsi(ext_date("Y-m-d", $ele_value)) : date("Y-m-d", $ele_value))."' /><input type='hidden' name='".$ele_name."' id='".$ele_name."' value='".date("Y-m-d", $ele_value)."' ".$this->getExtra()." /><script type='text/javascript'>
				Calendar.setup({
				inputField  : 'tmp_".$ele_name."',   // id of the input field
		       		ifFormat    : '%Y-%m-%d',       // format of the input field
        			langNumbers : true,
        			dateType	: '"._CALENDAR_TYPE."',
				onUpdate	: function(cal){document.getElementById('".$ele_name."').value = cal.date.print('%Y-%m-%d');}

				});
			</script>";}else{
		include_once XOOPS_ROOT_PATH.'/include/calendarjs.php';
		return "<input type='text' name='".$ele_name."' id='".$ele_name."' size='".$this->getSize()."' maxlength='".$this->getMaxlength()."' value='".date("Y-m-d", $ele_value)."'".$this->getExtra()." />&nbsp;&nbsp;<img src='" . ICMS_URL . "/images/calendar.png' alt='"._CALENDAR."' title='"._CALENDAR."' onclick='return showCalendar(\"".$ele_name."\");'>";
		}
	}
}

?>