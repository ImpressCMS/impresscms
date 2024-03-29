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
 * @version		SVN: $Id: Handler.php 12313 2013-09-15 21:14:35Z skenow $
 */

defined('ICMS_ROOT_PATH') or exit();

/**
 * User handler class.
 * This class is responsible for providing data access mechanisms to the data source
 * of user class objects.
 *
 * @author		Kazumi Ono <onokazu@xoops.org>
 * @copyright	Copyright (c) 2000 XOOPS.org
 * @category	ICMS
 * @package		Member
 * @subpackage	User
 */
class icms_member_user_Handler extends icms_core_ObjectHandler {
	/**
	 * create a new user
	 *
	 * @param bool $isNew flag the new objects as "new"?
	 * @return object icms_member_user_Object
	 */

	/**
	 * Make sure the registration date of a user is set.
	 * @param $obj the user object
	 */
	protected function beforeInsert(&$obj) {
		if (!$obj->user_regdate) {
			$obj->user_regdate = time();
		}
	}

	public function &create($isNew = TRUE) {
		$user = new icms_member_user_Object();
		if ($isNew) {
			$user->setNew();
		}
		return $user;
	}

	/**
	 * retrieve a user from ID
	 *
	 * @param int $id UID of the user
	 * @return mixed reference to the {@link icms_member_user_Object} object, FALSE if failed
	 */
	public function &get($id) {
		$id = (int) $id;
		$user = FALSE;
		if ($id > 0) {
			$sql = "SELECT * FROM " . $this->db->prefix('users') . " WHERE uid = '" . $id . "'";
			if (!$result = $this->db->query($sql)) {return $user;}
			$numrows = $this->db->getRowsNum($result);
			if ($numrows == 1) {
				$user = new icms_member_user_Object();
				$user->assignVars($this->db->fetchArray($result));
			}
		}
		return $user;
	}

	/**
	 * insert a new user in the database
	 *
	 * @param object $user reference to the {@link icms_member_user_Object} object
	 * @param bool $force
	 * @return bool FALSE if failed, TRUE if already present and unchanged or successful
	 */
	public function insert(&$user, $force = FALSE) {
		/* As of PHP5.3.0, is_a() is no longer deprecated and there is no need to replace it */
		if (!is_a($user, 'icms_member_user_Object')) {return FALSE;}
		if (!$user->isDirty()) {return TRUE;}
		if (!$user->cleanVars()) {
			return FALSE;
		}

		$dataToSave = [];
		foreach ($user->getVars() as $var => $varInfo) {
			if (!isset($user->cleanVars[$var])) {
				continue;
			}

			$currentValue = $user->cleanVars[$var];

			switch ($varInfo['data_type']) {
				case XOBJ_DTYPE_INT:
					$dataToSave[$var] = (int)$currentValue;
				break;
				case XOBJ_DTYPE_OTHER:
					if ($var === 'timezone_offset') {
						$dataToSave[$var] = (float)$currentValue;
					} else {
						$dataToSave[$var] = $this->db->quoteString($currentValue);
					}
				break;
				default:
					$dataToSave[$var] = $this->db->quoteString($currentValue);
				break;
			}
		}

		if ($user->isNew()) {
			$dataToSave['last_login'] = 0;
			$dataToSave['user_regdate'] = time();

			$sql = 'INSERT INTO `' . $this->db->prefix('users') . '`(';
			$first = true;
			foreach (array_keys($dataToSave) as $columnName) {
				if ($first) {
					$first = false;
				} else {
					$sql .= ', ';
				}

				$sql .= "`" . $columnName . "`";
			}
			$sql .= ') VALUE(';
			$first = true;
			foreach ($dataToSave as $columnValue) {
				if ($first) {
					$first = false;
				} else {
					$sql .= ', ';
				}

				$sql .= $columnValue;
			}
			$sql .= ');';
		} else {
			$sql = 'UPDATE `' . $this->db->prefix('users') . '` SET ';
			$first = true;
			foreach ($dataToSave as $columnName => $columnValue) {
				if ($first) {
					$first = false;
				} else {
					$sql .= ', ';
				}

				$sql .= "`" . $columnName . "`=" . $columnValue;
			}
			$sql .= ' WHERE `uid`=' . (int)$user->cleanVars['uid'];
		}
		if (FALSE != $force) {
			$result = $this->db->queryF($sql);
		} else {
			$result = $this->db->query($sql);
		}
		if (!$result) {
			return FALSE;
		}
		if ($user->isNew()) {
			$uid = $this->db->getInsertId();
			$user->assignVar('uid', $uid);
		}
		return TRUE;
	}

	/**
	 * delete a user from the database
	 *
	 * @param object $user reference to the user to delete
	 * @param bool $force
	 * @return bool FALSE if failed.
	 * @TODO we need some kind of error message instead of just a FALSE return to inform whether user was deleted aswell as PM messages.
	 */
	public function delete(&$user, $force = FALSE) {
		/* As of PHP5.3.0, is_a() is no longer deprecated and there is no need to replace it */
		if (!is_a($user, 'icms_member_user_Object')) {return FALSE;}
		$pass = substr(md5(time()), 0, 8);
		$sql = sprintf(
			"UPDATE %s SET level = '-1', pass = '%s' WHERE uid = '%u'",
			$this->db->prefix('users'), $pass, (int) $user->getVar('uid')
		);
		if (FALSE != $force) {
			$result = $this->db->queryF($sql);
		} else {
			$result = $this->db->query($sql);
		}
		if (!$result) {
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * retrieve users from the database
	 *
	 * @param object $criteria {@link icms_db_criteria_Element} conditions to be met
	 * @param bool $id_as_key use the UID as key for the array?
	 * @return array array of {@link icms_member_user_Object} objects
	 */
	public function getObjects($criteria = NULL, $id_as_key = FALSE) {
		$ret = array();
		$limit = $start = 0;
		$sql = "SELECT * FROM " . $this->db->prefix('users');
		if (isset($criteria) && is_subclass_of($criteria, 'icms_db_criteria_Element')) {
			$sql .= " " . $criteria->renderWhere();
			if ($criteria->getSort() != '') {
				$sql .= " ORDER BY " . $criteria->getSort() . " " . $criteria->getOrder();
			}
			$limit = $criteria->getLimit();
			$start = $criteria->getStart();
		}
		$result = $this->db->query($sql, $limit, $start);
		if (!$result) {return $ret;}
		while ($myrow = $this->db->fetchArray($result)) {
			$user = new icms_member_user_Object();
			$user->assignVars($myrow);
			if (!$id_as_key) {
				$ret[] =& $user;
			} else {
				$ret[$myrow['uid']] =& $user;
			}
			unset($user);
		}
		return $ret;
	}

	/**
	 * count users matching a condition
	 *
	 * @param object $criteria {@link icms_db_criteria_Element} to match
	 * @return int count of users
	 */
	public function getCount($criteria = NULL) {
		$sql = 'SELECT COUNT(*) FROM ' . $this->db->prefix('users');
		if (isset($criteria) && is_subclass_of($criteria, 'icms_db_criteria_Element')) {$sql .= ' ' . $criteria->renderWhere();}
		$result = $this->db->query($sql);
		if (!$result) {return 0;}
		list($count) = $this->db->fetchRow($result);
		return $count;
	}

	/**
	 * delete users matching a set of conditions
	 *
	 * @param object $criteria {@link icms_db_criteria_Element}
	 * @return bool FALSE if deletion failed
	 * @TODO we need to also delete the private messages of the user when we delete them! how do we determine which users were deleted from the criteria????
	 */
	public function deleteAll($criteria = NULL) {
		$pass = substr(md5(time()), 0, 8);
		$sql = sprintf("UPDATE %s SET level= '-1', pass = %s", $this->db->prefix('users'), $pass);
		if (isset($criteria) && is_subclass_of($criteria, 'icms_db_criteria_Element')) {$sql .= " " . $criteria->renderWhere();}
		if (!$result = $this->db->query($sql)) {return FALSE;}
		return TRUE;
	}

	/**
	 * Change a value for users with a certain criteria
	 *
	 * @param   string  $fieldname  Name of the field
	 * @param   string  $fieldvalue Value to write
	 * @param   object  $criteria   {@link icms_db_criteria_Element}
	 *
	 * @return  bool
	 **/
	public function updateAll($fieldname, $fieldvalue, $criteria = NULL) {
		$set_clause = is_numeric($fieldvalue) ? $fieldname . ' = ' . $fieldvalue : $fieldname . ' = ' . $this->db->quoteString($fieldvalue);
		$sql = 'UPDATE ' . $this->db->prefix('users') . ' SET ' . $set_clause;
		if (isset($criteria) && is_subclass_of($criteria, 'icms_db_criteria_Element')) {$sql .= ' ' . $criteria->renderWhere();}
		if (!$result = $this->db->query($sql)) {return FALSE;}
		return TRUE;
	}

	/**
	 *  Validates username, email address and password entries during registration
	 *  Username is validated for uniqueness and length
	 *  password is validated for length and strictness
	 *  email is validated as a proper email address pattern
	 *
	 *  @param string $uname User display name entered by the user
	 *  @param string $login_name Username entered by the user
	 *  @param string $email Email address entered by the user
	 *  @param string $pass Password entered by the user
	 *  @param string $vpass Password verification entered by the user
	 *  @param int $uid user id (only applicable if the user already exists)
	 *  @global array $icmsConfigUser user configuration
	 *  @return string of errors encountered while validating the user information, will be blank if successful
	 */
	public function userCheck($login_name, $uname, $email, $pass, $vpass, $uid = 0) {
		global $icmsConfigUser;

		// initializations
		$member_handler = icms::handler('icms_member');
		$thisUser = ($uid > 0) ? $thisUser = $member_handler->getUser($uid) : FALSE;
		$icmsStopSpammers = new icms_core_StopSpammer();
		$stop = '';
		switch ($icmsConfigUser['uname_test_level']) {
			case 0: // strict
				$restriction = '/[^a-zA-Z0-9\_\-]/';
				break;
			case 1: // medium
				$restriction = '/[^a-zA-Z0-9\_\-\<\>\,\.\$\%\#\@\!\\\'\"]/';
				break;
			case 2: // loose
				$restriction = '/[\000-\040]/';
				break;
		}

		// check email
		if ((is_object($thisUser) && $thisUser->getVar('email', 'e') != $email && $email !== FALSE) || !is_object($thisUser)) {
			if (!icms_core_DataFilter::checkVar($email, 'email', 0, 1)) $stop .= _US_INVALIDMAIL . '<br />';
			$count = $this->getCount(icms_buildCriteria(array('email' => addslashes($email))));
			if ($count > 0) $stop .= _US_EMAILTAKEN . '<br />';
		}

		// check login_name
		$login_name = icms_core_DataFilter::icms_trim($login_name);
		if ((is_object($thisUser) && $thisUser->getVar('login_name', 'e') != $login_name && $login_name !== FALSE) || !is_object($thisUser)) {
			if (empty($login_name) || preg_match($restriction, $login_name)) $stop .= _US_INVALIDNICKNAME . '<br />';
			if (strlen($login_name) > $icmsConfigUser['maxuname']) $stop .= sprintf(_US_NICKNAMETOOLONG, $icmsConfigUser['maxuname']) . '<br />';
			if (strlen($login_name) < $icmsConfigUser['minuname']) $stop .= sprintf(_US_NICKNAMETOOSHORT, $icmsConfigUser['minuname']) . '<br />';
			foreach ($icmsConfigUser['bad_unames'] as $bu) {
				if (!empty($bu) && preg_match('/' . $bu . '/i', $login_name)) {
					$stop .= _US_NAMERESERVED . '<br />';
					break;
				}
			}
			if (strrpos($login_name, ' ') > 0) $stop .= _US_NICKNAMENOSPACES . '<br />';
			$count = $this->getCount(icms_buildCriteria(array('login_name' => addslashes($login_name))));
			if ($count > 0) $stop .= _US_LOGINNAMETAKEN . '<br />';
		}

		// check uname
		if ((is_object($thisUser) && $thisUser->getVar('uname', 'e') != $uname && $uname !== FALSE) || !is_object($thisUser)) {
			$count = $this->getCount(icms_buildCriteria(array('uname' => addslashes($uname))));
			if ($count > 0) $stop .= _US_NICKNAMETAKEN . '<br />';
		}

		// check password
		if ($pass !== FALSE) {
			if (!isset($pass) || $pass == '' || !isset($vpass) || $vpass == '') $stop .= _US_ENTERPWD . '<br />';
			if ((isset($pass)) && ($pass != $vpass)) {
				$stop .= _US_PASSNOTSAME . '<br />';
			} elseif (($pass != '') && (strlen($pass) < $icmsConfigUser['minpass'])) {
				$stop .= sprintf(_US_PWDTOOSHORT,$icmsConfigUser['minpass']) . '<br />';
			}
			if (isset($pass) && isset($login_name) && ($pass == $login_name || $pass == icms_core_DataFilter::utf8_strrev($login_name, TRUE) || strripos($pass, $login_name) === TRUE)) $stop .= _US_BADPWD . '<br />';
		}

		// check other things
		if ($icmsStopSpammers->badIP($_SERVER['REMOTE_ADDR'])) $stop .= _US_INVALIDIP . '<br />';

		return $stop;
	}

	/**
	 * Return a linked username or full name for a specific $userid
	 *
	 * replaces include/functions.php :: xoops_getLinkedUnameFromId
	 *
	 * @param	integer	$uid	uid of the related user
	 * @param	boolean	$name	TRUE to return the fullname, FALSE to use the username; if TRUE and the user does not have fullname, username will be used instead
	 * @param	array	$users	array already containing icms_member_user_Object objects in which case we will save a query
	 * @param	boolean	$withContact TRUE if we want contact details to be added in the value returned (PM and email links)
	 * @param	boolean	$isAuthor	Set this to TRUE if you want the rel='author' attribute added to the link
	 */
	static public function getUserLink($uid, $name = FALSE, $users = array(), $withContact = FALSE, $isAuthor = FALSE) {
		global $icmsConfig;

		if (!is_numeric($uid)) return $uid;
		$uid = (int) $uid;
		if ($uid > 0) {
			if ($users == array()) {
				$member_handler = icms::handler("icms_member");
				$user = $member_handler->getUser($uid);
			} else {
				if (!isset($users[$uid])) return $icmsConfig["anonymous"];
				$user = $users[$uid];
			}

			if (is_object($user)) {
				$author = $isAuthor ? " rel='author'" : "";
				$fullname = '';
				$linkeduser = '';

				$username = $user->getVar('uname');
				$fullname2 = $user->getVar('name');
				if (($name) && !empty($fullname2)) $fullname = $user->getVar('name');
				if (!empty($fullname)) $linkeduser = $fullname . "[";
                $linkeduser .= '<a href="' . ICMS_URL . '/userinfo.php?uid=' . $uid . '"' . $author . '>';
				$linkeduser .= icms_core_DataFilter::htmlSpecialChars($username) . "</a>";
				if (!empty($fullname)) $linkeduser .= "]";

				if ($withContact) {
					$linkeduser .= '<a href="mailto:' . $user->getVar('email') . '">';
					$linkeduser .= '<img style="vertical-align: middle;" src="' . ICMS_IMAGES_URL
						. '/icons/' . $icmsConfig["language"] . '/email.gif' . '" alt="'
						. _US_SEND_MAIL . '" title="' . _US_SEND_MAIL . '"/></a>';
					$js = "javascript:openWithSelfMain('" . ICMS_URL . '/pmlite.php?send2=1&to_userid='
						. $uid . "', 'pmlite', 450, 370);";
					$linkeduser .= '<a href="' . $js . '"><img style="vertical-align: middle;" src="'
						. ICMS_IMAGES_URL . '/icons/' . $icmsConfig["language"] . '/pm.gif'
						. '" alt="' . _US_SEND_PM . '" title="' . _US_SEND_PM . '"/></a>';
				}

				return $linkeduser;
			}
		}
		return $icmsConfig["anonymous"];
	}

	/**
	 *
	 *
	 * @param string $email Email address for a user
	 */
	static public function getUnameFromEmail($email = '') {
		$db = icms_db_Factory::instance();
		if ($email !== '') {
			$sql = $db->query("SELECT uname, email FROM " . $db->prefix('users')
				. " WHERE email = '" . @htmlspecialchars($email, ENT_QUOTES, _CHARSET)
				. "'");
			list($uname, $email) = $db->fetchRow($sql);
		} else {
			redirect_header('user.php', 2, _US_SORRYNOTFOUND);
		}
		return $uname;
	}
}
