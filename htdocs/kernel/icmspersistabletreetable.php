<?php
if (!defined('ICMS_ROOT_PATH')) die("ImpressCMS root path not defined");
/**
 * @deprecated	 Use icms_ipf_view_Table, instead
 * @todo		Remove in version 1.4
 *
 * @category
 * @package
 * @subpackage
 *
 */
class icms_ipf_view_Tree extends icms_ipf_view_Table {
	private $_deprecated;
	public function __construct() {
		parent::getInstance();
		$this->_deprecated = icms_core_Debug::setDeprecated('icms_ipf_view_Table', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}
}

?>