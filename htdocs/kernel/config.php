<?php
defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');
/**
 * @deprecated	Use icms_config_Handler, instead
 * @todo		Remove in version 1.4
 */
class XoopsConfigHandler extends icms_config_Handler {
	private $_deprecated;
	public function __construct() {
		parent::__construct();
		$this->_deprecated = icms_deprecated('icms_config_Handler', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}
}
