<?php
/**
 * ImpressCMS Mimetypes
 *
 * @copyright The ImpressCMS Project http://www.impresscms.org/
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package Administration
 * @subpackage Mimetypes
 * @since 1.2
 * @author Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version SVN: $Id: main.php 11143 2011-03-30 13:46:23Z m0nty_ $
 */
if (!is_object(icms::$user) || !is_object($icmsModule) || !icms::$user->isAdmin($icmsModule->getVar('mid'))) {
	exit("Access Denied");
}

/**
 * Logic and rendering for mimetypes management
 *
 * @param bool $showmenu Doesn't appear to have any current functionality
 * @param int $mimetypeid Unique ID for mimetype entry
 */
function editmimetype($showmenu = FALSE, $mimetypeid = 0) {
	global $icms_mimetype_handler, $icmsAdminTpl;

	icms_cp_header();
	$mimetypeObj = $icms_mimetype_handler->get($mimetypeid);

	if (!$mimetypeObj->isNew()) {
		$sform = $mimetypeObj->getForm(_CO_ICMS_MIMETYPE_EDIT, 'addmimetype');
		$sform->assign($icmsAdminTpl);

		$icmsAdminTpl->assign('icms_mimetype_title', _CO_ICMS_MIMETYPE_EDIT_INFO);
		$icmsAdminTpl->display('db:system_adm_mimetype.html');
	} else {
		$mimetypeObj->setVar('mimetypeid', 0);
		$mimetypeObj->setVar('extension', '');

		$sform = $mimetypeObj->getForm(_CO_ICMS_MIMETYPE_CREATE, 'addmimetype');
		$sform->assign($icmsAdminTpl);

		$icmsAdminTpl->assign('icms_mimetype_title', _CO_ICMS_MIMETYPE_CREATE_INFO);
		$icmsAdminTpl->display('db:system_adm_mimetype.html');
	}
}
icms_loadLanguageFile('system', 'common');

$icms_mimetype_handler = icms_getModuleHandler('mimetype');

/*
 * GET variables
 * (string) op
 *
 * POST variables
 *
 */

/* default values */
$op = '';
$mimetypeid = 0;

$filter_get = array('mimetypeid' => 'int');

$filter_post = array('mimetypeid' => 'int');

/* filter the user input */
if (!empty($_GET)) {
	// in places where strict mode is not used for checkVarArray, make sure filter_ vars are not overwritten
	if (isset($_GET['filter_post'])) unset($_GET['filter_post']);
	$clean_GET = icms_core_DataFilter::checkVarArray($_GET, $filter_get, false);
	extract($clean_GET);
}

if (!empty($_POST)) {
	$clean_POST = icms_core_DataFilter::checkVarArray($_POST, $filter_post, false);
	extract($clean_POST);
}

switch ($op) {
	case "mod":
		editmimetype(TRUE, $mimetypeid);
		break;

	case "addmimetype":
		$controller = new icms_ipf_Controller($icms_mimetype_handler);
		$controller->storeFromDefaultForm(_CO_ICMS_MIMETYPE_CREATED, _CO_ICMS_MIMETYPE_MODIFIED);
		break;

	case "del":
		$controller = new icms_ipf_Controller($icms_mimetype_handler);
		$controller->handleObjectDeletion();
		break;

	default:
		icms_cp_header();

		$objectTable = new icms_ipf_view_Table($icms_mimetype_handler);
		$objectTable->addColumn(new icms_ipf_view_Column('name', _GLOBAL_LEFT, 150));
		$objectTable->addColumn(new icms_ipf_view_Column('extension', _GLOBAL_LEFT, 150));
		$objectTable->addColumn(new icms_ipf_view_Column('types', _GLOBAL_LEFT));
		$objectTable->addIntroButton('addmimetype', 'admin.php?fct=mimetype&amp;op=mod', _CO_ICMS_MIMETYPE_CREATE);
		$objectTable->addQuickSearch(array('name', 'extension', 'types'));

		$icmsAdminTpl->assign('icms_mimetype_table', $objectTable->fetch());
		$icmsAdminTpl->assign('icms_mimetype_explain', TRUE);
		$icmsAdminTpl->assign('icms_mimetype_title', _CO_ICMS_MIMETYPES_DSC);
		$icmsAdminTpl->display('db:system_adm_mimetype.html');
		break;
}

icms_cp_footer();
