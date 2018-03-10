<?php
/**
 * ImpressCMS AUTOTASKS
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Administration
 * @subpackage	Autotasks
 * @since		1.2 alpha 2
 * @author		MekDrop <mekdrop@gmail.com>
 * @version		SVN: $Id$
 */

/* set get and post filters before including admin_header, if not strings */
$filter_post = array();

$filter_get = array();

/* set default values for variables. $op and $fct are handled in the header */

/** common header for the admin functions */
include "admin_header.php";

/**
 * Method for editing autotask entries
 * 
 * @param boolean	$showmenu		This parameter is not used - why is it here?
 * @param int		$autotasksid	The unique identifier for the autotask
 * @param boolean	$clone			Indicator if an autotask is being created from another
 */
function editautotasks($showmenu = FALSE, $autotasksid = 0, $clone = FALSE) {
	global $icms_admin_handler, $icmsAdminTpl;

	icms_cp_header();
	$autotasksObj = $icms_admin_handler->get($autotasksid);

	if (!$clone && !$autotasksObj->isNew()) {
		$sform = $autotasksObj->getForm(_CO_ICMS_AUTOTASKS_EDIT, 'addautotasks');
		$sform->assign($icmsAdminTpl);
		$icmsAdminTpl->display('db:admin/autotasks/system_adm_autotasks.html');
	} else {
		$autotasksObj->setVar('sat_id', 0);
		$sform = $autotasksObj->getForm(_CO_ICMS_AUTOTASKS_CREATE, 'addautotasks');
		$sform->assign($icmsAdminTpl);
		$icmsAdminTpl->display('db:admin/autotasks/system_adm_autotasks.html');
	}
}

switch ($op) {
	case "mod":

		$autotasksid = isset($sat_id) ? (int) ($sat_id) : 0;
		editautotasks(TRUE, $autotasksid);
		break;

	case "clone":
		$autotasksid = isset($sat_id) ? (int) ($sat_id) : 0;
		editautotasks(TRUE, $autotasksid, TRUE);
		break;

	case "addautotasks":
		$controller = new icms_ipf_Controller($icms_admin_handler);
		$controller->storeFromDefaultForm(_CO_ICMS_AUTOTASKS_CREATED, _CO_ICMS_AUTOTASKS_MODIFIED, ICMS_URL . '/modules/system/admin.php?fct=autotasks');
		break;

	case "del":
		$controller = new icms_ipf_Controller($icms_admin_handler);
		$controller->handleObjectDeletion();
		break;

	default:
		icms_cp_header();

		$objectTable = new icms_ipf_view_Table($icms_admin_handler, FALSE, array('edit'));
		$objectTable->addColumn(new icms_ipf_view_Column('sat_name', 'left', FALSE, 'getNameForDisplay'));
		$objectTable->addColumn(new icms_ipf_view_Column('sat_repeat', 'center', 80, 'getRepeatForDisplay'));
		$objectTable->addColumn(new icms_ipf_view_Column('sat_interval', 'center', 80, 'getIntervalForDisplay'));
		$objectTable->addColumn(new icms_ipf_view_Column('sat_enabled', 'center', 80, 'getEnableForDisplay'));
		$objectTable->addColumn(new icms_ipf_view_Column('sat_onfinish', 'center', 120, 'getOnFinishForDisplay'));
		$objectTable->addColumn(new icms_ipf_view_Column('sat_type', 'center', 120, 'getTypeForDisplay'));
		$objectTable->addColumn(new icms_ipf_view_Column('sat_lastruntime', 'center', 180, 'getLastRunTimeForDisplay'));

		$objectTable->addIntroButton('addautotasks', 'admin.php?fct=autotasks&amp;op=mod', _CO_ICMS_AUTOTASKS_CREATE);
		$objectTable->addQuickSearch(array('title', 'summary', 'description'));
		$objectTable->addCustomAction('getDeleteButtonForDisplay');

		$icmsAdminTpl->assign('icms_autotasks_table', $objectTable->fetch());
		$icmsAdminTpl->display('db:admin/autotasks/system_adm_autotasks.html');

		break;
}

icms_cp_footer();
