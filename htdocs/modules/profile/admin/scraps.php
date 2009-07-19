<?php
/**
* Admin page to manage scrapss
*
* List, add, edit and delete scraps objects
*
* @copyright	GNU General Public License (GPL)
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		Jan Pedersen, Marcello Brandao, Sina Asghari, Gustavo Pilla <contact@impresscms.org>
* @package		profile
* @version		$Id$
*/

/**
 * Edit a Scraps
 *
 * @param int $scraps_id Scrapsid to be edited
*/
function editscraps($scraps_id = 0)
{
	global $profile_scraps_handler, $icmsModule, $icmsAdminTpl;

	$scrapsObj = $profile_scraps_handler->get($scraps_id);

	if (!$scrapsObj->isNew()){
		$icmsModule->displayAdminMenu(0, _AM_PROFILE_SCRAPS . " > " . _CO_ICMS_EDITING);
		$sform = $scrapsObj->getForm(_AM_PROFILE_SCRAPS_EDIT, 'addscraps');
		$sform->assign($icmsAdminTpl);

	} else {
		$icmsModule->displayAdminMenu(0, _AM_PROFILE_SCRAPS . " > " . _CO_ICMS_CREATINGNEW);
		$sform = $scrapsObj->getForm(_AM_PROFILE_SCRAPS_CREATE, 'addscraps');
		$sform->assign($icmsAdminTpl);

	}
	$icmsAdminTpl->display('db:profile_admin_scraps.html');
}

include_once("admin_header.php");

$profile_scraps_handler = icms_getModuleHandler('scraps');
/** Use a naming convention that indicates the source of the content of the variable */
$clean_op = '';
/** Create a whitelist of valid values, be sure to use appropriate types for each value
 * Be sure to include a value for no parameter, if you have a default condition
 */
$valid_op = array ('mod','changedField','addscraps','del','view','');

if (isset($_GET['op'])) $clean_op = htmlentities($_GET['op']);
if (isset($_POST['op'])) $clean_op = htmlentities($_POST['op']);

/** Again, use a naming convention that indicates the source of the content of the variable */
$clean_scraps_id = isset($_GET['scraps_id']) ? (int) $_GET['scraps_id'] : 0 ;

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

  		editscraps($clean_scraps_id);
  		break;
  	case "addscraps":
          include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";
          $controller = new IcmsPersistableController($profile_scraps_handler);
  		$controller->storeFromDefaultForm(_AM_PROFILE_SCRAPS_CREATED, _AM_PROFILE_SCRAPS_MODIFIED);

  		break;

  	case "del":
  	    include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";
          $controller = new IcmsPersistableController($profile_scraps_handler);
  		$controller->handleObjectDeletion();

  		break;

  	case "view" :
  		$scrapsObj = $profile_scraps_handler->get($clean_scraps_id);

  		icms_cp_header();
  		smart_adminMenu(1, _AM_PROFILE_SCRAPS_VIEW . ' > ' . $scrapsObj->getVar('scraps_name'));

  		smart_collapsableBar('scrapsview', $scrapsObj->getVar('scraps_name') . $scrapsObj->getEditScrapsLink(), _AM_PROFILE_SCRAPS_VIEW_DSC);

  		$scrapsObj->displaySingleObject();

  		smart_close_collapsable('scrapsview');

  		break;

  	default:

  		icms_cp_header();

  		$icmsModule->displayAdminMenu(0, _AM_PROFILE_SCRAPS);

  		include_once ICMS_ROOT_PATH."/kernel/icmspersistabletable.php";
  		$objectTable = new IcmsPersistableTable($profile_scraps_handler);
  		$objectTable->addColumn(new IcmsPersistableColumn(''));

  		$icmsAdminTpl->assign('profile_scraps_table', $objectTable->fetch());
  		$icmsAdminTpl->display('db:profile_admin_scraps.html');
  		break;
  }
  icms_cp_footer();
}
/**
 * If you want to have a specific action taken because the user input was invalid,
 * place it at this point. Otherwise, a blank page will be displayed
 */
?>