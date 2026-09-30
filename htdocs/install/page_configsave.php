<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */
/**
 * Installer mainfile creation page
 *
 * See the enclosed file license.txt for licensing information.
 * If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
 *
 * @copyright The XOOPS project http://www.xoops.org/
 * @license http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
 * @package installer
 * @since 2.3.0
 * @author Haruki Setoyama <haruki@planewave.org>
 * @author Kazumi Ono <webmaster@myweb.ne.jp>
 * @author Skalpa Keo <skalpa@xoops.org>
 * @author Taiwen Jiang <phppp@users.sourceforge.net>
 * @version $Id: page_configsave.php 12329 2013-09-19 13:53:36Z skenow $
 */

/**
 */
require_once 'common.inc.php';
if (!defined('XOOPS_INSTALL')) {
	exit();
}

$wizard->setPage('configsave');
$pageHasForm = true;
$pageHasHelp = false;

$vars = &$_SESSION['settings'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	$error = '';
	// let's try and put the db info in the trust path
	$sdata_file_name = md5($vars['ROOT_PATH'] . time()) . '.php';
	$sdata_path = $vars['TRUST_PATH'] . '/' . $sdata_file_name;

	if (!copy($vars['ROOT_PATH'] . '/install/templates/sdata.dist.php', $sdata_path)) {
		// we were not able to create the sdata file in trust path so we will use the old method
		$error = true;
	} else {
		clearstatcache();
		$content = file_get_contents($sdata_path);
		if ($content === false) {
			$error = ERR_READ_SDATA;
		} else {
			$sdata_rewrite = array();
			$sdata_rewrite['DB_HOST'] = $vars['DB_HOST'];
			$sdata_rewrite['DB_USER'] = $vars['DB_USER'];
			$sdata_rewrite['DB_PASS'] = $vars['DB_PASS'];
			$sdata_rewrite['DB_NAME'] = $vars['DB_NAME'];
			$sdata_rewrite['DB_PREFIX'] = $vars['DB_PREFIX'];
			$sdata_rewrite['DB_SALT'] = $vars['DB_SALT'];

			foreach ($sdata_rewrite as $key => $val) {
				$content = icms_install_rewrite_define($content, "SDATA_$key", (string) $val) ?? $content;
			}
			if (file_put_contents($sdata_path, $content) === false) {
				$error = ERR_WRITE_SDATA;
			}
		}
	}
	if (!$error) {
		// then we were able to save the db info into the trust path
		$dbinfo_in_trust_path = true;
		$vars['DB_HOST'] = 'SDATA_DB_HOST';
		$vars['DB_USER'] = 'SDATA_DB_USER';
		$vars['DB_PASS'] = 'SDATA_DB_PASS';
		$vars['DB_NAME'] = 'SDATA_DB_NAME';
		$vars['DB_PREFIX'] = 'SDATA_DB_PREFIX';
		$vars['DB_SALT'] = 'SDATA_DB_SALT';
	} else {
		$dbinfo_in_trust_path = false;
	}

	$mainfile_path = $vars['ROOT_PATH'] . '/mainfile.php';
	if (!copy($vars['ROOT_PATH'] . '/install/templates/mainfile.dist.php', $mainfile_path)) {
		$error = ERR_COPY_MAINFILE;
	} else {
		clearstatcache();

		$rewrite = array('GROUP_ADMIN' => 1, 'GROUP_USERS' => 2, 'GROUP_ANONYMOUS' => 3);
		$rewrite = array_merge($rewrite, $vars);
		$content = file_get_contents($mainfile_path);
		if ($content === false) {
			$error = ERR_READ_MAINFILE;
		} else {
			if ($dbinfo_in_trust_path) {
				// add the line in mainfile to include the sdata file
				$include_line = "include_once XOOPS_TRUST_PATH . '/" . $sdata_file_name . "' ;";
				$content = str_replace('// sdata#--#', $include_line, $content);
			} else {
				$content = str_replace('// sdata#--#', '', $content);
			}

			foreach ($rewrite as $key => $val) {
				// values that reference the constants defined in the sdata file are written unquoted
				$isSdataReference = $dbinfo_in_trust_path && isset($sdata_rewrite[$key]);
				$content = icms_install_rewrite_define($content, "XOOPS_$key", $val, $isSdataReference) ?? $content;
			}
			if (file_put_contents($mainfile_path, $content) === false) {
				$error = ERR_WRITE_MAINFILE;
			}
		}
	}

	// creating the required folders in trust_path
	if (!icms_core_Filesystem::mkdir($vars['TRUST_PATH'] . '/cache/htmlpurifier', 0777, '', array('[', '?', '"', '<', '>', '|', ' '))) {
	/**
	 *
	 * @todo trap error
	 */
	}
	if (is_dir($vars['TRUST_PATH'] . '/cache/htmlpurifier')) {
		if (!icms_core_Filesystem::mkdir($vars['TRUST_PATH'] . '/cache/htmlpurifier/HTML', 0777, '', array('[', '?', '"', '<', '>', '|', ' ')) && !icms_core_Filesystem::mkdir($vars['TRUST_PATH'] . '/cache/htmlpurifier/CSS', 0777, '', array('[', '?', '"', '<', '>', '|', ' ')) && !icms_core_Filesystem::mkdir($vars['TRUST_PATH'] . '/cache/htmlpurifier/URI', 0777, '', array(
			'[',
			'?',
			'"',
			'<',
			'>',
			'|',
			' ')) && !icms_core_Filesystem::mkdir($vars['TRUST_PATH'] . '/cache/htmlpurifier/Test', 0777, '', array('[', '?', '"', '<', '>', '|', ' '))) {
		/**
		 *
		 * @todo trap error
		 */
		}
	}

	if (empty($error)) {
		$wizard->redirectToPage('+1');
		exit();
	}
	$content = '<p class="errorMsg">' . $error . '</p>';
	include 'install_tpl.php';
	exit();
}

ob_start();
?>
<p class="x2-note"><?php

echo READY_SAVE_MAINFILE;
?></p>
<dl style="height: 200px; overflow: auto; border: 1px solid #D0D0D0">
<?php

foreach ($vars as $k => $v) {
	echo '<dt>XOOPS_' . htmlspecialchars((string) $k) . '</dt><dd>' . htmlspecialchars((string) $v) . '</dd>';
}
?>
</dl>

<?php
$content = ob_get_contents();
ob_end_clean();
include 'install_tpl.php';