<?php
/**
* Admin page to manage suspensionss
*
* List, add, edit and delete suspensions objects
*
* @copyright	GNU General Public License (GPL)
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		Jan Pedersen, Marcello Brandao, Sina Asghari, Gustavo Pilla <contact@impresscms.org>
* @package		profile
* @version		$Id$
*/

/**
 * Edit a Suspensions
 *
 * @param int $suspensions_id Suspensionsid to be edited
*/
function editsuspensions($suspensions_id = 0)
{
	global $profile_suspensions_handler, $icmsModule, $icmsAdminTpl;

	$suspensionsObj = $profile_suspensions_handler->get($suspensions_id);

	if (!$suspensionsObj->isNew()){
		$icmsModule->displayAdminMenu(0, _AM_PROFILE_SUSPENSIONS . " > " . _CO_ICMS_EDITING);
		$sform = $suspensionsObj->getForm(_AM_PROFILE_SUSPENSIONS_EDIT, 'addsuspensions');
		$sform->assign($icmsAdminTpl);

	} else {
		$icmsModule->displayAdminMenu(0, _AM_PROFILE_SUSPENSIONS . " > " . _CO_ICMS_CREATINGNEW);
		$sform = $suspensionsObj->getForm(_AM_PROFILE_SUSPENSIONS_CREATE, 'addsuspensions');
		$sform->assign($icmsAdminTpl);

	}
	$icmsAdminTpl->display('db:profile_admin_suspensions.html');
}

include_once("admin_header.php");

$profile_suspensions_handler = icms_getModuleHandler('suspensions');
/** Use a naming convention that indicates the source of the content of the variable */
$clean_op = '';
/** Create a whitelist of valid values, be sure to use appropriate types for each value
 * Be sure to include a value for no parameter, if you have a default condition
 */
$valid_op = array ('mod','changedField','addsuspensions','del','view','');

if (isset($_GET['op'])) $clean_op = htmlentities($_GET['op']);
if (isset($_POST['op'])) $clean_op = htmlentities($_POST['op']);

/** Again, use a naming convention that indicates the source of the content of the variable */
$clean_suspensions_id = isset($_GET['suspensions_id']) ? (int) $_GET['suspensions_id'] : 0 ;

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

  		editsuspensions($clean_suspensions_id);
  		break;
  	case "addsuspensions":
          include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";
          $controller = new IcmsPersistableController($profile_suspensions_handler);
  		$controller->storeFromDefaultForm(_AM_PROFILE_SUSPENSIONS_CREATED, _AM_PROFILE_SUSPENSIONS_MODIFIED);

  		break;

  	case "del":
  	    include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";
          $controller = new IcmsPersistableController($profile_suspensions_handler);
  		$controller->handleObjectDeletion();

  		break;

  	case "view" :
  		$suspensionsObj = $profile_suspensions_handler->get($clean_suspensions_id);

  		icms_cp_header();
  		smart_adminMenu(1, _AM_PROFILE_SUSPENSIONS_VIEW . ' > ' . $suspensionsObj->getVar('suspensions_name'));

  		smart_collapsableBar('suspensionsview', $suspensionsObj->getVar('suspensions_name') . $suspensionsObj->getEditSuspensionsLink(), _AM_PROFILE_SUSPENSIONS_VIEW_DSC);

  		$suspensionsObj->displaySingleObject();

  		smart_close_collapsable('suspensionsview');

  		break;

  	default:

  		icms_cp_header();

  		$icmsModule->displayAdminMenu(0, _AM_PROFILE_SUSPENSIONS);

  		include_once ICMS_ROOT_PATH."/kernel/icmspersistabletable.php";
  		$objectTable = new IcmsPersistableTable($profile_suspensions_handler);
  		$objectTable->addColumn(new IcmsPersistableColumn(''));

  		$objectTable->addIntroButton('addsuspensions', 'suspensions.php?op=mod', _AM_PROFILE_SUSPENSIONS_CREATE);
  		$icmsAdminTpl->assign('profile_suspensions_table', $objectTable->fetch());
  		$icmsAdminTpl->display('db:profile_admin_suspensions.html');
  		break;
  }
  icms_cp_footer();
}
/**
 * If you want to have a specific action taken because the user input was invalid,
 * place it at this point. Otherwise, a blank page will be displayed
 */
?>