<?php
defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');
/**
 * @deprecated	Use icms_ipf_member_Handler, instead
 * @todo		Remove in version 1.4
 */
class IcmsPersistableMemberHandler extends icms_ipf_member_Handler{
	private $_deprecated;
	public function __construct() {
		parent::getInstance();
		$this->_deprecated = icms_deprecated('icms_ipf_member_Handler', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}
}

?>