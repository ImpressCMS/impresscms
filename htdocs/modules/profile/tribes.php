<?php
/**
* Tribes page
*
* @copyright	GNU General Public License (GPL)
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.3
* @author		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
* @package		profile
* @version		$Id$
*/

/**
 * Edit a Tribe
 *
 * @param object $tribesObj ProfileTribe object to be edited
*/
function edittribes($tribesObj, $hideForm=false)
{
	global $profile_tribes_handler, $xoTheme, $icmsTpl, $icmsUser;

	$icmsTpl->assign('hideForm', $hideForm);
	if (!$tribesObj->isNew()){
		if (!$tribesObj->userCanEditAndDelete()) {
			redirect_header($tribesObj->getItemLink(true), 3, _NOPERM);
		}
		$tribesObj->hideFieldFromForm(array('creation_time', 'uid_owner', 'meta_keywords', 'meta_description', 'short_url'));
		$sform = $tribesObj->getSecureForm(_MD_PROFILE_TRIBES_EDIT, 'addtribes');
		$sform->assign($icmsTpl, 'profile_tribesform');
		$icmsTpl->assign('profile_category_path', $tribesObj->getVar('title') . ' > ' . _EDIT);
	} else {
		if (!$profile_tribes_handler->userCanSubmit()) {
			redirect_header(PROFILE_URL, 3, _NOPERM);
		}
		$tribesObj->setVar('uid_owner', $icmsUser->uid());
		$tribesObj->setVar('creation_time', time());
		$tribesObj->hideFieldFromForm(array('creation_time', 'uid_owner', 'meta_keywords', 'meta_description', 'short_url'));
		$sform = $tribesObj->getSecureForm(_MD_PROFILE_TRIBES_SUBMIT, 'addtribes');
		$sform->assign($icmsTpl, 'profile_tribesform');
		$icmsTpl->assign('profile_category_path', _SUBMIT);
	}

	$xoTheme->addStylesheet(ICMS_URL . '/modules/profile/module'.(( defined("_ADM_USE_RTL") && _ADM_USE_RTL )?'_rtl':'').'.css');
}


$profile_template = 'profile_tribes.html';
include_once 'header.php';

$xoTheme->addStylesheet(ICMS_LIBRARIES_URL.'/jquery/colorbox/colorbox.css');
$xoTheme->addStylesheet(ICMS_LIBRARIES_URL.'/jquery/colorbox/colorbox-custom.css');
if(ereg('msie', strtolower($_SERVER['HTTP_USER_AGENT']))) {$xoTheme->addStylesheet(ICMS_LIBRARIES_URL.'/jquery/colorbox/colorbox-custom-ie.css');}
$xoTheme->addScript(ICMS_LIBRARIES_URL.'/jquery/colorbox/colorbox.js');
$xoTheme->addScript(ICMS_LIBRARIES_URL.'/jquery/colorbox/lightbox.js');

$profile_tribes_handler = icms_getModuleHandler('tribes');

/** Use a naming convention that indicates the source of the content of the variable */
$clean_op = '';

if (isset($_GET['op'])) $clean_op = $_GET['op'];
if (isset($_POST['op'])) $clean_op = $_POST['op'];

/** Again, use a naming convention that indicates the source of the content of the variable */
global $icmsUser;
$clean_tribes_id = isset($_GET['tribes_id']) ? intval($_GET['tribes_id']) : 0 ;
$real_uid = is_object($icmsUser)?intval($icmsUser->uid()):0;
$clean_uid = isset($_GET['uid']) ? intval($_GET['uid']) : $real_uid ;
$tribesObj = $profile_tribes_handler->get($clean_tribes_id);

/** Create a whitelist of valid values, be sure to use appropriate types for each value
 * Be sure to include a value for no parameter, if you have a default condition
 */
$valid_op = array ('mod','addtribes','del','');
/**
 * Only proceed if the supplied operation is a valid operation
 */

$isAllowed = getAllowedItems('tribes', $clean_uid);
if (!$isAllowed) {
	redirect_header(icms_getPreviousPage('index.php'), 3, _NOPERM);
}

$xoopsTpl->assign('uid_owner',$uid);

if (in_array($clean_op,$valid_op,true)){
  switch ($clean_op) {
	case "mod":
		$tribesObj = $profile_tribes_handler->get($clean_tribes_id);
		if ($clean_tribes_id > 0 && $tribesObj->isNew()) {
			redirect_header(icms_getPreviousPage('index.php'), 3, _NOPERM);
		}
		edittribes($tribesObj);
		break;

	case "addtribes":
        if (!$xoopsSecurity->check()) {
        	redirect_header(icms_getPreviousPage('index.php'), 3, _MD_PROFILE_SECURITY_CHECK_FAILED . implode('<br />', $xoopsSecurity->getErrors()));
        }
         include_once ICMS_ROOT_PATH.'/kernel/icmspersistablecontroller.php';
        $controller = new IcmsPersistableController($profile_tribes_handler);
		$controller->storeFromDefaultForm(_MD_PROFILE_TRIBES_CREATED, _MD_PROFILE_TRIBES_MODIFIED);
		break;

	case "del":
		$tribesObj = $profile_tribes_handler->get($clean_tribes_id);
		if (!$tribesObj->userCanEditAndDelete()) {
			redirect_header($tribesObj->getItemLink(true), 3, _NOPERM);
		}
		if (isset($_POST['confirm'])) {
		    if (!$xoopsSecurity->check()) {
		    	redirect_header($impresscms->urls['previouspage'], 3, _MD_PROFILE_SECURITY_CHECK_FAILED . implode('<br />', $xoopsSecurity->getErrors()));
		    }
		}
  	    include_once ICMS_ROOT_PATH.'/kernel/icmspersistablecontroller.php';
        $controller = new IcmsPersistableController($profile_tribes_handler);
		$controller->handleObjectDeletionFromUserSide();
		$icmsTpl->assign('profile_category_path', $tribesObj->getVar('title') . ' > ' . _DELETE);

		break;

	default:
		if($real_uid){
			$tribesObj = $profile_tribes_handler->get($clean_tribes_id);
			edittribes($tribesObj, true);
		}
		if($clean_tribes_id > 0){
			$profile_tribes_handler->updateCounter($clean_tribes_id);
			$icmsTpl->assign('profile_single_tribe', $tribesObj->toArray());
			include_once ICMS_ROOT_PATH . '/include/comment_view.php';
		}elseif($clean_uid > 0){
			$tribesArray = $profile_tribes_handler->getTribes(false, false, $clean_uid);
			$icmsTpl->assign('profile_alltribes', $tribesArray);
		}elseif($real_uid > 0){
			$tribesArray = $profile_tribes_handler->getTribes(false, false, $real_uid);
			$icmsTpl->assign('profile_alltribes', $tribesArray);
		}else{
			redirect_header(PROFILE_URL);
		}


		/**
		 * Generating meta information for this page
		 */
		$icms_metagen = new IcmsMetagen($tribesObj->getVar('title'), $tribesObj->getVar('meta_keywords','n'), $tribesObj->getVar('meta_description', 'n'));
		$icms_metagen->createMetaTags();

		break;
	}
}
$icmsTpl->assign('profile_module_home', icms_getModuleName(true, true));

include_once 'footer.php';
?>