<?php
/**
 * Installer configuration check page
 *
 * See the enclosed file license.txt for licensing information.
 * If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
 *
 * @copyright   The XOOPS project http://www.xoops.org/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
 * @package		installer
 * @since       2.3.0
 * @author		Haruki Setoyama  <haruki@planewave.org>
 * @author 		Kazumi Ono <webmaster@myweb.ne.jp>
 * @author		Skalpa Keo <skalpa@xoops.org>
 * @author		Taiwen Jiang <phppp@users.sourceforge.net>
 * @author		David Janssens <david.j@impresscms.org>
 */

/**
 *
 */
require_once 'common.inc.php';
if (!defined( 'XOOPS_INSTALL' ) )	exit();
$requirements_array = array();

$wizard->setPage( 'modcheck' );
$pageHasForm = false;

$diagsOK = false;

function xoDiag( $status = -1, $str = '') {
	if ($status == -1) {
		$GLOBALS['error'] = true;
	}
	$classes = array( -1 => 'error', 0 => 'warning', 1 => 'success' );
	$strings = array( -1 => FAILED, 0 => WARNING, 1 => SUCCESS );
	if (empty($str)) {
		$str = $strings[$status];
	}
	$badges = array( -1 => 'badge--error', 0 => 'badge--warning', 1 => 'badge--success' );
	$icons = array( -1 => '&#10005;', 0 => '!', 1 => '&#10003;' );
	return '<span class="badge ' . $badges[$status] . '"><span aria-hidden="true">' . $icons[$status] . '</span> ' . $str . '</span>';
}
function xoDiagBoolSetting( $name, $wanted = false, $severe = false) {
	$setting = strtolower( ini_get( $name ) );
	$setting = ( empty( $setting ) || $setting == 'off' || $setting == 'false' ) ? false : true;
	if ($setting == $wanted) {
		return xoDiag( 1, $setting ? 'ON' : 'OFF' );
	} else {
		return xoDiag( $severe ? -1 : 0, $setting ? 'ON' : 'OFF' );
	}
}

function xoDiagIfWritable( $path) {
	$path = "../" . $path;
	$error = true;
	if (!is_dir( $path )) {
		if (file_exists( $path )) {
			@chmod( $path, 0666 );
			$error = !is_writeable( $path );
		}
	} else {
		@chmod( $path, 0777 );
		$error = !is_writeable( $path );
	}
	return xoDiag( $error ? -1 : 1, $error ? 'Not writable' : 'Writable' );
}

function imCheckRequirements()
{
	$requirement['server_api']['description']=PHP_SAPI;
	$requirement['server_api']['result']=php_sapi_name();
	$requirement['server_api']['status']=true;

	$requirement['php_version']['description']=_PHP_VERSION;
	if (version_compare( phpversion(), '7.0', '>=')) {
		$requirement['php_version']['status']=1;
	} else {
		$requirement['php_version']['status']=0;
	}
	$requirement['php_version']['result']=phpversion();

	$requirement['mysql']['description']="MySQL Handler";
	$requirement['mysql']['result']=in_array("mysql",PDO::getAvailableDrivers(),TRUE) ? SUCCESS : FAILED;
	$requirement['mysql']['status']=in_array("mysql",PDO::getAvailableDrivers(),TRUE) ? true : false;

	$requirement['session']['description']="Session Extension";
	$requirement['session']['result']=extension_loaded( 'session' ) ? SUCCESS : FAILED;
	$requirement['session']['status']=extension_loaded( 'session' ) ? true : false;

	$requirement['pcre']['description']="PCRE Extension";
	$requirement['pcre']['result']=extension_loaded( 'PCRE' ) ? SUCCESS : FAILED;
	$requirement['pcre']['status']=extension_loaded( 'PCRE' ) ? true : false;

	$requirement['curl']['description']="CURL Extension";
	$requirement['curl']['result']=extension_loaded( 'curl' ) ? SUCCESS : FAILED;
	$requirement['curl']['status']=extension_loaded( 'curl' ) ? true : false;

	$requirement['file_upload']['description']="File uploads";
	$requirement['file_upload']['result']=xoDiagBoolSetting( 'file_uploads', true ) ? SUCCESS : FAILED;
	$requirement['file_upload']['status']=xoDiagBoolSetting( 'file_uploads', true ) ? true : false;

	$requirement['gd']['description']="GD Extension";
	$requirement['gd']['result']=extension_loaded( 'GD' ) ? SUCCESS : FAILED;
	$requirement['gd']['status']=extension_loaded( 'GD' ) ? true : false;
	
	
	return $requirement;
}

ob_start();
$requirements_array = imCheckRequirements();
$allOk = !in_array( false, array_map( 'boolval', array_column( $requirements_array, 'status' ) ), true );
?>
<p class="x2-note<?php echo $allOk ? '' : ' error'; ?>"<?php echo $allOk ? '' : ' role="alert"'; ?>><?php echo $allOk ? ESS_SERVER_OK : ESS_SERVER_PROBLEM; ?></p>
<details class="collapsible"<?php echo $allOk ? '' : ' open'; ?>><summary><?php echo ESS_SHOW_DETAILS; ?></summary><div class="collapsible__body">
<fieldset>
<legend><?php echo REQUIREMENTS; ?></legend>
<ul class="status-list">
<?php foreach($requirements_array as $requirement) { ?>
	<li><span class="status-name"><?php echo $requirement['description']; ?></span>
		<?php echo xoDiag($requirement['status'] ? 1 : -1, $requirement['result']); ?></li>
<?php } ?>
</ul>
</fieldset>

<fieldset>
<legend><?php echo RECOMMENDED_EXTENSIONS; ?></legend>
<p><?php echo RECOMMENDED_EXTENSIONS_MSG; ?></p>
<ul class="status-list">
<?php
$ext = array();
if (extension_loaded( 'iconv' ) )		$ext[] = 'Iconv';
if (extension_loaded( 'mbstring' ) )	$ext[] = 'MBString';
?>
	<li><span class="status-name"><?php printf( PHP_EXTENSION, CHAR_ENCODING ); ?></span>
		<?php echo empty($ext) ? xoDiag( 0, NONE ) : xoDiag( 1, implode( ', ', $ext ) ); ?></li>
<?php
$ext = array();
if (extension_loaded( 'xml' ) )		$ext[] = 'XML';
?>
	<li><span class="status-name"><?php printf( PHP_EXTENSION, XML_PARSING ); ?></span>
		<?php echo empty($ext) ? xoDiag( 0, NONE ) : xoDiag( 1, implode( ', ', $ext ) ); ?></li>
</ul>
</fieldset>
</div></details>
<!--
	<table class="diags">
	<caption><?php echo FILE_PERMISSIONS; ?></caption>
    <thead>
    	<tr><th>Path</th><th>Status</th></tr>
    </thead>
	<?php
		$paths = array("uploads/", "cache/", "templates_c/", "mainfile.php");
		foreach ( $paths as $path) {
	?>
	<tr>
		<th scope="row"><?php echo $path; ?></th>
		<td><?php echo xoDiagIfWritable( $path ); ?></td>
	</tr>
	<?php } ?>
	</table>
	-->
	<?php
	$content = ob_get_contents();
	ob_end_clean();

	include 'install_tpl.php';
