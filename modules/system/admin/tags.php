<?php
/**
 * ImpressCMS Tags
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Administration
 * @subpackage	Tags
 * @since		2.0
 */

/* set get and post filters before including admin_header, if not strings */
$filter_get = array('id' => 'int');

$filter_post = array('id' => 'int');

/* set default values for variables. $op and $fct are handled in the header */
$id = 0;

/** common header for the admin functions */
include 'admin_header.php';

$clean_op = $op;

/* conventions used elsewhere: add{object}, mod, del */
$valid_op = array ("addtags", "mod", "edit", "changedField", "del", "");

if (in_array($clean_op, $valid_op, TRUE)) {
	switch ($clean_op) {
		case "addtags":
			$controller = new icms_ipf_Controller($icms_admin_handler);
			$controller->storeFromDefaultForm(_CO_SYSTEM_TAG_CREATED, _CO_SYSTEM_TAG_MODIFIED);
			break;

		case "edit":
		case "mod":
		case "changedField":
			icms_cp_header();

			$tagObj = $icms_admin_handler->get($id);

			if (!$tagObj->isNew()){
				$sform = $tagObj->getForm(_CO_SYSTEM_TAG_EDIT, 'addtags');
				$sform->assign($icmsAdminTpl);

			} else {
				$sform = $tagObj->getForm(_CO_SYSTEM_TAG_CREATE, 'addtags');
				$sform->assign($icmsAdminTpl);

			}
			$icmsAdminTpl->display(ICMS_MODULES_PATH . "/system/templates/admin/tags/system_adm_tags.html");

			break;

		case "del":
			$controller = new icms_ipf_Controller($icms_admin_handler);
			$controller->handleObjectDeletion();
			break;

		default:
			icms_cp_header();
			$objectTable = new icms_ipf_view_Table($icms_admin_handler);

			$objectTable->addColumn(new icms_ipf_view_Column("tag", _GLOBAL_LEFT));
			$objectTable->addColumn(new icms_ipf_view_Column("status", _GLOBAL_LEFT));
			$objectTable->addColumn(new icms_ipf_view_Column("count", _GLOBAL_LEFT));

			$objectTable->addIntroButton("addtag", "admin.php?fct=tags&amp;op=edit", _CO_SYSTEM_TAG_CREATE);
			$objectTable->addQuickSearch(array("tag"));

			$icmsAdminTpl->assign("icms_tags_table", $objectTable->fetch());
			$icmsAdminTpl->assign("icms_tags_explain", TRUE);
			$icmsAdminTpl->assign("icms_tags_title", _CO_SYSTEM_TAGS_DSC);

			$icmsAdminTpl->display(ICMS_MODULES_PATH . "/system/templates/admin/tags/system_adm_tags.html");
		}
}

icms_cp_footer();
