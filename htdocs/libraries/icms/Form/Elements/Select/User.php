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
declare(strict_types=1);

namespace Icms\Form\Elements\Select;

use icms_form_elements_Label as Label;
use icms_form_elements_Tray as Tray;
use Icms\Form\Elements\Select as SelectElement;

/**
 * user select with page navigation
 *
 * limit: Only works with javascript enabled
 *
 * @copyright	ImpressCMS Project
 * @license		GNU General Public License (GPL)
 * @category	ICMS
 * @package		Form
 * @subpackage	Elements
 * @author		Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @author		Kazumi Ono <onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
class User extends Tray
{
	/**
	 * Constructor
	 *
	 * @param string    $caption     Form field caption
	 * @param string    $name        Field name
	 * @param bool      $includeAnon Include anonymous user?
	 * @param mixed     $value       Pre-selected value (or array of them).
	 *                                For large member lists, only temporary users are stored in $value
	 * @param int       $size        Number of rows. "1" makes a drop-down-list.
	 * @param bool      $multiple    Allow multiple selections?
	 * @param bool      $showRemoved Include removed users?
	 * @param bool      $justRemoved Only removed users?
	 */
	public function __construct(
		string $caption,
		string $name,
		bool $includeAnon = false,
		$value = null,
		int $size = 1,
		bool $multiple = false,
		bool $showRemoved = false,
		bool $justRemoved = false
	)
	{
		$limit = 200;
		$selectElement = new SelectElement('', $name, $value, $size, $multiple);

		if ($includeAnon) {
			$config = \Xoops\Core\Registry::getInstance()->getConfig();
			$anonymous = $config->get('anonymous');
			$selectElement->addOption('0', $anonymous ?? 'Anonymous');
		}

		/** @var \Icms\Member\MemberHandler $memberHandler */
		$memberHandler = \icms::handler('icms_member');
		$userCount = $memberHandler->getUserCount();

		// Normalize value to an array of user IDs
		$value = is_array($value)
			? $value
			: ($value === null ? [] : [$value]);
		$value = array_values(array_map('intval', $value));

		// Build criteria for user list
		$criteria = new \Icms\Db\Criteria_Compo();
		if ($userCount > $limit && count($value) > 0) {
			$criteria->add(
				new \Icms\Db\Criteria_Item('uid', '(' . implode(',', $value) . ')', 'IN')
			);
		} else {
			$criteria->setLimit($limit);
		}
		$criteria->setSort('uname');

		// Exclude removed users unless requested
		if (!$showRemoved) {
			$criteria->add(new \Icms\Db\Criteria_Item('level', '-1', '!='));
		} elseif ($showRemoved && $justRemoved) {
			$criteria->add(new \Icms\Db\Criteria_Item('level', '-1'));
		}
		$criteria->setOrder('ASC');

		$userList = $memberHandler->getUserList($criteria);
		$selectElement->addOptionArray($userList);

		// If user count <= limit, we're done
		if ($userCount <= $limit) {
			parent::__construct($caption, '', $name);
			$this->addElement($selectElement);
			return;
		}

		// Load language file
		\icms_loadLanguageFile('core', 'findusers');

		$jsAddUsers = $this->getAddUsersScript($name, $multiple);
		$actionTray = new Tray('', ' | ');
		$actionTray->addElement(
			new Label(
				'',
				"<a href='#' onclick='var sel = xoopsGetElementById(\"" .
				$name . ($multiple ? '[]' : '') . "\");" .
				"for (var i = sel.options.length-1; i >= 0; i--) {" .
				"if (!sel.options[i].selected) {sel.options[i] = null;}}" .
				"; return false;'>" .
				$this->gettext('_MA_USER_REMOVE') . "</a>"
			)
		);
		$actionTray->addElement(
			new Label(
				'',
				"<a href='#' onclick='openWithSelfMain(\"" .
				\Icms\Http\Uri::getBaseUrl() .
				"/include/findusers.php?target={$name}&multiple={$multiple}&token={$this->getSecurityToken()}\", " .
				"userselect, 800, 600, null); return false;' >" .
				$this->gettext('_MA_USER_MORE') . "</a> " .
				$jsAddUsers
			)
		);

		parent::__construct($caption, '<br /><br />', $name);
		$this->addElement($selectElement);
		$this->addElement($actionTray);
	}

	/**
	 * Generates the JavaScript for adding users
	 *
	 * @param string $name     Field name
	 * @param bool   $multiple Is multiple selection enabled?
	 * @return string JavaScript code
	 */
	private function getAddUsersScript(string $name, bool $multiple): string
	{
		return '<script type="text/javascript">
				function addusers(opts){
					var num = opts.substring(0, opts.indexOf(":"));
					opts = opts.substring(opts.indexOf(":")+1, opts.length);
					var sel = xoopsGetElementById("' .
						$name . ($multiple ? '[]' : '') . '");
					var arr = new Array(num);
					for (var n=0; n < num; n++) {
						var nm = opts.substring(0, opts.indexOf(":"));
						opts = opts.substring(opts.indexOf(":")+1, opts.length);
						var val = opts.substring(0, opts.indexOf(":"));
						opts = opts.substring(nm - val.length, opts.length);
						var txt = opts.substring(0, nm - val.length);
						opts = opts.substring(nm - val.length, opts.length);
						var added = false;
						for (var k = 0; k < sel.options.length; k++) {
							if (sel.options[k].value == val){
								added = true;
								break;
							}
						}
						if (added == false) {
							sel.options[k] = new Option(txt, val);
							sel.options[k].selected = true;
						}
					}
					return true;
				}
				</script>';
	}

	/**
	 * Gets the security token
	 *
	 * @return string Security token
	 */
	private function getSecurityToken(): string
	{
		return \Xoops\Core\Security::getToken();
	}
}
class_alias(User::class, 'icms_form_elements_select_User');