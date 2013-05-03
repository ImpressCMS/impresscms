<?php
/**
 * Manage users
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @category	ICMS
 * @package		Member
 * @subpackage	User
 * @version		SVN: $Id: Object.php 11761 2012-06-29 12:00:13Z m0nty $
 */

defined('ICMS_ROOT_PATH') or exit();
/**
 * Class for users
 * @author		Kazumi Ono <onokazu@xoops.org>
 * @category	ICMS
 * @package		Member
 * @subpackage	User
 */
class icms_member_user_Object extends icms_core_Object {
	/**
	 * Array of groups that user belongs to
	 * @var array
	 */
	private $_groups = array();
	/**
	 * @var bool is the user admin?
	 */
	static private $_isAdmin = array();
	/**
	 * @var string user's rank
	 */
	private $_rank = null;
	/**
	 * @var bool is the user online?
	 */
	private $_isOnline = null;

	/**
	 * constructor
	 * @param array $id Array of key-value-pairs to be assigned to the user. (for backward compatibility only)
	 * @param int $id ID of the user to be loaded from the database.
	 */
	public function __construct($id = null) {
		$this->initVar('uid', XOBJ_DTYPE_INT, null, false);
		$this->initVar('name', XOBJ_DTYPE_TXTBOX, null, false, 60);
		$this->initVar('uname', XOBJ_DTYPE_TXTBOX, null, true, 255);
		$this->initVar('email', XOBJ_DTYPE_TXTBOX, null, true, 60);
		$this->initVar('url', XOBJ_DTYPE_TXTBOX, null, false, 255);
		$this->initVar('user_avatar', XOBJ_DTYPE_TXTBOX, null, false, 30);
		$this->initVar('user_regdate', XOBJ_DTYPE_INT, null, false);
		$this->initVar('user_icq', XOBJ_DTYPE_TXTBOX, null, false, 15);
		$this->initVar('user_from', XOBJ_DTYPE_TXTBOX, null, false, 100);
		$this->initVar('user_sig', XOBJ_DTYPE_TXTAREA, null, false, null);
		$this->initVar('user_viewemail', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('actkey', XOBJ_DTYPE_OTHER, null, false);
		$this->initVar('user_aim', XOBJ_DTYPE_TXTBOX, null, false, 18);
		$this->initVar('user_yim', XOBJ_DTYPE_TXTBOX, null, false, 25);
		$this->initVar('user_msnm', XOBJ_DTYPE_TXTBOX, null, false, 100);
		$this->initVar('pass', XOBJ_DTYPE_TXTBOX, null, false, 255);
		$this->initVar('posts', XOBJ_DTYPE_INT, null, false);
		$this->initVar('attachsig', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('rank', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('level', XOBJ_DTYPE_TXTBOX, 0, false);
		$this->initVar('theme', XOBJ_DTYPE_OTHER, null, false);
		$this->initVar('timezone_offset', XOBJ_DTYPE_OTHER, null, false);
		$this->initVar('last_login', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('umode', XOBJ_DTYPE_OTHER, null, false);
		$this->initVar('uorder', XOBJ_DTYPE_INT, 1, false);
		// RMV-NOTIFY
		$this->initVar('notify_method', XOBJ_DTYPE_OTHER, 1, false);
		$this->initVar('notify_mode', XOBJ_DTYPE_OTHER, 0, false);
		$this->initVar('user_occ', XOBJ_DTYPE_TXTBOX, null, false, 100);
		$this->initVar('bio', XOBJ_DTYPE_TXTAREA, null, false, null);
		$this->initVar('user_intrest', XOBJ_DTYPE_TXTBOX, null, false, 150);
		$this->initVar('user_mailok', XOBJ_DTYPE_INT, 1, false);

		$this->initVar('language', XOBJ_DTYPE_OTHER, null, false);
		$this->initVar('openid', XOBJ_DTYPE_TXTBOX, '', false, 255);
		$this->initVar('user_viewoid', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('pass_expired', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('login_name', XOBJ_DTYPE_TXTBOX, null, true, 255);

		// for backward compatibility
		if (isset($id)) {
			if (is_array($id)) {
				$this->assignVars($id);
			} else {
				$member_handler = icms::handler('icms_member');
				$user =& $member_handler->getUser($id);
				foreach ($user->vars as $k => $v) {
					$this->assignVar($k, $v['value']);
				}
			}
		}
	}

	/**
	 * check if the user is a guest user
	 *
	 * @return bool returns false
	 */
	public function isGuest() {
		return false;
	}

	/**
	 * Updated by Catzwolf 11 Jan 2004
	 * find the username for a given ID
	 *
	 * @param int $userid ID of the user to find
	 * @param int $usereal switch for usename or realname
	 * @return string name of the user. name for "anonymous" if not found.
	 */
	static public function getUnameFromId($userid, $usereal = 0) {
		$userid = (int) $userid;
		$usereal = (int) $usereal;
		if ($userid > 0) {
			$member_handler = icms::handler('icms_member');
			$user =& $member_handler->getUser($userid);
			if (is_object($user)) {
				if ($usereal) {
					$name = $user->getVar('name');
					if ($name != '') {
						return icms_core_DataFilter::htmlSpecialChars($name);
					} else {
						return icms_core_DataFilter::htmlSpecialChars($user->getVar('uname'));
					}
				} else {
					return icms_core_DataFilter::htmlSpecialChars($user->getVar('uname'));
				}
			}
		}
		return $GLOBALS['icmsConfig']['anonymous'];
	}

	/**
	 * set the groups for the user
	 *
	 * @param array $groupsArr Array of groups that user belongs to
	 */
	public function setGroups($groupsArr) {
		if (is_array($groupsArr)) {
			$this->_groups =& $groupsArr;
		}
	}

	/**
	 * sends a welcome message to the user which account has just been activated
	 *
	 * return TRUE if success, FALSE if not
	 */
	public function sendWelcomeMessage() {
		global $icmsConfig, $icmsConfigUser;

		if (!$icmsConfigUser['welcome_msg']) return true;

		$xoopsMailer = new icms_messaging_Handler();
		$xoopsMailer->useMail();
		$xoopsMailer->setBody($icmsConfigUser['welcome_msg_content']);
		$xoopsMailer->assign('UNAME', $this->getVar('uname'));
		$user_email = $this->getVar('email');
		$xoopsMailer->assign('X_UEMAIL', $user_email);
		$xoopsMailer->setToEmails($user_email);
		$xoopsMailer->setFromEmail($icmsConfig['adminmail']);
		$xoopsMailer->setFromName($icmsConfig['sitename']);
		$xoopsMailer->setSubject(sprintf(_US_YOURREGISTRATION, icms_core_DataFilter::stripSlashesGPC($icmsConfig['sitename'])));
		if (!$xoopsMailer->send(true)) {
			$this->setErrors(_US_WELCOMEMSGFAILED);
			return false;
		} else {
			return true;
		}
	}

	/**
	 * sends a notification to admins to inform them that a new user registered
	 *
	 * This method first checks in the preferences if we need to send a notification to admins upon new user
	 * registration. If so, it sends the mail.
	 *
	 * return TRUE if success, FALSE if not
	 */
	public function newUserNotifyAdmin() {
		global $icmsConfigUser, $icmsConfig;

		if ($icmsConfigUser['new_user_notify'] == 1 && !empty($icmsConfigUser['new_user_notify_group'])) {
			$member_handler = icms::handler('icms_member');
			$xoopsMailer = new icms_messaging_Handler();
			$xoopsMailer->useMail();
			$xoopsMailer->setTemplate('newuser_notify.tpl');
			$xoopsMailer->assign('UNAME', $this->getVar('uname'));
			$xoopsMailer->assign('EMAIL', $this->getVar('email'));
			$xoopsMailer->setToGroups($member_handler->getGroup($icmsConfigUser['new_user_notify_group']));
			$xoopsMailer->setFromEmail($icmsConfig['adminmail']);
			$xoopsMailer->setFromName($icmsConfig['sitename']);
			$xoopsMailer->setSubject(sprintf(_US_NEWUSERREGAT, $icmsConfig['sitename']));
			if (!$xoopsMailer->send(true)) {
				$this->setErrors(_US_NEWUSERNOTIFYADMINFAIL);
				return false;
			} else {
				return true;
			}
		} else {
			return true;
		}
	}

	/**
	 * get the groups that the user belongs to
	 *
	 * @return array array of groups
	 */
	public function &getGroups() {
		if (empty($this->_groups)) {
			$member_handler = icms::handler('icms_member');
			$this->_groups =& $member_handler->getGroupsByUser($this->getVar('uid'));
		}
		return $this->_groups;
	}

	/**
	 * Is the user admin ?
	 *
	 * This method will return true if this user has admin rights for the specified module.<br />
	 * - If you don't specify any module ID, the current module will be checked.<br />
	 * - If you set the module_id to -1, it will return true if the user has admin rights for at least one module
	 *
	 * @param int $module_id check if user is admin of this module
	 * @staticvar array $buffer result buffer
	 * @return bool is the user admin of that module?
	 */
	public function isAdmin($module_id = null) {
		static $buffer = array();
		if (is_null($module_id)) {
			$module_id = isset($GLOBALS['xoopsModule']) ? $GLOBALS['xoopsModule']->getVar('mid', 'n') : 1;
		} elseif((int) $module_id < 1) {$module_id = 0;}

		if (!isset($buffer[$module_id])) {
			$moduleperm_handler = icms::handler('icms_member_groupperm');
			$buffer[$module_id] = $moduleperm_handler->checkRight('module_admin', $module_id, $this->getGroups());
		}
		return $buffer[$module_id];
	}

	/**
	 * get the user's rank
	 * @return array array of rank ID and title
	 */
	public function rank() {
		if (!isset($this->_rank)) {
			$this->_rank = icms_getModuleHandler("userrank", "system")->getRank($this->getVar('rank'), $this->getVar('posts'));
		}
		return $this->_rank;
	}

	/**
	 * is the user activated?
	 * @return bool
	 */
	public function isActive() {
		if ($this->getVar('level') <= 0) {return false;}
		return true;
	}

	/**
	 * is the user currently logged in?
	 * @return bool
	 */
	public function isOnline() {
		if (!isset($this->_isOnline)) {
			$onlinehandler = icms::handler('icms_core_Online');
			$this->_isOnline =
				($onlinehandler->getCount(new icms_db_criteria_Item('online_uid', $this->getVar('uid'))) > 0)
				? true
				: false;
		}
		return $this->_isOnline;
	}

	/**
	 * Gravatar plugin for ImpressCMS
	 * @author TheRplima
	 *
	 * @param string $rating
	 * @param integer $size (size in pixels of the image. Accept values between 1 to 80. Default 80)
	 * @param string $default (url of default avatar. Will be used if no gravatar are found)
	 * @param string $border (hexadecimal color)
	 *
	 * @return string (gravatar or ImpressCMS avatar)
	 */
	public function gravatar($rating = false, $size = false, $default = false, $border = false, $overwrite = false) {
		if (!$overwrite && is_file(ICMS_UPLOAD_PATH . '/' . $this->getVar('user_avatar')) && $this->getVar('user_avatar') != 'blank.gif') {
			return ICMS_UPLOAD_URL . '/' . $this->getVar('user_avatar');
		}
		$ret = "http://www.gravatar.com/avatar/" . md5(strtolower($this->getVar('email', 'E'))) . "?d=identicon";
		if ($rating && $rating != '') {$ret .= "&amp;rating=" . $rating;}
		if ($size && $size != '') {$ret .="&amp;size=" . $size;}
		if ($default && $default != '') {$ret .= "&amp;default=" . urlencode($default);}
		if ($border && $border != '') {$ret .= "&amp;border=" . $border;}
		return $ret;
	}
}