<?php

	$xoopsOption['pagetype'] = 'error';
	include_once ('mainfile.php');

	$errorn = isset( $_GET['errorn'] ) ? $_GET['errorn'] : 0;

	// If there is not any error defined... it redirects to the home page.
 	if( $errorn == 0 )
 		header('Location: '.XOOPS_URL);

	$xoopsOption['template_main'] = 'system_error.html';
	require_once XOOPS_ROOT_PATH.'/header.php';

	$siteName = $xoopsConfig['sitename'];
	$xoopsTpl->assign('lang_error_desc', sprintf(constant( '_ERR_'.$errorn.'_DESC' ), $siteName));
	$xoopsTpl->assign('lang_error_title', constant( '_ERR_'.$errorn.'_TITLE' ));
	$xoopsTpl->assign('xoops_pagetitle', constant( '_ERR_'.$errorn.'_PAGE_TITLE' ));
	$xoopsTpl->assign('lang_found_contact', sprintf(_ERR_CONTACT, $xoopsConfig['adminmail']));
	$xoopsTpl->assign('lang_search', _ERR_SEARCH);
	$xoopsTpl->assign('lang_advanced_search', _ERR_ADVANCED_SEARCH);
	$xoopsTpl->assign('lang_start_again', _ERR_START_AGAIN);
	$xoopsTpl->assign('lang_search_our_site', _ERR_SEARCH_OUR_SITE);

	require_once XOOPS_ROOT_PATH.'/footer.php';
?>
