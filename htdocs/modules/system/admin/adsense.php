<?php
/**
 * ImpressCMS Adsenses
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Administration
 * @subpackage	AdSense
 * @since		1.2
 * @author		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version		$Id: adsense.php 11610 2012-02-28 03:53:55Z skenow $
 */
/* set get and post filters before including admin_header, if not strings */
$filter_post = array();

$filter_get = array();

/* set default values for variables. $op and $fct are handled in the header */

/** common header for the admin functions */
include "admin_header.php";

/**
 * Edit AdSense entries
 *
 * @param $showmenu		This parameter is not used (why is it here?)
 * @param $adsenseid	Unique identifier of the AdSense unit
 * @param $clone		Is this cloning an existing AdSense unit?
 */
function editadsense($showmenu = FALSE, $adsenseid = 0, $clone = FALSE) {
	global $icms_admin_handler, $icmsAdminTpl;

	icms_cp_header();
	$adsenseObj = $icms_admin_handler->get($adsenseid);

	if (!$clone && !$adsenseObj->isNew()) {
		$sform = $adsenseObj->getForm(_CO_ICMS_ADSENSES_EDIT, 'addadsense');
		$sform->assign($icmsAdminTpl);
		
		$icmsAdminTpl->assign('icms_adsense_title', _CO_ICMS_ADSENSES_EDIT_INFO);
		$icmsAdminTpl->display(ICMS_MODULES_PATH . '/system/templates/admin/adsense/system_adm_adsense.html');
	} else {
		$adsenseObj->setVar('adsenseid', 0);
		$adsenseObj->setVar('tag', '');
		$sform = $adsenseObj->getForm(_CO_ICMS_ADSENSES_CREATE, 'addadsense');
		$sform->assign($icmsAdminTpl);

		$icmsAdminTpl->assign('icms_adsense_title', _CO_ICMS_ADSENSES_CREATE_INFO);
		$icmsAdminTpl->display(ICMS_MODULES_PATH . '/system/templates/admin/adsense/system_adm_adsense.html');
	}
}

switch ($op) {
	case "mod":
		$adsenseid = isset($adsenseid) ? (int) $adsenseid : 0;
		editadsense(TRUE, $adsenseid);
		break;

	case "clone":
		$adsenseid = isset($adsenseid) ? (int) $adsenseid : 0;
		editadsense(TRUE, $adsenseid, TRUE);
		break;

	case "addadsense":
		$controller = new icms_ipf_Controller($icms_admin_handler);
		$controller->storeFromDefaultForm(_CO_ICMS_ADSENSES_CREATED, _CO_ICMS_ADSENSES_MODIFIED);
		break;

	case "del":
		$controller = new icms_ipf_Controller($icms_admin_handler);
		$controller->handleObjectDeletion();
		break;

	default:
		icms_cp_header();
		$objectTable = new icms_ipf_view_Table($icms_admin_handler);
		$objectTable->addColumn(new icms_ipf_view_Column('description', _GLOBAL_LEFT));
		$objectTable->addColumn(new icms_ipf_view_Column(_CO_ICMS_ADSENSE_TAG_CODE, 'center', 200, 'getXoopsCode'));

		$objectTable->addIntroButton('addadsense', 'admin.php?fct=adsense&amp;op=mod', _CO_ICMS_ADSENSES_CREATE);
		$objectTable->addQuickSearch(array('title', 'summary', 'description'));
		$objectTable->addCustomAction('getCloneLink');
		
		$icmsAdminTpl->assign('icms_adsense_table', $objectTable->fetch());
		$icmsAdminTpl->assign('icms_adsense_explain', TRUE);
		$icmsAdminTpl->assign('icms_adsense_title', _CO_ICMS_ADSENSES_DSC);
		
		$icmsAdminTpl->display(ICMS_MODULES_PATH . '/system/templates/admin/adsense/system_adm_adsense.html');
		break;
}

icms_cp_footer();
