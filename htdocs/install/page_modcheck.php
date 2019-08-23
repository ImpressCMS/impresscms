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
	return '<td class="' . $classes[$status] . '">' . $str . '</td>';
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
	if (version_compare( phpversion(), '5.6', '>=')) {
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

	$requirement['file_upload']['description']="File uploads";
	$requirement['file_upload']['result']=xoDiagBoolSetting( 'file_uploads', true ) ? _YES : _NO;
	$requirement['file_upload']['status']=xoDiagBoolSetting( 'file_uploads', true ) ? true : false;

	return $requirement;
}

ob_start();
$requirements_array = imCheckRequirements();
?>
<fieldset>
<h3><?php echo REQUIREMENTS; ?></h3>
<?php foreach($requirements_array as &$requirement)
	 {
	 ?>
<h4><?php echo $requirement['description']; ?>:&nbsp; <?php echo xoDiag($requirement['status'], $requirement['result']); ?> <img
	src="img/<?php echo $requirement['status'] ? "yes" : "no"; ?>.png" alt="<?php echo $requirement['status'] ? SUCCESS : FAILED; ?>" class="rootimg" /></h4>
<div class="clear">&nbsp;</div>
		 <?php } ?>
</fieldset>

<fieldset>
<h3><?php echo RECOMMENDED_EXTENSIONS; ?></h3>
<p><?php echo RECOMMENDED_EXTENSIONS_MSG; ?></p>
<div class="clear">&nbsp;</div>

<h4><?php printf( PHP_EXTENSION, CHAR_ENCODING ); ?>:&nbsp; <?php
$ext = array();
if (extension_loaded( 'iconv' ) )		$ext[] = 'Iconv';
if (extension_loaded( 'mb_string' ) )	$ext[] = 'MBString';
if (empty($ext)) {
	echo xoDiag( 0, NONE );
} else {
	echo xoDiag( 1, implode( ',', $ext ) );
}
?> <img src="img/yes.png" alt="Success" class="rootimg" /></h4>
<div class="clear">&nbsp;</div>
<h4><?php printf( PHP_EXTENSION, XML_PARSING ); ?>:&nbsp; <?php
$ext = array();
if (extension_loaded( 'xml' ) )		$ext[] = 'XML';
//if (extension_loaded( 'dom' ) )		$ext[] = 'DOM';
if (empty($ext)) {
	echo xoDiag( 0, NONE );
} else {
	echo xoDiag( 1, implode( ',', $ext ) );
}
?> <img src="img/yes.png" alt="Success" class="rootimg" /></h4>
<div class="clear">&nbsp;</div>
<h4><?php printf( PHP_EXTENSION, OPEN_ID ); ?>:&nbsp; <?php
$ext = array();
if (extension_loaded( 'curl' ) )		$ext[] = 'Curl  <img src="img/yes.png" alt="Success" class="rootimg" />  ';
if (extension_loaded( 'bcmath' ) )		$ext[] = ' Math Support  <img src="img/yes.png" alt="Success" class="rootimg" />  ';
if (extension_loaded( 'openssl' ) )	$ext[] = ' OpenSSL  <img src="img/yes.png" alt="Success" class="rootimg" />';
if (empty($ext)) {
	echo xoDiag( 0, NONE );
} else {
	echo xoDiag( 1, implode( ' ', $ext ) );
}
?></h4>
<div class="clear">&nbsp;</div>
</fieldset>
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
