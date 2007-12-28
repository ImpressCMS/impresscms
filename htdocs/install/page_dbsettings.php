<?php
/**
* Installer database configuration page
*
* See the enclosed file license.txt for licensing information.
* If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
*
* @copyright    The XOOPS project http://www.xoops.org/
* @license      http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
* @package		installer
* @since        2.3.0
* @author		Haruki Setoyama  <haruki@planewave.org>
* @author 		Kazumi Ono <webmaster@myweb.ne.jp>
* @author		Skalpa Keo <skalpa@xoops.org>
* @version		$Id$
*/

require_once 'common.inc.php';
if ( !defined( 'XOOPS_INSTALL' ) )	exit();

	$wizard->setPage( 'dbsettings' );
	$pageHasForm = true;
	$pageHasHelp = true;

	$vars =& $_SESSION['settings'];

// Load config values from mainfile.php constants if 1st invocation, or reload has been asked
if ( !isset( $vars['DB_HOST'] ) || false !== @strpos( $_SERVER['HTTP_CACHE_CONTROL'], 'max-age=0' ) ) {
	$keys = array( 'DB_TYPE', 'DB_HOST', 'DB_USER', 'DB_NAME', 'DB_PREFIX', 'DB_PCONNECT' );
	foreach ( $keys as $k ) {
		$vars[ $k ] = defined( "XOOPS_$k" ) ? constant( "XOOPS_$k" ) : '';
	}
	$vars['DB_PASS'] = '';
}

// Set default values
if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	$params = array( 'DB_TYPE', 'DB_HOST', 'DB_USER', 'DB_PASS', 'DB_NAME', 'DB_PREFIX' );
	foreach ( $params as $name ) {
		$vars[$name] = $_POST[$name];
	}
	$vars['DB_PCONNECT'] = @$_POST['DB_PCONNECT'] ? 1 : 0;

	// Check if the given settings are correct
	$error = '';
	if ( ! ( $link = @mysql_connect( $vars['DB_HOST'], $vars['DB_USER'], $vars['DB_PASS'], true ) ) ) {
		$error = ERR_NO_DBCONNECTION;
	} elseif ( ! @mysql_select_db( $vars['DB_NAME'], $link ) ) {
		$error = ERR_NO_DATABASE;
	}
	if ( empty( $error ) ) {
		$wizard->redirectToPage( '+1' );
		exit();
	} else {
		$wizard->redirectToPage( '+0', 400, 'Bad values' );
	}
	exit();
}

$error = '';
if ( !empty( $vars['DB_HOST'] ) && !empty( $vars['DB_USER'] ) ) {
	if ( ! ( $link = @mysql_connect( $vars['DB_HOST'], $vars['DB_USER'], $vars['DB_PASS'], true ) ) ) {
		$error = ERR_NO_DBCONNECTION;
	} elseif ( ! @mysql_select_db( $vars['DB_NAME'], $link ) ) {
		// Database not here: try to create it
		$result = mysql_query( "CREATE DATABASE `" . $vars['DB_NAME'] . '`' );
		if ( !$result ) {
			$error = ERR_NO_DATABASE;
		} else {
			$error = sprintf( DATABASE_CREATED, $vars['DB_NAME'] );
		}
	}
}

if ( @empty( $vars['DB_HOST'] ) ) {
	// Fill with default values
	$vars = array_merge( $vars, array(
		'DB_TYPE'		=> 'mysql',
		'DB_HOST'		=> 'localhost',
		'DB_USER'		=> '',
		'DB_PASS'		=> '',
		'DB_NAME'		=> '',
		'DB_PREFIX'		=> 'x' . substr( md5( time() ), 0, 3 ),
		'DB_PCONNECT'	=> 0,
	) );
}


function xoFormField( $name, $value, $label, $help = '', $type='text' ) {
	$label = htmlspecialchars( $label );
	$name = htmlspecialchars( $name, ENT_QUOTES );
	$value = htmlspecialchars( $value, ENT_QUOTES );
	echo "<label for='$name'>$label</label>\n";
	if ( $help ) {
		echo '<div class="xoform-help">' . $help . "</div>\n";
	}
	echo "<input type='$type' name='$name' id='$name' value='$value' />";
}


    ob_start();
?>
<?php if ( !empty( $error ) ) echo '<div class="x2-note error">' . $error . "</div>\n"; ?>
<fieldset>
	<legend><?php echo LEGEND_CONNECTION; ?></legend>
	<label>
		<?php echo 'Database:'; ?>
		<select size="2" name="DB_TYPE">
			<option value="mysql" selected="selected">mysql</option>
			<!-- <option value="mysqli">mysqli</option> //-->
		</select>
	</label>
	<?php echo xoFormField( 'DB_HOST',	$vars['DB_HOST'],		DB_HOST_LABEL, DB_HOST_HELP ); ?>
	<?php echo xoFormField( 'DB_USER',	$vars['DB_USER'],		DB_USER_LABEL, DB_USER_HELP ); ?>
	<?php echo xoFormField( 'DB_PASS',	$vars['DB_PASS'],		DB_PASS_LABEL, DB_PASS_HELP, 'password' ); ?>

	<label style="text-align:center" title="<?php echo htmlspecialchars( DB_PCONNECT_HELP, ENT_QUOTES ); ?>">
		<?php echo htmlspecialchars( DB_PCONNECT_LABEL ); ?>
		<input class="checkbox" type="checkbox" name="DB_PCONNECT" value="1" />
	</label>
</fieldset>
<fieldset>
	<legend><?php echo LEGEND_DATABASE; ?></legend>
	<?php echo xoFormField( 'DB_NAME',		$vars['DB_NAME'],		DB_NAME_LABEL,	 DB_NAME_HELP ); ?>
	<?php echo xoFormField( 'DB_PREFIX',	$vars['DB_PREFIX'],		DB_PREFIX_LABEL, DB_PREFIX_HELP ); ?>
</fieldset>
<?php
	$content = ob_get_contents();
	ob_end_clean();
    include 'install_tpl.php';
?>