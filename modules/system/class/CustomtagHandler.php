<?php
/**
 * ImpressCMS Customtags
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since	1.1
 * @author	marcan <marcan@impresscms.org>
 * @package     ImpressCMS\Modules\System\Class\CustomTag
 */

defined("ICMS_ROOT_PATH") or die("ImpressCMS root path not defined");

defined('ICMS_CUSTOMTAG_TYPE_XCODES') || define('ICMS_CUSTOMTAG_TYPE_XCODES', 1);
defined('ICMS_CUSTOMTAG_TYPE_HTML') || define('ICMS_CUSTOMTAG_TYPE_HTML', 2);
defined('ICMS_CUSTOMTAG_TYPE_PHP') || define('ICMS_CUSTOMTAG_TYPE_PHP', 3);


/**
 * Handler for the custom tag object
 * 
 * @package     ImpressCMS\Modules\System\Class\CustomTag
 */
class mod_system_CustomtagHandler extends icms_ipf_Handler {
	private $_objects = FALSE;

	/**
	 * Constructor
	 * @param object $db
	 */
	public function __construct($db) {
		parent::__construct($db, 'customtag', 'customtagid', 'name', 'description', 'system');
		$this->addPermission('view_customtag', _CO_ICMS_CUSTOMTAG_PERMISSION_VIEW, _CO_ICMS_CUSTOMTAG_PERMISSION_VIEW_DSC);
	}

	/**
	 * Return an array of custom tag types
	 */
	public function getCustomtag_types() {
		$ret = array(ICMS_CUSTOMTAG_TYPE_XCODES => 'BB-Codes', ICMS_CUSTOMTAG_TYPE_HTML => 'HTML', ICMS_CUSTOMTAG_TYPE_PHP => 'PHP');
		return $ret;
	}

	/**
	 * Return an array of custom tags, indexed by name
	 */
	public function getCustomtagsByName() {
		if (!$this->_objects) {
			global $icmsConfig;

			$ret = array();

			$criteria = new icms_db_criteria_Compo();

			$criteria_language = new icms_db_criteria_Compo();
			$criteria_language->add(new icms_db_criteria_Item('language', $icmsConfig['language']));
			$criteria_language->add(new icms_db_criteria_Item('language', 'all'), 'OR');
			$criteria->add($criteria_language);

			$icms_permissions_handler = new icms_ipf_permission_Handler($this);
			$granted_ids = $icms_permissions_handler->getGrantedItems('view_customtag');

			if ($granted_ids && count($granted_ids) > 0) {
				$criteria->add(new icms_db_criteria_Item('customtagid', '(' . implode(', ', $granted_ids) . ')', 'IN'));
				$customtagsObj = $this->getObjects($criteria, TRUE);
				foreach ($customtagsObj as $customtagObj) {
					$ret[$customtagObj->getVar('name')] = $customtagObj;
				}
			}
			$this->_objects = $ret;
		}
		return $this->_objects;
	}
}
