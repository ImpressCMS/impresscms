<?php
/**
* Admin page to manage friendpetitions
*
* List, add, edit and delete friendpetition objects
*
* @copyright	GNU General Public License (GPL)
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		Jan Pedersen, Marcello Brandao, Sina Asghari, Gustavo Pilla <contact@impresscms.org>
* @package		profile
* @version		$Id$
*/

/**
 * Edit a Friendpetition
 *
 * @param int $friendpetition_id Friendpetitionid to be edited
*/
function editfriendpetition($friendpetition_id = 0)
{
	global $profile_friendpetition_handler, $icmsModule, $icmsAdminTpl;

	$friendpetitionObj = $profile_friendpetition_handler->get($friendpetition_id);

	if (!$friendpetitionObj->isNew()){
		$icmsModule->displayAdminMenu(0, _AM_PROFILE_FRIENDPETITIONS . " > " . _CO_ICMS_EDITING);
		$sform = $friendpetitionObj->getForm(_AM_PROFILE_FRIENDPETITION_EDIT, 'addfriendpetition');
		$sform->assign($icmsAdminTpl);

	} else {
		$icmsModule->displayAdminMenu(0, _AM_PROFILE_FRIENDPETITIONS . " > " . _CO_ICMS_CREATINGNEW);
		$sform = $friendpetitionObj->getForm(_AM_PROFILE_FRIENDPETITION_CREATE, 'addfriendpetition');
		$sform->assign($icmsAdminTpl);

	}
	$icmsAdminTpl->display('db:profile_admin_friendpetition.html');
}

include_once("admin_header.php");

$profile_friendpetition_handler = icms_getModuleHandler('friendpetition');
/** Use a naming convention that indicates the source of the content of the variable */
$clean_op = '';
/** Create a whitelist of valid values, be sure to use appropriate types for each value
 * Be sure to include a value for no parameter, if you have a default condition
 */
$valid_op = array ('mod','changedField','addfriendpetition','del','view','');

if (isset($_GET['op'])) $clean_op = htmlentities($_GET['op']);
if (isset($_POST['op'])) $clean_op = htmlentities($_POST['op']);

/** Again, use a naming convention that indicates the source of the content of the variable */
$clean_friendpetition_id = isset($_GET['friendpetition_id']) ? (int) $_GET['friendpetition_id'] : 0 ;

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

  		editfriendpetition($clean_friendpetition_id);
  		break;
  	case "addfriendpetition":
          include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";
          $controller = new IcmsPersistableController($profile_friendpetition_handler);
  		$controller->storeFromDefaultForm(_AM_PROFILE_FRIENDPETITION_CREATED, _AM_PROFILE_FRIENDPETITION_MODIFIED);

  		break;

  	case "del":
  	    include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";
          $controller = new IcmsPersistableController($profile_friendpetition_handler);
  		$controller->handleObjectDeletion();

  		break;

  	case "view" :
  		$friendpetitionObj = $profile_friendpetition_handler->get($clean_friendpetition_id);

  		icms_cp_header();
  		smart_adminMenu(1, _AM_PROFILE_FRIENDPETITION_VIEW . ' > ' . $friendpetitionObj->getVar('friendpetition_name'));

  		smart_collapsableBar('friendpetitionview', $friendpetitionObj->getVar('friendpetition_name') . $friendpetitionObj->getEditFriendpetitionLink(), _AM_PROFILE_FRIENDPETITION_VIEW_DSC);

  		$friendpetitionObj->displaySingleObject();

  		smart_close_collapsable('friendpetitionview');

  		break;

  	default:

  		icms_cp_header();

  		$icmsModule->displayAdminMenu(0, _AM_PROFILE_FRIENDPETITIONS);

  		include_once ICMS_ROOT_PATH."/kernel/icmspersistabletable.php";
  		$objectTable = new IcmsPersistableTable($profile_friendpetition_handler);
  		$objectTable->addColumn(new IcmsPersistableColumn(''));

  		$icmsAdminTpl->assign('profile_friendpetition_table', $objectTable->fetch());
  		$icmsAdminTpl->display('db:profile_admin_friendpetition.html');
  		break;
  }
  icms_cp_footer();
}
/**
 * If you want to have a specific action taken because the user input was invalid,
 * place it at this point. Otherwise, a blank page will be displayed
 */
?>