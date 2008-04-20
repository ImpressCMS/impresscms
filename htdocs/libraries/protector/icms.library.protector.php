<?php
/**
* Initiating Protector module
*
* This file is responsible for initiating the Protector module so no hacks on mainfile are required
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		libraries
* @since		1.1
* @author		marcan <marcan@impresscms.org>
* @version		$Id: icms.library.protector.php 1706 2008-04-19 16:02:25Z malanciault $
*/

function icmsLibraryProtector_StartCoreBoot() {
	$filename = ICMS_TRUST_PATH.'/modules/protector/include/precheck.inc.php';
	if (file_exists($filename)) {
		include $filename;
	}
}

function icmsLibraryProtector_FinishCoreBoot() {
	$filename = ICMS_TRUST_PATH.'/modules/protector/include/postcheck.inc.php';
	if (file_exists($filename)) { 
		include $filename;
	}
}

?>