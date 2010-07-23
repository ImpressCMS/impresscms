<?php
/**
 * Class to encrypt User Passwords.
 * @package      kernel
 * @subpackage   core
 * @since        deprecated since 1.3
 * @author       vaughan montgomery (vaughan@impresscms.org)
 * @author       ImpressCMS Project
 * @copyright    (c) 2007-2008 The ImpressCMS Project - www.impresscms.org
 **/

class icms_Password extends icms_core_Password
{
	private $_deprecated;
	/**
	 * Constructor
	 * @param object $db reference to the {@link XoopsDatabase} object
	 *
	 */
	public function __construct(&$db) {
		parent::__construct($db);
		$this->_deprecated = icms_core_Debug::setDeprecated('icms_core_Password', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}
}
?>