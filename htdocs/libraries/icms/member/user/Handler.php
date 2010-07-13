<?php
/**
 * Manage of users
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	XOOPS_copyrights.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	LICENSE.txt
 * @package	core
 * @since	XOOPS
 * @author	http://www.xoops.org The XOOPS Project
 * @author	modified by UnderDog <underdog@impresscms.org>
 * @version	$Id: user.php 19586 2010-06-24 11:48:14Z malanciault $
 */

if (!defined('ICMS_ROOT_PATH')) {exit();}

/**
 * XOOPS user handler class.
 * This class is responsible for providing data access mechanisms to the data source
 * of XOOPS user class objects.
 *
 * @author  Kazumi Ono <onokazu@xoops.org>
 * @copyright copyright (c) 2000-2003 XOOPS.org
 * @package kernel
 */
class icms_member_user_Handler extends icms_core_ObjectHandler
{
	/**
	 * create a new user
	 *
	 * @param bool $isNew flag the new objects as "new"?
	 * @return object icms_member_user_Object
	 */
	function &create($isNew = true)
	{
		$user = new icms_member_user_Object();
		if ($isNew)
		{
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
	function &get($id)
	{
		$id = (int) ($id);
		$user = false;
		if ($id > 0)
		{
			$sql = "SELECT * FROM ".$this->db->prefix('users')." WHERE uid='".$id."'";
			if (!$result = $this->db->query($sql)) {return $user;}
			$numrows = $this->db->getRowsNum($result);
			if ($numrows == 1)
			{
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
	function insert(&$user, $force = false)
	{
		/**
		 * @TODO: Change to if (!(class_exists($this->className) && $obj instanceof $this->className)) when going fully PHP5
		 */
		icms_debug(get_class($user));
		if (!is_a($user, 'icms_member_user_Object')) {return false;}
		if (!$user->isDirty()) {return true;}
		if (!$user->cleanVars()) {return false;}
		foreach ($user->cleanVars as $k => $v) {${$k} = $v;}

		// RMV-NOTIFY
		if ($user->isNew())
		{
			$uid = $this->db->genId($this->db->prefix('users').'_uid_seq');
			$sql = sprintf("INSERT INTO %s (uid, uname, name, email, url, user_avatar, user_regdate, user_icq, user_from, user_sig, user_viewemail, actkey, user_aim, user_yim, user_msnm, pass, posts, attachsig, rank, level, theme, timezone_offset, last_login, umode, uorder, notify_method, notify_mode, user_occ, bio, user_intrest, user_mailok, language, openid, salt, user_viewoid, pass_expired, enc_type, login_name) VALUES ('%u', %s, %s, %s, %s, %s, '%u', %s, %s, %s, '%u', %s, %s, %s, %s, %s, '%u', '%u', '%u', '%u', %s, %s, '%u', %s, '%u', '%u', '%u', %s, %s, %s, '%u', %s, %s, %s, '%u', '%u', '%u', %s)", $this->db->prefix('users'), (int) ($uid), $this->db->quoteString($uname), $this->db->quoteString($name), $this->db->quoteString($email), $this->db->quoteString($url), $this->db->quoteString($user_avatar), time(), $this->db->quoteString($user_icq), $this->db->quoteString($user_from), $this->db->quoteString($user_sig), (int) ($user_viewemail), $this->db->quoteString($actkey), $this->db->quoteString($user_aim), $this->db->quoteString($user_yim), $this->db->quoteString($user_msnm), $this->db->quoteString($pass), (int) ($posts), (int) ($attachsig), (int) ($rank), (int) ($level), $this->db->quoteString($theme), $this->db->quoteString((float)($timezone_offset)), 0, $this->db->quoteString($umode), (int) ($uorder), (int) ($notify_method), (int) ($notify_mode), $this->db->quoteString($user_occ), $this->db->quoteString($bio), $this->db->quoteString($user_intrest), (int) ($user_mailok), $this->db->quoteString($language), $this->db->quoteString($openid), $this->db->quoteString($salt), (int) ($user_viewoid), (int) ($pass_expired), (int) ($enc_type), $this->db->quoteString($login_name));
		} else {
			$sql = sprintf("UPDATE %s SET uname = %s, name = %s, email = %s, url = %s, user_avatar = %s, user_icq = %s, user_from = %s, user_sig = %s, user_viewemail = '%u', user_aim = %s, user_yim = %s, user_msnm = %s, posts = %d, pass = %s, attachsig = '%u', rank = '%u', level= '%s', theme = %s, timezone_offset = %s, umode = %s, last_login = '%u', uorder = '%u', notify_method = '%u', notify_mode = '%u', user_occ = %s, bio = %s, user_intrest = %s, user_mailok = '%u', language = %s, openid = %s, salt = %s, user_viewoid = '%u', pass_expired = '%u', enc_type = '%u', login_name = %s WHERE uid = '%u'", $this->db->prefix('users'), $this->db->quoteString($uname), $this->db->quoteString($name), $this->db->quoteString($email), $this->db->quoteString($url), $this->db->quoteString($user_avatar), $this->db->quoteString($user_icq), $this->db->quoteString($user_from), $this->db->quoteString($user_sig), $user_viewemail, $this->db->quoteString($user_aim), $this->db->quoteString($user_yim), $this->db->quoteString($user_msnm), (int) ($posts), $this->db->quoteString($pass), (int) ($attachsig), (int) ($rank), (int) ($level), $this->db->quoteString($theme), $this->db->quoteString((float)($timezone_offset)), $this->db->quoteString($umode), (int) ($last_login), (int) ($uorder), (int) ($notify_method), (int) ($notify_mode), $this->db->quoteString($user_occ), $this->db->quoteString($bio), $this->db->quoteString($user_intrest), (int) ($user_mailok), $this->db->quoteString($language), $this->db->quoteString($openid), $this->db->quoteString($salt), (int) ($user_viewoid), (int) ($pass_expired), (int) ($enc_type), $this->db->quoteString($login_name), (int) ($uid));
		}
		if (false != $force)
		{
			$result = $this->db->queryF($sql);
		} else {
			$result = $this->db->query($sql);
		}
		if (!$result) {return false;}
		if ($user->isNew())
		{
			$uid = $this->db->getInsertId();
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
	 */
	function delete(&$user, $force = false)
	{
		/**
		 * @TODO: Change to if (!(class_exists($this->className) && $obj instanceof $this->className)) when going fully PHP5
		 */
		if (!is_a($user, 'icms_member_user_Object')) {return false;}
		$pass = substr ( md5 ( time () ), 0, 8 );
		$salt = substr ( md5 ( time () * 2 ), 0, 12 );
		$sql = sprintf("UPDATE %s SET level = '-1', pass = '%s', salt = '%s' WHERE uid = '%u'", $this->db->prefix('users'), $pass, $salt, (int) ($user->getVar('uid')));
		if (false != $force)
		{
			$result = $this->db->queryF($sql);
		}
		else {$result = $this->db->query($sql);}
		if (!$result) {return false;}
		return true;
	}

	/**
	 * retrieve users from the database
	 *
	 * @param object $criteria {@link icms_criteria_Element} conditions to be met
	 * @param bool $id_as_key use the UID as key for the array?
	 * @return array array of {@link icms_member_user_Object} objects
	 */
	function getObjects($criteria = null, $id_as_key = false)
	{
		$ret = array();
		$limit = $start = 0;
		$sql = "SELECT * FROM ".$this->db->prefix('users');
		if (isset($criteria) && is_subclass_of($criteria, 'icms_criteria_Element'))
		{
			$sql .= " ".$criteria->renderWhere();
			if ($criteria->getSort() != '') {$sql .= " ORDER BY ".$criteria->getSort()." ".$criteria->getOrder();}
			$limit = $criteria->getLimit();
			$start = $criteria->getStart();
		}
		$result = $this->db->query($sql, $limit, $start);
		if (!$result) {return $ret;}
		while ($myrow = $this->db->fetchArray($result))
		{
			$user = new icms_member_user_Object();
			$user->assignVars($myrow);
			if (!$id_as_key)
			{
				$ret[] =& $user;
			}
			else {$ret[$myrow['uid']] =& $user;}
			unset($user);
		}
		return $ret;
	}

	/**
	 * count users matching a condition
	 *
	 * @param object $criteria {@link icms_criteria_Element} to match
	 * @return int count of users
	 */
	function getCount($criteria = null)
	{
		$sql = 'SELECT COUNT(*) FROM '.$this->db->prefix('users');
		if (isset($criteria) && is_subclass_of($criteria, 'icms_criteria_Element')) {$sql .= ' '.$criteria->renderWhere();}
		$result = $this->db->query($sql);
		if (!$result) {return 0;}
		list($count) = $this->db->fetchRow($result);
		return $count;
	}

	/**
	 * delete users matching a set of conditions
	 *
	 * @param object $criteria {@link icms_criteria_Element}
	 * @return bool FALSE if deletion failed
	 */
	function deleteAll($criteria = null)
	{
		$pass = substr ( md5 ( time () ), 0, 8 );
		$salt = substr ( md5 ( time () * 2 ), 0, 12 );
		$sql = sprintf("UPDATE %s SET level= '-1', pass = %s, salt = %s", $this->db->prefix('users'), $pass, $salt);
		if (isset($criteria) && is_subclass_of($criteria, 'icms_criteria_Element')) {$sql .= " ".$criteria->renderWhere();}
		if (!$result = $this->db->query($sql)) {return false;}
		return true;
	}

	/**
	 * Change a value for users with a certain criteria
	 *
	 * @param   string  $fieldname  Name of the field
	 * @param   string  $fieldvalue Value to write
	 * @param   object  $criteria   {@link icms_criteria_Element}
	 *
	 * @return  bool
	 **/
	function updateAll($fieldname, $fieldvalue, $criteria = null)
	{
		$set_clause = is_numeric($fieldvalue) ? $fieldname.' = '.$fieldvalue : $fieldname.' = '.$this->db->quoteString($fieldvalue);
		$sql = 'UPDATE '.$this->db->prefix('users').' SET '.$set_clause;
		if (isset($criteria) && is_subclass_of($criteria, 'icms_criteria_Element')) {$sql .= ' '.$criteria->renderWhere();}
		if (!$result = $this->db->query($sql)) {return false;}
		return true;
	}

	/**
	 *  Validates username, email address and password entries during registration
	 *  Username is validated for uniqueness and length, password is validated for length and strictness,
	 *  email is validated as a proper email address pattern
	 *
	 *  @param string $uname User display name entered by the user
	 *  @param string $login_name Username entered by the user
	 *  @param string $email Email address entered by the user
	 *  @param string $pass Password entered by the user
	 *  @param string $vpass Password verification entered by the user
	 *  @return string of errors encountered while validating the user information, will be blank if successful
	 */
	function userCheck($login_name, $uname, $email, $pass, $vpass)
	{
		global $icmsConfigUser, $xoopsDB;
		$myts =& icms_core_Textsanitizer::getInstance();
		include_once ICMS_ROOT_PATH . '/kernel/icmsstopspammer.php';
		$stop = '';
		if (!checkEmail($email)) {
			$stop .= _US_INVALIDMAIL.'<br />';
		}
		foreach ($icmsConfigUser['bad_emails'] as $be) {
			if (!empty($be) && preg_match('/'.$be.'/i', $email)) {
				$stop .= _US_INVALIDMAIL.'<br />';
				break;
			}
		}
		if (strrpos($email,' ') > 0) {
			$stop .= _US_EMAILNOSPACES.'<br />';
		}
		$login_name = xoops_trim($login_name);
		switch ($icmsConfigUser['uname_test_level']) {
			case 0:
				// strict
				$restriction = '/[^a-zA-Z0-9\_\-]/';
				break;
			case 1:
				// medium
				$restriction = '/[^a-zA-Z0-9\_\-\<\>\,\.\$\%\#\@\!\\\'\"]/';
				break;
			case 2:
				// loose
				$restriction = '/[\000-\040]/';
				break;
		}
		$icmsStopSpammers = new icms_core_StopSpammer();
		if ($icmsStopSpammers->badUsername($uname)) {
			$stop .= _US_INVALIDNICKNAME . '<br />';
		}
		if ($icmsStopSpammers->badEmail($email)) {
			$stop .= _US_INVALIDMAIL . '<br />';
		}
		if ($icmsStopSpammers->badIP($_SERVER['REMOTE_ADDR'])) {
			$stop .= _US_INVALIDIP . '<br />';
		}
		if (empty($login_name) || preg_match($restriction, $login_name)) {
			$stop .= _US_INVALIDNICKNAME.'<br />';
		}
		if (strlen($login_name) > $icmsConfigUser['maxuname']) {
			$stop .= sprintf(_US_NICKNAMETOOLONG, $icmsConfigUser['maxuname']).'<br />';
		}
		if (strlen($login_name) < $icmsConfigUser['minuname']) {
			$stop .= sprintf(_US_NICKNAMETOOSHORT, $icmsConfigUser['minuname']).'<br />';
		}
		foreach ($icmsConfigUser['bad_unames'] as $bu) {
			if (!empty($bu) && preg_match('/'.$bu.'/i', $login_name)) {
				$stop .= _US_NAMERESERVED.'<br />';
				break;
			}
		}
		if (strrpos($login_name, ' ') > 0) {
			$stop .= _US_NICKNAMENOSPACES.'<br />';
		}
		$sql = sprintf('SELECT COUNT(*) FROM %s WHERE login_name = %s', $xoopsDB->prefix('users'), $xoopsDB->quoteString(addslashes($login_name)));
		$result = $xoopsDB->query($sql);
		list($count) = $xoopsDB->fetchRow($result);
		if ($count > 0) {
			$stop .= _US_LOGINNAMETAKEN.'<br />';
		}
		$count = 0;
		if ($uname) {
			$sql = sprintf('SELECT COUNT(*) FROM %s WHERE uname = %s', $xoopsDB->prefix('users'), $xoopsDB->quoteString(addslashes($uname)));
			$result = $xoopsDB->query($sql);
			list($count) = $xoopsDB->fetchRow($result);
			if ($count > 0) {
				$stop .= _US_NICKNAMETAKEN.'<br />';
			}
		}
		$count = 0;
		if ($email) {
			$sql = sprintf('SELECT COUNT(*) FROM %s WHERE email = %s', $xoopsDB->prefix('users'), $xoopsDB->quoteString(addslashes($email)));
			$result = $xoopsDB->query($sql);
			list($count) = $xoopsDB->fetchRow($result);
			if ($count > 0) {
				$stop .= _US_EMAILTAKEN.'<br />';
			}
		}
		if (!isset($pass) || $pass == '' || !isset($vpass) || $vpass == '') {
			$stop .= _US_ENTERPWD.'<br />';
		}
		if ((isset($pass)) && ($pass != $vpass)) {
			$stop .= _US_PASSNOTSAME.'<br />';
		} elseif (($pass != '') && (strlen($pass) < $icmsConfigUser['minpass'])) {
			$stop .= sprintf(_US_PWDTOOSHORT,$icmsConfigUser['minpass']).'<br />';
		}
		if ((isset($pass)) && (isset($login_name)))
		{
			if ($pass == $login_name || $pass == icms_utf8_strrev($login_name, true) || strripos($pass, $login_name) === true)
			{
				$stop .= _US_BADPWD.'<br />';
			}
		}
		return $stop;
	}
}
?>