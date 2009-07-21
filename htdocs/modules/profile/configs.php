<?php
/**
* configs page
*
* @copyright	GNU General Public License (GPL)
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.3
* @author		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
* @package		profile
* @version		$Id$
*/

/**
 * Edit a config
 *
 * @param object $configsObj Profileconfig object to be edited
*/
function editconfigs($configsObj, $admin=false, $uid=0, $unsuspend=false)
{
	global $profile_configs_handler, $xoTheme, $icmsTpl, $icmsUser;

	if($admin && $uid != 0 && $unsuspend == true){
	}elseif($admin && $uid != 0 && $configsObj->isNew()){
		$member_handler =& xoops_gethandler('member');
		$processUser =& $member_handler->getUser($uid);
		$configsObj->setVar('config_uid', $uid);
		$configsObj->setVar('backup_email', $processUser->email());
		$configsObj->setVar('backup_password', $processUser->pass());
		$configsObj->hideFieldFromForm(array('status', 'backup_email', 'backup_password', 'pictures', 'audio', 'videos', 'scraps', 'friendship', 'tribes', 'profile_contact', 'profile_general', 'profile_stats'));
		$sform = $configsObj->getSecureForm(_MD_PROFILE_CONFIGS_EDIT, 'addconfigs');
		$sform->assign($icmsTpl, 'profile_configsform');
		$icmsTpl->assign('profile_category_path', icms_getLinkedUnameFromId($uid) . ' > ' . _EDIT);
	}elseif ($configsObj->isNew()){
		$configsObj->hideFieldFromForm(array('status', 'backup_email', 'backup_password', 'suspension', 'end_suspension'));
		$sform = $configsObj->getSecureForm(_MD_PROFILE_CONFIGS_EDIT, 'addconfigs');
		$sform->assign($icmsTpl, 'profile_configsform');
		$icmsTpl->assign('profile_category_path', icms_getLinkedUnameFromId($icmsUser->uid()) . ' > ' . _EDIT);
	} else {
		if (!$profile_configs_handler->userCanSubmit()) {
			redirect_header(PROFILE_URL, 3, _NOPERM);
		}
		$configsObj->setVar('config_uid', $icmsUser->uid());
		$configsObj->hideFieldFromForm(array('config_uid', 'status', 'backup_email', 'backup_password', 'suspension', 'end_suspension'));
		$sform = $configsObj->getSecureForm(_MD_PROFILE_CONFIGS_SUBMIT, 'addconfigs');
		$sform->assign($icmsTpl, 'profile_configsform');
		$icmsTpl->assign('profile_category_path', _SUBMIT);
	}

	$xoTheme->addStylesheet(ICMS_URL . '/modules/profile/module'.(( defined("_ADM_USE_RTL") && _ADM_USE_RTL )?'_rtl':'').'.css');
}


$profile_template = 'profile_configs.html';
include_once 'header.php';

$profile_configs_handler = icms_getModuleHandler('configs');

/** Use a naming convention that indicates the source of the content of the variable */
$clean_op = '';

if (isset($_GET['op'])) $clean_op = $_GET['op'];
if (isset($_POST['op'])) $clean_op = $_POST['op'];

/** Again, use a naming convention that indicates the source of the content of the variable */
global $icmsUser, $profile_isAdmin;
$clean_uid = isset($_GET['uid']) ? intval($_GET['uid']) : 0 ;
$real_uid = is_object($icmsUser)?intval($icmsUser->uid()):0;
$CheckID = !empty($clean_uid)?$clean_uid:$real_uid;
$clean_configs_id = $profile_configs_handler->getConfigIdPerUser($CheckID);
$configsObj = $profile_configs_handler->get($clean_configs_id);
/** Create a whitelist of valid values, be sure to use appropriate types for each value
 * Be sure to include a value for no parameter, if you have a default condition
 */
$valid_op = array ('mod','addconfigs','suspend','unsuspend', '');
/**
 * Only proceed if the supplied operation is a valid operation
 */
if (in_array($clean_op,$valid_op,true)){
  switch ($clean_op) {
	case "mod":
		$configsObj = $profile_configs_handler->get($clean_configs_id);
		if ($clean_uid < 1 && !$profile_isAdmin) {
			redirect_header(icms_getPreviousPage('index.php'), 3, _NOPERM);
		}
		editconfigs($configsObj, true, $clean_uid );
		break;

	case "suspend":
		$configsObj = $profile_configs_handler->get($clean_configs_id);
		if ($clean_uid < 1 && !$profile_isAdmin) {
			redirect_header(icms_getPreviousPage('index.php'), 3, _NOPERM);
		}
		editconfigs($configsObj, true, $clean_uid );
		break;

	case "unsuspend":
		$configsObj = $profile_configs_handler->get($clean_uid);
		if ($clean_uid > 0 && !$profile_isAdmin) {
			redirect_header(icms_getPreviousPage('index.php'), 3, _NOPERM);
		}
		editconfigs($configsObj, true, $clean_uid, true );
		break;

	case "addconfigs":
        if (!$xoopsSecurity->check()) {
        	redirect_header(icms_getPreviousPage('index.php'), 3, _MD_PROFILE_SECURITY_CHECK_FAILED . implode('<br />', $xoopsSecurity->getErrors()));
        }
         include_once ICMS_ROOT_PATH.'/kernel/icmspersistablecontroller.php';
        $controller = new IcmsPersistableController($profile_configs_handler);
		$controller->storeFromDefaultForm(_MD_PROFILE_CONFIGS_CREATED, _MD_PROFILE_CONFIGS_MODIFIED);
		break;

	default:
		if ($real_uid > 0) {
			editconfigs($configsObj);
		}elseif($profile_isAdmin && $clean_uid > 0){
			$configsObj = $profile_configs_handler->get($clean_configs_id);
			editconfigs($configsObj, true, $clean_uid );
		}else{
		    redirect_header(icms_getPreviousPage('index.php'), 3, _NOPERM);
		}
		break;
	}
}
$icmsTpl->assign('profile_module_home', icms_getModuleName(true, true));

include_once 'footer.php';
?>