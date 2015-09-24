<?php
/**
 * ImpressCMS Analytics
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Administration
 * @subpackage	Analytics
 * @since		2.0
 * @author		David Janssens (david.j@impresscms.org)
 * @version		$Id$
 */
/* set get and post filters before including admin_header, if not strings */
$filter_post = array();

$filter_get = array();

/* set default values for variables. $op and $fct are handled in the header */

/** common header for the admin functions */
include "admin_header.php";

/**
 * Edit Analytics information
 *
 * @param $showmenu		This parameter is not used (why is it here?)
 * @param $analyticsid	Unique identifier of the analytics unit
 * @param $clone		Is this cloning an existing analytics unit?
 */
function editanalytics($showmenu = FALSE, $analyticsid = 0, $clone = FALSE) {
	global $icms_admin_handler, $icmsAdminTpl;

	icms_cp_header();
	$analyticsObj = $icms_admin_handler->get($analyticsid);

	if (!$clone && !$analyticsObj->isNew()) {
		$sform = $analyticsObj->getForm(_CO_ICMS_analyticsS_EDIT, 'addanalytics');
		$sform->assign($icmsAdminTpl);
		
		$icmsAdminTpl->assign('icms_analytics_title', _CO_ICMS_analyticsS_EDIT_INFO);
		$icmsAdminTpl->display('db:admin/analytics/system_adm_analytics.html');
	} else {
		$analyticsObj->setVar('analyticsid', 0);
		$analyticsObj->setVar('tag', '');
		$sform = $analyticsObj->getForm(_CO_ICMS_analyticsS_CREATE, 'addanalytics');
		$sform->assign($icmsAdminTpl);

		$icmsAdminTpl->assign('icms_analytics_title', _CO_ICMS_analyticsS_CREATE_INFO);
		$icmsAdminTpl->display('db:admin/analytics/system_adm_analytics.html');
	}
}

switch ($op) {
	case "mod":
		$analyticsid = isset($analyticsid) ? (int) $analyticsid : 0;
		editanalytics(TRUE, $analyticsid);
		break;

	case "clone":
		$analyticsid = isset($analyticsid) ? (int) $analyticsid : 0;
		editanalytics(TRUE, $analyticsid, TRUE);
		break;

	case "addanalytics":
		$controller = new icms_ipf_Controller($icms_admin_handler);
		$controller->storeFromDefaultForm(_CO_ICMS_analyticsS_CREATED, _CO_ICMS_analyticsS_MODIFIED);
		break;

	case "del":
		$controller = new icms_ipf_Controller($icms_admin_handler);
		$controller->handleObjectDeletion();
		break;

	default:
		icms_cp_header();
		$objectTable = new icms_ipf_view_Table($icms_admin_handler);
		$objectTable->addColumn(new icms_ipf_view_Column('description', _GLOBAL_LEFT));
		$objectTable->addColumn(new icms_ipf_view_Column(_CO_ICMS_analytics_TAG_CODE, 'center', 200, 'getXoopsCode'));

		$objectTable->addIntroButton('addanalytics', 'admin.php?fct=analytics&amp;op=mod', _CO_ICMS_analyticsS_CREATE);
		$objectTable->addQuickSearch(array('title', 'summary', 'description'));
		$objectTable->addCustomAction('getCloneLink');
		
		$icmsAdminTpl->assign('icms_analytics_table', $objectTable->fetch());
		$icmsAdminTpl->assign('icms_analytics_explain', TRUE);
		$icmsAdminTpl->assign('icms_analytics_title', _CO_ICMS_analyticsS_DSC);
		
		$icmsAdminTpl->display(ICMS_MODULES_PATH . '/system/templates/admin/analytics/system_adm_analytics.html');
		break;
}

icms_cp_footer();
