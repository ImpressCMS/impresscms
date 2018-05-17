<?php
/*
 You may not change or alter any portion of this comment or credits
 of supporting developers from this source code or any supporting source code
 which is considered copyrighted (c) material of the original comment or credit authors.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/
/**
 * Installer mainfile creation page
 *
 * See the enclosed file license.txt for licensing information.
 * If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
 *
 * @copyright    The XOOPS project http://www.xoops.org/
 * @license      http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
 * @package		installer
 * @since        Xoops 2.3.0
 * @author		Haruki Setoyama  <haruki@planewave.org>
 * @author 		Kazumi Ono <webmaster@myweb.ne.jp>
 * @author		Skalpa Keo <skalpa@xoops.org>
 * @author		Taiwen Jiang <phppp@users.sourceforge.net>
 */
/**
 *
 */
require_once 'common.inc.php';
if (!defined('XOOPS_INSTALL')) {
	exit();
}

$wizard->setPage('configsave');
$pageHasForm = true;
$pageHasHelp = false;

$vars = & $_SESSION['settings'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	$error = '';

	$rez = '';
	foreach (($vars + $_ENV) as $cfg_name => $cfg_value) {
		if ($cfg_name == 'ROOT_PATH') {
			continue;
		}
		$rez .= $cfg_name . '=' . $cfg_value . "\n";
	}
	$env_file = ICMS_ROOT_PATH . DIRECTORY_SEPARATOR . '.env';

	@chmod($env_file, 0777);
	if (file_put_contents($env_file, $rez, LOCK_EX) === false) {
		$error = ERR_WRITE_ENV_DATA;
	}
	@chmod($env_file, 0400);

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
<p class="x2-note"><?php echo READY_SAVE_MAINFILE; ?></p>
<dl style="height: 200px; overflow: auto; border: 1px solid #D0D0D0">
<?php foreach ($vars as $k => $v) {
	echo "<dt>ICMS_$k</dt><dd>$v</dd>";
} ?>
</dl>

<?php
$content = ob_get_contents();
ob_end_clean();
include 'install_tpl.php';
