<?php
/**
 * ImpressCMS Ratings
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		System
 * @subpackage	Ratings
 * @since		1.2
 * @todo		Complete this feature? You cannot add a rating and there are no ratings to modify
 * @version		SVN: $Id$
 */

/* set get and post filters before including admin_header, if not strings */
$filter_get = array('rating_id' => 'int');

$filter_post = array('rating_id' => 'int');

/* set default values for variables, $op and $fct are handled in the header */
$rating_id = 0;
$changedField = "";

/** common header for the admin functions */
include "admin_header.php";

function editrating($showmenu = FALSE, $ratingid = 0) {
	global $icms_admin_handler, $icmsAdminTpl, $op, $changedField;

	icms_cp_header();

	$ratingObj = $icms_admin_handler->get($ratingid);

	if (!$ratingObj->isNew()) {

		$sform = $ratingObj->getForm(_CO_ICMS_RATINGS_EDIT, 'addrating');

		$sform->assign($icmsAdminTpl);
		$icmsAdminTpl->assign('icms_rating_title', _CO_ICMS_RATINGS_EDIT_INFO);
		$icmsAdminTpl->display('db:admin/rating/system_adm_rating.html');
	} else {
		$ratingObj->hideFieldFromForm(array('item', 'itemid', 'uid', 'date', 'rate'));

		if (isset($op)) {
			$controller = new icms_ipf_Controller($icms_admin_handler);
			$controller->postDataToObject($ratingObj);

			if ($op == 'changedField') {
				switch($changedField) {
					case 'dirname' :
						$ratingObj->showFieldOnForm(array('item', 'itemid', 'uid', 'date', 'rate'));
						break;
				}
			}
		}

		$sform = $ratingObj->getForm(_CO_ICMS_RATINGS_CREATE, 'addrating');
		$sform->assign($icmsAdminTpl);

		$icmsAdminTpl->assign('icms_rating_title', _CO_ICMS_RATINGS_CREATE_INFO);
		$icmsAdminTpl->display('db:admin/rating/system_adm_rating.html');
	}
}

switch ($op) {
	/*	case "mod":
	 case "changedField";

		$ratingid = isset($_GET['ratingid']) ? (int) ($_GET['ratingid']) : 0 ;

		editrating(true, $ratingid);

		break;

		case "clone":

		$ratingid = isset($_GET['ratingid']) ? (int) ($_GET['ratingid']) : 0 ;

		editrating(true, $ratingid, true);
		break;

		case "addrating":
		$controller = new icms_ipf_Controller($icms_admin_handler);
		$controller->storeFromDefaultForm(_CO_ICMS_RATINGS_CREATED, _CO_ICMS_RATINGS_MODIFIED, ICMS_URL . '/modules/system/admin.php?fct=rating');
		break;
		*/
	case "del":
		$controller = new icms_ipf_Controller($icms_admin_handler);
		$controller->handleObjectDeletion();

		break;

	default:

		icms_cp_header();

		$objectTable = new icms_ipf_view_Table($icms_admin_handler, false, array('delete'));
		$objectTable->addColumn(new icms_ipf_view_Column('name', _GLOBAL_LEFT, false, 'getUnameValue'));
		$objectTable->addColumn(new icms_ipf_view_Column('dirname', _GLOBAL_LEFT));
		$objectTable->addColumn(new icms_ipf_view_Column('item', _GLOBAL_LEFT, false, 'getItemValue'));
		$objectTable->addColumn(new icms_ipf_view_Column('date', 'center', 150));
		$objectTable->addColumn(new icms_ipf_view_Column('rate', 'center', 60, 'getRateValue'));
		//$objectTable->addIntroButton('addrating', 'admin.php?fct=rating&op=mod', _CO_ICMS_RATINGS_CREATE);

		//$objectTable->addQuickSearch(array('title', 'summary', 'description'));

		$icmsAdminTpl->assign('icms_rating_table', $objectTable->fetch());

		$icmsAdminTpl->assign('icms_rating_explain', TRUE);
		$icmsAdminTpl->assign('icms_rating_title', _CO_ICMS_RATINGS_DSC);

		$icmsAdminTpl->display('db:admin/rating/system_adm_rating.html');

		break;
}

icms_cp_footer();

