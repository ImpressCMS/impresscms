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
 * user select with page navigation
 *
 * limit: Only works with javascript enabled
 *
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @category	ICMS
 * @package		Form
 * @subpackage	Elements
 * @version		SVN: $Id: User.php 12313 2013-09-15 21:14:35Z skenow $
 */
defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * user select with page navigation
 *
 * @category	ICMS
 * @package		Form
 * @subpackage  Elements
 *
 * @author		Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @author		Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
class icms_form_elements_select_User extends icms_form_elements_Tray {

	/**
	 * Constructor
	 *
	 * @param	string	$caption
	 * @param	string	$name
	 * @param	mixed	$value			Pre-selected value (or array of them).
	 *									For an item with massive members, such as "Registered Users", "$value" should be used to store selected temporary users only instead of all members of that item
	 * @param	bool	$include_anon	Include user "anonymous"?
	 * @param	int		$size			Number or rows. "1" makes a drop-down-list.
	 * @param	bool	$multiple	   Allow multiple selections?
	 */
	public function __construct($caption, $name, $include_anon = FALSE, $value = NULL, $size = 1, $multiple = FALSE, $showremovedusers = FALSE, $justremovedusers = FALSE) {
		$limit = 200;
		$select_element = new icms_form_elements_Select('', $name, $value, $size, $multiple);
		if ($include_anon) {
			$select_element->addOption(0, $GLOBALS['icmsConfig']['anonymous']);
		}
		$member_handler = icms::handler('icms_member');
		$user_count = $member_handler->getUserCount();
		$value = is_array($value)
			? $value
			: (empty ($value)
				? array ()
				: array ($value)
			);
		if ($user_count > $limit && count($value) > 0) {
			$criteria = new icms_db_criteria_Compo(new icms_db_criteria_Item("uid", "(" . implode(",", $value) . ")", "IN"));
		} else {
			$criteria = new icms_db_criteria_Compo();
			$criteria->setLimit($limit);
		}
		$criteria->setSort('uname');
		if (!$showremovedusers) {
			$criteria->add(new icms_db_criteria_Item('level', '-1', '!='));
		} elseif ($showremovedusers && $justremovedusers) {
			$criteria->add(new icms_db_criteria_Item('level', '-1'));
		}
		$criteria->setOrder('ASC');
		$users = $member_handler->getUserList($criteria);
		$select_element->addOptionArray($users);
		if ($user_count <= $limit) {
			parent::__construct($caption, "", $name);
			$this->addElement($select_element);
			return;
		}

		icms_loadLanguageFile('core', 'findusers');

		$js_addusers = "<script type=\"text/javascript\">
					function addusers(opts){
						var num = opts.substring(0, opts.indexOf(\":\"));
						opts = opts.substring(opts.indexOf(\":\")+1, opts.length);
						var sel = xoopsGetElementById(\"" . $name . ($multiple ? "[]" : "") . "\");
						var arr = new Array(num);
						for (var n=0; n < num; n++) {
							var nm = opts.substring(0, opts.indexOf(\":\"));
							opts = opts.substring(opts.indexOf(\":\")+1, opts.length);
							var val = opts.substring(0, opts.indexOf(\":\"));
							opts = opts.substring(opts.indexOf(\":\")+1, opts.length);
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
					</script>";

		$token = icms::$security->createToken();
		$action_tray = new icms_form_elements_Tray("", " | ");
		$action_tray->addElement(new icms_form_elements_Label('',
			"<a href='#' onclick='var sel = xoopsGetElementById(\"" . $name
			. ($multiple ? "[]" : "") . "\");for (var i = sel.options.length-1; i >= 0; i--) {if (!sel.options[i].selected) {sel.options[i] = null;}}; return false;'>"
			. _MA_USER_REMOVE . "</a>"));
		$action_tray->addElement(new icms_form_elements_Label('',
			"<a href='#' onclick='openWithSelfMain(\"" . ICMS_URL
			. "/include/findusers.php?target={$name}&amp;multiple={$multiple}&amp;token={$token}\", \"userselect\", 800, 600, null); return false;' >"
			. _MA_USER_MORE . "</a>" . $js_addusers));

		parent::__construct($caption, '<br /><br />', $name);
		$this->addElement($select_element);
		$this->addElement($action_tray);
	}
}

