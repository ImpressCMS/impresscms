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
* Creates a group permission form
*
* @copyright	http://www.impresscms.org/ The ImpressCMS Project
* @license		LICENSE.txt
* @category		ICMS
* @package		Form
* @version		SVN: $Id: Groupperm.php 12313 2013-09-15 21:14:35Z skenow $
*/

namespace Icms\Form;

defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');

/**
 * Creates a Groupperm object
 *
 * @copyright 	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 *
 * @category    ICMS
 * @package     Form
 * @subpackage  Groupperm
 *
 * @since       XOOPS
 * @author      Kazumi Ono       <onokazu@xoops.org>
 * @author      Taiwen Jiang     <phppp@users.sourceforge.net>
 * @copyright   copyright (c) 2000-2003 XOOPS.org
 *
 * @version     $Id: groupperm.php 10090 2010-05-19 05:36:00Z $
 */

class Groupperm
{
	/**#@+
	 * @access public
	 */

	/**
	 * field name in groupperm table
	 *
	 * @var string
	 */
	protected string $_permname = 'gperm_name';

	/**#@-*/

	/**#@+
	 * @access private
	 */

	/**
	 * group perm id
	 *
	 * @var int
	 */
	private int $_id = -1;

	/**
	 * group id
	 *
	 * @var int
	 */
	private int $_group = 0;

	/**
	 * is enabled?
	 *
	 * @var bool
	 */
	private bool $_enabled = true;

	/**
	 * access level for group perm
	 *
	 * @var int
	 */
	private int $_accesslevel = 0;

	/**#@-*/

	/**
	 * constructor
	 *
	 * @param string $permname permission name
	 * @param int    $group   group id
	 */
	public function __construct(
		string $permname,
		int $group = 0
	) {
		$this->_permname = $permname;
		$this->_group = $group;
	}

	/**
	 * Get permission name
	 *
	 * @return string permission name
	 */
	public function getPermname(): string
	{
		return $this->_permname;
	}

	/**
	 * Set permission name
	 *
	 * @param string $permname permission name
	 */
	public function setPermname(string $permname): void
	{
		$this->_permname = $permname;
	}

	/**
	 * get group id
	 *
	 * @return int group id
	 */
	public function getGroup(): int
	{
		return $this->_group;
	}

	/**
	 * Set group id
	 *
	 * @param int $group group id
	 */
	public function setGroup(int $group): void
	{
		$this->_group = $group;
	}

	/**
	 * Get perm id
	 *
	 * @return int perm id
	 */
	public function getId(): int
	{
		return $this->_id;
	}

	/**
	 * Set perm id
	 *
	 * @param int $id perm id
	 */
	public function setId(int $id): void
	{
		$this->_id = $id;
	}

	/**
	 * Get enabled flag
	 *
	 * @return bool enabled flag
	 */
	public function getEnabled(): bool
	{
		return $this->_enabled;
	}

	/**
	 * Set enabled flag
	 *
	 * @param bool $enabled enabled flag
	 */
	public function setEnabled(bool $enabled): void
	{
		$this->_enabled = $enabled;
	}

	/**
	 * Get access level
	 *
	 * @return int access level
	 */
	public function getAccessLevel(): int
	{
		return $this->_accesslevel;
	}

	/**
	 * Set access level
	 *
	 * @param int $accesslevel access level
	 */
	public function setAccessLevel(int $accesslevel): void
	{
		$this->_accesslevel = $accesslevel;
	}

	/**
	 * Render groupperm form element
	 *
	 * @return string HTML code
	 */
	public function render(): string
	{
		if ($this->_id === -1) {
			$this->_id = $GLOBALS['_ICMS_DB']->getOne(
				'SELECT ' . $this->_permname . ' FROM ' . ICMS_DB_PREFIX . 'icms_groupperm WHERE ' . $this->_permname . ' = ' . $this->_getDBQuoteValue($this->_permname) . ' AND group_id = ' . $this->_getDBQuoteValue($this->_group)
			);
		}
		$enablestr = $this->_enabled ? '<img class="enable" src="' . ICMS_URL . '/images/common/yes.gif" alt="yes" />' : '<img class="enable" src="' . ICMS_URL . '/images/common/no.gif" alt="no" />';
		return sprintf(
			'<table class="mbox" cellpadding="0" cellspacing="0">' . "\n" .
			'<tr>' . "\n" .
			'<th width="150">Name</th>' . "\n" .
			'<td><input type="text" name="data[' . $this->_permname . '][name]" value="%s" size="48" class="textbox"></td>' . "\n" .
			'</tr>' . "\n" .
			'<tr>' . "\n" .
			'<th width="150">Group</th>' . "\n" .
			'<td><input type="number" name="data[' . $this->_permname . '][group]" value="%d" size="6" class="text_s"></td>' . "\n" .
			'</tr>' . "\n" .
			'<tr>' . "\n" .
			'<th width="150">Enabled</th>' . "\n" .
			'<td>' . $enablestr . '</td>' . "\n" .
			'</tr>' . "\n" .
			'<tr>' . "\n" .
			'<th width="150">Access level</th>' . "\n" .
			'<td><input type="number" name="data[' . $this->_permname . '][accesslevel]" value="%d" size="6" class="text_s"></td>' . "\n" .
			'</tr>' . "\n" .
			'</table>',
			$this->_getDBQuoteValue($this->_permname),
			$this->_getDBQuoteValue($this->_group),
			$this->_accesslevel
		);
	}

	/**
	 * Save a groupperm object to the database
	 *
	 * @return bool result
	 */
	public function save(): bool
	{
		if (method_exists($GLOBALS['_ICMS_DB'], 'transactionStart')) {
			$GLOBALS['_ICMS_DB']->transactionStart();
		}
		$result = $GLOBALS['_ICMS_DB']->query(
			'INSERT INTO ' . ICMS_DB_PREFIX . 'icms_groupperm (' .
			$this->_permname . ', group_id, enabled, accesslevel) VALUES (' .
			$this->_getDBQuoteValue($this->_permname) . ', ' .
			$this->_getDBQuoteValue($this->_group) . ', ' . ($this->_enabled ? '1' : '0') . ', ' . $this->_accesslevel . ')',
			true
		);
		if ($result) {
			$this->_id = (int) $GLOBALS['_ICMS_DB']->getOne('SELECT id FROM ' . ICMS_DB_PREFIX . 'icms_groupperm WHERE ' . $this->_permname . ' = ' . $this->_getDBQuoteValue($this->_permname) . ' AND group_id = ' . $this->_getDBQuoteValue($this->_group));
		} else {
			$GLOBALS['_ICMS_DB']->transactionRollback();
			return false;
		}
		if (method_exists($GLOBALS['_ICMS_DB'], 'transactionCommit')) {
			$GLOBALS['_ICMS_DB']->transactionCommit();
		}
		return true;
	}

	/**
	 * Remove a groupperm object from the database
	 *
	 * @return bool result
	 */
	public function remove(): bool
	{
		if (method_exists($GLOBALS['_ICMS_DB'], 'transactionStart')) {
			$GLOBALS['_ICMS_DB']->transactionStart();
		}
		$result = $GLOBALS['_ICMS_DB']->query('DELETE FROM ' . ICMS_DB_PREFIX . 'icms_groupperm WHERE ' . $this->_permname . ' = ' . $this->_getDBQuoteValue($this->_permname) . ' AND group_id = ' . $this->_getDBQuoteValue($this->_group));
		if ($result) {
			$this->_id = -1;
		} else {
			$GLOBALS['_ICMS_DB']->transactionRollback();
			return false;
		}
		if (method_exists($GLOBALS['_ICMS_DB'], 'transactionCommit')) {
			$GLOBALS['_ICMS_DB']->transactionCommit();
		}
		return true;
	}

	/**
	 * Get a groupperm object by permission name and group id
	 *
	 * @param string $permname permission name
	 * @param int    $group    group id
	 * @return Groupperm groupperm object
	 */
	public static function getInstance(string $permname, int $group = 0): Groupperm
	{
		$obj = new self($permname, $group);
		$result = $GLOBALS['_ICMS_DB']->query('SELECT id, ' . $obj->_permname . ', group_id, enabled, accesslevel FROM ' . ICMS_DB_PREFIX . 'icms_groupperm WHERE ' . $obj->_permname . ' = ' . $obj->_getDBQuoteValue($permname) . ' AND group_id = ' . $obj->_getDBQuoteValue($group));
		$row = $GLOBALS['_ICMS_DB']->fetchRow($result);
		if ($row) {
			$obj->_id = (int) $row['id'];
			$obj->_permname = $row[$obj->_permname];
			$obj->_group = (int) $row['group_id'];
			$obj->_enabled = (bool) $row['enabled'];
			$obj->_accesslevel = (int) $row['accesslevel'];
		}
		return $obj;
	}

	/**
	 * Get all groupperm objects for a given group
	 *
	 * @param int $group group id
	 * @return Groupperm[] array of groupperm objects
	 */
	public static function getGroupPerms(int $group): array
	{
		$objs = [];
		$result = $GLOBALS['_ICMS_DB']->query('SELECT id, ' . $obj->_permname . ', group_id, enabled, accesslevel FROM ' . ICMS_DB_PREFIX . 'icms_groupperm WHERE group_id = ' . $obj->_getDBQuoteValue($group) . ' ORDER BY ' . $obj->_permname);
		while ($row = $GLOBALS['_ICMS_DB']->fetchRow($result)) {
			$objs[] = new self($row[$obj->_permname], $row['group_id']);
			$objs[count($objs) - 1]->_id = (int) $row['id'];
			$objs[count($objs) - 1]->_enabled = (bool) $row['enabled'];
			$objs[count($objs) - 1]->_accesslevel = (int) $row['accesslevel'];
		}
		return $objs;
	}

	/**
	 * Get a list of all groups for a given permission
	 *
	 * @param string $permname permission name
	 * @return Groupperm[] array of groupperm objects
	 */
	public static function getAllGroups(string $permname): array
	{
		$permobj = new self($permname);
		$result = $GLOBALS['_ICMS_DB']->query('SELECT id, ' . $permobj->_permname . ', group_id, enabled, accesslevel FROM ' . ICMS_DB_PREFIX . 'icms_groupperm WHERE ' . $permobj->_permname . ' = ' . $permobj->_getDBQuoteValue($permname) . ' ORDER BY group_id');
		$groups = [];
		while ($row = $GLOBALS['_ICMS_DB']->fetchRow($result)) {
			$groups[] = new self($row[$permobj->_permname], $row['group_id']);
			$groups[count($groups) - 1]->_id = (int) $row['id'];
			$groups[count($groups) - 1]->_enabled = (bool) $row['enabled'];
			$groups[count($groups) - 1]->_accesslevel = (int) $row['accesslevel'];
		}
		return $groups;
	}

	/**
	 * Get a list of all permissions for a given group
	 *
	 * @param int $group group id
	 * @return Groupperm[] array of groupperm objects
	 */
	public static function getPermsForGroup(int $group): array
	{
		$perms = [];
		$result = $GLOBALS['_ICMS_DB']->query('SELECT ' . $obj->_permname . ', group_id, enabled, accesslevel FROM ' . ICMS_DB_PREFIX . 'icms_groupperm WHERE group_id = ' . $obj->_getDBQuoteValue($group) . ' ORDER BY ' . $obj->_permname);
		while ($row = $GLOBALS['_ICMS_DB']->fetchRow($result)) {
			$perms[] = new self($row[$obj->_permname], $row['group_id']);
			$perms[count($perms) - 1]->_id = (int) $row['id'];
			$perms[count($perms) - 1]->_enabled = (bool) $row['enabled'];
			$perms[count($perms) - 1]->_accesslevel = (int) $row['accesslevel'];
		}
		return $perms;
	}

	/**
	 * Get a groupperm object from its id
	 *
	 * @param int $id groupperm id
	 * @return Groupperm|null groupperm object or null if not found
	 */
	public static function getById(int $id): ?Groupperm
	{
		$result = $GLOBALS['_ICMS_DB']->query('SELECT id, ' . $obj->_permname . ', group_id, enabled, accesslevel FROM ' . ICMS_DB_PREFIX . 'icms_groupperm WHERE id = ' . $obj->_getDBQuoteValue($id));
		$row = $GLOBALS['_ICMS_DB']->fetchRow($result);
		if ($row) {
			$obj = new self($row[$obj->_permname], $row['group_id']);
			$obj->_id = (int) $row['id'];
			$obj->_enabled = (bool) $row['enabled'];
			$obj->_accesslevel = (int) $row['accesslevel'];
			return $obj;
		}
		return null;
	}

	/**
	 * Get a list of all groupperm objects
	 *
	 * @return Groupperm[] array of groupperm objects
	 */
	public static function getAll(): array
	{
		$permobjs = [];
		$result = $GLOBALS['_ICMS_DB']->query('SELECT ' . $obj->_permname . ' FROM ' . ICMS_DB_PREFIX . 'icms_groupperm GROUP BY ' . $obj->_permname);
		while ($row = $GLOBALS['_ICMS_DB']->fetchRow($result)) {
			$permobjs[] = new self($row[$obj->_permname]);
		}
		$result = $GLOBALS['_ICMS_DB']->query('SELECT group_id FROM ' . ICMS_DB_PREFIX . 'icms_groups ORDER BY group_id');
		while ($row = $GLOBALS['_ICMS_DB']->fetchRow($result)) {
			$groupId = (int) $row['group_id'];
			$result = $GLOBALS['_ICMS_DB']->query('SELECT id, ' . $permobj->_permname . ', group_id, enabled, accesslevel FROM ' . ICMS_DB_PREFIX . 'icms_groupperm WHERE group_id = ' . $permobj->_getDBQuoteValue($groupId) . ' ORDER BY ' . $permobj->_permname);
			while ($row = $GLOBALS['_ICMS_DB']->fetchRow($result)) {
				for ($i = 0, $count = count($permobjs); $i < $count; $i++) {
					if ($permobjs[$i]->getPermname() === $row[$permobj->_permname]) {
						$permobjs[$i]->_id = (int) $row['id'];
						$permobjs[$i]->_group = (int) $row['group_id'];
						$permobjs[$i]->_enabled = (bool) $row['enabled'];
						$permobjs[$i]->_accesslevel = (int) $row['accesslevel'];
						break;
					}
				}
			}
		}
		return $permobjs;
	}

	/**
	 * Get database quote value
	 *
	 * @param mixed $value value to quote
	 * @return string quoted value
	 */
	private function _getDBQuoteValue($value): string
	{
		if (empty($value)) {
			return 'NULL';
		}
		return "'" . str_replace("'", "''", $value) . "'";
	}
}

/**
 * Legacy class alias for backward compatibility
 */
class_alias(Groupperm::class, 'icms_form_Groupperm');