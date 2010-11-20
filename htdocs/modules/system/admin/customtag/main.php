<?php
/**
 * ImpressCMS Customtags
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		Administration
 * @since		1.1
 * @author		marcan <marcan@impresscms.org>
 * @version		$Id$
 */
if (!is_object(icms::$user) || !is_object($icmsModule) || !icms::$user->isAdmin($icmsModule->getVar('mid'))) {
	exit("Access Denied");
}

function editcustomtag($showmenu = false, $customtagid = 0, $clone=false)
{
	global $icms_customtag_handler, $icmsAdminTpl;

	icms_cp_header();

	$customtagObj = $icms_customtag_handler->get($customtagid);

	if (!$clone && !$customtagObj->isNew()) {

		$sform = $customtagObj->getForm(_CO_ICMS_CUSTOMTAG_EDIT, 'addcustomtag');

		$sform->assign($icmsAdminTpl);
		$icmsAdminTpl->assign('icms_custom_tag_title', _CO_ICMS_CUSTOMTAG_EDIT_INFO);
		$icmsAdminTpl->display('db:admin/customtag/system_adm_customtag.html');
	} else {
		$customtagObj->setVar('customtagid', 0);
		$customtagObj->setVar('tag', '');

		$sform = $customtagObj->getForm(_CO_ICMS_CUSTOMTAG_CREATE, 'addcustomtag');
		$sform->assign($icmsAdminTpl);

		$icmsAdminTpl->assign('icms_custom_tag_title', _CO_ICMS_CUSTOMTAG_CREATE_INFO);
		$icmsAdminTpl->display('db:admin/customtag/system_adm_customtag.html');
	}
}
icms_loadLanguageFile('system', 'common');

$icms_customtag_handler = icms_getModuleHandler('customtag');

if (!empty($_POST)) foreach ($_POST as $k => $v) ${$k} = StopXSS($v);
if (!empty($_GET)) foreach ($_GET as $k => $v) ${$k} = StopXSS($v);
$op = (isset($_POST['op']))?trim(StopXSS($_POST['op'])):((isset($_GET['op']))?trim(StopXSS($_GET['op'])):'');

switch ($op) {
	case "mod":

		$customtagid = isset($_GET['customtagid']) ? (int) ($_GET['customtagid']) : 0 ;

		editcustomtag(true, $customtagid);

		break;

	case "clone":

		$customtagid = isset($_GET['customtagid']) ? (int) ($_GET['customtagid']) : 0 ;

		editcustomtag(true, $customtagid, true);
		break;

	case "addcustomtag":
		$controller = new icms_ipf_Controller($icms_customtag_handler);
		$controller->storeFromDefaultForm(_CO_ICMS_CUSTOMTAG_CREATED, _CO_ICMS_CUSTOMTAG_MODIFIED);
		break;

	case "del":
		$controller = new icms_ipf_Controller($icms_customtag_handler);
		$controller->handleObjectDeletion();

		break;

	default:

		icms_cp_header();

		$objectTable = new icms_ipf_view_Table($icms_customtag_handler);
		$objectTable->addColumn(new icms_ipf_view_Column('name', _GLOBAL_LEFT, 150, 'getCustomtagName'));
		$objectTable->addColumn(new icms_ipf_view_Column('description', _GLOBAL_LEFT));
		$objectTable->addColumn(new icms_ipf_view_Column(_CO_ICMS_CUSTOMTAGS_TAG_CODE, 'center', 200, 'getXoopsCode'));
		$objectTable->addColumn(new icms_ipf_view_Column('language', 'center', 150));

		$objectTable->addIntroButton('addcustomtag', 'admin.php?fct=customtag&amp;op=mod', _CO_ICMS_CUSTOMTAG_CREATE);

		$objectTable->addQuickSearch(array('title', 'summary', 'description'));

		$objectTable->addCustomAction('getCloneLink');

		$icmsAdminTpl->assign('icms_customtag_table', $objectTable->fetch());

		$icmsAdminTpl->assign('icms_custom_tag_explain', true);
		$icmsAdminTpl->assign('icms_custom_tag_title', _CO_ICMS_CUSTOMTAGS_DSC);

		$icmsAdminTpl->display(ICMS_ROOT_PATH . '/modules/system/templates/admin/customtag/system_adm_customtag.html');

		break;
}

icms_cp_footer();

?>