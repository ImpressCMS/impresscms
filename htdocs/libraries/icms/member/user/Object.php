<?php
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //
/**
 * Manage users
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @category	ICMS
 * @package		Member
 * @subpackage	User
 * @version		SVN: $Id$
 */

defined('ICMS_ROOT_PATH') or exit();
/**
 * Class for users
 * @author		Kazumi Ono <onokazu@xoops.org>
 * @copyright	Copyright (c) 2000 XOOPS.org
 * @category	ICMS
 * @package		Member
 * @subpackage	User
 */
class icms_member_user_Object 
    extends icms_ipf_Object {
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
     * @param array $data Data to load into this object
	 */
	public function __construct(&$handler, $data = array()) {
            //parent::__construct($handler, $data);
            
		$this->initVar('uid', self::DTYPE_INTEGER, null, false, null, null, null, 'User ID');
		$this->initVar('name', self::DTYPE_DEP_TXTBOX, null, false, 60, null, null, _US_REALNAME);
		$this->initVar('uname', self::DTYPE_DEP_TXTBOX, null, true, 255, null, null, 'User Name');
		$this->initVar('email', self::DTYPE_DEP_TXTBOX, null, true, 60, null, null, _US_EMAIL);
		$this->initVar('url', self::DTYPE_DEP_TXTBOX, null, false, 255, null, null, _US_WEBSITE);
		$this->initVar('user_avatar', self::DTYPE_FILE, null, false, 30, null, null, _US_AVATAR);
		$this->initVar('user_regdate', self::DTYPE_INTEGER, null, false, null, null, null, 'Registration date');
		$this->initVar('user_icq', self::DTYPE_DEP_TXTBOX, null, false, 15, null, null, _US_ICQ);
		$this->initVar('user_from', self::DTYPE_DEP_TXTBOX, null, false, 100, null, null, _US_LOCATION);
		$this->initVar('user_sig', self::DTYPE_STRING, null, false, null, null, null, _US_SIGNATURE);
		$this->initVar('user_viewemail', self::DTYPE_BOOLEAN, 0, false, null, null, null, _US_ALLOWVIEWEMAIL);
		$this->initVar('actkey', self::DTYPE_DEP_TXTBOX, null, false, 100, null, null, 'Activation key');
		$this->initVar('user_aim', self::DTYPE_DEP_TXTBOX, null, false, 18, null, null, _US_AIM);
		$this->initVar('user_yim', self::DTYPE_DEP_TXTBOX, null, false, 25, null, null, _US_YIM);
		$this->initVar('user_msnm', self::DTYPE_DEP_TXTBOX, null, false, 100, null, null, _US_MSNM);
		$this->initVar('pass', self::DTYPE_DEP_TXTBOX, null, false, 255, null, null, _US_PASSWORD);
		$this->initVar('posts', self::DTYPE_INTEGER, null, false, null, null, null, _US_POSTS);
		$this->initVar('attachsig', self::DTYPE_BOOLEAN, 0, false, null, null, null, _US_SHOWSIG);
		$this->initVar('rank', self::DTYPE_INTEGER, 0, false, null, null, null, _US_RANK);
		$this->initVar('level', self::DTYPE_FLOAT, 0, false, null, null, null, 'Level');
		$this->initVar('theme', self::DTYPE_STRING, null, false, null, null, null, _US_SELECT_THEME);
		$this->initVar('timezone_offset', self::DTYPE_FLOAT, null, false, null, null, null, _US_TIMEZONE);
		$this->initVar('last_login', self::DTYPE_INTEGER, 0, false, null, null, null, _US_LASTLOGIN);
		$this->initVar('umode', self::DTYPE_INTEGER, null, false, null, null, null, _US_CDISPLAYMODE);
		$this->initVar('uorder', self::DTYPE_INTEGER, 1, false, null, null, null, _US_CSORTORDER);
		// RMV-NOTIFY
		$this->initVar('notify_method', self::DTYPE_INTEGER, 1, false, null, null, null, _NOT_NOTIFYMETHOD);
		$this->initVar('notify_mode', self::DTYPE_INTEGER, 0, false, null, null, null, _NOT_NOTIFYMODE);
		$this->initVar('user_occ', self::DTYPE_DEP_TXTBOX, null, false, 100, null, null, _US_OCCUPATION);
		$this->initVar('bio', self::DTYPE_STRING, null, false, null, null, null, null, _US_EXTRAINFO);
		$this->initVar('user_intrest', self::DTYPE_DEP_TXTBOX, null, false, 150, null, null, _US_INTEREST);
		$this->initVar('user_mailok', self::DTYPE_INTEGER, 1, false, null, null, null, _US_MAILOK);

		$this->initVar('language', self::DTYPE_STRING, null, false, null, null, null, _US_SELECT_LANG);
		$this->initVar('openid', self::DTYPE_DEP_TXTBOX, '', false, 255, null, null, _US_OPENID_YOUR);
		$this->initVar('user_viewoid', self::DTYPE_INTEGER, 0, false, null, null, null, _US_ALLOWVIEWEMAILOPENID);
		$this->initVar('pass_expired', self::DTYPE_BOOLEAN, 0, false, null, null, null, 'Pass Expired?');
		$this->initVar('login_name', self::DTYPE_DEP_TXTBOX, null, true, 255, null, null, _US_LOGINNAME);                
                
                if (isset($data['_rank'])) {
                    $this->_rank = $data['_rank'];
                    unset($data['_rank']);
                }
                if (isset($data['_groups'])) {
                    $this->_groups = $data['_groups'];
                    unset($data['_groups']);
                }
                
                parent::__construct($handler, $data);                                
	}

	/**
	 * check if the user is a guest user
	 *
	 * @return bool returns false
	 */
	public function isGuest() {
		return false;
	}

    public function getForm($form_caption, $form_name, $form_action = false, $submit_button_caption = _CO_ICMS_SUBMIT, $cancel_js_action = false, $captcha = false) {
        $this->hideFieldFromForm('pass');

        $this->makeFieldReadOnly('posts');
        $this->makeFieldReadOnly('user_regdate');
        $this->makeFieldReadOnly('last_login');
        $this->makeFieldReadOnly('uid');
        $this->makeFieldReadOnly('actkey');

        $this->setControl('theme', 'theme');
        $this->setControl('language', 'language');
        $this->setControl('uname', 'text');
        $this->setControl('login_name', 'text');
        $this->setControl('user_aim', 'text');
        $this->setControl('actkey', 'text');
        $this->setControl('name', 'text');
        $this->setControl('url', 'text');
        $this->setControl('email', 'text');
        $this->setControl('user_msnm', 'text');
        $this->setControl('user_yim', 'text');
        $this->setControl('user_icq', 'text');
        $this->setControl('timezone_offset', 'timezone');
        $this->setControl('user_from', 'country');
        $this->setControl('last_login', 'date');
        $this->setControl('user_regdate', 'date');
        $this->setControl('notify_method', 'notify_method');
        $this->setControl('pass_expired', 'yesno');
        $this->setControl('user_viewoid', 'yesno');
        $this->setControl('user_mailok', 'yesno');
        $this->setControl('attachsig', 'yesno');
        $this->setControl('rank', array(
            'name' => 'select',
            'itemHandler' => 'member_rank',
            'module' => 'icms',
            'method' => 'getList'
        ));
        $this->setControl('notify_method', array(
            'name' => 'select',
            'options' => array(
                XOOPS_NOTIFICATION_METHOD_DISABLE => _NOT_METHOD_DISABLE,
                XOOPS_NOTIFICATION_METHOD_PM => _NOT_METHOD_PM,
                XOOPS_NOTIFICATION_METHOD_EMAIL => _NOT_METHOD_EMAIL
            )
        ));
        $this->setControl('notify_mode', array(
            'name' => 'select',
            'options' => array(
                XOOPS_NOTIFICATION_MODE_SENDALWAYS => _NOT_MODE_SENDALWAYS,
                XOOPS_NOTIFICATION_MODE_SENDONCETHENDELETE => _NOT_MODE_SENDONCE,
                XOOPS_NOTIFICATION_MODE_SENDONCETHENWAIT => _NOT_MODE_SENDONCEPERLOGIN
            )
        ));
        $this->setControl('umode', array(
            'name' => 'select',
            'options' => array("nest" => _NESTED, "flat" => _FLAT, "thread" => _THREADED)
        ));
        $this->setControl('uorder', array(
            'name' => 'select',
            'options' => array("0" => _OLDESTFIRST, "1" => _NEWESTFIRST)
        ));
        return parent::getForm($form_caption, $form_name, $form_action, $submit_button_caption, $cancel_js_action, $captcha);
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
                trigger_error('Use same function from handler. This one is deprecahed!', E_DEPRECATED);
		$handler = icms::handler('icms_member_user');
                return $this->handler->getUnameFromId($userid, (bool)$usereal);
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
			$this->_groups = $member_handler->getGroupsByUser($this->getVar('uid'));
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
			$this->_rank = icms::handler('icms_member_rank')->getRank($this->getVar('rank'), $this->getVar('posts'));
		}
		return $this->_rank;
	}

	/**
	 * is the user activated?
	 * @return bool
	 */
	public function isActive() {
		return $this->getVar('level') > 0;
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


        
        public function setVar($name, $value, $options = null) {
            parent::setVar($name, $value, $options);
            if ($this->isSameAsLoggedInUser()) {
                $_SESSION['icmsUser'][$name] = parent::getVar($name);
            }
        }
        
        /**
         * Logs in current user
         */
        public function login() {
            $this->setVar('last_login', time());
            $this->store();
            $data = $this->toArray();
            $data['_rank'] = $this->rank();
            $data['_groups'] = $this->getGroups();
            unset($data['itemLink'], $data['itemUrl'], $data['editItemLink'], $data['deleteItemLink'], $data['printAndMailLink']);
            $class = get_class($this->handler);
            $_SESSION = array(
                'icmsUser' => $data, 
                'icmsUserHandler' => $class,
                'icmsUserPaths' => array(
                    icms_Autoloader::classPath($class),
                    icms_Autoloader::classPath(get_class($this))
                )
            );
        }
        
        /**
         * Logs out current user
         * 
         * @return boolean
         */
        public function logout() {
            if (!isset($_SESSION['icmsUser']['uid'])) 
                return false;
            if ($_SESSION['icmsUser']['uid'] != $this->getVar('uid')) 
                return false;
            $_SESSION = array();            
        }
        
        /**
         * Checks if this user is same as logged in user
         * 
         * @return boolean
         */
        public function isSameAsLoggedInUser() {
            if (!icms::$user)
                return false;
            return icms::$user->getVar('uid') == $this->getVar('uid');
        }
        
      /*  /**
         * Converts user to array
         * 
         * @return array
         */
      /*  public function toArray() {           
            $data = parent::toArray();
            if ($this->isSameAsLoggedInUser()) {
                if (!$data['user_viewoid'])
                    unset($data['openid']);
                unset($data['pass_expired'], $data['login_name'], $data['pass']);
                foreach (array_keys($data) as $key) {
                    if ($key == 'uid')
                        continue;
                }
            }
            return $data;
        }*/
}