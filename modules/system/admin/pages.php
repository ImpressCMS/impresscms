<?php
/**
 * Admin ImpressCMS Pages (symlinks)
 *
 * List, add, edit and delete pages objects
 *
 * @copyright	The ImpressCMS Project <http://www.impresscms.org>
 * @license		GNU General Public License (GPL) <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @since		ImpressCMS 1.2
 * @package 	System
 * @subpackage	Symlinks
 * @author		Gustavo Pilla (aka nekro) <nekro@impresscms.org>
 */

/* set get and post filters before including admin_header, if not strings */
$filter_get = array('page_id' => 'int');

$filter_post = array('page_id' => 'int');

/* set default values for variables. $op and $fct are handled in the header */
$page_id = 0;

/** common header for the admin functions */
include 'admin_header.php';

/**
 * Edit a symlink
 *
 * @param int $post_id Postid to be edited
 */
function editpage($page_id = 0, $clone = false) {
	global $icms_admin_handler, $icmsAdminTpl;

	$pageObj = $icms_admin_handler->get($page_id);

	if (!$clone && !$pageObj->isNew()) {
		$sform = $pageObj->getForm(_AM_SYSTEM_PAGES_EDIT, 'addpage');
		$sform->assign($icmsAdminTpl);

	} else {
		$pageObj->setVar('page_type', 'C');
		$sform = $pageObj->getForm(_AM_SYSTEM_PAGES_CREATE, 'addpage');
		$sform->assign($icmsAdminTpl);

	}
	$icmsAdminTpl->assign('page_id', $page_id);
	$icmsAdminTpl->assign('icms_page_title', _AM_SYSTEM_PAGES_TITLE);
	$icmsAdminTpl->display('db:admin/pages/system_adm_pagemanager_index.html');
}

/* Use a naming convention that indicates the source of the content of the variable */
$clean_op = $op;
/* Create a whitelist of valid values, be sure to use appropriate types for each value
 * Be sure to include a value for no parameter, if you have a default condition
 */
$valid_op = array('mod', 'changedField', 'addpage', 'del', 'status', '');

/* Again, use a naming convention that indicates the source of the content of the variable */
$clean_page_id = $page_id;

/*
 * in_array() is a native PHP function that will determine if the value of the
 * first argument is found in the array listed in the second argument. Strings
 * are case sensitive and the 3rd argument determines whether type matching is
 * required
 */
if (in_array($clean_op, $valid_op, true)) {
	switch ($clean_op) {
		case 'status' :
			$icms_admin_handler->changeStatus($clean_page_id);
			if (isset($rtn)) {
				redirect_header(ICMS_URL . base64_decode($rtn));
			}

			$rtn = '/modules/system/admin.php?fct=pages';
			redirect_header(ICMS_URL . $rtn);
			break;

		case "clone" :
			icms_cp_header();
			editpage($clean_page_id, true);
			break;

		case "mod" :
		case "changedField" :
			icms_cp_header();
			editpage($clean_page_id);
			break;

		case "addpage" :
			$controller = new icms_ipf_Controller($icms_admin_handler);
			$controller->storeFromDefaultForm(_AM_SYSTEM_PAGES_CREATED, _AM_SYSTEM_PAGES_MODIFIED);
			break;

		case "del" :
			$controller = new icms_ipf_Controller($icms_admin_handler);
			$controller->handleObjectDeletion();
			break;

		default :
			icms_cp_header();
			$objectTable = new icms_ipf_view_Table($icms_admin_handler);

			$objectTable->addColumn(new icms_ipf_view_Column('page_status', 'center', false, 'getCustomPageStatus'));
			$objectTable->addColumn(new icms_ipf_view_Column('page_title', _GLOBAL_LEFT, false, 'getAdminViewItemLink'));
			$objectTable->addColumn(new icms_ipf_view_Column('page_url'));
			$objectTable->addColumn(new icms_ipf_view_Column('page_moduleid', 'center', false, 'getCustomPageModuleid'));

			$objectTable->addIntroButton('addpost', 'admin.php?fct=pages&amp;op=mod', _AM_SYSTEM_PAGES_CREATE);
			$objectTable->addCustomAction('getViewItemLink');
			$objectTable->addQuickSearch(array('page_title', 'page_url'));
			$objectTable->addFilter('page_moduleid', 'getModulesArray');

			$icmsAdminTpl->assign('icms_page_table', $objectTable->fetch());
			$icmsAdminTpl->assign('icms_page_title', _AM_SYSTEM_PAGES_TITLE);
			$icmsAdminTpl->display('db:admin/pages/system_adm_pagemanager_index.html');
			break;
	}
	icms_cp_footer();
}
