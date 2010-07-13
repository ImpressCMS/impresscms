<?php
/**
 * @deprecated	Use icms_ipf_Highlighter, instead
 * @todo		Remove in version 1.4
 *
 */
class IcmsPersistableHighlighter extends icms_ipf_Highlighter{
	private $_deprecated;
	public function __construct() {
		parent::getInstance();
		$this->_deprecated = icms_core_Debug::setDeprecated('icms_ipf_Highlighter', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}
}
?>