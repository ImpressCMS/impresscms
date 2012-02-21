<?php
/**
 * Admin ImpressCMS Blocks
 *
 * List, add, edit and delete block objects
 *
 * @copyright	The ImpressCMS Project <http://www.impresscms.org>
 * @license		GNU General Public License (GPL) <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @since		ImpressCMS 1.2
 * @package		Administration
 * @subpackage	Blocks
 * @version		$Id$
 * @author		Gustavo Pilla (aka nekro) <nekro@impresscms.org>
 */

/* set filters before including admin_header */
$bid = 0;
$op = "";

$filter_get = array(
	'bid' => 'int',
	'startbid' => 'int',
	'limitsel' => 'int',
	);

include "admin_header.php";

$clean_bid = $bid;
$clean_op = $op;

/**
 * Edit a block
 *
 * @param int $bid ID of block to be edited
 * @param bool $clone Set to 'TRUE' if the block is being cloned
 */
function editblock($bid = 0, $clone = FALSE) {
	global $icms_admin_handler, $icmsAdminTpl;

	$blockObj = $icms_admin_handler->get($bid);

	if (isset($_POST['op']) && $_POST['op'] == 'changedField' && in_array($_POST['changedField'], array('c_type'))) {
		$controller = new icms_ipf_Controller($icms_admin_handler);
		$controller->postDataToObject($blockObj);
	}
	
	if ($blockObj->getVar("c_type") == "H") {
		$blockObj->setControl("content", array("name" => "source", "syntax" => "html"));
	} elseif ($blockObj->getVar("c_type") == "P") {
		$blockObj->setControl("content", array("name" => "source", "syntax" => "php"));
	} else {
		$blockObj->setControl("content", "dhtmltextarea");
	}

	if (!$blockObj->isNew() && $blockObj->getVar('edit_func') != '') $blockObj->showFieldOnForm('options');
	if (!$clone && !$blockObj->isNew()) {
		$sform = $blockObj->getForm(_AM_SYSTEM_BLOCKSADMIN_EDIT, 'addblock');
		$sform->assign($icmsAdminTpl);
	} else {
		if ($clone) {
			if ($blockObj->getVar('block_type') != 'C') {
				$blockObj->setVar('block_type', 'K');
				$blockObj->hideFieldFromForm('content');
				$blockObj->hideFieldFromForm('c_type');
			}
			$blockObj->setVar('bid', '0');
			$blockObj->setNew();
		} else {
			$blockObj->setVar('block_type', 'C');
		}
		$sform = $blockObj->getForm(_AM_SYSTEM_BLOCKSADMIN_CREATE, 'addblock');
		$sform->assign($icmsAdminTpl);
	}

	$icmsAdminTpl->assign('bid', $bid);
	$icmsAdminTpl->display('db:admin/blocksadmin/system_adm_blocksadmin.html');
}


/* Create a whitelist of valid values, be sure to use appropriate types for each value
 * Be sure to include a value for no parameter, if you have a default condition
 */
$valid_op = array(
	'mod',
	'changedField',
	'addblock',
	'del',
	'clone',
	'up',
	'down',
	'visible',
	'change_blocks',
	''
);

/*
 * in_array() is a native PHP function that will determine if the value of the
 * first argument is found in the array listed in the second argument. Strings
 * are case sensitive and the 3rd argument determines whether type matching is
 * required
 */
if (in_array($clean_op, $valid_op, TRUE)) {
	switch($clean_op) {
		case 'visible' :
			$icms_admin_handler->changeVisible($bid);
			if (isset($rtn)) redirect_header(ICMS_URL . base64_decode($rtn));
						
			$return = '/modules/system/admin.php?fct=blocksadmin';
			if (isset($sortsel)) {
				$return .= '&amp;sortsel=' . $sortsel . '&amp;ordersel=' . $ordersel . '&amp;limitsel=' . $limitsel . '&amp;startbid=' . $startbid;
			}

			redirect_header(ICMS_URL . $return);
			break;

		case "up" :
			$icms_admin_handler->upWeight($bid);
			if (isset($rtn)) redirect_header(ICMS_URL . base64_decode($rtn));			
			
			$return = '/modules/system/admin.php?fct=blocksadmin';
			if (isset($sortsel)) {
				$return .= '&amp;sortsel=' . $sortsel . '&amp;ordersel=' . $ordersel . '&amp;limitsel=' . $limitsel . '&amp;startbid=' . $startbid;
			}

			redirect_header(ICMS_URL . $return);
			break;

		case "down" :
			$icms_admin_handler->downWeight($bid);
			if (isset($rtn)) redirect_header(ICMS_URL . base64_decode($rtn));			
			
			$return = '/modules/system/admin.php?fct=blocksadmin';
			if (isset($sortsel)) {
				$return .= '&amp;sortsel=' . $sortsel . '&amp;ordersel=' . $ordersel . '&amp;limitsel=' . $limitsel . '&amp;startbid=' . $startbid;
			}

			redirect_header(ICMS_URL . $return);
			break;
			
		case "clone" :
			icms_cp_header();
			editblock($clean_bid, TRUE);
			break;

		case "mod" :
		case "changedField" :
			icms_cp_header();
			editblock($clean_bid);
			break;

		case "addblock" :
			$controller = new icms_ipf_Controller($icms_admin_handler);
			$controller->storeFromDefaultForm(_AM_SYSTEM_BLOCKSADMIN_CREATED, _AM_SYSTEM_BLOCKSADMIN_MODIFIED);
			break;

		case "del" :
			$controller = new icms_ipf_Controller($icms_admin_handler);
			$controller->handleObjectDeletion();

			break;

		case "change_blocks" :
			foreach ($_POST['SystemBlocksadmin_objects'] as $k => $v) {
				$changed = FALSE;
				$obj = $icms_admin_handler->get($v);

				if ($obj->getVar('side', 'e') != $_POST['block_side'][$k]) {
					$obj->setVar('side', (int) $_POST['block_side'][$k]);
					$changed = TRUE;
				}
				if ($obj->getVar('weight', 'e') != $_POST['block_weight'][$k]) {
					$obj->setVar('weight', (int) $_POST['block_weight'][$k]);
					$changed = TRUE;
				}
				if ($changed) {
					$icms_admin_handler->insert($obj);
				}
			}

			if (isset($rtn)) redirect_header(ICMS_URL . base64_decode($rtn), 2, _AM_SYSTEM_BLOCKSADMIN_MODIFIED);

			$return = '/modules/system/admin.php?fct=blocksadmin';
			if (isset($sortsel)) {
				$return .= '&amp;sortsel=' . $sortsel . '&amp;ordersel=' . $ordersel . '&amp;limitsel=' . $limitsel . '&amp;startbid=' . $startbid;
			}
			redirect_header(ICMS_URL . $return, 2, _AM_SYSTEM_BLOCKSADMIN_MODIFIED);
			break;

		default :
			icms_cp_header();
			$objectTable = new icms_ipf_view_Table($icms_admin_handler);
			$objectTable->addColumn(new icms_ipf_view_Column('visible', 'center'));
			$objectTable->addColumn(new icms_ipf_view_Column('name'));
			$objectTable->addColumn(new icms_ipf_view_Column('title', _GLOBAL_LEFT, FALSE, 'getAdminViewItemLink'));
			$objectTable->addColumn(new icms_ipf_view_Column('mid'));
			$objectTable->addColumn(new icms_ipf_view_Column('side', 'center', FALSE, 'getSideControl'));
			$objectTable->addColumn(new icms_ipf_view_Column('weight', 'center', FALSE, 'getWeightControl'));

			$objectTable->addIntroButton('addpost', 'admin.php?fct=blocksadmin&amp;op=mod', _AM_SYSTEM_BLOCKSADMIN_CREATE);
			$objectTable->addQuickSearch(array(
				'title',
				'name'
				));

			$objectTable->addFilter('mid', 'getModulesArray');
			$objectTable->addFilter('visible', 'getVisibleStatusArray');
			$objectTable->addFilter('side', 'getBlockPositionArray');

			$objectTable->addCustomAction('getBlankLink');
			$objectTable->addCustomAction('getUpActionLink');
			$objectTable->addCustomAction('getDownActionLink');
			$objectTable->addCustomAction('getCloneActionLink');

			$objectTable->addActionButton('change_blocks', FALSE, _SUBMIT);

			$icmsAdminTpl->assign('icms_block_table', $objectTable->fetch());

			$icmsAdminTpl->display('db:admin/blocksadmin/system_adm_blocksadmin.html');
			break;
	}
	icms_cp_footer();
}
/*
 * If you want to have a specific action taken because the user input was invalid,
 * place it at this point. Otherwise, a blank page will be displayed
 */
