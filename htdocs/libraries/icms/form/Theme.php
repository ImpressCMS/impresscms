<?php
/**
 * Creates a form attribut styled by the theme
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @category	ICMS
 * @package		Form
 * @version		SVN: $Id: Theme.php 10575 2010-08-28 21:30:27Z skenow $
 */

defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');

/**
 * Form that will output as a theme-enabled HTML table
 *
 * Also adds JavaScript to validate required fields
 *
 * @author		Kazumi Ono	<onokazu@xoops.org>
 * @category	ICMS
 * @package     Form
 *
 */
class icms_form_Theme extends icms_form_Base {
	/**
	 * Insert an empty row in the table to serve as a seperator.
	 *
	 * @param	string  $extra  HTML to be displayed in the empty row.
	 * @param	string	$class	CSS class name for <td> tag
	 */
	public function insertBreak($extra = '', $class= '') {
		$class = ($class != '') ? " class='$class'" : '';
		//Fix for $extra tag not showing
		if ($extra) {
			$extra = "<div class='clear'>$extra</div>";
			$this->addElement($extra);
		} else {
			$extra = "<div class='clear'>&nbsp;</div>";
			$this->addElement($extra);
		}
	}

	/**
	 * create HTML to output the form as a theme-enabled table with validation.
	 *
	 * @return	string
	 */
	public function render() {
		$ele_name = $this->getName();
		$ret = "<form id='" . $ele_name . "' name='" . $ele_name . "' action='" . $this->getAction()	. "' method='" . $this->getMethod() . "'" . $this->getExtra() . ">
		<div class='icms-theme-form'>
		<fieldset>
		<legend>" . $this->getTitle() . "</legend>
		<div class='icms-form-contents'>";
		
		$hidden = '';
		$class ='even';
		foreach ( $this->getElements() as $ele ) {
			$requiredClass = $ele->isRequired() ? " required" : "";
			$groupName = $ele->getName() != '' && $ele->getName() != 'XOOPS_TOKEN_REQUEST' ? " group-" . $ele->getName() : "";
	
			if(!$ele->isHidden()) {
				$ret .= "<div class='fieldWrapper" . $groupName . $requiredClass . "'>";
			}

			if (!is_object($ele)) {
				$ret .= $ele;
			} elseif ( !$ele->isHidden() ) {
				$caption = $ele->getCaption();
				if ($caption != '') {
					$ret .=
					"<label for='".$ele->getName()."' class='caption-text'>{$caption}"
					. "<span class='caption-marker'>*</span></label>";
				}
					
				if (($desc = $ele->getDescription()) != '') {
					$ret .= "<div class='icms-form-element-help'>{$desc}</div>";
				}
					
				$ret .= "<div class='$class'>" . $ele->render() . "</div>\n";
			} else {
				$hidden .= $ele->render();
			}

			if(!$ele->isHidden()) {
				$ret .= "</div>";
			}
		}
			
		$ret .= "\n<div class='hidden'>$hidden</div>\n</fieldset></div>\n</form>\n";
		// $ret .= $this->renderValidationJS(true);
		return $ret;
	}
}

