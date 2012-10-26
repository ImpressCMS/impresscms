<?php
/**
 * icms_form_elements_Colorpicker component class file
 *
 * This class provides a textfield with a color picker popup. This color picker
 * comes from Tigra project (http://www.softcomplex.com/products/tigra_color_picker/).
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	XOOPS_copyrights.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @license		http://www.fsf.org/copyleft/gpl.html GNU public license
 * @category	ICMS
 * @package		Form
 * @subpackage	Elements
 * @author		Zoullou <webmaster@zoullou.org>
 * @since		Xoops 2.0.15
 * @version		$Id: Colorpicker.php 10446 2010-07-27 16:07:52Z skenow $
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * Color Picker
 *
 * @category	ICMS
 * @package     Form
 * @subpackage	Elements
 *
 * @author	Kazumi Ono	<onokazu@xoops.org>
 */
class icms_form_elements_Colorpicker extends icms_form_elements_Text {

	/**
	 * Constructor
	 * @param	string  $caption  Caption of the element
	 * @param	string  $name     Name of the element
	 * @param	string  $value    Value of the element
	 */
	public function __construct($caption, $name, $value = "#FFFFFF") {
		parent::__construct($caption, $name, 9, 7, $value);
	}

	/**
	 * Render the color picker
	 * @return  $string	rendered color picker HTML
	 */
	public function render() {
		$this->setExtra(' data-module="' . ICMS_LIBRARIES_URL . '/jscore/app/widgets/colorpicker/main" class="colorpicker" style="background-color:' . $this->getValue() . ';"');
		return parent::render() . "\n";
	}

	/**
	 * Returns custom validation Javascript
	 *
	 * @return	string	Element validation Javascript
	 */
	public function renderValidationJS() {
		// $eltname = $this->getName();
		// $eltcaption = $this->getCaption();
		// $eltmsg = empty($eltcaption) ? sprintf(_FORM_ENTER, $eltname) : sprintf(_FORM_ENTER, $eltcaption);
		// $eltmsg = str_replace('"', '\"', stripslashes($eltmsg));
		// $eltmsg = strip_tags($eltmsg);
		// return "if (myform.{$eltname}.value == \"\") { window.alert(\"{$eltmsg}\"); myform.{$eltname}.focus(); return false; }";
	}

}
