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
 * Management of members
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	LICENSE.txt
 * @author	modified by UnderDog <underdog@impresscms.org>
 */

namespace ImpressCMS\Core\Facades;

use ImpressCMS\Core\Database\Criteria\CriteriaCompo;
use ImpressCMS\Core\Database\Criteria\CriteriaElement;
use ImpressCMS\Core\Database\Criteria\CriteriaItem;
use ImpressCMS\Core\Database\Legacy\Updater\TableUpdater;
use ImpressCMS\Core\DataFilter;
use ImpressCMS\Core\Models\Group;
use ImpressCMS\Core\Models\GroupHandler;
use ImpressCMS\Core\Models\GroupMembershipHandler;
use ImpressCMS\Core\Models\User;
use ImpressCMS\Core\Models\UserHandler;
use ImpressCMS\Core\Security\Password;

/**
 * Member handler class.
 * This class provides simple interface (a facade class) for handling groups/users/
 * membership data.
 *
 * @author      Kazumi Ono <onokazu@xoops.org>
 * @package	ICMS\Member
 */
class Member extends AbstractFacade {

	/**
	 * holds reference to user handler(DAO) class
	 */
	protected $_uHandler;
	/**#@-*/

	protected $db;
	/**#@+
	 * holds reference to group handler(DAO) class
	 * @access private
	 */
	private $_gHandler;
	/**
	 * holds reference to membership handler(DAO) class
	 */
	private $_mHandler;
	/**
	 * holds temporary user objects
	 */
	private $_members = [];

	/**
	 * constructor
	 *
	 */
	public function __construct(&$db) {
		$this->_gHandler = new GroupHandler($db);
		$this->_uHandler = new UserHandler($db);
		$this->_mHandler = new GroupMembershipHandler($db);
		$this->db = &$db;
	}

	/**
	 * create a new group
	 *
	 * @return Group
	 */
	public function &createGroup(&$isNew = true) {
		return $this->_gHandler->create();
	}

	/**
	 * create a new user
	 *
	 * @return User
	 */
	public function &createUser(&$isNew = true) {
		return $this->_uHandler->create();
	}

	/**
	 * delete a group
	 *
	 * @param Group $group reference to the group to delete
	 * @return bool
	 */
	public function deleteGroup(&$group) {
		$this->_gHandler->delete($group);
		$this->_mHandler->deleteAll(new CriteriaItem('groupid', $group->getVar('groupid')));
		return true;
	}

	/**
	 * delete a user
	 *
	 * @param User $user reference to the user to delete
	 *
	 * @return bool
	 */
	public function deleteUser(&$user) {
		$this->_uHandler->delete($user);
		$this->_mHandler->deleteAll(new CriteriaItem('uid', $user->getVar('uid')));
		return true;
	}

	/**
	 * insert a group into the database
	 *
	 * @param Group $group reference to the group to insert
	 * @return bool
	 */
	public function insertGroup(&$group) {
		return $this->_gHandler->insert($group);
	}

	/**
	 * retrieve groups from the database
	 *
	 * @param CriteriaElement $criteria Criteria
	 * @param bool $id_as_key use the group's ID as key for the array?
	 *
	 * @return Group[]
	 */
	public function &getGroups($criteria = null, $id_as_key = false) {
		return $this->_gHandler->getObjects($criteria, $id_as_key);
	}

	/**
	 * retrieve users from the database
	 *
	 * @param CriteriaElement $criteria Criteria
	 * @param bool $id_as_key use the group's ID as key for the array?
	 *
	 * @return User[]
	 */
	public function getUsers($criteria = null, $id_as_key = false) {
		return $this->_uHandler->getObjects($criteria, $id_as_key);
	}

	/**
	 * get a list of groupnames and their IDs
	 *
	 * @param CriteriaElement $criteria Criteria object
	 *
	 * @return array associative array of group-IDs and names
	 */
	public function getGroupList($criteria = null) {
		$groups = $this->_gHandler->getObjects($criteria, true);
		$ret = array();
		foreach (array_keys($groups) as $i) {
			$ret[$i] = $groups[$i]->getVar('name');
		}
		return $ret;
	}

	/**
	 * get a list of usernames and their IDs
	 *
	 * @deprecated	This isn't really a membership method, but for the user handler
	 *
	 * @param CriteriaElement $criteria Criteria object
	 * @return array associative array of user-IDs and names
	 */
	public function getUserList($criteria = null) {
		trigger_error('This isn\'t really a membership method, but for the user handler', E_USER_DEPRECATED);

		$users = $this->_uHandler->getObjects($criteria, true);
		$ret = array();
		foreach (array_keys($users) as $i) {
			$ret[$i] = $users[$i]->getVar('uname');
		}
		return $ret;
	}

	/**
	 * add a user to a group
	 *
	 * @param int $group_id ID of the group
	 * @param int $user_id ID of the user
	 *
	 * @return bool
	 */
	public function addUserToGroup($group_id, $user_id) {
		$mship = & $this->_mHandler->create();
		$mship->setVar('groupid', $group_id);
		$mship->setVar('uid', $user_id);
		return $this->_mHandler->insert($mship);
	}

	/**
	 * remove a list of users from a group
	 *
	 * @param int $group_id ID of the group
	 * @param array $user_ids array of user-IDs
	 * @return bool success?
	 */
	public function removeUsersFromGroup($group_id, $user_ids = array()) {
		$criteria = new CriteriaCompo();
		$criteria->add(new CriteriaItem('groupid', $group_id));
		$criteria2 = new CriteriaCompo();
		foreach ($user_ids as $uid) {
			$criteria2->add(new CriteriaItem('uid', $uid), 'OR');
		}
		$criteria->add($criteria2);
		return $this->_mHandler->deleteAll($criteria);
	}

	/**
	 * get a list of users belonging to a group
	 *
	 * @param int $group_id ID of the group
	 * @param bool $asobject return the users as objects?
	 * @param int $limit number of users to return
	 * @param int $start index of the first user to return
	 * @return array|User[]
	 * or of associative arrays matching the record structure in the database.
	 */
	public function &getUsersByGroup($group_id, $asobject = false, $limit = 0, $start = 0) {
		$user_ids = $this->_mHandler->getUsersByGroup($group_id, $limit, $start);
		if (!$asobject) {
			return $user_ids;
		} else {
			$ret = array();
			foreach ($user_ids as $u_id) {
				$user = & $this->getUser($u_id);
				if (is_object($user)) {
					$ret[] = & $user;
				}
				unset($user);
			}
			return $ret;
		}
	}

	/**
	 * retrieve a user
	 *
	 * @param int $id ID for the user
	 * @return User|null
	 */
	public function &getUser($id)
	{
		if (!isset($this->_members[$id])) {
			$this->_members[$id] = &$this->_uHandler->get($id);
		}
		return $this->_members[$id];
	}

	/**
	 * log in a user
	 * @param string $uname username as entered in the login form
	 * @param string $pwd password entered in the login form
	 * @return User|false
	 */
	public function loginUser($uname, $pwd) {

		$icmspass = new Password();

		if (strpos($uname, '@') !== false) {
			$uname = self::icms_getLoginFromUserEmail($uname);
		}

/*		$is_expired = $icmspass->passExpired($uname);
		if ($is_expired == 1) {
			redirect_header(ICMS_URL . '/user.php?op=resetpass&uname=' . $uname, 5, _US_PASSEXPIRED, false);
		} */

        $pwd = $icmspass->verifyPass($pwd, $uname);

		$table = new TableUpdater('users');
		if ($table->fieldExists('loginname')) {
			$criteria = new CriteriaCompo(new CriteriaItem('loginname', $uname));
		} elseif ($table->fieldExists('login_name')) {
			$criteria = new CriteriaCompo(new CriteriaItem('login_name', $uname));
		} else {
			$criteria = new CriteriaCompo(new CriteriaItem('uname', $uname));
		}
		$criteria->add(new CriteriaItem('pass', $pwd));
		$user = $this->_uHandler->getObjects($criteria, false);
		if (!$user || count($user) != 1) {
			$user = false;
			return $user;
		}
		return $user[0];
	}

	public function icms_getLoginFromUserEmail($email = '')
	{
		$table = new TableUpdater('users');

		if ($email !== '') {
			if ($table->fieldExists('loginname')) {
				$sql = \icms::$xoopsDB->query('SELECT loginname, email FROM ' . \icms::$xoopsDB->prefix('users')
					. " WHERE email = '" . @htmlspecialchars($email, ENT_QUOTES, _CHARSET) . "'");
			} elseif ($table->fieldExists('login_name')) {
				$sql = \icms::$xoopsDB->query('SELECT login_name, email FROM ' . \icms::$xoopsDB->prefix('users')
					. " WHERE email = '" . @htmlspecialchars($email, ENT_QUOTES, _CHARSET) . "'");
			}
			list($uname, $email) = \icms::$xoopsDB->fetchRow($sql);
		} else {
			redirect_header('user.php', 2, _US_SORRYNOTFOUND);
		}
		return $uname;
	}

	/**
	 * count users matching certain conditions
	 *
	 * @param CriteriaElement $criteria Criteria object
	 * @return int
	 */
	public function getUserCount($criteria = null) {
		return $this->_uHandler->getCount($criteria);
	}

	/**
	 * count users belonging to a group
	 *
	 * @param int $group_id ID of the group
	 * @return int
	 */
	public function getUserCountByGroup($group_id) {
		return $this->_mHandler->getCount(new CriteriaItem('groupid', $group_id));
	}

	/**
	 * updates a single field in a users record
	 *
	 * @param User $user reference
	 * @param string $fieldName name of the field to update
	 * @param string $fieldValue updated value for the field
	 * @return bool TRUE if success or unchanged, FALSE on failure
	 */
	public function updateUserByField(&$user, $fieldName, $fieldValue) {
		$user->setVar($fieldName, $fieldValue);
		return $this->insertUser($user);
	}

	/**
	 * insert a user into the database
	 *
	 * @param \ImpressCMS\Core\Member\UserModel $user User
	 * @return bool TRUE if already in database and unchanged
	 * FALSE on failure
	 */
	public function insertUser(&$user, $force = false)
	{
		return $this->_uHandler->insert($user, $force);
	}

	/**
	 * updates a single field in a users record
	 *
	 * @param string $fieldName name of the field to update
	 * @param string $fieldValue updated value for the field
	 * @param CriteriaElement $criteria Criteria object
	 * @return bool TRUE if success or unchanged, FALSE on failure
	 */
	public function updateUsersByField($fieldName, $fieldValue, $criteria = null) {
		return $this->_uHandler->updateAll($fieldName, $fieldValue, $criteria);
	}

	/**
	 * activate a user
	 *
	 * @param User $user User
	 * @return bool successful?
	 */
	public function activateUser(&$user) {
		if ($user->getVar('level') != 0) {
			return true;
		}
		$user->setVar('level', 1);
		return $this->_uHandler->insert($user, true);
	}

	/**
	 * Get a list of users belonging to certain groups and matching criteria
	 * Temporary solution
	 *
	 * @param int $groups IDs of groups
	 * @param CriteriaElement $criteria Criteria object
	 * @param bool $asobject return the users as objects?
	 * @param bool $id_as_key use the UID as key for the array if $asobject is TRUE
	 *
	 * @return array|User[]
	 */
	public function getUsersByGroupLink($groups, $criteria = null, $asobject = false, $id_as_key = false) {
		$ret = array();

		$select = $asobject? 'u.*' : 'u.uid';
		$sql[] = "	SELECT DISTINCT {$select} "
				. '	FROM ' . \icms::$xoopsDB->prefix('users') . ' AS u'
				. ' LEFT JOIN ' . \icms::$xoopsDB->prefix('groups_users_link') . ' AS m ON m.uid = u.uid'
				. "	WHERE 1 = '1'";
		if (!empty($groups)) {
			$sql[] = 'm.groupid IN (' . implode((array)", ", $groups) . ')';
		}
		$limit = $start = 0;
		if (isset($criteria) && is_subclass_of($criteria, CriteriaElement::class)) {
			$sql_criteria = $criteria->render();
			if ($criteria->getSort() != '') {
				$sql_criteria .= ' ORDER BY ' . $criteria->getSort() . ' ' . $criteria->getOrder();
			}
			$limit = $criteria->getLimit();
			$start = $criteria->getStart();
			if ($sql_criteria) {
				$sql[] = $sql_criteria;
			}
		}
		$sql_string = implode(" AND ", array_filter($sql));
		if (!$result = \icms::$xoopsDB->query($sql_string, $limit, $start)) {
			return $ret;
		}
		while ($myrow = \icms::$xoopsDB->fetchArray($result)) {
			if ($asobject) {
				$user = new User($this, $myrow);
				if (!$id_as_key) {
					$ret[] = & $user;
				} else {
					$ret[$myrow['uid']] = & $user;
				}
				unset($user);
			} else {
				$ret[] = $myrow['uid'];
			}
		}
		return $ret;
	}

	/**
	 * Get count of users belonging to certain groups and matching criteria
	 * Temporary solution
	 *
	 * @param array $groups IDs of groups
	 * @return int count of users
	 */
	public function getUserCountByGroupLink($groups, $criteria = null) {
		$ret = 0;

		$sql[] = '	SELECT COUNT(DISTINCT u.uid) '
				. '	FROM ' . \icms::$xoopsDB->prefix('users') . ' AS u'
				. ' LEFT JOIN ' . \icms::$xoopsDB->prefix('groups_users_link') . ' AS m ON m.uid = u.uid'
				. "	WHERE 1 = '1'";
		if (!empty($groups)) {
			$sql[] = 'm.groupid IN (' . implode(', ', $groups) . ')';
		}
		if (isset($criteria) && is_subclass_of($criteria, CriteriaElement::class)) {
			$sql[] = $criteria->render();
		}
		$sql_string = implode(' AND ', array_filter($sql));
		if (!$result = \icms::$xoopsDB->query($sql_string)) {
			return $ret;
		}
		list($ret) = \icms::$xoopsDB->fetchRow($result);
		return $ret;
	}

	/**
	 * Gets the usergroup with the most rights for a specific userid
	 *
	 * @param int  $uid  the userid to get the usergroup for
	 *
	 * @return int  the best usergroup belonging to the userid
	 */
	public function getUserBestGroup($uid) {
		$ret = ICMS_GROUP_ANONYMOUS;
		$uid = (int) $uid;
		$gperms = array();
		if ($uid <= 0) {
			return $ret;
		}

		$groups = $this->getGroupsByUser($uid);
		if (in_array(ICMS_GROUP_ADMIN, $groups, true)) {
			$ret = ICMS_GROUP_ADMIN;
		} else {
			foreach ($groups as $group) {
				$sql = 'SELECT COUNT(gperm_id) as total FROM '
					. \icms::$xoopsDB->prefix('group_permission')
					. ' WHERE gperm_groupid=' . $group;
				if (!$result = \icms::$xoopsDB->query($sql)) {
					return $ret;
				}
				list($t) = \icms::$xoopsDB->fetchRow($result);
				$gperms[$group] = $t;
			}
			foreach ($gperms as $key => $val) {
				if ($val == max($gperms)) {
					$ret = $key;
					break;
				}
			}
		}

		return $ret;
	}

	/**
	 * get a list of groups that a user is member of
	 *
	 * @param int $user_id ID of the user
	 * @param bool $asobject return groups as Group objects or arrays?
	 *
	 * @return array|Group[]
	 */
	public function &getGroupsByUser($user_id, $asobject = false)
	{
		$group_ids = $this->_mHandler->getGroupsByUser($user_id);
		if (!$asobject) {
			return $group_ids;
		} else {
			foreach ($group_ids as $g_id) {
				$ret[] = &$this->getGroup($g_id);
			}
			return $ret;
		}
	}

	/**
	 * retrieve a group
	 *
	 * @param int $id ID for the group
	 *
	 * @return Group|null
	 */
	public function &getGroup($id)
	{
		return $this->_gHandler->get($id);
	}

	/**
	 * Generates an array of usernames
	 *
	 * @param string $email email of user
	 * @param string $name name of user
	 * @param int $count number of names to generate
	 * @return array $names
	 * @author xHelp Team
	 */
	public function genUserNames($email, $count = 20) {
		$name = substr($email, 0, strpos($email, '@')); //Take the email address without domain as username

		$names = array();
		$userid   = explode('@', $email);

		$basename = '';
		$hasbasename = false;
		$emailname = $userid[0];

		$names[] = $emailname;

		if (strlen($name) > 0) {
			$name = explode(' ', trim($name));
			if (count($name) > 1) {
				$basename = strtolower(substr($name[0], 0, 1) . $name[count($name) - 1]);
			} else {
				$basename = strtolower($name[0]);
			}
			$basename = DataFilter::icms_substr($basename, 0, 60, '');
			//Prevent Duplication of Email Username and Name
			if (!in_array($basename, $names)) {
				$names[] = $basename;
				$hasbasename = true;
			}
		}

		$i = count($names);
		$onbasename = 1;
		while ($i < $count) {
			$num = $this->genRandNumber();
			if ($onbasename < 0 && $hasbasename) {
				$names[] = DataFilter::icms_substr($basename, 0, 58, '') . $num;

			} else {
				$names[] = DataFilter::icms_substr($emailname, 0, 58, '') . $num;
			}
			$i = count($names);
			$onbasename = ~ $onbasename;
			$num = '';
		}

		return $names;

	}

	/**
	 * Creates a random number with a specified number of $digits
	 *
	 * @param int $digits number of digits
	 * @return return int random number
	 * @author xHelp Team
	 */
	public function genRandNumber($digits = 2) {
		$this->initRand();
		$tmp = array();

		for ($i = 0; $i < $digits; $i++) {
			$tmp[$i] = (rand() % 9);
		}
		return implode('', $tmp);
	}

	/**
	 * Gives the random number generator a seed to start from
	 *
	 * @return void
	 *
	 * @access public
	 */
	public function initRand() {
		static $randCalled = false;
		if (!$randCalled) {
			srand((double) microtime() * 1000000);
			$randCalled = true;
		}
	}
}
