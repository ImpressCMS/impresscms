<?php
defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');
/**
 *
 * @deprecated	Use icms_avatar_Object, instead
 * @todo		Remove in version 1.4
 *
 */
class XoopsAvatar extends icms_avatar_Object {
	private $_deprecated;
	public function __construct() {
		parent::__construct();
		$this->_deprecated = icms_deprecated('icms_avatar_Object', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}
}

/**
 * @deprecated	Use icms_avatar_Handler, instead
 * @todo		Remove in version 1.4
 */
class XoopsAvatarHandler extends icms_avatar_Handler {
	private $_deprecated;
	public function __construct() {
		parent::__construct();
		$this->_deprecated = icms_deprecated('icms_avatar_Handler', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}
}