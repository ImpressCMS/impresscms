<?php
// $Id: index.php 695 2006-09-04 11:34:55Z skalpa $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
@include_once '../mainfile.php';

error_reporting( E_ALL );

if (!defined( 'ICMS_ROOT_PATH' )) {
	die( 'Bad installation: please add this folder to the ImpressCMS install you want to upgrade');
}
/*
 * gets list of name of directories inside a directory
 */
function getDirList($dirname) {
	$dirlist = array();
	if (is_dir($dirname) && $handle = opendir($dirname)) {
		while (false !== ($file = readdir($handle))) {
			if (substr( $file, 0, 1 ) != '.'  && strtolower($file) != 'cvs') {
				if (is_dir( "$dirname/$file" )) {
					$dirlist[] = $file;
				}
			}
		}
		closedir($handle);
		asort($dirlist);
		reset($dirlist);
	}
	return $dirlist;
}
if (file_exists("./language/".$xoopsConfig['language']."/upgrade.php")) {
	include_once "./language/".$xoopsConfig['language']."/upgrade.php";
	$language = $xoopsConfig['language'];
} elseif (file_exists("./language/english/upgrade.php")) {
	include_once "./language/english/upgrade.php";
	$language = 'english';
} else {
	echo 'no language file.';
	exit();
}

ob_start();

global $xoopsUser;
if (!$xoopsUser || !$xoopsUser->isAdmin()) {
	include_once "login.php";
} else {
	$op = @$_REQUEST['action'];
	if (empty( $_SESSION['xoops_upgrade'] )) {
		$op = '';
	}
	if (empty( $op )) {
		// a new check starts a new upgrade operation
		unset( $_SESSION['icms_upgrade_operation'] );
		include_once 'check_version.php';
	} else {
		// correlate the events of all requests of this upgrade run with one operation ID kept in the session
		$operationId = isset( $_SESSION['icms_upgrade_operation'] ) ? (string) $_SESSION['icms_upgrade_operation'] : '';
		if (!\Icms\Core\EventLog::resumeOperation( $operationId )) {
			$_SESSION['icms_upgrade_operation'] = \Icms\Core\EventLog::beginOperation();
			\Icms\Core\EventLog::audit( \Icms\Core\EventLog::INFO, 'upgrade.started', 'upgrade', 'info', array(
				'count' => count( $_SESSION['xoops_upgrade'] ),
			) );
		}

		$next = array_shift( $_SESSION['xoops_upgrade'] );
		printf( '<h2>' . _PERFORMING_UPGRADE . '</h2>', $next );
		\Icms\Core\EventLog::audit( \Icms\Core\EventLog::INFO, 'upgrade.step.started', 'upgrade', 'info', array( 'step' => $next ) );
		try {
			$upgrader = include_once "$next/index.php";
			$res = $upgrader->apply();
		} catch (Throwable $e) {
			// keep the failed step queued so a retry repeats it, and let the logger show the exception as before
			array_unshift( $_SESSION['xoops_upgrade'], $next );
			\Icms\Core\EventLog::logThrowable( $e, 'upgrade.step.failed', 'upgrade', \Icms\Core\EventLog::ERROR, array( 'step' => $next ), true );
			throw $e;
		}
		if (!$res) {
			// not finished yet: the step stays queued and is repeated on reload
			\Icms\Core\EventLog::audit( \Icms\Core\EventLog::WARNING, 'upgrade.step.incomplete', 'upgrade', 'failure', array( 'step' => $next ) );
		} else {
			\Icms\Core\EventLog::audit( \Icms\Core\EventLog::INFO, 'upgrade.step.completed', 'upgrade', 'success', array( 'step' => $next ) );
			if (empty( $_SESSION['xoops_upgrade'] )) {
				\Icms\Core\EventLog::audit( \Icms\Core\EventLog::INFO, 'upgrade.completed', 'upgrade', 'success' );
				unset( $_SESSION['icms_upgrade_operation'] );
				\Icms\Core\EventLog::endOperation();
			}
		}
		if (!$res) {
			array_unshift( $_SESSION['xoops_upgrade'], $next );
			echo '<a id="link-next" href="index.php?action=next">' . _RELOAD . '</a>';
		} else {
			$text = empty( $_SESSION['xoops_upgrade'] ) ? '<a id="link-next" href="index.php">' . _FINISH . '</a>' : sprintf('<a id="link-next" href="index.php?action=next">' . _APPLY_NEXT . '</a>', $_SESSION['xoops_upgrade'][0] );
			echo $text ;
		}
	}
}

$content = ob_get_contents();
ob_end_clean();

include_once 'upgrade_tpl.php';

?>