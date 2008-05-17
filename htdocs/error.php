<?php
/**
* Page handling HTTP errors
*
* This page handles some HTTP errors that may occur on a site. The htaccess file needs to be
* edited as well. An example of such htaccess can be found in htaccess.txt.
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		core
* @since		1.0
* @author		young-pee <nekro@impresscms.org>
* @author		malanciault <marcan@impresscms.org)
* @version		$Id$
*/

$xoopsOption['pagetype'] = 'error';
include_once ('mainfile.php');

$e = isset( $_GET['e'] ) ? $_GET['e'] : 0;

// If there is not any error defined... it redirects to the home page.
if( $e == 0 )
	header('Location: '.ICMS_URL);

$xoopsOption['template_main'] = 'system_error.html';
require_once ICMS_ROOT_PATH.'/header.php';

$siteName = $xoopsConfig['sitename'];
$lang_error_no = sprintf(_ERR_NO, $e);
$xoopsTpl->assign('lang_error_no', $lang_error_no);
$xoopsTpl->assign('lang_error_desc', sprintf(constant( '_ERR_'.$e.'_DESC' ), $siteName));
$xoopsTpl->assign('lang_error_title', $lang_error_no . ' ' . constant( '_ERR_'.$e.'_TITLE' ));
$xoopsTpl->assign('xoops_pagetitle', $lang_error_no . ' ' . constant( '_ERR_'.$e.'_TITLE' ));
$xoopsTpl->assign('lang_found_contact', sprintf(_ERR_CONTACT, $xoopsConfig['adminmail']));
$xoopsTpl->assign('lang_search', _ERR_SEARCH);
$xoopsTpl->assign('lang_advanced_search', _ERR_ADVANCED_SEARCH);
$xoopsTpl->assign('lang_start_again', _ERR_START_AGAIN);
$xoopsTpl->assign('lang_search_our_site', _ERR_SEARCH_OUR_SITE);

require_once ICMS_ROOT_PATH.'/footer.php';
?>
