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
 * Manage avatars for users
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	LICENSE.txt
 * @author	Kazumi Ono (aka onokazo)
 */

namespace ImpressCMS\Core\Models;

use Exception;
use ImpressCMS\Core\Database\Criteria\CriteriaCompo;
use ImpressCMS\Core\Database\Criteria\CriteriaElement;
use ImpressCMS\Core\Database\Criteria\CriteriaItem;
use ImpressCMS\Core\File\Filesystem;
use PDO;

/**
 * Avatar handler class.
 * This class is responsible for providing data access mechanisms to the data source
 * of Avatar class objects.
 *
 * @author  	Kazumi Ono <onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2007 XOOPS.org
 *
 * @package	ICMS\Data\Avatar
 */
class AvatarHandler extends AbstractExtendedHandler {

	public function __construct(&$db) {
		parent::__construct($db, 'data_avatar', 'avatar_id', 'avatar_name', 'avatar_file', 'icms', 'avatar', 'avatar_id');
	}

	/**
	 * Deletes an avatar
	 * @see AbstractHandler#delete($object)
	 *
	 * @param Avatar   $avatar Avatar to delete
	 * @param bool                      $force  Force deletion?
	 *
	 * @return boolean
	 */
	public function delete(&$avatar, $force = false) {
		if (!parent::delete($avatar, $force)) {
			return false;
		}
		$sql = sprintf(
			"DELETE FROM %s WHERE avatar_id = '%u'",
			$this->db->prefix('avatar_user_link'), $avatar->avatar_id
		);
		$this->db->query($sql);
		return true;
	}

	/**
	 * @inheritDoc
	 */
	public function getObjects($criteria = null, $id_as_key = false, $as_object = true, $sql = false, $debug = false)
	{
		if ($sql) {
			throw new Exception('$sql must be not specified for this method call');
		}
		$ret = array();

		$sql = 'SELECT a.*, COUNT(u.user_id) AS count FROM '
			. $this->table . ' a LEFT JOIN '
			. $this->db->prefix('avatar_user_link') . ' u ON u.avatar_id=a.avatar_id';

		if (isset($criteria) && is_subclass_of($criteria, \Imponeer\Database\Criteria\CriteriaElement::class)) {
			$sql .= ' ' . $criteria->renderWhere();
			$sql .= ' GROUP BY a.avatar_id ORDER BY avatar_weight, avatar_id';
			$limit = $criteria->getLimit();
			$start = $criteria->getStart();

			if ((int)$limit) {
				$sql .= ' LIMIT :start, :limit';
			}

			$query = $this->db->prepare($sql);
			foreach ($criteria->getBindData() as $key => $value) {
				$query->bindValue($key, $value);
			}
			if ((int)$limit) {
				$query->bindValue('start', $start, PDO::PARAM_INT);
				$query->bindValue('limit', $limit, PDO::PARAM_INT);
			}
			if ($query->execute()) {
				$result = $query;
			}
		} else {
			$result = $this->db->query($sql);
		}
		if (!$result) {
			return $ret;
		}
		while ($myrow = $this->db->fetchArray($result)) {
			$avatar = new Avatar($this, $myrow);
			$avatar->setUserCount($myrow['count']);
			if (!$id_as_key) {
				$ret[] = & $avatar;
			} else {
				$ret[$myrow['avatar_id']] = & $avatar;
			}
			unset($avatar);
		}
		return $ret;
	}

	/**
	 * Links a user with an avatar
	 * @param integer $avatar_id
	 * @param integer $user_id
	 * @return boolean
	 */
	public function addUser($avatar_id, $user_id) {
		$avatar_id = (int) $avatar_id;
		$user_id = (int) ($user_id);
		if ($avatar_id < 1 || $user_id < 1) {
			return false;
		}
		$sql = sprintf(
			"DELETE FROM %s WHERE user_id = '%u'",
			$this->db->prefix('avatar_user_link'), $user_id
		);
		$this->db->query($sql);
		$sql = sprintf(
			"INSERT INTO %s (avatar_id, user_id) VALUES ('%u', '%u')",
			$this->db->prefix('avatar_user_link'), $avatar_id, $user_id
		);
		if (!$result = & $this->db->query($sql)) {
			return false;
		}
		return true;
	}

	/**
	 * Get an array of users linked to an avatar
	 * @param object $avatar
	 * @return array
	 */
	public function getUser(&$avatar) {
		$ret = array();

		/* As of PHP5.3.0, is_a() is no longer deprecated */
		if (!is_a($avatar, Avatar::class)) {
			return false;
		}

		$sql = "SELECT user_id FROM " . $this->db->prefix('avatar_user_link')
			. " WHERE avatar_id='" . (int) $avatar->avatar_id . "'";
		if (!$result = $this->db->query($sql)) {
			return $ret;
		}
		while ($myrow = $this->db->fetchArray($result)) {
			$ret[] = & $myrow['user_id'];
		}
		return $ret;
	}

	/**
	 * Get a list of avatars
	 * @param null $criteria
	 * @param int $limit
	 * @param int $start
	 * @param bool $debug
	 * @param string $avatar_type
	 * @param integer $avatar_display
	 * @return array
	 * @throws Exception
	 */
	public function getList($criteria = null, $limit = 0, $start = 0, $debug = false, $avatar_type = null, $avatar_display = null)
	{
		if (!($criteria instanceof \Imponeer\Database\Criteria\CriteriaCompo)) {
			$criteria = new CriteriaCompo();
		}
		if (isset($avatar_type)) {
			$avatar_type = ($avatar_type == 'C') ? 'C' : 'S';
			$criteria->add(new CriteriaItem('avatar_type', $avatar_type));
		}
		if (isset($avatar_display)) {
			$criteria->add(new CriteriaItem('avatar_display', (int)$avatar_display));
		}
		$avatars = $this->getObjects($criteria, true);
		$ret = array('blank.gif' => _NONE);
		foreach (array_keys($avatars) as $i) {
			$ret[$avatars[$i]->avatar_file] = $avatars[$i]->avatar_name;
		}
		return $ret;
	}

	/**
	 * Gets list of avatar file names in a certain directory
	 * if directory is not specified, default avatar directory will be searched
	 *
	 * @param   string	$avatar_dir name of the directory to scan for files,
	 * @return  array	 $avatars	list of avatars in the directory
	 */
	public static function getListFromDir($avatar_dir = '') {
		$avatars = [];
		if ($avatar_dir) {
			$avatars = Filesystem::getFileList(ICMS_ROOT_PATH . '/images/avatar/' . $avatar_dir . '/', $avatar_dir . '/', ['gif', 'jpg', 'png']);
		} else {
			$avatars = Filesystem::getFileList(ICMS_ROOT_PATH . '/images/avatar/', '', ['gif', 'jpg', 'png']);
		}
		return $avatars;
	}

	/**
	 * Gets list of all avatar image files inside default avatars directory
	 *
	 * @return  mixed	 $avatars|false  list of avatar files in the directory or false if no avatars
	 */
	public static function getAllFromDir() {
		$avatars = array();
		$dirlist = Filesystem::getDirList(ICMS_ROOT_PATH . '/images/avatar/');
		if (count($dirlist) > 0) {
			foreach ($dirlist as $dir) {
				$avatars[$dir] = & Filesystem::getFileList(ICMS_ROOT_PATH . '/images/avatar/' . $dir . '/', $dir . '/', ['gif', 'jpg', 'png']);
			}
		} else {
			return false;
		}
		return $avatars;
	}
}

