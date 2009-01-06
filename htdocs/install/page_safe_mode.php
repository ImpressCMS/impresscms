<?php
/**
* Installer No PHP 5 information page
*
* See the enclosed file license.txt for licensing information.
* If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
*
* @copyright    The XOOPS project http://www.xoops.org/
* @license      http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
* @package		installer
* @since        2.3.0
* @author		marcan <marcan@impresscms.org>
* @version		$Id: page_modcheck.php 4797 2008-09-12 02:51:14Z malanciault $
*/
/**
 *
 */
require_once 'common.inc.php';
if ( !defined( 'XOOPS_INSTALL' ) )	exit();

	$wizard->setPage( 'safe_mode' );
	$pageHasForm = false;

	ob_start();
	echo SAFE_MODE_CONTENT;
	$content = ob_get_contents();
	ob_end_clean();

    include 'install_tpl.php';

?>