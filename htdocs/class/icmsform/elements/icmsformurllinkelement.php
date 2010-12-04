<?php
/**
 * Form control creating an element to link and URL to an object derived from icms_ipf_Object
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		icms_ipf_Object
 * @since		1.1
 * @author		marcan <marcan@impresscms.org>
 * @version		$Id$
 */

defined("ICMS_ROOT_PATH") or die("ImpressCMS root path not defined");

class IcmsFormUrlLinkElement extends icms_ipf_form_elements_Urllink {
	private $_deprecated;

	public function __construct($form_caption, $key, $object) {
		parent::__construct($form_caption, $key, $object);
		$this->_deprecated = icms_core_Debug::setDeprecated('icms_ipf_form_elements_Urllink', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}
}