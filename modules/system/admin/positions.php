<?php
/**
 * Admin ImpressCMS Block Positions
 *
 * List, add, edit and delete block positions
 *
 * @copyright	The ImpressCMS Project <http://www.impresscms.org>
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.2
 * @package 	Administration
 * @subpackage	Block Positions
 * @author		Gustavo Pilla (aka nekro) <nekro@impresscms.org>
 * @author		Rodrigo Pereira Lima (AKA TheRplima) <therplima@impresscms.org>
 */

/* set get and post filters before including admin_header, if not strings */
$filter_get = array('id' => 'int');

$filter_post = array('id' => 'int');

/* set default values for variables, $op and $fct are handled in the header */
$id = 0;

/** common header for the admin functions */
include "admin_header.php";

/**
 * Edit a block position
 * @param $id
 */
function editblockposition($id = 0) {
	global $icms_admin_handler, $icmsAdminTpl;

	$blockObj = $icms_admin_handler->get($id);

	if (!$blockObj->isNew()) {
		$sform = $blockObj->getForm(_AM_SYSTEM_POSITIONS_EDIT, 'addblockposition');
		$sform->assign($icmsAdminTpl);
	} else {
		$sform = $blockObj->getForm(_AM_SYSTEM_POSITIONS_CREATE, 'addblockposition');
		$sform->assign($icmsAdminTpl);
	}
	$icmsAdminTpl->assign('id', $id);
	$icmsAdminTpl->assign('lang_badmin', _AM_SYSTEM_POSITIONS_TITLE);
	$icmsAdminTpl->display('db:admin/positions/system_adm_positions.html');
}

$clean_op = $op;
$clean_id = $id;

$valid_op = array ('mod', 'changedField', 'addblockposition', 'del', '');

if (in_array($clean_op, $valid_op, TRUE)) {

	switch ($clean_op) {
		case "mod":
		case "changedField":
			icms_cp_header();
			editblockposition($clean_id);
			break;

		case "addblockposition":
			$controller = new icms_ipf_Controller($icms_admin_handler);
			$controller->storeFromDefaultForm(_AM_SYSTEM_POSITIONS_CREATED, _AM_SYSTEM_POSITIONS_MODIFIED);
			break;

		case "del":
			$controller = new icms_ipf_Controller($icms_admin_handler);
			$controller->handleObjectDeletion();
			break;

		default:
			icms_cp_header();
			$objectTable = new icms_ipf_view_Table($icms_admin_handler, FALSE);

			$objectTable->addColumn(new icms_ipf_view_Column('pname'), 'center');
			$objectTable->addColumn(new icms_ipf_view_Column('title', FALSE, FALSE, 'getCustomTitle', FALSE, FALSE, FALSE));
			$objectTable->addColumn(new icms_ipf_view_Column('description'));

			$objectTable->addIntroButton('addblockposition', 'admin.php?fct=positions&amp;op=mod', _AM_SYSTEM_POSITIONS_CREATE);
			$objectTable->addQuickSearch(array('pname', 'title', 'description'));

			$icmsAdminTpl->assign('icms_blockposition_table', $objectTable->fetch());
			$icmsAdminTpl->assign('lang_badmin', _AM_SYSTEM_POSITIONS_TITLE);
			$icmsAdminTpl->assign('icms_blockposition_info', _AM_SYSTEM_POSITIONS_INFO);

			$icmsAdminTpl->display('db:admin/positions/system_adm_positions.html');
			break;
	}
	icms_cp_footer();
}
