<?php
/**
* Audios page
*
* @copyright	GNU General Public License (GPL)
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		Jan Pedersen, Marcello Brandao, Sina Asghari, Gustavo Pilla <contact@impresscms.org>
* @package		profile
* @version		$Id$
*/

/**
 * Edit a Audio
 *
 * @param object $audiosObj ProfileAudio object to be edited
*/
function editaudios($audiosObj)
{
	global $profile_audios_handler, $xoTheme, $icmsTpl, $icmsUser;

	if (!$audiosObj->isNew()){
		if (!$audiosObj->userCanEditAndDelete()) {
			redirect_header($audiosObj->getItemLink(true), 3, _NOPERM);
		}
		$audiosObj->hideFieldFromForm(array('creation_time', 'uid_owner', 'meta_keywords', 'meta_description', 'short_url'));
		$sform = $audiosObj->getSecureForm(_MD_PROFILE_AUDIOS_EDIT, 'addaudios');
		$sform->assign($icmsTpl, 'profile_audioseditform');
		$icmsTpl->assign('profile_category_path', $audiosObj->getVar('title') . ' > ' . _EDIT);
	} else {
		if (!$profile_audios_handler->userCanSubmit()) {
			redirect_header(PROFILE_URL, 3, _NOPERM);
		}
		$audiosObj->setVar('uid_owner', $icmsUser->uid());
		$audiosObj->setVar('creation_time', time());
		$audiosObj->hideFieldFromForm(array('creation_time', 'uid_owner', 'meta_keywords', 'meta_description', 'short_url'));
		$sform = $audiosObj->getSecureForm(_MD_PROFILE_AUDIOS_SUBMIT, 'addaudios');
		$sform->assign($icmsTpl, 'profile_audiosform');
		$icmsTpl->assign('profile_category_path', _SUBMIT);
	}

	$xoTheme->addStylesheet(ICMS_URL . '/modules/profile/module'.(( defined("_ADM_USE_RTL") && _ADM_USE_RTL )?'_rtl':'').'.css');
}


$profile_template = 'profile_audio.html';
include_once 'header.php';

$profile_audios_handler = icms_getModuleHandler('audio');

/** Use a naming convention that indicates the source of the content of the variable */
$clean_op = '';

if (isset($_GET['op'])) $clean_op = $_GET['op'];
if (isset($_POST['op'])) $clean_op = $_POST['op'];

/** Again, use a naming convention that indicates the source of the content of the variable */
global $icmsUser;
$clean_audios_id = isset($_GET['audios_id']) ? intval($_GET['audios_id']) : 0 ;
$clean_uid = isset($_GET['uid']) ? intval($_GET['uid']) : 0 ;
$real_uid = is_object($icmsUser)?intval($icmsUser->uid()):0;
$audiosObj = $profile_audios_handler->get($clean_audios_id);
/** Create a whitelist of valid values, be sure to use appropriate types for each value
 * Be sure to include a value for no parameter, if you have a default condition
 */
$valid_op = array ('mod','addaudios','del','');
/**
 * Only proceed if the supplied operation is a valid operation
 */
if (in_array($clean_op,$valid_op,true)){
  switch ($clean_op) {
	case "mod":
		$audiosObj = $profile_audios_handler->get($clean_audios_id);
		if ($clean_audios_id > 0 && $audiosObj->isNew()) {
			redirect_header(icms_getPreviousPage('index.php'), 3, _NOPERM);
		}
		editaudios($audiosObj);
		break;

	case "addaudios":
        if (!$xoopsSecurity->check()) {
        	redirect_header(icms_getPreviousPage('index.php'), 3, _MD_PROFILE_SECURITY_CHECK_FAILED . implode('<br />', $xoopsSecurity->getErrors()));
        }
         include_once ICMS_ROOT_PATH.'/kernel/icmspersistablecontroller.php';
        $controller = new IcmsPersistableController($profile_audios_handler);
		$controller->storeFromDefaultForm(_MD_PROFILE_AUDIOS_CREATED, _MD_PROFILE_AUDIOS_MODIFIED);
		break;

	case "del":
		$audiosObj = $profile_audios_handler->get($clean_audios_id);
		if (!$audiosObj->userCanEditAndDelete()) {
			redirect_header($audiosObj->getItemLink(true), 3, _NOPERM);
		}
		if (isset($_POST['confirm'])) {
		    if (!$xoopsSecurity->check()) {
		    	redirect_header($impresscms->urls['previouspage'], 3, _MD_PROFILE_SECURITY_CHECK_FAILED . implode('<br />', $xoopsSecurity->getErrors()));
		    }
		}
  	    include_once ICMS_ROOT_PATH.'/kernel/icmspersistablecontroller.php';
        $controller = new IcmsPersistableController($profile_audios_handler);
		$controller->handleObjectDeletionFromUserSide();
		$icmsTpl->assign('profile_category_path', $audiosObj->getVar('title') . ' > ' . _DELETE);

		break;

	default:
		$values = array();
		if($real_uid){
			$audiosObj = $profile_audios_handler->get($clean_audios_id);
			if ($clean_audios_id > 0 && $audiosObj->isNew()) {
				redirect_header(icms_getPreviousPage('index.php'), 3, _NOPERM);
			}
			editaudios($audiosObj);
		}
		if($clean_audios_id > 0){
			$audiosArray = $profile_audios_handler->getAudio($clean_audios_id);
			$profile_audios_handler->updateCounter($clean_audios_id);
			$icmsTpl->assign('profile_single_audio', $audiosObj->toArray());
			$icmsTpl->assign('profile_category_path', $audiosArray['title']);
		}elseif($clean_uid > 0){
			$audiosArray = $profile_audios_handler->getAudios(false, false, $clean_uid);
			$icmsTpl->assign('profile_allaudios', $audiosArray);
		}elseif($real_uid > 0){
			$audiosArray = $profile_audios_handler->getAudios(false, false, $real_uid);
			$icmsTpl->assign('profile_allaudios', $audiosArray);
		}else{
			redirect_header(PROFILE_URL);
		}


		/**
		 * Generating meta information for this page
		 */
		$icms_metagen = new IcmsMetagen($audiosObj->getVar('title'), $audiosObj->getVar('meta_keywords','n'), $audiosObj->getVar('meta_description', 'n'));
		$icms_metagen->createMetaTags();

		break;
	}
}
$icmsTpl->assign('profile_module_home', icms_getModuleName(true, true));

include_once 'footer.php';
?>