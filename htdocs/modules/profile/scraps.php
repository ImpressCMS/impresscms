<?php
/**
* Scraps page
*
* @copyright	GNU General Public License (GPL)
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.3
* @author		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
* @package		profile
* @version		$Id$
*/

/**
 * Edit a Scrap
 *
 * @param object $scrapsObj ProfileScrap object to be edited
*/
function editscraps($scrapsObj, $uid, $hideForm=false)
{
	global $profile_scraps_handler, $xoTheme, $icmsTpl, $icmsUser;

	$icmsTpl->assign('hideForm', $hideForm);
	if (!$scrapsObj->isNew()){
		if (!$scrapsObj->userCanEditAndDelete()) {
			redirect_header($scrapsObj->getItemLink(true), 3, _NOPERM);
		}
		$scrapsObj->hideFieldFromForm(array('scrap_from', 'meta_keywords', 'meta_description', 'short_url'));
		$sform = $scrapsObj->getSecureForm(_MD_PROFILE_SCRAPS_EDIT, 'addscraps');
		$sform->assign($icmsTpl, 'profile_scrapsform');
		$icmsTpl->assign('profile_category_path', icms_getLinkedUnameFromId($uid) . ' > ' . _EDIT);
	} else {
		if (!$profile_scraps_handler->userCanSubmit()) {
			redirect_header(PROFILE_URL, 3, _NOPERM);
		}
		$scrapsObj->setVar('scrap_from', $icmsUser->uid());
		$scrapsObj->setVar('scrap_to', $uid);
		$scrapsObj->setVar('creation_time', time());
		$scrapsObj->hideFieldFromForm(array('creation_time', 'scrap_from', 'meta_keywords', 'meta_description', 'short_url'));
		$sform = $scrapsObj->getSecureForm(_MD_PROFILE_SCRAPS_SUBMIT, 'addscraps');
		$sform->assign($icmsTpl, 'profile_scrapsform');
		$icmsTpl->assign('profile_category_path', _SUBMIT);
	}

	$xoTheme->addStylesheet(ICMS_URL . '/modules/profile/module'.(( defined("_ADM_USE_RTL") && _ADM_USE_RTL )?'_rtl':'').'.css');
}


$profile_template = 'profile_scraps.html';
include_once 'header.php';

$profile_scraps_handler = icms_getModuleHandler('scraps');

/** Use a naming convention that indicates the source of the content of the variable */
$clean_op = '';

if (isset($_GET['op'])) $clean_op = $_GET['op'];
if (isset($_POST['op'])) $clean_op = $_POST['op'];

/** Again, use a naming convention that indicates the source of the content of the variable */
global $icmsUser, $profile_isAdmin;
$clean_scraps_id = isset($_GET['scraps_id']) ? intval($_GET['scraps_id']) : 0 ;
$real_uid = is_object($icmsUser)?intval($icmsUser->uid()):0;
$clean_uid = isset($_GET['uid']) ? intval($_GET['uid']) : $real_uid ;
$scrapsObj = $profile_scraps_handler->get($clean_scraps_id);
/** Create a whitelist of valid values, be sure to use appropriate types for each value
 * Be sure to include a value for no parameter, if you have a default condition
 */
$valid_op = array ('mod','addscraps','del','');

$isAllowed = getAllowedItems('scraps', $clean_uid);
if (!$isAllowed) {
	redirect_header(icms_getPreviousPage('index.php'), 3, _NOPERM);
}

/**
 * Only proceed if the supplied operation is a valid operation
 */
if (in_array($clean_op,$valid_op,true)){
  switch ($clean_op) {
	case "mod":
		$scrapsObj = $profile_scraps_handler->get($clean_scraps_id);
		if ($clean_scraps_id > 0 && $scrapsObj->isNew()) {
			redirect_header(icms_getPreviousPage('index.php'), 3, _NOPERM);
		}
		editscraps($scrapsObj);
		break;

	case "addscraps":
        if (!$xoopsSecurity->check()) {
        	redirect_header(icms_getPreviousPage('index.php'), 3, _MD_PROFILE_SECURITY_CHECK_FAILED . implode('<br />', $xoopsSecurity->getErrors()));
        }
         include_once ICMS_ROOT_PATH.'/kernel/icmspersistablecontroller.php';
        $controller = new IcmsPersistableController($profile_scraps_handler);
		$controller->storeFromDefaultForm(_MD_PROFILE_SCRAPS_CREATED, _MD_PROFILE_SCRAPS_MODIFIED);
		break;

	case "del":
		$scrapsObj = $profile_scraps_handler->get($clean_scraps_id);
		if (!$scrapsObj->userCanEditAndDelete()) {
			redirect_header($scrapsObj->getItemLink(true), 3, _NOPERM);
		}
		if (isset($_POST['confirm'])) {
		    if (!$xoopsSecurity->check()) {
		    	redirect_header($impresscms->urls['previouspage'], 3, _MD_PROFILE_SECURITY_CHECK_FAILED . implode('<br />', $xoopsSecurity->getErrors()));
		    }
		}
  	    include_once ICMS_ROOT_PATH.'/kernel/icmspersistablecontroller.php';
        $controller = new IcmsPersistableController($profile_scraps_handler);
		$controller->handleObjectDeletionFromUserSide();
		$icmsTpl->assign('profile_category_path', $scrapsObj->getVar('scrap_text') . ' > ' . _DELETE);

		break;

	default:
		if($clean_scraps_id > 0 && $profile_isAdmin){
			$icmsTpl->assign('profile_single_scrap', $scrapsObj->toArray());
		}elseif($clean_uid > 0){
			$scrapsArray = $profile_scraps_handler->getScraps(false, false, $clean_uid);
			$icmsTpl->assign('profile_allscraps', $scrapsArray);
			if($real_uid){
				$scrapsObj = $profile_scraps_handler->get($clean_scraps_id);
				editscraps($scrapsObj, $clean_uid, true);
			}
		}elseif($real_uid > 0){
			$scrapsArray = $profile_scraps_handler->getScraps(false, false, $real_uid);
			$icmsTpl->assign('profile_allscraps', $scrapsArray);
			editscraps($scrapsObj, $real_uid, true);
		}else{
			redirect_header(PROFILE_URL);
		}


		/**
		 * Generating meta information for this page
		 */
		$icms_metagen = new IcmsMetagen($scrapsObj->getVar('scrap_text'), $scrapsObj->getVar('meta_keywords','n'), $scrapsObj->getVar('meta_description', 'n'));
		$icms_metagen->createMetaTags();

		break;
	}
}
$icmsTpl->assign('profile_module_home', icms_getModuleName(true, true));

include_once 'footer.php';
?>