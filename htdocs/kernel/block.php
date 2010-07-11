<?php
defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');
/**
 * @deprecated	Use icms_block_Object, instead
 * @todo		Remove in version 1.4
 *
 */
class XoopsBlock extends icms_block_Object {
	private $_deprecated;
	public function __construct() {
		parent::__construct();
		$this->_deprecated = icms_deprecated('icms_core_Block', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}
}
/**
 * @deprecated	Use icms_block_Handler, instead
 * @todo		Remove in version 1.4
 *
 */
class XoopsBlockHandler extends icms_block_Handler {
	private $_deprecated;
	public function __construct() {
		parent::__construct();
		$this->_deprecated = icms_deprecated('icms_core_BlockHandler', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}
}
