<?php
/**
 * @deprecated	Use imcs_core_StopSpammer, instead
 * @todo		Remove in version 1.4
 */
class IcmsStopSpammer extends icms_core_StopSpammer{
	private $_deprecated;
	public function __construct() {
		parent::getInstance();
		$this->_deprecated = icms_deprecated('imcs_core_StopSpammer', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}
}

?>