<?php
/**
 * Creates a form attribute which is able to select a language
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @category	ICMS
 * @package		Form
 * @subpackage	Elements
 * @version	$Id$
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * lists of values
 */
include_once ICMS_ROOT_PATH . "/class/xoopslists.php";
/**
 * parent class
 */
include_once ICMS_ROOT_PATH . "/class/xoopsform/formselect.php";

/**
 * A select field with available languages
 *
 * @category	ICMS
 * @package     Form
 * @subpackage  Elements
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 */
class icms_form_elements_select_Lang extends icms_form_elements_Select {
	/**
	 * Constructor
	 *
	 * @param	string	$caption
	 * @param	string	$name
	 * @param	mixed	$value	Pre-selected value (or array of them).
	 * 							Legal is any name of a ICMS_ROOT_PATH."/language/" subdirectory.
	 * @param	int		$size	Number of rows. "1" makes a drop-down-list.
	 */
	public function __construct($caption, $name, $value = null, $size = 1) {
		parent::__construct($caption, $name, $value, $size);
		$this->addOptionArray(IcmsLists::getLangList());
	}
}

