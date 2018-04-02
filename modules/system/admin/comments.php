<?php
// $Id: xoops_version.php 12313 2013-09-15 21:14:35Z skenow $
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
 * Administration of comments, mainfile
 *
 * @copyright	http://www.XOOPS.org/
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		Administration
 * @subpackage	Comments
 */

/* set filter types, if not strings */
$filter_get = array(
	'status' => array('int', 'options' => array(1, 3)),
	'module' => 'int',
	'start' => 'int',
	'limit' => 'int',
	'com_id' => 'int',
);

$filter_post = array();

/* set default values for variables */
$status = $module = $start = $limit = $com_id = 0;
$sort = $order = "";

/** common header for the admin functions */
include "admin_header.php";
$comment_handler = $icms_admin_handler;

switch ($op) {
	default:
	case 'list':
		include_once ICMS_INCLUDE_PATH . '/comment_constants.php';
		icms_loadLanguageFile('core', 'comment');
		$limit_array = array(10, 20, 50, 100);
		$status_array = array(
			XOOPS_COMMENT_PENDING => _CM_PENDING,
			XOOPS_COMMENT_ACTIVE => _CM_ACTIVE,
			XOOPS_COMMENT_HIDDEN => _CM_HIDDEN,
		);
		$status_array2 = array(
			XOOPS_COMMENT_PENDING => '<span style="text-decoration: none; font-weight: bold; color: #00ff00;">' . _CM_PENDING . '</span>',
			XOOPS_COMMENT_ACTIVE => '<span style="text-decoration: none; font-weight: bold; color: #ff0000;">' . _CM_ACTIVE . '</span>',
			XOOPS_COMMENT_HIDDEN => '<span style="text-decoration: none; font-weight: bold; color: #0000ff;">' . _CM_HIDDEN . '</span>',
		);
		$otherorder = 'DESC';
		$comments = array();
		$module_handler = icms::handler('icms_module');
		$module_array = $module_handler->getList(new icms_db_criteria_Item('hascomments', 1));
		$criteria = new icms_db_criteria_Compo();
		if ($status > 0) {
			$criteria->add(new icms_db_criteria_Item('com_status', $status));
		}
		if ($module > 0) {
			$criteria->add(new icms_db_criteria_Item('com_modid', $module));
		}
		$total = $comment_handler->getCount($criteria);
		if ($total > 0) {
			if (!in_array($limit, $limit_array)) {
				$limit = 50;
			}
			$sort = (!in_array($sort, array('com_modid', 'com_status', 'com_created', 'com_uid', 'com_ip', 'com_title')))
				? 'com_id'
				: $sort;
			if ($order != 'ASC') {
				$order = 'DESC';
				$otherorder = 'ASC';
			}
			$criteria->setSort($sort);
			$criteria->setOrder($order);
			$criteria->setLimit($limit);
			$criteria->setStart($start);
			$comments =& $comment_handler->getObjects($criteria, TRUE);
		}
		icms_cp_header();
		$module_array[0] = _MD_AM_ALLMODS;
		$icmsAdminTpl->assign("modulesarray", $module_array);
		$status_array[0] = _MD_AM_ALLSTATUS;
		$icmsAdminTpl->assign("statusarray", $status_array);
		$icmsAdminTpl->assign("limitarray", $limit_array);
		foreach (array_keys($comments) as $i) {
			$poster_uname = $icmsConfig['anonymous'];
			if ($comments[$i]->getVar('com_uid') > 0) {
				$poster =& $member_handler->getUser($comments[$i]->getVar('com_uid'));
				if (is_object($poster)) {
					$poster_uname = '<a href="' . ICMS_URL . '/userinfo.php?uid=' . $comments[$i]->getVar('com_uid') . '">' . $poster->getVar('uname') . '</a>';
				}
			}
			$icon = $comments[$i]->getVar('com_icon');
			$icon = empty($icon )
				? '/images/icons/' . $GLOBALS["icmsConfig"]["language"] . '/no_posticon.gif'
				: ( '/images/subject/' . htmlspecialchars($icon, ENT_QUOTES ) );
			$icon = '<img src="' . ICMS_URL . $icon  . '" alt="" />';
			$comment[$i]['icon'] = $icon ;
			$comment[$i]['title'] = $comments[$i]->getVar('com_title');
			$comment[$i]['date'] = formatTimestamp($comments[$i]->getVar('com_created'), 'm');
			$comment[$i]['poster'] = $poster_uname;
			$comment[$i]['ip'] = icms_conv_nr2local($comments[$i]->getVar('com_ip'));
			$comment[$i]['modid'] = $module_array[$comments[$i]->getVar('com_modid')];
			$comment[$i]['status'] = $status_array2[$comments[$i]->getVar('com_status')];
			$comment[$i]['id'] = $i;
			$icmsAdminTpl->assign("commentsarray", $comment);
		}
		$icmsAdminTpl->assign("total", sprintf(_MD_AM_COMFOUND, '<b>' . icms_conv_nr2local($total) . '</b>'));
		$icmsAdminTpl->assign("otherorder",$otherorder);
		$icmsAdminTpl->assign("start", $start);
		$icmsAdminTpl->assign("status", $status);
		$icmsAdminTpl->assign("module", $module );
		$icmsAdminTpl->assign("limit", $limit);
		$icmsAdminTpl->display("db:admin/comments/system_adm_comments.html");
		icms_cp_footer();
		break;

	case 'jump':
		if ($com_id > 0) {
			$comment =& $comment_handler->get($com_id);
			if (is_object($comment)) {
				$module_handler = icms::handler('icms_module');
				$module =& $module_handler->get($comment->getVar('com_modid'));
				$comment_config = $module->getInfo('comments');
				header('Location: ' . ICMS_MODULES_URL . '/' . $module->getVar('dirname') . '/' . $comment_config['pageName']
					. '?' . $comment_config['itemName'] . '=' . $comment->getVar('com_itemid') . '&com_id=' . $comment->getVar('com_id')
					. '&com_rootid=' . $comment->getVar('com_rootid') . '&com_mode=thread&' . str_replace('&amp;', '&', $comment->getVar('com_exparams')) . '#comment'
					. $comment->getVar('com_id'));
				exit();
			}
		}
		redirect_header('admin.php?fct=comments', 1);
		break;
}
