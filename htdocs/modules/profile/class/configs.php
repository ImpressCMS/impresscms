<?php

/**
* Classes responsible for managing profile configs objects
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
include_once ICMS_ROOT_PATH . '/kernel/icmspersistableobject.php';
include_once(ICMS_ROOT_PATH . '/modules/profile/include/functions.php');

/**
 * Config status definitions
 */
define('PROFILE_CONFIG_STATUS_EVERYBODY', 1);
define('PROFILE_CONFIG_STATUS_MEMBERS', 2);
define('PROFILE_CONFIG_STATUS_FRIENDS', 3);
define('PROFILE_CONFIG_STATUS_ME', 4);

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
		$this->quickInitVar('config_uid', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('pictures', XOBJ_DTYPE_INT, false, false, false, PROFILE_CONFIG_STATUS_MEMBERS);
		$this->quickInitVar('audio', XOBJ_DTYPE_INT, false, false, false, PROFILE_CONFIG_STATUS_MEMBERS);
		$this->quickInitVar('videos', XOBJ_DTYPE_INT, false, false, false, PROFILE_CONFIG_STATUS_MEMBERS);
		$this->quickInitVar('scraps', XOBJ_DTYPE_INT, false, false, false, PROFILE_CONFIG_STATUS_MEMBERS);
		$this->quickInitVar('friendship', XOBJ_DTYPE_INT, false, false, false, PROFILE_CONFIG_STATUS_MEMBERS);
		$this->quickInitVar('tribes', XOBJ_DTYPE_INT, false, false, false, PROFILE_CONFIG_STATUS_MEMBERS);
		$this->quickInitVar('profile_contact', XOBJ_DTYPE_INT, false, false, false, PROFILE_CONFIG_STATUS_MEMBERS);
		$this->quickInitVar('profile_general', XOBJ_DTYPE_INT, false, false, false, PROFILE_CONFIG_STATUS_MEMBERS);
		$this->quickInitVar('profile_stats', XOBJ_DTYPE_INT, false, false, false, PROFILE_CONFIG_STATUS_MEMBERS);
		$this->quickInitVar('suspension', XOBJ_DTYPE_INT, false);
		$this->quickInitVar('backup_password', XOBJ_DTYPE_TXTAREA, false);
		$this->quickInitVar('backup_email', XOBJ_DTYPE_TXTBOX, false);
		$this->quickInitVar('end_suspension', XOBJ_DTYPE_LTIME, false);
		$this->quickInitVar('status', XOBJ_DTYPE_TXTBOX, false);

		$this->hideFieldFromForm('status');
		$this->hideFieldFromForm('backup_password');
		$this->hideFieldFromForm('configs_id');
		$this->hideFieldFromForm('backup_email');
		$this->setControl('config_uid', 'user');
		$this->setControl('suspension', 'yesno');
		$this->setControl('pictures', array (
			'itemHandler' => 'configs',
			'method' => 'getConfig_statusArray',
			'module' => 'profile'
		));
		$this->setControl('audio', array (
			'itemHandler' => 'configs',
			'method' => 'getConfig_statusArray',
			'module' => 'profile'
		));
		$this->setControl('videos', array (
			'itemHandler' => 'configs',
			'method' => 'getConfig_statusArray',
			'module' => 'profile'
		));
		$this->setControl('scraps', array (
			'itemHandler' => 'configs',
			'method' => 'getConfig_statusArray',
			'module' => 'profile'
		));
		$this->setControl('friendship', array (
			'itemHandler' => 'configs',
			'method' => 'getConfig_statusArray',
			'module' => 'profile'
		));
		$this->setControl('tribes', array (
			'itemHandler' => 'configs',
			'method' => 'getConfig_statusArray',
			'module' => 'profile'
		));
		$this->setControl('profile_contact', array (
			'itemHandler' => 'configs',
			'method' => 'getConfig_statusArray',
			'module' => 'profile'
		));
		$this->setControl('profile_general', array (
			'itemHandler' => 'configs',
			'method' => 'getConfig_statusArray',
			'module' => 'profile'
		));
		$this->setControl('profile_stats', array (
			'itemHandler' => 'configs',
			'method' => 'getConfig_statusArray',
			'module' => 'profile'
		));
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
	/**
	 * Check to see wether the current user can edit or delete this config
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
		return $this->getVar('config_uid', 'e') == $icmsUser->uid();
	}

}
class ProfileConfigsHandler extends IcmsPersistableObjectHandler {

	/**
	 * @var array of status
	 */
	var $_config_statusArray = array ();
	/**
	 * Constructor
	 */
	public function __construct(& $db) {
		$this->IcmsPersistableObjectHandler($db, 'configs', array('configs_id', 'config_uid'), '', '', 'profile');
	}
	/**
	 * Create the criteria that will be used by getConfigs and getConfigsCount
	 *
	 * @param int $start to which record to start
	 * @param int $limit limit of configs to return
	 * @param int $user_id if specifid, only the configs of this user will be returned
	 * @param int $config_id ID of a single config to retrieve
	 * @return CriteriaCompo $criteria
	 */
	function getConfigsCriteria($start = 0, $limit = 0, $user_id = false, $config_id = false) {
		global $icmsUser;

		$criteria = new CriteriaCompo();
		if ($start) {
			$criteria->setStart($start);
		}
		if ($limit) {
			$criteria->setLimit(intval($limit));
		}
		if ($user_id) {
			$criteria->add(new Criteria('config_uid', $user_id));
		}
		if ($config_id) {
			$criteria->add(new Criteria('configs_id', $config_id));
		}
		return $criteria;
	}

	/**
	 * Get single config object
	 *
	 * @param int $configs_id
	 * @return object ProfileConfig object
	 */
	function getConfig($user_id=false, $configs_id=false) {
		$ret = $this->getConfigs(0, 0, $user_id, $configs_id);
		return isset($ret[$configs_id]) ? $ret[$configs_id] : false;
	}

	/**
	 * Get configs as array, ordered by creation_time DESC
	 *
	 * @param int $start to which record to start
	 * @param int $limit max configs to display
	 * @param int $user_id if specifid, only the config of this user will be returned
	 * @param int $configs_id ID of a single config to retrieve
	 * @return array of configs
	 */
	function getConfigs($start = 0, $limit = 0, $user_id = false, $configs_id = false) {
		$criteria = $this->getConfigsCriteria($start, $limit, $user_id, $configs_id);
		$ret = $this->getObjects($criteria, true, false);
		return $ret;
	}

	/**
	 * Retreive the possible status of a config object
	 *
	 * @return array of status
	 */
	function getConfig_statusArray() {
		if (!$this->_config_statusArray) {
			$this->_config_statusArray[PROFILE_CONFIG_STATUS_EVERYBODY] = _CO_PROFILE_CONFIG_STATUS_EVERYBODY;
			$this->_config_statusArray[PROFILE_CONFIG_STATUS_MEMBERS] = _CO_PROFILE_CONFIG_STATUS_MEMBERS;
			$this->_config_statusArray[PROFILE_CONFIG_STATUS_FRIENDS] = _CO_PROFILE_CONFIG_STATUS_FRIENDS;
			$this->_config_statusArray[PROFILE_CONFIG_STATUS_ME] = _CO_PROFILE_CONFIG_STATUS_ME;
		}
		return $this->_config_statusArray;
	
	}
	/**
	 * Retreive the number of each item submitted by user in each section
	 *
	 * @return array of amounts
	 */
	function geteachSectioncounts($uid){
		$sql = 'SELECT COUNT(*) FROM'.$this->db->prefix('profile_audio').' WHERE uid_owner="$uid"';
		$audio = $this->query($sql);
		
		$sql = 'SELECT COUNT(*) FROM'.$this->db->prefix('profile_configs').' WHERE user_id="$uid"';
		$configs = $this->query($sql);
		
		$sql = 'SELECT COUNT(*) FROM'.$this->db->prefix('profile_friendship').' WHERE (friend1_uid="$uid" OR friend2_uid="$uid")';
		$friendship = $this->query($sql);
		
		$sql = 'SELECT COUNT(*) FROM'.$this->db->prefix('profile_scraps').' WHERE uid_owner="$uid"';
		$scraps = $this->query($sql);
		
		$sql = 'SELECT COUNT(*) FROM'.$this->db->prefix('profile_videos').' WHERE uid_owner="$uid"';
		$videos = $this->query($sql);
		
		$sql = 'SELECT COUNT(*) FROM'.$this->db->prefix('profile_tribes').' WHERE owner_uid="$uid"';
		$tribes = $this->query($sql);
		
		return array(
			'audio' => $audio,
			'configs' => $configs,
			'friendship' => $friendship,
			'scraps' => $scraps,
			'videos' => $videos,
			'tribes' => $tribes
			);
	}

	/**
	 * Check wether the current user can access a section or not
	 *
	 * @return bool true if he can false if not
	 */
	function userCanAcsessSection(& $obj, $item, $uid=false) {
		global $icmsUser, $profile_isAdmin;
		$status = $obj->getVar($item, 'e');
		if ($profile_isAdmin) {
			return true;
		}
		if($status == PROFILE_CONFIG_STATUS_EVERYBODY){
			return true;
		}
		if ($status == PROFILE_CONFIG_STATUS_MEMBERS && is_object($icmsUser)) {
			return true;
		}
		if($status == PROFILE_CONFIG_STATUS_FRIENDS){
			/*
			 * TODO: Create a function to check if a user is a friend or not.
			 */
				return false;
		}
		if ($status == PROFILE_CONFIG_STATUS_ME) {
			return $obj->getVar('config_uid', 'e') == $icmsUser->uid();
		}
	}

	/**
	 * Check wether the current user can submit a new config or not
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

	/**
	 * Retreive the config_id of user
	 *
	 * @return array of amounts
	 */
	function getConfigIdPerUser($uid){
		$sql = 'SELECT '.$this->keyName.' FROM'.$this->table.' WHERE config_uid="$uid"';
		$ret = $this->query($sql, false);
		return $ret;
	}

}
?>