<?php
/**
* ImpressCMS User Ranks.
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		Administration
* @since		1.2
* @author		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
* @version		$Id$
*/

if ( !is_object($icmsUser) || !is_object($icmsModule) || !$icmsUser->isAdmin($icmsModule->mid()) ) {
    exit("Access Denied");
}

$icms_userrank_handler = icms_getmodulehandler('userrank');
function edituserrank($showmenu = false, $rank_id = 0, $clone=false)
{	
	global $icms_userrank_handler, $icmsAdminTpl;

	icms_cp_header();
	
	$userrankObj = $icms_userrank_handler->get($rank_id);

	if (!$clone && !$userrankObj->isNew()){

		$sform = $userrankObj->getForm(_CO_ICMS_USERRANKS_EDIT, 'adduserrank');
		
		$sform->assign($icmsAdminTpl);
		$icmsAdminTpl->assign('icms_userrank_title', _CO_ICMS_USERRANKS_EDIT_INFO);
		$icmsAdminTpl->display('db:admin/userrank/system_adm_userrank.html');
	} else {
		$userrankObj->setVar('rank_id', 0);

		$sform = $userrankObj->getForm(_CO_ICMS_USERRANKS_CREATE, 'adduserrank');
		$sform->assign($icmsAdminTpl);

		$icmsAdminTpl->assign('icms_userrank_title', _CO_ICMS_USERRANKS_CREATE_INFO);
		$icmsAdminTpl->display('db:admin/userrank/system_adm_userrank.html');		
	}
}
icms_loadLanguageFile('system', 'common');


if(!empty($_POST)) foreach($_POST as $k => $v) ${$k} = StopXSS($v);
if(!empty($_GET)) foreach($_GET as $k => $v) ${$k} = StopXSS($v);
$op = (isset($_POST['op']))?trim(StopXSS($_POST['op'])):((isset($_GET['op']))?trim(StopXSS($_GET['op'])):'');

switch ($op) {
	case "mod":

		$rank_id = isset($_GET['rank_id']) ? intval($_GET['rank_id']) : 0 ;

		edituserrank(true, $rank_id);
		
		break;

	case "clone":

		$rank_id = isset($_GET['rank_id']) ? intval($_GET['rank_id']) : 0 ;

		edituserrank(true, $rank_id, true);
		break;

	case "adduserrank":
        include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";
        $controller = new IcmsPersistableController($icms_userrank_handler);
		$controller->storeFromDefaultForm(_CO_ICMS_USERRANKS_CREATED, _CO_ICMS_USERRANKS_MODIFIED);
		break;

	case "del":
		include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";
	    $controller = new IcmsPersistableController($icms_userrank_handler);		
		$controller->handleObjectDeletion();

		break;

	default:

		icms_cp_header();
		
		include_once ICMS_ROOT_PATH."/kernel/icmspersistabletable.php";
		
		$objectTable = new IcmsPersistableTable($icms_userrank_handler);
		$objectTable->addColumn(new IcmsPersistableColumn('rank_title', _GLOBAL_LEFT));
		$objectTable->addColumn(new IcmsPersistableColumn('rank_min', _GLOBAL_LEFT));
		$objectTable->addColumn(new IcmsPersistableColumn('rank_max', _GLOBAL_LEFT));
		$objectTable->addColumn(new IcmsPersistableColumn(_CO_ICMS_USERRANK_RANK_IMAGE, 'center', 200, 'getRankPicture'));
		//$objectTable->addColumn(new IcmsPersistableColumn('language', 'center', 150));

		$objectTable->addIntroButton('adduserrank', 'admin.php?fct=userrank&op=mod', _CO_ICMS_USERRANKS_CREATE);

		$objectTable->addQuickSearch(array('title', 'summary', 'description'));

		$objectTable->addCustomAction('getCloneLink');

		$icmsAdminTpl->assign('icms_userrank_table', $objectTable->fetch());
		
		$icmsAdminTpl->assign('icms_userrank_explain', true);
		$icmsAdminTpl->assign('icms_userrank_title', _CO_ICMS_USERRANKS_DSC);

		$icmsAdminTpl->display(ICMS_ROOT_PATH . '/modules/system/templates/admin/userrank/system_adm_userrank.html');

		break;
}

icms_cp_footer();

/*if ( !is_object($icmsUser) || !is_object($icmsModule) || !$icmsUser->isAdmin($icmsModule->mid()) ) {
	exit("Access Denied");
}

if(!empty($_POST)) foreach($_POST as $k => $v) ${$k} = StopXSS($v);
if(!empty($_GET)) foreach($_GET as $k => $v) ${$k} = StopXSS($v);
$op = (isset($_GET['op']))?trim(StopXSS($_GET['op'])):((isset($_POST['op']))?trim(StopXSS($_POST['op'])):'RankForumAdmin');

$config_handler =& xoops_gethandler('config');
$xoopsConfigUser =& $config_handler->getConfigsByCat(XOOPS_CONF_USER);

switch ($op) {

	case "RankForumEdit":
		$rank_id = isset($_GET['rank_id']) ? intval($_GET['rank_id']) : 0;
		if ($rank_id > 0) {
			include_once XOOPS_ROOT_PATH."/modules/system/admin/userrank/userrank.php";
			RankForumEdit($rank_id);
		}
		break;

	case "RankForumDel":
		$rank_id = isset($_GET['rank_id']) ? intval($_GET['rank_id']) : 0;
		if ($rank_id > 0) {
			xoops_cp_header();
			xoops_confirm(array('fct' => 'userrank', 'op' => 'RankForumDelGo', 'rank_id' => $rank_id), 'admin.php', _AM_WAYSYWTDTR);
			xoops_cp_footer();
		}
		break;

	case "RankForumDelGo":
		$rank_id = isset($_POST['rank_id']) ? intval($_POST['rank_id']) : 0;
		if ($rank_id <= 0 | !$GLOBALS['xoopsSecurity']->check()) {
			redirect_header("admin.php?fct=userrank", 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
		}
		$db =& Database::getInstance();
		$sql = sprintf("DELETE FROM %s WHERE rank_id = '%u'", $db->prefix("ranks"), intval($rank_id));
		$db->query($sql);
		redirect_header("admin.php?fct=userrank&amp;op=ForumAdmin",1,_AM_DBUPDATED);
		break;

	case "RankForumAdd":
		if (!$GLOBALS['xoopsSecurity']->check()) {
			redirect_header("admin.php?fct=userrank", 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
		}
		$db =& Database::getInstance();
		$myts =& MyTextSanitizer::getInstance();
		$rank_special = isset($_POST['rank_special']) && intval($_POST['rank_special']) ? 1 : 0;
		$rank_title = htmlspecialchars($_POST['rank_title']);
		$rank_title = $myts->stripSlashesGPC($rank_title);
		$rank_image = '';
		include_once XOOPS_ROOT_PATH.'/class/uploader.php';
		$uploader = new XoopsMediaUploader(, array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png'), $xoopsConfigUser['rank_maxsize'], $xoopsConfigUser['rank_width'], $xoopsConfigUser['rank_height']);
		$uploader->setPrefix('rank');
		if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
			if ($uploader->upload()) {
				$rank_image = $uploader->getSavedFileName();
			} else {
				xoops_error( $uploader->getErrors() );
			}
		}
		$newid = $db->genId($db->prefix("ranks")."_rank_id_seq");
		if ($rank_special == 1) {
			$sql = "INSERT INTO ".$db->prefix("ranks")." (rank_id, rank_title, rank_min, rank_max, rank_special, rank_image) VALUES ('".intval($newid)."', ".$db->quoteString($rank_title).", '-1', '-1', '1', ".$db->quoteString($rank_image).")";
		} else {
			$sql = "INSERT INTO ".$db->prefix("ranks")." (rank_id, rank_title, rank_min, rank_max, rank_special, rank_image) VALUES ('".intval($newid)."', ".$db->quoteString($rank_title).", '".intval($_POST['rank_min'])."', '".intval($_POST['rank_max'])."' , '0', ".$db->quoteString($rank_image).")";
		}
		if (!$db->query($sql)) {
			xoops_cp_header();
			xoops_error('Failed storing rank data into the database');
			xoops_cp_footer();
		} else {
			redirect_header("admin.php?fct=userrank&amp;op=RankForumAdmin",3,_AM_DBUPDATED);
		}
		break;

	case "RankForumSave":
		$rank_id = isset($_POST['rank_id']) ? intval($_POST['rank_id']) : 0;
		if ($rank_id <= 0 | !$GLOBALS['xoopsSecurity']->check()) {
			redirect_header("admin.php?fct=userrank", 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
		}
		$db =& Database::getInstance();
		$myts =& MyTextSanitizer::getInstance();
		$rank_special = isset($_POST['rank_special']) && intval($_POST['rank_special']) ? 1 : 0;
		$rank_title = htmlspecialchars($_POST['rank_title']);
		$rank_title = $myts->stripSlashesGPC($rank_title);
		$delete_old_image = false;
		include_once XOOPS_ROOT_PATH.'/class/uploader.php';
		$uploader = new XoopsMediaUploader(XOOPS_UPLOAD_PATH, array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png'), $xoopsConfigUser['rank_maxsize'], $xoopsConfigUser['rank_width'], $xoopsConfigUser['rank_height']);
		$uploader->setPrefix('rank');
		if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
			if ($uploader->upload()) {
				$rank_image = $uploader->getSavedFileName();
				$delete_old_image = true;
			} else {
				xoops_error( $uploader->getErrors() );
			}
		}

		if ($rank_special > 0) {
			$_POST['rank_min'] = $_POST['rank_max'] = -1;
		}

		$sql = "UPDATE ".$db->prefix("ranks")." SET rank_title = ".$db->quoteString($rank_title).", rank_min = '".intval($_POST['rank_min'])."', rank_max = '".intval($_POST['rank_max'])."', rank_special = " . $db->quoteString($rank_special);
		if ($delete_old_image) {
			$sql .= ", rank_image = ".$db->quoteString($rank_image)."";
		}

		$sql .= " WHERE rank_id = '".$rank_id."'";

		if (!$db->query($sql)) {
			xoops_cp_header();
			xoops_error('Failed storing rank data into the database');
			xoops_cp_footer();
		} else {
			if ($delete_old_image) {
				$old_rank_path = str_replace("\\", "/", realpath(XOOPS_UPLOAD_PATH.'/'.trim($_POST['old_rank'])));
				if (0 === strpos($old_rank_path, XOOPS_UPLOAD_PATH) && is_file($old_rank_path)) {
					unlink($old_rank_path);
				}
			}
			redirect_header("admin.php?fct=userrank&amp;op=RankForumAdmin",1,_AM_DBUPDATED);
		}
		break;

	default:
		include_once XOOPS_ROOT_PATH."/modules/system/admin/userrank/userrank.php";
		RankForumAdmin();
		break;
}*/
?>