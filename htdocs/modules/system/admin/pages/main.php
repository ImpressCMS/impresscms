<?php
/**
* Admin ImpressCMS Pages (symlinks)
*
* List, add, edit and delete pages objects
*
* @copyright	The ImpressCMS Project <http://www.impresscms.org>
* @license		GNU General Public License (GPL) <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
* @since		ImpressCMS 1.2
* @package Administration
* @version		$Id$
* @author		Gustavo Pilla (aka nekro) <nekro@impresscms.org>
*/

if (! is_object ( $icmsUser ) || ! is_object ( $icmsModule ) || ! $icmsUser->isAdmin ( $icmsModule->mid () )) {
	exit ( "Access Denied" );
}

/**
 * Edit a Blog Post
 *
 * @param int $post_id Postid to be edited
 */
function editpage($page_id = 0, $clone = false) {
	global $icms_page_handler, $icmsAdminTpl;

	$pageObj = $icms_page_handler->get ( $page_id );

	if (! $clone && ! $pageObj->isNew ()) {
		$sform = $pageObj->getForm ( _AM_SYSTEM_PAGES_EDIT, 'addpage' );
		$sform->assign ( $icmsAdminTpl );

	} else {
		$pageObj->setVar ( 'page_type', 'C' );
		$sform = $pageObj->getForm ( _AM_SYSTEM_PAGES_CREATE, 'addpage' );
		$sform->assign ( $icmsAdminTpl );

	}
	$icmsAdminTpl->assign ( 'page_id', $page_id );
	$icmsAdminTpl->assign ( 'icms_page_title', _AM_SYSTEM_PAGES_TITLE );
	$icmsAdminTpl->display ( 'db:admin/pages/system_adm_pagemanager_index.html' );
}

$icms_page_handler = icms_getModuleHandler ( 'pages' );
/** Use a naming convention that indicates the source of the content of the variable */
$clean_op = '';
/** Create a whitelist of valid values, be sure to use appropriate types for each value
 * Be sure to include a value for no parameter, if you have a default condition
 */
$valid_op = array ('mod', 'changedField', 'addpage', 'del', 'status', '' );

if (isset ( $_GET ['op'] ))
	$clean_op = htmlentities ( $_GET ['op'] );
if (isset ( $_POST ['op'] ))
	$clean_op = htmlentities ( $_POST ['op'] );

/** Again, use a naming convention that indicates the source of the content of the variable */
$clean_page_id = isset ( $_GET ['page_id'] ) ? ( int ) $_GET ['page_id'] : 0;
$clean_page_id = isset ( $_POST ['page_id'] ) ? ( int ) $_POST ['page_id'] : $clean_page_id;

/**
 * in_array() is a native PHP function that will determine if the value of the
 * first argument is found in the array listed in the second argument. Strings
 * are case sensitive and the 3rd argument determines whether type matching is
 * required
 */
if (in_array ( $clean_op, $valid_op, true )) {
	switch ( $clean_op) {
		case 'status' :
			$icms_page_handler->changeStatus( $page_id );
			$rtn = '/modules/system/admin.php?fct=pages';
			if (isset ( $_GET ['rtn'] ))
				redirect_header ( ICMS_URL . base64_decode ( $_GET ['rtn'] ) ); else
			redirect_header ( ICMS_URL . $rtn );
		break;

		case "clone" :
			icms_cp_header ();
			editpage ( $clean_page_id, true );
		break;

		case "mod" :
		case "changedField" :
			icms_cp_header ();
			editpage ( $clean_page_id );
		break;

		case "addpage" :
			$controller = new icms_ipf_Controller ( $icms_page_handler );
			$controller->storeFromDefaultForm ( _AM_SYSTEM_PAGES_CREATED, _AM_SYSTEM_PAGES_MODIFIED );
		break;

		case "del" :
			$controller = new icms_ipf_Controller ( $icms_page_handler );
			$controller->handleObjectDeletion ();

		break;

		default :

			icms_cp_header ();
			$objectTable = new icms_ipf_view_Table ( $icms_page_handler );
			$objectTable->addColumn ( new icms_ipf_view_Column ( 'page_status', 'center', false, 'getCustomPageStatus' ) );
			$objectTable->addColumn ( new icms_ipf_view_Column ( 'page_title', _GLOBAL_LEFT, false, 'getAdminViewItemLink' ) );
			$objectTable->addColumn ( new icms_ipf_view_Column ( 'page_url' ) );
			$objectTable->addColumn ( new icms_ipf_view_Column ( 'page_moduleid', 'center', false, 'getCustomPageModuleid' ) );

			$objectTable->addIntroButton ( 'addpost', 'admin.php?fct=pages&amp;op=mod', _AM_SYSTEM_PAGES_CREATE );

			$objectTable->addCustomAction('getViewItemLink');

			$objectTable->addQuickSearch ( array ('page_title', 'page_url' ) );

			$objectTable->addFilter ( 'page_moduleid', 'getModulesArray' );

			$icmsAdminTpl->assign ( 'icms_page_table', $objectTable->fetch () );
			$icmsAdminTpl->assign ( 'icms_page_title', _AM_SYSTEM_PAGES_TITLE );
			$icmsAdminTpl->display ( 'db:admin/pages/system_adm_pagemanager_index.html' );
		break;
	}
	icms_cp_footer ();
}
/**
 * If you want to have a specific action taken because the user input was invalid,
 * place it at this point. Otherwise, a blank page will be displayed
 */
?>