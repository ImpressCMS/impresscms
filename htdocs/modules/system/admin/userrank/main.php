<?php
// $Id: main.php 12313 2013-09-15 21:14:35Z skenow $
// ------------------------------------------------------------------------ //
// XOOPS - PHP Content Management System //
// Copyright (c) 2000 XOOPS.org //
// <http://www.xoops.org/> //
// ------------------------------------------------------------------------ //
// This program is free software; you can redistribute it and/or modify //
// it under the terms of the GNU General Public License as published by //
// the Free Software Foundation; either version 2 of the License, or //
// (at your option) any later version. //
// //
// You may not change or alter any portion of this comment or credits //
// of supporting developers from this source code or any supporting //
// source code which is considered copyrighted (c) material of the //
// original comment or credit authors. //
// //
// This program is distributed in the hope that it will be useful, //
// but WITHOUT ANY WARRANTY; without even the implied warranty of //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the //
// GNU General Public License for more details. //
// //
// You should have received a copy of the GNU General Public License //
// along with this program; if not, write to the Free Software //
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA 02111-1307 USA //
// ------------------------------------------------------------------------ //
// Author: Kazumi Ono (AKA onokazu) //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project //
// ------------------------------------------------------------------------- //
/**
 * ImpressCMS User Ranks.
 *
 * @copyright The ImpressCMS Project http://www.impresscms.org/
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package System
 * @subpackage Users
 * @since 1.2
 * @author Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version SVN: $Id: main.php 12313 2013-09-15 21:14:35Z skenow $
 */
if (!is_object(icms::$user) || !is_object($icmsModule) || !icms::$user->isAdmin($icmsModule->getVar("mid"))) {
	exit("Access Denied");
}

/**
 * Logic and rendering for editing user ranks
 *
 * @param bool $showmenu Unnecessary? Not in any other location
 * @param int $rank_id Unique ID for the rank entry
 * @param bool $clone Are you cloning an existing rank?
 */
function edituserrank($showmenu = FALSE, $rank_id = 0, $clone = FALSE) {
	global $icms_userrank_handler, $icmsAdminTpl, $rank_id;

	icms_cp_header();
	$userrankObj = $icms_userrank_handler->get($rank_id);

	if (!$clone && !$userrankObj->isNew()) {
		$sform = $userrankObj->getForm(_CO_ICMS_USERRANKS_EDIT, "adduserrank");
		$sform->assign($icmsAdminTpl);
		$icmsAdminTpl->assign("icms_userrank_title", _CO_ICMS_USERRANKS_EDIT_INFO);
		$icmsAdminTpl->display('db:system_adm_userrank.html');
	} else {
		$userrankObj->setVar("rank_id", 0);
		$sform = $userrankObj->getForm(_CO_ICMS_USERRANKS_CREATE, "adduserrank");
		$sform->assign($icmsAdminTpl);
		$icmsAdminTpl->assign("icms_userrank_title", _CO_ICMS_USERRANKS_CREATE_INFO);
		$icmsAdminTpl->display('db:system_adm_userrank.html');
	}
}

$icms_userrank_handler = icms_getModuleHandler("userrank", "system");

/*
 * GET variables
 * (string) op
 * (int) rank_id
 *
 * POST variables
 * (int) rank_min
 * (int) rank_max
 * (int) rank_special
 * (url) url_rank_image
 * (int) delete_rank_image
 */

/* default values */
$op = '';

$filter_get = array('rank_id' => 'int');

$filter_post = array('rank_id' => 'int', 'rank_min' => 'int', 'rank_max' => 'int', 'rank_special' => 'int', 'url_rank_image' => 'url', 'delete_rank_image' => 'int');

/* filter the user input */
if (!empty($_GET)) {
	// in places where strict mode is not used for checkVarArray, make sure filter_ vars are not overwritten
	if (isset($_GET['filter_post'])) unset($_GET['filter_post']);
	$clean_GET = icms_core_DataFilter::checkVarArray($_GET, $filter_get, FALSE);
	extract($clean_GET);
}

if (!empty($_POST)) {
	$clean_POST = icms_core_DataFilter::checkVarArray($_POST, $filter_post, FALSE);
	extract($clean_POST);
}

switch ($op) {
	case "mod":
		edituserrank(TRUE, $rank_id);
		break;

	case "clone":
		edituserrank(TRUE, $rank_id, TRUE);
		break;

	case "adduserrank":
		$controller = new icms_ipf_Controller($icms_userrank_handler);
		$controller->storeFromDefaultForm(_CO_ICMS_USERRANKS_CREATED, _CO_ICMS_USERRANKS_MODIFIED);
		break;

	case "del":
		$controller = new icms_ipf_Controller($icms_userrank_handler);
		$controller->handleObjectDeletion();
		break;

	default:
		icms_cp_header();
		$objectTable = new icms_ipf_view_Table($icms_userrank_handler);
		$objectTable->addColumn(new icms_ipf_view_Column("rank_title", _GLOBAL_LEFT, FALSE, "getRankTitle"));
		$objectTable->addColumn(new icms_ipf_view_Column("rank_min"));
		$objectTable->addColumn(new icms_ipf_view_Column("rank_max"));
		$objectTable->addColumn(new icms_ipf_view_Column("rank_image", "center", 200, "getRankPicture", FALSE, FALSE, FALSE));
		$objectTable->addIntroButton("adduserrank", "admin.php?fct=userrank&amp;op=mod", _CO_ICMS_USERRANKS_CREATE);
		$objectTable->addQuickSearch(array("rank_title"));
		$objectTable->addCustomAction("getCloneLink");
		$icmsAdminTpl->assign("icms_userrank_table", $objectTable->fetch());
		$icmsAdminTpl->assign("icms_userrank_explain", TRUE);
		$icmsAdminTpl->assign("icms_userrank_title", _CO_ICMS_USERRANKS_DSC);
		$icmsAdminTpl->display('db:system_adm_userrank.html');
		break;
}

icms_cp_footer();
