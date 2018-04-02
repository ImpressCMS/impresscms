<?php
/**
 * ImpressCMS Mimetypes
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		Administration
 * @subpackage	Mimetypes
 * @since		1.2
 * @author		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 */

/* set get and post filters before including admin_header, if not strings */
$filter_get = array('mimetypeid' => 'int');

$filter_post = array('mimetypeid' => 'int');

/* set default values for variables. $op and $fct are handled in the header */
$mimetypeid = 0;

/** common header for the admin functions */
include 'admin_header.php';

/**
 * Logic and rendering for mimetypes management
 *
 * @param bool	$showmenu	Doesn't appear to have any current functionality
 * @param int	$mimetypeid	Unique ID for mimetype entry
 */
function editmimetype($showmenu = FALSE, $mimetypeid = 0) {
	global $icms_admin_handler, $icmsAdminTpl;

	icms_cp_header();
	$mimetypeObj = $icms_admin_handler->get($mimetypeid);

	if (!$mimetypeObj->isNew()) {
		$sform = $mimetypeObj->getForm(_CO_ICMS_MIMETYPE_EDIT, 'addmimetype');
		$sform->assign($icmsAdminTpl);

		$icmsAdminTpl->assign('icms_mimetype_title', _CO_ICMS_MIMETYPE_EDIT_INFO);
		$icmsAdminTpl->display('db:admin/mimetype/system_adm_mimetype.html');
	} else {
		$mimetypeObj->setVar('mimetypeid', 0);
		$mimetypeObj->setVar('extension', '');

		$sform = $mimetypeObj->getForm(_CO_ICMS_MIMETYPE_CREATE, 'addmimetype');
		$sform->assign($icmsAdminTpl);

		$icmsAdminTpl->assign('icms_mimetype_title', _CO_ICMS_MIMETYPE_CREATE_INFO);
		$icmsAdminTpl->display('db:admin/mimetype/system_adm_mimetype.html');
	}
}

switch ($op) {
	case "mod":
		editmimetype(TRUE, $mimetypeid);
		break;

	case "addmimetype":
		$controller = new icms_ipf_Controller($icms_admin_handler);
		$controller->storeFromDefaultForm(_CO_ICMS_MIMETYPE_CREATED, _CO_ICMS_MIMETYPE_MODIFIED);
		break;

	case "del":
		$controller = new icms_ipf_Controller($icms_admin_handler);
		$controller->handleObjectDeletion();
		break;

	default:
		icms_cp_header();

		$objectTable = new icms_ipf_view_Table($icms_admin_handler);
		$objectTable->addColumn(new icms_ipf_view_Column('name', _GLOBAL_LEFT, 150));
		$objectTable->addColumn(new icms_ipf_view_Column('extension', _GLOBAL_LEFT, 150));
		$objectTable->addColumn(new icms_ipf_view_Column('types', _GLOBAL_LEFT));

		$objectTable->addIntroButton('addmimetype', 'admin.php?fct=mimetype&amp;op=mod', _CO_ICMS_MIMETYPE_CREATE);
		$objectTable->addQuickSearch(array('name', 'extension', 'types'));

		$icmsAdminTpl->assign('icms_mimetype_table', $objectTable->fetch());
		$icmsAdminTpl->assign('icms_mimetype_explain', TRUE);
		$icmsAdminTpl->assign('icms_mimetype_title', _CO_ICMS_MIMETYPES_DSC);
		$icmsAdminTpl->display('db:admin/mimetype/system_adm_mimetype.html');
		break;
}

icms_cp_footer();
