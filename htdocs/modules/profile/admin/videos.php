<?php
/**
* Admin page to manage videoss
*
* List, add, edit and delete videos objects
*
* @copyright	GNU General Public License (GPL)
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		Jan Pedersen, Marcello Brandao, Sina Asghari, Gustavo Pilla <contact@impresscms.org>
* @package		profile
* @version		$Id$
*/

/**
 * Edit a Videos
 *
 * @param int $videos_id Videosid to be edited
*/
function editvideos($videos_id = 0)
{
	global $profile_videos_handler, $icmsModule, $icmsAdminTpl;

	$videosObj = $profile_videos_handler->get($videos_id);

	if (!$videosObj->isNew()){
		$icmsModule->displayAdminMenu(0, _AM_PROFILE_VIDEOS . " > " . _CO_ICMS_EDITING);
		$sform = $videosObj->getForm(_AM_PROFILE_VIDEOS_EDIT, 'addvideos');
		$sform->assign($icmsAdminTpl);

	} else {
		$icmsModule->displayAdminMenu(0, _AM_PROFILE_VIDEOS . " > " . _CO_ICMS_CREATINGNEW);
		$sform = $videosObj->getForm(_AM_PROFILE_VIDEOS_CREATE, 'addvideos');
		$sform->assign($icmsAdminTpl);

	}
	$icmsAdminTpl->display('db:profile_admin_videos.html');
}

include_once("admin_header.php");

$profile_videos_handler = icms_getModuleHandler('videos');
/** Use a naming convention that indicates the source of the content of the variable */
$clean_op = '';
/** Create a whitelist of valid values, be sure to use appropriate types for each value
 * Be sure to include a value for no parameter, if you have a default condition
 */
$valid_op = array ('mod','changedField','addvideos','del','view','');

if (isset($_GET['op'])) $clean_op = htmlentities($_GET['op']);
if (isset($_POST['op'])) $clean_op = htmlentities($_POST['op']);

/** Again, use a naming convention that indicates the source of the content of the variable */
$clean_videos_id = isset($_GET['videos_id']) ? (int) $_GET['videos_id'] : 0 ;

/**
 * in_array() is a native PHP function that will determine if the value of the
 * first argument is found in the array listed in the second argument. Strings
 * are case sensitive and the 3rd argument determines whether type matching is
 * required
*/
if (in_array($clean_op,$valid_op,true)){
  switch ($clean_op) {
  	case "mod":
  	case "changedField":

  		icms_cp_header();

  		editvideos($clean_videos_id);
  		break;
  	case "addvideos":
          include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";
          $controller = new IcmsPersistableController($profile_videos_handler);
  		$controller->storeFromDefaultForm(_AM_PROFILE_VIDEOS_CREATED, _AM_PROFILE_VIDEOS_MODIFIED);

  		break;

  	case "del":
  	    include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";
          $controller = new IcmsPersistableController($profile_videos_handler);
  		$controller->handleObjectDeletion();

  		break;

  	case "view" :
  		$videosObj = $profile_videos_handler->get($clean_videos_id);

  		icms_cp_header();
  		smart_adminMenu(1, _AM_PROFILE_VIDEOS_VIEW . ' > ' . $videosObj->getVar('videos_name'));

  		smart_collapsableBar('videosview', $videosObj->getVar('videos_name') . $videosObj->getEditVideosLink(), _AM_PROFILE_VIDEOS_VIEW_DSC);

  		$videosObj->displaySingleObject();

  		smart_close_collapsable('videosview');

  		break;

  	default:

  		icms_cp_header();

  		$icmsModule->displayAdminMenu(0, _AM_PROFILE_VIDEOS);

  		include_once ICMS_ROOT_PATH."/kernel/icmspersistabletable.php";
  		$objectTable = new IcmsPersistableTable($profile_videos_handler);
  		$objectTable->addColumn(new IcmsPersistableColumn(''));

  		$icmsAdminTpl->assign('profile_videos_table', $objectTable->fetch());
  		$icmsAdminTpl->display('db:profile_admin_videos.html');
  		break;
  }
  icms_cp_footer();
}
/**
 * If you want to have a specific action taken because the user input was invalid,
 * place it at this point. Otherwise, a blank page will be displayed
 */
?>