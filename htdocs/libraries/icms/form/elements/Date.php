<?php
/**
 * Class to create a form field with a date selector
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Form
 * @subpackage	Elements
 * @version		SVN: $Id$
 **/

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * A text field with calendar popup
 *
 * @category	ICMS
 * @package     Form
 * @subpackage  Elements
 *
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 */
class icms_form_elements_Date extends icms_form_elements_Text {


	/**
	 * Constructor
	 *
	 * @param string	$caption
	 * @param string	$name
	 * @param int		$size
	 * @param mixed		$value
	 */
	public function __construct($caption, $name, $size = 15, $value= 0) {
		$value = !is_numeric($value) ? time() : (int) ($value);
		parent::__construct($caption, $name, $size, 25, $value);
	}

	/**
	 * Render the Date field
	 */
	public function render() {
		global $icmsConfigPersona;
		$ele_name = $this->getName();
		$ele_value = $this->getValue(false);
		$jstime = formatTimestamp($ele_value, _SHORTDATESTRING);

		include_once ICMS_ROOT_PATH . '/include/calendar' . ($icmsConfigPersona['use_jsjalali'] == true ? 'jalali' : '') . 'js.php';

		$result = "<input type='text' name='".$ele_name."' id='".$ele_name."' size='".$this->getSize()."' maxlength='".$this->getMaxlength()."' value='".date(_SHORTDATESTRING, $ele_value)."'".$this->getExtra()." />&nbsp;&nbsp;<img src='" . ICMS_URL . "/images/calendar.png' alt='"._CALENDAR."' title='"._CALENDAR."' onclick='return showCalendar(\"".$ele_name."\");' style='cursor:pointer;'>";
		if ($icmsConfigPersona['use_jsjalali']) {
			include_once ICMS_ROOT_PATH . '/include/jalali.php';
			$result = "<input id='tmp_".$ele_name."' readonly='readonly' size='".$this->getSize()."' maxlength='".$this->getMaxlength()."' value='".(_CALENDAR_TYPE=='jalali' ? icms_conv_nr2local(jdate(_SHORTDATESTRING, $ele_value)) : date(_SHORTDATESTRING, $ele_value))."' /><input type='hidden' name='".$ele_name."' id='".$ele_name."' value='".date(_SHORTDATESTRING, $ele_value)."' ".$this->getExtra()." />&nbsp;&nbsp;<img src='" . ICMS_URL . "/images/calendar.png' alt='"._CALENDAR."' title='"._CALENDAR."' id='btn_".$ele_name."' style='cursor:pointer;'><script type='text/javascript'>
				Calendar.setup({
					inputField  : 'tmp_".$ele_name."',
		       		ifFormat    : '%Y-%m-%d',
		       		button      : 'btn_".$ele_name."',
        			langNumbers : true,
        			dateType	: '"._CALENDAR_TYPE."',
					onUpdate	: function(cal){document.getElementById('".$ele_name."').value = cal.date.print('%Y-%m-%d');}
				});
			</script>";
		}
		return $result;
	}
}