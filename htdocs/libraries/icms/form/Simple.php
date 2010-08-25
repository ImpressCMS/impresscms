<?php
/**
 * Creates a simple form
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	XOOPS_copyrights.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	LICENSE.txt
 * @package	XoopsForms
 * @since	XOOPS
 * @author	http://www.xoops.org The XOOPS Project
 * @author	modified by UnderDog <underdog@impresscms.org>
 * @version	$Id: simpleform.php 19125 2010-04-04 02:13:34Z skenow $
 * @todo	this class is not used by the core; we will probably remove it in 1.4
 */

defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');

/**
 *
 *
 * @package     kernel
 * @subpackage  form
 *
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */

/**
 * Form that will output as a simple HTML form with minimum formatting
 *
 * @author	Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 *
 * @package     kernel
 * @subpackage  form
 */
class icms_form_Simple extends XoopsForm {
	/**
	 * This method is required - this method in the parent (abstract) class is also abstract
	 * @param string $extra
	 */
	public function insertBreak( $extra = NULL ){
	}
	/**
	 * create HTML to output the form with minimal formatting
	 *
	 * @return	string
	 */
	public function render() {
		$ret = $this->getTitle()."\n<form name='".$this->getName()."' id='".$this->getName()."' action='".$this->getAction()."' method='".$this->getMethod()."'".$this->getExtra().">\n";
		foreach ( $this->getElements() as $ele ) {
			if ( !$ele->isHidden() ) {
				$ret .= "<strong>".$ele->getCaption()."</strong><br />".$ele->render()."<br />\n";
			} else {
				$ret .= $ele->render()."\n";
			}
		}
		$ret .= "</form>\n";
		return $ret;
	}
}