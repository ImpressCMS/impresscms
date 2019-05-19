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
// URL: http://www.xoops.org/ http://jp.xoops.org/  http://www.myweb.ne.jp/  //
// Project: The XOOPS Project (http://www.xoops.org/)                        //
// ------------------------------------------------------------------------- //

/**
 * The commentreply include file
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	LICENSE.txt
 * @package	core
 * @since	XOOPS
 * @author	http://www.xoops.org The XOOPS Project
 * @author	modified by UnderDog <underdog@impresscms.org>
 */

if (!is_object($icmsModule)) {
	exit();
}

include_once ICMS_INCLUDE_PATH . '/comment_constants.php';
if (('system' != $icmsModule->getVar('dirname')
	&& XOOPS_COMMENT_APPROVENONE == $icmsModuleConfig['com_rule'])
	|| (!is_object(icms::$user) && !$icmsModuleConfig['com_anonpost'])
	|| !is_object($icmsModule)) {
	redirect_header(ICMS_URL . '/user.php', 1, _NOPERM);
}

icms_loadLanguageFile('core', 'comment');
$com_id = isset($_GET['com_id'])?(int) $_GET['com_id']:0;
$com_mode = isset($_GET['com_mode'])? htmlspecialchars(trim($_GET['com_mode']), ENT_QUOTES, _CHARSET):'';
if ($com_mode == '') {
	if (is_object(icms::$user)) {
		$com_mode = icms::$user->getVar('umode');
	} else {
		$com_mode = $icmsConfig['com_mode'];
	}
}
if (!isset($_GET['com_order'])) {
	if (is_object(icms::$user)) {
		$com_order = icms::$user->getVar('uorder');
	} else {
		$com_order = $icmsConfig['com_order'];
	}
} else {
	$com_order = (int) $_GET['com_order'];
}
$comment_handler = icms::handler('icms_data_comment');
$comment = & $comment_handler->get($com_id);
$r_name = icms_member_user_Object::getUnameFromId($comment->getVar('com_uid'));
$r_text = _CM_POSTER . ': <strong>' . $r_name . '</strong>&nbsp;&nbsp;' . _CM_POSTED . ': <strong>' . formatTimestamp($comment->getVar('com_created')) . '</strong><br /><br />' . $comment->getVar('com_text');
$com_title = $comment->getVar('com_title', 'E');
if (!preg_match("/^(Re|" . _CM_RE . "):/i", $com_title)) {
	$com_title = _CM_RE . ": " . icms_core_DataFilter::icms_substr($com_title, 0, 56);
}
$com_pid = $com_id;
$com_text = '';
$com_id = 0;
$dosmiley = 1;
$groups   = (is_object(icms::$user))? icms::$user->getGroups():ICMS_GROUP_ANONYMOUS;
$gperm_handler = icms::handler('icms_member_groupperm');
if ($icmsConfig ['editor_default'] != 'dhtmltextarea' && $gperm_handler->checkRight('use_wysiwygeditor', 1, $groups, 1, false)) {
	$dohtml = 1;
	$dobr = 0;
} else {
	$dohtml = 0;
	$dobr = 1;
}
$doxcode = 1;
$doimage = 1;
$com_icon = '';
$com_rootid = $comment->getVar('com_rootid');
$com_itemid = $comment->getVar('com_itemid');
include ICMS_ROOT_PATH . '/header.php';
//themecenterposts($comment->getVar('com_title'), $r_text);
echo '<table cellpadding="4" cellspacing="1" width="98%" class="outer"><tr><td class="head">' . $comment->getVar('com_title') . '</td></tr><tr><td><br />' . $r_text . '<br /></td></tr></table>';
include ICMS_INCLUDE_PATH . '/comment_form.php';
include ICMS_ROOT_PATH . '/footer.php';
