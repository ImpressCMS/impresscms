<?php
/**
 * Form control creating an autocomplete select box powered by Scriptaculous for an object derived from icms_ipf_Object
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		ipf
 * @subpackage	form
 * @since		1.1
 * @author		marcan <marcan@impresscms.org>
 * @version		$Id:$
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

class icms_ipf_form_elements_Autocomplete extends icms_form_elements_Text {
	private $_include_file;

	/**
	 * Constructor
	 * @param	object    $object   reference to targetobject (@link icms_ipf_Object)
	 * @param	string    $key      the form name
	 */
	public function __construct($object, $key) {
		$var = $object->vars[$key];
		$control = $object->controls[$key];
		$form_maxlength = isset($control['maxlength']) ? $control['maxlength'] : (isset($var['maxlength']) ? $var['maxlength'] : 255);
		$form_size = isset($control['size']) ? $control['size'] : 50;
		$this->_include_file = $control['file'];
		parent::__construct($var['form_caption'], $key, $form_size, $form_maxlength, $object->getVar($key, 'e'));
	}

	/**
	 * Prepare HTML for output
	 *
	 * @global	icms_view_theme_Object	$xoTheme	theme object
	 * @return	string					$ret		the constructed HTML
	 */
	public function render(){
		global $xoTheme;

		$minchars = isset($control['minchars']) ? $control['minchars'] : 3;
		$matchsubset = isset($control['matchsubset']) ? $control['matchsubset'] : 1;
		$matchcontains = isset($control['matchcontains']) ? $control['matchcontains'] : 1;
		$cachelength = isset($control['cachelength']) ? $control['cachelength'] : 10;
		$selectonly = isset($control['selectonly']) ? $control['selectonly'] : 3;

		$ret  = "<input type='text' name='" . $this->getName() . "' id='" . $this->getName() . "' ";
		$ret .= "size='" . $this->getSize() . "' maxlength='" . $this->getMaxlength() . "' ";
		$ret .= "value='" . $this->getValue() . "'" . $this->getExtra() . " />";

		$js  = "jQuery(document).ready(function() {\n";
		$js .= " jQuery('#" . $this->getName() . "').autocomplete('" . $this->_include_file . "', {\n";
		$js .= "  minChars:" . $minchars . ",\n";
		$js .= "  matchSubset:" . $matchsubset . ",\n";
		$js .= "  matchContains:" . $matchcontains . ",\n";
		$js .= "  cacheLength:" . $cachelength . ",\n";
		$js .= "  selectOnly:" . $selectonly . "\n";
		$js .= " });\n";
		$js .= "});";
		$xoTheme->addScript('', array('type' => 'text/javascript'), $js);

		return $ret;
	}
}