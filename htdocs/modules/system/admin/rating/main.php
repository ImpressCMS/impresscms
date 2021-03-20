<?php
/**
 * ImpressCMS Ratings
 *
 * @copyright The ImpressCMS Project http://www.impresscms.org/
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package System
 * @subpackage Ratings
 * @since 1.2
 * @todo Complete this feature? You cannot add a rating and there are no ratings to modify
 * @version SVN: $Id: main.php 11147 2011-03-30 14:12:26Z m0nty_ $
 */
if (!is_object(icms::$user) || !is_object($icmsModule) || !icms::$user->isAdmin($icmsModule->getVar('mid'))) {
	exit("Access Denied");
}

function editrating($showmenu = FALSE, $ratingid = 0) {
	global $icms_rating_handler, $icmsAdminTpl, $op, $changedField;

	icms_cp_header();

	$ratingObj = $icms_rating_handler->get($ratingid);

	if (!$ratingObj->isNew()) {

		$sform = $ratingObj->getForm(_CO_ICMS_RATINGS_EDIT, 'addrating');

		$sform->assign($icmsAdminTpl);
		$icmsAdminTpl->assign('icms_rating_title', _CO_ICMS_RATINGS_EDIT_INFO);
		$icmsAdminTpl->display('db:system_adm_rating.html');
	} else {
		$ratingObj->hideFieldFromForm(array('item', 'itemid', 'uid', 'date', 'rate'));

		$controller = new icms_ipf_Controller($icms_rating_handler);
		$controller->postDataToObject($ratingObj);

		if ($op == 'changedField') {
			switch ($changedField) {
				case 'dirname':
					$ratingObj->showFieldOnForm(array('item', 'itemid', 'uid', 'date', 'rate'));
					break;
			}
		}

		$sform = $ratingObj->getForm(_CO_ICMS_RATINGS_CREATE, 'addrating');
		$sform->assign($icmsAdminTpl);

		$icmsAdminTpl->assign('icms_rating_title', _CO_ICMS_RATINGS_CREATE_INFO);
		$icmsAdminTpl->display('db:system_adm_rating.html');
	}
}
icms_loadLanguageFile('system', 'common');

$icms_rating_handler = icms_getmodulehandler('rating');

/*
 * GET variables
 * (string) op
 *
 * POST variables
 *
 */

/* default values */
$op = '';

$filter_get = array();

$filter_post = array();

/* filter the user input */
if (!empty($_GET)) {
	// in places where strict mode is not used for checkVarArray, make sure filter_ vars are not overwritten
	if (isset($_GET['filter_post'])) unset($_GET['filter_post']);
	$clean_GET = icms_core_DataFilter::checkVarArray($_GET, $filter_get, FALSE);
	extract($clean_GET);
}

if (!empty($_POST)) {
	$clean_POST = icms_core_DataFilter::checkVarArray($_POST, $filter_post, FALSE);
	extract($clean_POST);
}

switch ($op) {
	/*
	 * case "mod":
	 * case "changedField";
	 *
	 * $ratingid = isset($_GET['ratingid']) ? (int) ($_GET['ratingid']) : 0 ;
	 *
	 * editrating(true, $ratingid);
	 *
	 * break;
	 *
	 * case "clone":
	 *
	 * $ratingid = isset($_GET['ratingid']) ? (int) ($_GET['ratingid']) : 0 ;
	 *
	 * editrating(true, $ratingid, true);
	 * break;
	 *
	 * case "addrating":
	 * $controller = new icms_ipf_Controller($icms_rating_handler);
	 * $controller->storeFromDefaultForm(_CO_ICMS_RATINGS_CREATED, _CO_ICMS_RATINGS_MODIFIED, ICMS_URL . '/modules/system/admin.php?fct=rating');
	 * break;
	 */
	case "del":
		$controller = new icms_ipf_Controller($icms_rating_handler);
		$controller->handleObjectDeletion();

		break;

	default:

		icms_cp_header();

		$objectTable = new icms_ipf_view_Table($icms_rating_handler, false, array('delete'));
		$objectTable->addColumn(new icms_ipf_view_Column('name', _GLOBAL_LEFT, false, 'getUnameValue'));
		$objectTable->addColumn(new icms_ipf_view_Column('dirname', _GLOBAL_LEFT));
		$objectTable->addColumn(new icms_ipf_view_Column('item', _GLOBAL_LEFT, false, 'getItemValue'));
		$objectTable->addColumn(new icms_ipf_view_Column('date', 'center', 150));
		$objectTable->addColumn(new icms_ipf_view_Column('rate', 'center', 60, 'getRateValue'));
		// $objectTable->addIntroButton('addrating', 'admin.php?fct=rating&op=mod', _CO_ICMS_RATINGS_CREATE);

		// $objectTable->addQuickSearch(array('title', 'summary', 'description'));

		$icmsAdminTpl->assign('icms_rating_table', $objectTable->fetch());

		$icmsAdminTpl->assign('icms_rating_explain', TRUE);
		$icmsAdminTpl->assign('icms_rating_title', _CO_ICMS_RATINGS_DSC);

		$icmsAdminTpl->display('db:system_adm_rating.html');

		break;
}

icms_cp_footer();

