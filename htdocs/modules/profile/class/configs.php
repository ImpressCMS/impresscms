<?php

/**
* Classes responsible for managing profile configs objects
*
* @copyright	GNU General Public License (GPL)
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.3
* @author		Jan Pedersen, Marcello Brandao, Sina Asghari, Gustavo Pilla <contact@impresscms.org>
* @package		profile
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

// including the IcmsPersistabelSeoObject
include_once ICMS_ROOT_PATH . '/kernel/icmspersistableobject.php';
include_once(ICMS_ROOT_PATH . '/modules/profile/include/functions.php');

class ProfileConfigs extends IcmsPersistableObject {

	/**
	 * Constructor
	 *
	 * @param object $handler ProfilePostHandler object
	 */
	public function __construct(& $handler) {
		global $icmsConfig;

		$this->IcmsPersistableObject($handler);

		$this->quickInitVar('configs_id', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('config_id', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('config_uid', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('pictures', XOBJ_DTYPE_INT, false);
		$this->quickInitVar('audios', XOBJ_DTYPE_INT, false);
		$this->quickInitVar('videos', XOBJ_DTYPE_INT, false);
		$this->quickInitVar('scraps', XOBJ_DTYPE_INT, false);
		$this->quickInitVar('friends', XOBJ_DTYPE_INT, false);
		$this->quickInitVar('tribes', XOBJ_DTYPE_INT, false);
		$this->quickInitVar('profile_contact', XOBJ_DTYPE_INT, false);
		$this->quickInitVar('profile_general', XOBJ_DTYPE_INT, false);
		$this->quickInitVar('profile_stats', XOBJ_DTYPE_INT, false);
		$this->quickInitVar('suspension', XOBJ_DTYPE_INT, false);
		$this->quickInitVar('backup_password', XOBJ_DTYPE_TXTAREA, false);
		$this->quickInitVar('backup_email', XOBJ_DTYPE_TXTBOX, false);
		$this->quickInitVar('end_suspension', XOBJ_DTYPE_TXTBOX, false);
		$this->quickInitVar('status', XOBJ_DTYPE_TXTBOX, false);

	}

	/**
	 * Overriding the IcmsPersistableObject::getVar method to assign a custom method on some
	 * specific fields to handle the value before returning it
	 *
	 * @param str $key key of the field
	 * @param str $format format that is requested
	 * @return mixed value of the field that is requested
	 */
	function getVar($key, $format = 's') {
		if ($format == 's' && in_array($key, array ())) {
			return call_user_func(array ($this,	$key));
		}
		return parent :: getVar($key, $format);
	}
}
class ProfileConfigsHandler extends IcmsPersistableObjectHandler {

	/**
	 * Constructor
	 */
	public function __construct(& $db) {
		$this->IcmsPersistableObjectHandler($db, 'configs', 'configs_id', '', '', 'profile');
	}
}
?>