<?php
/**
* ImpressCMS Customtags
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		core
* @since		1.1
* @author		marcan <marcan@impresscms.org>
* @version		$Id$
*/
if ( !is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->mid()) ) {
    exit("Access Denied");
}

function editcustomtag($showmenu = false, $customtagid = 0, $clone=false)
{	
	global $icms_customtag_handler, $icmsAdminTpl;

	xoops_cp_header();
	
	$customtagObj = $icms_customtag_handler->get($customtagid);

	if (!$clone && !$customtagObj->isNew()){

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

$icms_customtag_handler = xoops_getmodulehandler('customtag');

icms_loadLanguageFile('system', 'customtag', true);
icms_loadLanguageFile('system', 'common');


$op = '';

if (isset($_GET['op'])) $op = $_GET['op'];
if (isset($_POST['op'])) $op = $_POST['op'];

switch ($op) {
	case "mod":

		$customtagid = isset($_GET['customtagid']) ? intval($_GET['customtagid']) : 0 ;

		editcustomtag(true, $customtagid);
		
		break;

	case "clone":

		$customtagid = isset($_GET['customtagid']) ? intval($_GET['customtagid']) : 0 ;

		editcustomtag(true, $customtagid, true);
		break;

	case "addcustomtag":
        include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";
        $controller = new IcmsPersistableController($icms_customtag_handler);
		$controller->storeFromDefaultForm(_CO_ICMS_CUSTOMTAG_CREATED, _CO_ICMS_CUSTOMTAG_MODIFIED);
		break;

	case "del":
		include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";
	    $controller = new IcmsPersistableController($icms_customtag_handler);		
		$controller->handleObjectDeletion();

		break;

	default:

		xoops_cp_header();
		
		include_once ICMS_ROOT_PATH."/kernel/icmspersistabletable.php";
		
		$objectTable = new IcmsPersistableTable($icms_customtag_handler);
		$objectTable->addColumn(new IcmsPersistableColumn('name', 'left', 150, 'getCustomtagName'));
		$objectTable->addColumn(new IcmsPersistableColumn('description', 'left'));
		$objectTable->addColumn(new IcmsPersistableColumn('language', 'center', 150));

		$objectTable->addIntroButton('addcustomtag', 'admin.php?fct=customtag&op=mod', _CO_ICMS_CUSTOMTAG_CREATE);

		$objectTable->addQuickSearch(array('title', 'summary', 'description'));

		$objectTable->addCustomAction('getCloneLink');

		$icmsAdminTpl->assign('icms_customtag_table', $objectTable->fetch());
		
		$icmsAdminTpl->assign('icms_custom_tag_title', _CO_ICMS_CUSTOMTAGS_DSC);

		$icmsAdminTpl->display(ICMS_ROOT_PATH . '/modules/system/templates/admin/customtag/system_adm_customtag.html');

		break;
}

xoops_cp_footer();

?>