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
 * Edit a Tribeuser
 *
 * @param object $tribeuserObj ProfileTribeuser object to be edited
*/
function edittribeuser($tribesObj, $hideForm=false) {
	global $profile_tribeuser_handler, $icmsTpl, $icmsUser, $real_uid;

	// general check
	if (!$profile_tribeuser_handler->userCanSubmit()) return;

	$tribeuserObj = $profile_tribeuser_handler->get(0);
	// check tribe security level
	if ($tribesObj->getVar('security') == PROFILE_TRIBES_SECURITY_EVERYBODY || $tribesObj->getVar('security') == PROFILE_TRIBES_SECURITY_APPROVAL) {
		// don't show the form if the current user is the owner of this tribe
		if ($real_uid == $tribesObj->getVar('uid_owner')) return;
		// don't show the form if the user is already a member of this tribe
		$tribeuser_id = $profile_tribeuser_handler->getTribeuserId($tribesObj->getVar('tribes_id'), $icmsUser->getVar('uid'));
		if ($tribeuser_id > 0) return;
		$tribeuserObj->hideFieldFromForm('user_id');
		if ($tribesObj->getVar('security') == PROFILE_TRIBES_SECURITY_APPROVAL) {
			$tribeuserObj->setVar('approved', 0);
		}
	} elseif ($tribesObj->getVar('security') == PROFILE_TRIBES_SECURITY_INVITATION) {
		// don't show the form if the user isn't the owner of the tribe
		if ($real_uid != $tribesObj->getVar('uid_owner')) return;
		$tribeuserObj->setVar('accepted', 0);
	} else {
		return;
	}

	$tribeuserObj->hideFieldFromForm('tribe_id');
	$tribeuserObj->hideFieldFromForm('approved');
	$tribeuserObj->hideFieldFromForm('accepted');

	$tribeuserObj->setVar('tribe_id', $tribesObj->getVar('tribes_id'));
	$tribeuserObj->setVar('user_id', $icmsUser->getVar('uid'));

	$sform = $tribeuserObj->getSecureForm($real_uid == $tribesObj->getVar('uid_owner') ? _MD_PROFILE_TRIBEUSER_SUBMIT : _MD_PROFILE_TRIBEUSER_JOIN, 'addtribeuser');
	$sform->assign($icmsTpl, 'profile_tribeuserform');
	$icmsTpl->assign('hideForm', $hideForm);
}

/**
 * Edit a Tribe
 *
 * @param object $tribesObj ProfileTribe object to be edited
*/
function edittribes($tribesObj, $hideForm=false) {
	global $profile_tribes_handler, $icmsTpl, $icmsUser;

	$icmsTpl->assign('hideForm', $hideForm);
	if (!$tribesObj->isNew()){
		if (!$tribesObj->userCanEditAndDelete()) {
			redirect_header($tribesObj->getItemLink(true), 3, _NOPERM);
		}
		$tribesObj->hideFieldFromForm(array('creation_time', 'uid_owner', 'meta_keywords', 'meta_description', 'short_url'));
		$sform = $tribesObj->getSecureForm(_MD_PROFILE_TRIBES_EDIT, 'addtribes');
		$sform->assign($icmsTpl, 'profile_tribesform');
	} else {
		if (!$profile_tribes_handler->userCanSubmit()) {
			redirect_header(PROFILE_URL, 3, _NOPERM);
		}
		$tribesObj->setVar('uid_owner', $icmsUser->uid());
		$tribesObj->setVar('creation_time', time());
		$tribesObj->hideFieldFromForm(array('creation_time', 'uid_owner', 'meta_keywords', 'meta_description', 'short_url'));
		$sform = $tribesObj->getSecureForm(_MD_PROFILE_TRIBES_SUBMIT, 'addtribes');
		$sform->assign($icmsTpl, 'profile_tribesform');
	}
}


$profile_template = 'profile_tribes.html';
include_once 'header.php';

$profile_tribes_handler = icms_getModuleHandler('tribes');
$profile_tribeuser_handler = icms_getModuleHandler('tribeuser');

/** Use a naming convention that indicates the source of the content of the variable */
$clean_op = '';

if (isset($_GET['op'])) $clean_op = $_GET['op'];
if (isset($_POST['op'])) $clean_op = $_POST['op'];

/** Again, use a naming convention that indicates the source of the content of the variable */
global $icmsUser;
$clean_tribes_id = isset($_GET['tribes_id']) ? intval($_GET['tribes_id']) : 0 ;
$real_uid = is_object($icmsUser) ? intval($icmsUser->uid()) : 0;
$clean_uid = isset($_GET['uid']) ? intval($_GET['uid']) : $real_uid ;
$tribesObj = $profile_tribes_handler->get($clean_tribes_id);
$userCanEditAndDelete = $real_uid && (($clean_tribes_id && $real_uid == $tribesObj->getVar('uid_owner')) || (!$clean_tribes_id && $real_uid == $uid));

/** Create a whitelist of valid values, be sure to use appropriate types for each value
 * Be sure to include a value for no parameter, if you have a default condition
 */
$valid_op = array ('mod','addtribeuser','edittribeuser','deltribeuser','addtribes','del','');

$isAllowed = getAllowedItems('tribes', $clean_uid);
if (!$isAllowed) {
	redirect_header(icms_getPreviousPage('index.php'), 3, _NOPERM);
	exit();
}

$xoopsTpl->assign('uid_owner', $uid);

/** Only proceed if the supplied operation is a valid operation */
if (in_array($clean_op,$valid_op,true)){
	switch ($clean_op) {
		case "mod":
			if ($clean_tribes_id > 0 && $tribesObj->isNew()) {
				redirect_header(icms_getPreviousPage('index.php'), 3, _NOPERM);
				exit();
			}
			edittribes($tribesObj);
			break;

		case "addtribeuser":
			if (!$xoopsSecurity->check()) {
				redirect_header(icms_getPreviousPage('index.php'), 3, _MD_PROFILE_SECURITY_CHECK_FAILED . implode('<br />', $xoopsSecurity->getErrors()));
				exit();
			}
			include_once ICMS_ROOT_PATH.'/kernel/icmspersistablecontroller.php';
			$controller = new IcmsPersistableController($profile_tribeuser_handler);
			$controller->storeFromDefaultForm(_MD_PROFILE_TRIBEUSER_CREATED, _MD_PROFILE_TRIBEUSER_MODIFIED);
			break;

		case "edittribeuser":
			if (!$xoopsSecurity->check()) {
				redirect_header(icms_getPreviousPage('index.php'), 3, _MD_PROFILE_SECURITY_CHECK_FAILED . implode('<br />', $xoopsSecurity->getErrors()));
				exit();
			}
			if ($tribesObj->isNew()) {
				redirect_header(icms_getPreviousPage('index.php'), 3, _NOPERM);
				exit();
			}
			$clean_tribeuser_id = isset($_POST['tribeuser_id']) ? intval($_POST['tribeuser_id']) : 0;
			$tribeuserObj = $profile_tribeuser_handler->get($clean_tribeuser_id);
			if ($tribeuserObj->isNew()) {
				redirect_header(icms_getPreviousPage('index.php'), 3, _MD_PROFILE_TRIBEUSER_NOTFOUND);
				exit();
			}
			$store = isset($_POST['store']) ? intval($_POST['store']) : 0;
			$clean_action = isset($_POST['action']) ? $_POST['action'] : '';
			$valid_action = array ('approved', 'accepted');
			if (in_array($clean_action, $valid_action, true)) {
				if (($clean_action == 'approved' && $real_uid != $tribesObj->getVar('uid_owner')) || ($clean_action == 'accepted' && $real_uid != $tribeuserObj->getVar('user_id'))) {
					redirect_header(icms_getPreviousPage('index.php'), 3, _NOPERM);
					exit();
				}
				if ($store == 1) {
					$tribeuserObj->setVar($clean_action, 1);
					$profile_tribeuser_handler->insert($tribeuserObj);
				} else {
					// delete this tribeuser object
					$profile_tribeuser_handler->delete($tribeuserObj);
				}
				redirect_header(icms_getPreviousPage('index.php'), 3, _MD_PROFILE_TRIBEUSER_OP_SUCCESS);
			} else {
				redirect_header(icms_getPreviousPage('index.php'), 3, _NOPERM);
				exit();
			}
			break;

		case "deltribeuser":
			$clean_tribeuser_id = isset($_POST['tribeuser_id']) ? intval($_POST['tribeuser_id']) : 0;
			$tribeuserObj = $profile_tribeuser_handler->get($clean_tribeuser_id);
			if (!$tribeuserObj->userCanEditAndDelete() && !$userCanEditAndDelete) {
				redirect_header(icms_getPreviousPage('index.php'), 3, _NOPERM);
				exit();
			}
			if (!$xoopsSecurity->check()) {
				redirect_header(icms_getPreviousPage('index.php'), 3, _MD_PROFILE_SECURITY_CHECK_FAILED . implode('<br />', $xoopsSecurity->getErrors()));
			}
			include_once ICMS_ROOT_PATH.'/kernel/icmspersistablecontroller.php';
			$controller = new IcmsPersistableController($profile_tribeuser_handler);
			$controller->handleObjectDeletionFromUserSide();
			break;

		case "addtribes":
			if (!$xoopsSecurity->check()) {
				redirect_header(icms_getPreviousPage('index.php'), 3, _MD_PROFILE_SECURITY_CHECK_FAILED . implode('<br />', $xoopsSecurity->getErrors()));
				exit();
			}
			include_once ICMS_ROOT_PATH.'/kernel/icmspersistablecontroller.php';
			$controller = new IcmsPersistableController($profile_tribes_handler);
			$controller->storeFromDefaultForm(_MD_PROFILE_TRIBES_CREATED, _MD_PROFILE_TRIBES_MODIFIED);
			break;

		case "del":
			if (!$tribesObj->userCanEditAndDelete()) {
				redirect_header($tribesObj->getItemLink(true), 3, _NOPERM);
				exit();
			}
			if (isset($_POST['confirm'])) {
			    if (!$xoopsSecurity->check()) {
				redirect_header(icms_getPreviousPage('index.php'), 3, _MD_PROFILE_SECURITY_CHECK_FAILED . implode('<br />', $xoopsSecurity->getErrors()));
				exit();
			    }
			}
			include_once ICMS_ROOT_PATH.'/kernel/icmspersistablecontroller.php';
			$controller = new IcmsPersistableController($profile_tribes_handler);
			$controller->handleObjectDeletionFromUserSide();
			break;

		default:
			if ($userCanEditAndDelete) edittribes($tribesObj, true);
			if ($clean_tribes_id > 0) {
				if ($tribesObj->isNew()) {
					redirect_header(icms_getPreviousPage('index.php'), 3, _MD_PROFILE_TRIBES_NOTFOUND);
					exit();
				}
				edittribeuser($tribesObj, true);
				$profile_tribes_handler->updateCounter($clean_tribes_id);
				$icmsTpl->assign('profile_tribe', $tribesObj->toArray());
				$profile_tribeuser_handler = icms_getModuleHandler('tribeuser');
				$icmsTpl->assign('profile_tribe_members', $profile_tribeuser_handler->getTribeusers(0, 0, false, false, $clean_tribes_id, '=', 1, 1));
				$icmsTpl->assign('userCanEditAndDelete', $userCanEditAndDelete);
				$icmsTpl->assign('delete_image', ICMS_IMAGES_SET_URL."/actions/editdelete.png");
				if (is_object($icmsUser)) {
					$tribeusers = array();
					if ($tribesObj->getVar('security') == PROFILE_TRIBES_SECURITY_EVERYBODY) {
						$tribeusers = $profile_tribeuser_handler->getTribeusers(0, 1, $icmsUser->getVar('uid'), false, $clean_tribes_id);
					} elseif ($tribesObj->getVar('security') == PROFILE_TRIBES_SECURITY_APPROVAL) {
						$tribeusers = $profile_tribeuser_handler->getTribeusers(0, 1, $icmsUser->getVar('uid'), false, $clean_tribes_id, '=', 1);
					} elseif ($tribesObj->getVar('security') == PROFILE_TRIBES_SECURITY_INVITATION) {
						$tribeusers = $profile_tribeuser_handler->getTribeusers(0, 1, $icmsUser->getVar('uid'), false, $clean_tribes_id, '=', false, 1);
					}
					if (count($tribeusers) == 1 || $profile_isAdmin || $real_uid == $tribesObj->getVar('uid_owner')) $icmsTpl->assign('showContent', true);
				}

				icms_makeSmarty(array(
					'lang_members'       => _MD_PROFILE_TRIBES_MEMBERS,
					'lang_discussions'   => _MD_PROFILE_TRIBES_DISCUSSIONS,
					'lang_statistics'    => _MD_PROFILE_TRIBES_STATISTICS,
					'lang_creation_time' => _MD_PROFILE_TRIBES_CREATION_TIME,
					'lang_clicks'        => _MD_PROFILE_TRIBES_CLICKS,
					'lang_owner'         => _MD_PROFILE_TRIBES_OWNER,
					'lang_delete'        => _DELETE
				));
			} elseif ($clean_uid > 0) {
				$tribes = array();
				$tribes['own'] = $profile_tribes_handler->getTribes(false, false, $clean_uid);
				$tribes['member'] = $profile_tribes_handler->getMembershipTribes($clean_uid);
				$icmsTpl->assign('profile_tribes', $tribes);
			} elseif ($real_uid > 0) {
				$tribes = array();
				$tribes['own'] = $profile_tribes_handler->getTribes(false, false, $real_uid);
				$tribes['member'] = $profile_tribes_handler->getMembershipTribes($real_uid);
				$icmsTpl->assign('profile_tribes', $tribes);
			} else {
				redirect_header(PROFILE_URL);
			}

			icms_makeSmarty(array(
				'lang_tribes_own'        => sprintf(_MD_PROFILE_TRIBES_OWN),
				'lang_tribes_membership' => sprintf(_MD_PROFILE_TRIBES_MEMBERSHIPS)
			));

			/**
			 * Generating meta information for this page
			 */
			$icms_metagen = new IcmsMetagen($tribesObj->getVar('title'), $tribesObj->getVar('meta_keywords','n'), $tribesObj->getVar('meta_description', 'n'));
			$icms_metagen->createMetaTags();

			break;
	}
}
$icmsTpl->assign('profile_category_path', _MD_PROFILE_TRIBES);

include_once 'footer.php';
?>