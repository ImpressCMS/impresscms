<?php
/**
 * Creates a form attribute which is able to select an editor
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	XOOPS_copyrights.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	LICENSE.txt
 * @package	XoopsForms
 * @since	XOOPS
 * @author	http://www.xoops.org The XOOPS Project
 * @author	modified by UnderDog <underdog@impresscms.org>
 * @version	$Id: formselecteditor.php 19892 2010-07-27 00:12:10Z skenow $
 */
/**
 * base class
 */

/**
 * A select box with available editors
 *
 * @package     kernel
 * @subpackage  form
 *
 * @author	    phppp (D.J.)
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
class icms_form_elements_select_Editor extends icms_form_elements_Tray {
	/**
	 * Constructor
	 *
	 * @param	object	$form	the form calling the editor selection
	 * @param	string	$name	editor name
	 * @param	string	$value	Pre-selected text value
	 * @param	bool	$noHtml  dohtml disabled
	 */
	public function __construct(&$form, $name = "editor", $value = NULL, $noHtml = FALSE) {
		global $icmsConfig;

		if (empty($value)){
			$value = $icmsConfig['editor_default'];
		}

		parent::__construct(_SELECT);
		$edtlist = icms_plugins_EditorHandler::getList();
		$option_select = new icms_form_elements_Select("", $name, $value);
		$querys = preg_replace('/editor=(.*?)&/','',$_SERVER['QUERY_STRING']);
		$extra = 'onchange="if(this.options[this.selectedIndex].value.length > 0 ){
				window.location = \'?editor=\'+this.options[this.selectedIndex].value+\'&'.$querys.'\';
			}"';
		$option_select->setExtra($extra);
		$option_select->addOptionArray($edtlist);

		$this->addElement($option_select);
	}
}
?>