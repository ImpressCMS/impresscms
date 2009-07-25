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
		$this->quickInitVar('creation_time', XOBJ_DTYPE_LTIME, false);
		$this->quickInitVar('private', XOBJ_DTYPE_INT, false);
		//$this->initCommonVar('counter', false);
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
	function getScrapText() {
		$ret = $this->getVar('scrap_text', 'e');
		return $ret;
	}

	function getScrapShortenText() {
		$ret = '<a href="' . ICMS_URL . '/modules/profile/scraps.php?scraps_id=' . $this->id () . '">'.icms_wordwrap($this->getVar('scrap_text', 'e'), 300, true).'</a>';
		return $ret;
	}

	/**
	 * Check to see wether the current user can edit or delete this scrap
	 *
	 * @return bool true if he can, false if not
	 */
	function userCanEditAndDelete() {
		global $icmsUser, $profile_isAdmin;
		if (!is_object($icmsUser)) {
			return false;
		}
		if ($profile_isAdmin) {
			return true;
		}
		return false;
	}

	/**
	 * Check to see wether the current user can view this scrap
	 *
	 * @return bool true if he can, false if not
	 */
	function userCanView() {
		global $icmsUser, $profile_isAdmin;
		if($profile_isAdmin || $this->getVar('private', 'e') == 0 || (($this->getVar('private', 'e') == 1) && is_object($icmsUser) && ($this->getVar('scrap_from', 'e') == intval($icmsUser->uid()) || $this->getVar('scrap_to', 'e') == intval($icmsUser->uid()) ))){
			return true;
		}
		return false;
	}

	/**
	 * Overridding IcmsPersistable::toArray() method to add a few info
	 *
	 * @return array of scrap info
	 */
	function toArray() {
		$ret = parent :: toArray();
		$ret['creation_time'] = formatTimestamp($this->getVar('creation_time', 'e'), 'm');
		$ret['editItemLink'] = $this->getEditItemLink(false, true, true);
		$ret['deleteItemLink'] = $this->getDeleteItemLink(false, true, true);
		$ret['userCanEditAndDelete'] = $this->userCanEditAndDelete();
		$ret['userCanView'] = $this->userCanView();
		$ret['scrap_senderid'] = $this->getVar('scrap_from','e');
		$ret['scrap_receiverid'] = $this->getVar('scrap_to','e');
		$ret['scrap_sender_link'] = $this->getScrapSender();
		$ret['scrap_content'] = $this->getScrapText();
		$ret['scrap_receiver_link'] = $this->getScrapReceiver();
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

	/**
	 * Create the criteria that will be used by getScraps and getScrapsCount
	 *
	 * @param int $start to which record to start
	 * @param int $limit limit of scraps to return
	 * @param int $scrap_to if specifid, only the scraps of this user will be returned
	 * @param int $scrap_id ID of a single scrap to retrieve
	 * @return CriteriaCompo $criteria
	 */
	function getScrapsCriteria($start = 0, $limit = 0, $scrap_to = false, $scrap_id = false) {
		global $icmsUser;

		$criteria = new CriteriaCompo();
		if ($start) {
			$criteria->setStart($start);
		}
		if ($limit) {
			$criteria->setLimit(intval($limit));
		}
		$criteria->setSort('creation_time');
		$criteria->setOrder('DESC');

		$criteria->add(new Criteria('private', 0));
		
		if ($scrap_to) {
			$criteria->add(new Criteria('scrap_to', $scrap_to));
		}
		if ($scrap_id) {
			$criteria->add(new Criteria('scraps_id', $scrap_id));
		}
		return $criteria;
	}

	/**
	 * Get single scrap object
	 *
	 * @param int $scraps_id
	 * @return object ProfileScrap object
	 */
	function getScrap($scraps_id=false, $scrap_to=false) {
		$ret = $this->getScraps(0, 0, $scrap_to, $scraps_id);
		return isset($ret[$scraps_id]) ? $ret[$scraps_id] : false;
	}

	/**
	 * Get scraps as array, ordered by creation_time DESC
	 *
	 * @param int $start to which record to start
	 * @param int $limit max scraps to display
	 * @param int $scrap_to if specifid, only the scrap of this user will be returned
	 * @param int $scraps_id ID of a single scrap to retrieve
	 * @return array of scraps
	 */
	function getScraps($start = 0, $limit = 0, $scrap_to = false, $scraps_id = false) {
		$criteria = $this->getScrapsCriteria($start, $limit, $scrap_to, $scraps_id);
		$ret = $this->getObjects($criteria, true, false);
		return $ret;
	}

	
	/**
	 * Check wether the current user can submit a new scrap or not
	 *
	 * @return bool true if he can false if not
	 */
	function userCanSubmit() {
		global $icmsUser;
		if (!is_object($icmsUser)) {
			return false;
		}
		return true;
	}

}
?>