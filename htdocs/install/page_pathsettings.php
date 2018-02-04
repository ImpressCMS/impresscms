<?php
/**
 * Installer paths configuration page
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
 * @version		$Id$
 */
/**
 *
 */
require_once 'common.inc.php';

if (!defined( 'XOOPS_INSTALL' ) )	exit();

$wizard->setPage( 'pathsettings' );
$pageHasForm = true;
$pageHasHelp = true;

require __DIR__ . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'pathstuffcontroller.php';

$ctrl = new PathStuffController();

$ctrl->execute();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	return;
}

ob_start();
?> <script type="text/javascript" src="pathsettings.js"></script>

<div class="blokz">
<fieldset>
<h3><?php echo _INSTALL_WEB_LOCATIONS; ?></h3>
<label for="url"><?php echo _INSTALL_WEB_LOCATIONS_LABEL; ?></label>
<div class="xoform-help"><?php echo XOOPS_URL_HELP; ?></div>
<div class="clear">&nbsp;</div>
<input type="text" name="URL" id="url"
	value="<?php echo $ctrl->xoopsUrl; ?>" /></fieldset>
<br />
</div>
<div class="bloky">
<fieldset>
<h3><?php echo _INSTALL_PHYSICAL_PATH; ?></h3>
<label for="rootpath"><?php echo XOOPS_ROOT_PATH_LABEL; ?></label>
<div class="xoform-help"><?php echo XOOPS_ROOT_PATH_HELP; ?></div>
<div class="clear">&nbsp;</div>
<input type="text" name="ROOT_PATH" id="rootpath"
	value="<?=htmlentities(ICMS_ROOT_PATH); ?>" readonly />
<?php if ( !empty( $ctrl->permErrors )) { ?>
<div id="rootperms"><?php echo CHECKING_PERMISSIONS . '<br /><p>' . ERR_NEED_WRITE_ACCESS . '</p>'; ?>
<ul class="diags">
<?php foreach ( $ctrl->permErrors as $path => $result) {
	if ($result) {
		echo '<li class="success">' . sprintf( IS_WRITABLE, $path ) . '</li>';
	} else {
		echo '<li class="failure">' . sprintf( IS_NOT_WRITABLE, $path ) . '</li>';
	}
} ?>
	<button type="button" id="permrefresh" /><?php echo BUTTON_REFRESH; ?></button>
</ul>
<?php } else { echo '<div id="rootperms">'.CHECKING_PERMISSIONS .'<br /><ul class="diags"><li class="success">'.ALL_PERM_OK.'</li></ul></div>';} ?>

</fieldset>
<br />
</div>
<?php
$content = ob_get_contents();
ob_end_clean();

include 'install_tpl.php';
