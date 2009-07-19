<?php

/**
* Classes responsible for managing profile scraps objects
*
* @copyright	GNU General Public License (GPL)
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.3
* @author		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
* @package		profile
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

// including the IcmsPersistabelSeoObject
include_once ICMS_ROOT_PATH . '/kernel/icmspersistableseoobject.php';
include_once(ICMS_ROOT_PATH . '/modules/profile/include/functions.php');

class ProfileScraps extends IcmsPersistableSeoObject {

	/**
	 * Constructor
	 *
	 * @param object $handler ProfilePostHandler object
	 */
	public function __construct(& $handler) {
		global $icmsConfig;

		$this->IcmsPersistableObject($handler);

		$this->quickInitVar('scraps_id', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('scrap_text', XOBJ_DTYPE_TXTAREA, true);
		$this->quickInitVar('scrap_from', XOBJ_DTYPE_INT, false);
		$this->quickInitVar('scrap_to', XOBJ_DTYPE_INT, false);
		$this->quickInitVar('private', XOBJ_DTYPE_INT, false);
		$this->initCommonVar('counter', false);
		$this->initCommonVar('dohtml', false, true);
		$this->initCommonVar('dobr');
		$this->initCommonVar('doimage', false, true);
		$this->initCommonVar('dosmiley', false, true);
		$this->initCommonVar('doxcode', false, true);

		$this->setControl('scrap_from', 'user');
		$this->setControl('private', 'yesno');
		$this->hideFieldFromForm('scrap_to');
		$this->setControl('scrap_text', 'dhtmltextarea');


		$this->IcmsPersistableSeoObject();
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
	function getScrapSender() {
		return icms_getLinkedUnameFromId($this->getVar('scrap_from', 'e'));
	}

	function getScrapReceiver() {
		return icms_getLinkedUnameFromId($this->getVar('scrap_to', 'e'));
	}

	function getScrapShortenText() {
		$ret = '<a href="' . ICMS_URL . '/modules/profile/scraps.php?scraps_id=' . $this->id () . '">'.icms_wordwrap($this->getVar('scrap_text', 'e'), 300, true).'</a>';
		return $ret;
	}

}
class ProfileScrapsHandler extends IcmsPersistableObjectHandler {

	/**
	 * Constructor
	 */
	public function __construct(& $db) {
		$this->IcmsPersistableObjectHandler($db, 'scraps', 'scraps_id', 'scrap_text', '', 'profile');
	}
}
?>