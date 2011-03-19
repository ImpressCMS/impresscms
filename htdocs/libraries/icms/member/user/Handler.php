<?php
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
 * User handler class.
 * This class is responsible for providing data access mechanisms to the data source
 * of user class objects.
 *
 * @author		Kazumi Ono <onokazu@xoops.org>
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
	public function &create($isNew = true) {
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
		$user = false;
		if ($id > 0) {
			$sql = "SELECT * FROM " . icms::$xoopsDB->prefix('users') . " WHERE uid = '" . $id . "'";
			if (!$result = icms::$xoopsDB->query($sql)) {return $user;}
			$numrows = icms::$xoopsDB->getRowsNum($result);
			if ($numrows == 1) {
				$user = new icms_member_user_Object();
				$user->assignVars(icms::$xoopsDB->fetchArray($result));
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
	public function insert(&$user, $force = false) {
		/* As of PHP5.3.0, is_a() is no longer deprecated and there is no need to replace it */
		if (!is_a($user, 'icms_member_user_Object')) {return false;}
		if (!$user->isDirty()) {return true;}
		if (!$user->cleanVars()) {return false;}
		foreach ($user->cleanVars as $k => $v) {${$k} = $v;}

		// RMV-NOTIFY
		if ($user->isNew()) {
			$uid = icms::$xoopsDB->genId(icms::$xoopsDB->prefix('users') . '_uid_seq');
			$sql = sprintf("INSERT INTO %s (uid, uname, name, email, url, user_avatar, user_regdate, user_icq,
							user_from, user_sig, user_viewemail, actkey, user_aim, user_yim, user_msnm, pass, posts,
							attachsig, rank, level, theme, timezone_offset, last_login, umode, uorder, notify_method,
							notify_mode, user_occ, bio, user_intrest, user_mailok, language, openid, salt,
							user_viewoid, pass_expired, enc_type, login_name) VALUES ('%u', %s, %s, %s, %s, %s, '%u',
							%s, %s, %s, '%u', %s, %s, %s, %s, %s, '%u', '%u', '%u', '%u', %s, %s, '%u', %s, '%u',
							'%u', '%u', %s, %s, %s, '%u', %s, %s, %s, '%u', '%u', '%u', %s)",
							icms::$xoopsDB->prefix('users'),
							(int) $uid,
							icms::$xoopsDB->quoteString($uname),
							icms::$xoopsDB->quoteString($name),
							icms::$xoopsDB->quoteString($email),
							icms::$xoopsDB->quoteString($url),
							icms::$xoopsDB->quoteString($user_avatar),
							time(),
							icms::$xoopsDB->quoteString($user_icq),
							icms::$xoopsDB->quoteString($user_from),
							icms::$xoopsDB->quoteString($user_sig),
							(int) $user_viewemail,
							icms::$xoopsDB->quoteString($actkey),
							icms::$xoopsDB->quoteString($user_aim),
							icms::$xoopsDB->quoteString($user_yim),
							icms::$xoopsDB->quoteString($user_msnm),
							icms::$xoopsDB->quoteString($pass),
							(int) $posts,
							(int) $attachsig,
							(int) $rank,
							(int) $level,
							icms::$xoopsDB->quoteString($theme),
							icms::$xoopsDB->quoteString((float)($timezone_offset)),
							0,
							icms::$xoopsDB->quoteString($umode),
							(int) $uorder,
							(int) $notify_method,
							(int) $notify_mode,
							icms::$xoopsDB->quoteString($user_occ),
							icms::$xoopsDB->quoteString($bio),
							icms::$xoopsDB->quoteString($user_intrest),
							(int) $user_mailok,
							icms::$xoopsDB->quoteString($language),
							icms::$xoopsDB->quoteString($openid),
							icms::$xoopsDB->quoteString($salt),
							(int) $user_viewoid,
							(int) $pass_expired,
							(int) $enc_type,
							icms::$xoopsDB->quoteString($login_name)
						);
		} else {
			$sql = sprintf("UPDATE %s SET uname = %s, name = %s, email = %s, url = %s, user_avatar = %s,
							user_icq = %s, user_from = %s, user_sig = %s, user_viewemail = '%u', user_aim = %s,
							user_yim = %s, user_msnm = %s, posts = %d, pass = %s, attachsig = '%u', rank = '%u',
							level= '%s', theme = %s, timezone_offset = %s, umode = %s, last_login = '%u',
							uorder = '%u', notify_method = '%u', notify_mode = '%u', user_occ = %s, bio = %s,
							user_intrest = %s, user_mailok = '%u', language = %s, openid = %s, salt = %s,
							user_viewoid = '%u', pass_expired = '%u', enc_type = '%u', login_name = %s WHERE uid = '%u'",
							icms::$xoopsDB->prefix('users'), icms::$xoopsDB->quoteString($uname),
							icms::$xoopsDB->quoteString($name), icms::$xoopsDB->quoteString($email),
							icms::$xoopsDB->quoteString($url), icms::$xoopsDB->quoteString($user_avatar),
							icms::$xoopsDB->quoteString($user_icq), icms::$xoopsDB->quoteString($user_from),
							icms::$xoopsDB->quoteString($user_sig), $user_viewemail,
							icms::$xoopsDB->quoteString($user_aim), icms::$xoopsDB->quoteString($user_yim),
							icms::$xoopsDB->quoteString($user_msnm), (int) $posts,
							icms::$xoopsDB->quoteString($pass), (int) $attachsig,
							(int) $rank, (int) $level,
							icms::$xoopsDB->quoteString($theme), icms::$xoopsDB->quoteString((float)($timezone_offset)),
							icms::$xoopsDB->quoteString($umode), (int) $last_login,
							(int) $uorder, (int) $notify_method,
							(int) $notify_mode, icms::$xoopsDB->quoteString($user_occ),
							icms::$xoopsDB->quoteString($bio), icms::$xoopsDB->quoteString($user_intrest),
							(int) $user_mailok, icms::$xoopsDB->quoteString($language),
							icms::$xoopsDB->quoteString($openid), icms::$xoopsDB->quoteString($salt),
							(int) $user_viewoid, (int) $pass_expired,
							(int) $enc_type, icms::$xoopsDB->quoteString($login_name),
							(int) $uid
						);
		}
		if (false != $force) {
			$result = icms::$xoopsDB->queryF($sql);
		} else {
			$result = icms::$xoopsDB->query($sql);
		}
		if (!$result) {return false;}
		if ($user->isNew()) {
			$uid = icms::$xoopsDB->getInsertId();
			$user->assignVar('uid', $uid);
		}
		return true;
	}

	/**
	 * delete a user from the database
	 *
	 * @param object $user reference to the user to delete
	 * @param bool $force
	 * @return bool FALSE if failed.
	 * @TODO we need some kind of error message instead of just a false return to inform whether user was deleted aswell as PM messages.
	 */
	public function delete(&$user, $force = false) {
		/* As of PHP5.3.0, is_a() is no longer deprecated and there is no need to replace it */
		if (!is_a($user, 'icms_member_user_Object')) {return false;}
		$pass = substr(md5(time()), 0, 8);
		$salt = substr(md5(time() * 2), 0, 12);
		$sql = sprintf("UPDATE %s SET level = '-1', pass = '%s', salt = '%s' WHERE uid = '%u'",
						icms::$xoopsDB->prefix('users'),
						$pass,
						$salt,
						(int) $user->getVar('uid')
					);
		if (false != $force) {
			$result = icms::$xoopsDB->queryF($sql);
		} else {
			$result = icms::$xoopsDB->query($sql);
		}
		if (!$result) {
			return false;
		}
		return true;
	}

	/**
	 * retrieve users from the database
	 *
	 * @param object $criteria {@link icms_db_criteria_Element} conditions to be met
	 * @param bool $id_as_key use the UID as key for the array?
	 * @return array array of {@link icms_member_user_Object} objects
	 */
	public function getObjects($criteria = null, $id_as_key = false) {
		$ret = array();
		$limit = $start = 0;
		$sql = "SELECT * FROM " . icms::$xoopsDB->prefix('users');
		if (isset($criteria) && is_subclass_of($criteria, 'icms_db_criteria_Element')) {
			$sql .= " " . $criteria->renderWhere();
			if ($criteria->getSort() != '') {
				$sql .= " ORDER BY " . $criteria->getSort() . " " . $criteria->getOrder();
			}
			$limit = $criteria->getLimit();
			$start = $criteria->getStart();
		}
		$result = icms::$xoopsDB->query($sql, $limit, $start);
		if (!$result) {return $ret;}
		while ($myrow = icms::$xoopsDB->fetchArray($result)) {
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
	public function getCount($criteria = null) {
		$sql = 'SELECT COUNT(*) FROM ' . icms::$xoopsDB->prefix('users');
		if (isset($criteria) && is_subclass_of($criteria, 'icms_db_criteria_Element')) {$sql .= ' ' . $criteria->renderWhere();}
		$result = icms::$xoopsDB->query($sql);
		if (!$result) {return 0;}
		list($count) = icms::$xoopsDB->fetchRow($result);
		return $count;
	}

	/**
	 * delete users matching a set of conditions
	 *
	 * @param object $criteria {@link icms_db_criteria_Element}
	 * @return bool FALSE if deletion failed
	 * @TODO we need to also delete the private messages of the user when we delete them! how do we determine which users were deleted from the criteria????
	 */
	public function deleteAll($criteria = null) {
		$pass = substr(md5(time()), 0, 8);
		$salt = substr(md5(time() * 2), 0, 12);
		$sql = sprintf("UPDATE %s SET level= '-1', pass = %s, salt = %s", icms::$xoopsDB->prefix('users'), $pass, $salt);
		if (isset($criteria) && is_subclass_of($criteria, 'icms_db_criteria_Element')) {$sql .= " " . $criteria->renderWhere();}
		if (!$result = icms::$xoopsDB->query($sql)) {return false;}
		return true;
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
	public function updateAll($fieldname, $fieldvalue, $criteria = null) {
		$set_clause = is_numeric($fieldvalue) ? $fieldname . ' = ' . $fieldvalue : $fieldname . ' = ' . icms::$xoopsDB->quoteString($fieldvalue);
		$sql = 'UPDATE ' . icms::$xoopsDB->prefix('users') . ' SET ' . $set_clause;
		if (isset($criteria) && is_subclass_of($criteria, 'icms_db_criteria_Element')) {$sql .= ' ' . $criteria->renderWhere();}
		if (!$result = icms::$xoopsDB->query($sql)) {return false;}
		return true;
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
	function userCheck($login_name, $uname, $email, $pass, $vpass, $uid = 0)
	{
		global $icmsConfigUser;

		// initializations
		$member_handler = icms::handler('icms_member');
		$thisUser = ($uid > 0) ? $thisUser = $member_handler->getUser($uid) : false;
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
		if ((is_object($thisUser) && $thisUser->getVar('email', 'e') != $email && $email !== false) || !is_object($thisUser)) {
			if (!icms_core_DataFilter::checkVar($email, 'email', 0, 1)) $stop .= _US_INVALIDMAIL.'<br />';
			$count = $this->getCount(icms_buildCriteria(array('email' => addslashes($email))));
			if ($count > 0) $stop .= _US_EMAILTAKEN.'<br />';
		}

		// check login_name
		$login_name = icms_core_DataFilter::icms_trim($login_name);
		if ((is_object($thisUser) && $thisUser->getVar('login_name', 'e') != $login_name && $login_name !== false) || !is_object($thisUser)) {
			if (empty($login_name) || preg_match($restriction, $login_name)) $stop .= _US_INVALIDNICKNAME.'<br />';
			if (strlen($login_name) > $icmsConfigUser['maxuname']) $stop .= sprintf(_US_NICKNAMETOOLONG, $icmsConfigUser['maxuname']).'<br />';
			if (strlen($login_name) < $icmsConfigUser['minuname']) $stop .= sprintf(_US_NICKNAMETOOSHORT, $icmsConfigUser['minuname']).'<br />';
			foreach ($icmsConfigUser['bad_unames'] as $bu) {
				if (!empty($bu) && preg_match('/'.$bu.'/i', $login_name)) {
					$stop .= _US_NAMERESERVED.'<br />';
					break;
				}
			}
			if (strrpos($login_name, ' ') > 0) $stop .= _US_NICKNAMENOSPACES.'<br />';
			$count = $this->getCount(icms_buildCriteria(array('login_name' => addslashes($login_name))));
			if ($count > 0) $stop .= _US_LOGINNAMETAKEN.'<br />';
		}

		// check uname
		if ((is_object($thisUser) && $thisUser->getVar('uname', 'e') != $uname && $uname !== false) || !is_object($thisUser)) {
			if ($icmsStopSpammers->badUsername($uname)) $stop .= _US_INVALIDNICKNAME.'<br />';
			$count = $this->getCount(icms_buildCriteria(array('uname' => addslashes($uname))));
			if ($count > 0) $stop .= _US_NICKNAMETAKEN.'<br />';
		}

		// check password
		if ($pass !== false) {
			if (!isset($pass) || $pass == '' || !isset($vpass) || $vpass == '') $stop .= _US_ENTERPWD.'<br />';
			if ((isset($pass)) && ($pass != $vpass)) {
				$stop .= _US_PASSNOTSAME.'<br />';
			} elseif (($pass != '') && (strlen($pass) < $icmsConfigUser['minpass'])) {
				$stop .= sprintf(_US_PWDTOOSHORT,$icmsConfigUser['minpass']).'<br />';
			}
			if (isset($pass) && isset($login_name) && ($pass == $login_name || $pass == icms_core_DataFilter::utf8_strrev($login_name, true) || strripos($pass, $login_name) === true)) $stop .= _US_BADPWD.'<br />';
		}

		// check other things
		if ($icmsStopSpammers->badIP($_SERVER['REMOTE_ADDR'])) $stop .= _US_INVALIDIP.'<br />';

		return $stop;
	}

	/**
	 *
	 *
	 * @param string $email Email address for a user
	 */
	static public function getUnameFromEmail($email = '') {
		if ($email !== '') {
			$sql = icms::$xoopsDB->query("SELECT uname, email FROM " . icms::$xoopsDB->prefix('users')
				. " WHERE email = '" . @htmlspecialchars($email, ENT_QUOTES, _CHARSET)
				. "'");
			list($uname, $email) = icms::$xoopsDB->fetchRow($sql);
		} else {
			redirect_header('user.php', 2, _US_SORRYNOTFOUND);
		}
		return $uname;
	}


}

