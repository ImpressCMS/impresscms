<?php
/**
 * Setting to add TRUST_PATH to mainfile.php
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		upgrader
 * @since		1.1
 * @author	   Sina Asghari <pesian_stranger@users.sourceforge.net>
 * @version		$Id: setting_trust_path.php 5547 2008-10-09 19:42:15Z pesian_stranger $
 */

if ( !defined( 'ICMS_ROOT_PATH' ) ) {
    die( 'Bad installation: please add this folder to the ImpressCMS install you want to upgrade');
}

$vars =& $_SESSION['settings'];

function icmsFormField( $name, $value, $label, $maxlength, $help = '' )
{
    $label = htmlspecialchars( $label );
    $name = htmlspecialchars( $name, ENT_QUOTES );
    $value = htmlspecialchars( $value, ENT_QUOTES );
    $maxlength = (int) $maxlength;

    $field = "<label for='$name'>$label</label>\n";
    if ( $help ) {
        $field .= '<div class="xoform-help1">' . $help . "</div>\n";
    }
    $field .= "<input type='text' name='$name' id='$name' value='$value' />";

    return $field;
}


if ( $_SERVER['REQUEST_METHOD'] == 'POST' && @$_POST['task'] == 'trust_path' ) {
	$vars['TRUST_PATH'] = $_POST['TRUST_PATH'];
	$error = '';
	if ( @empty( $vars['TRUST_PATH'] ) )
	{
    	$error = ERR_NO_TRUST_PATH;
	} elseif ( $vars['TRUST_PATH'] == ICMS_ROOT_PATH ) {
    	$error = ERR_INVALID_TRUST_PATH;
	} elseif ( !is_dir( $vars['TRUST_PATH'] ) ) {
    	$error = ERR_WRONG_TRUST_PATH;
	}
	if ( $error ) {
		echo '<div class="x2-note error">' . $error . "</div>\n";
    	return false;
	} elseif ( substr( $vars, -1 ) == '/' OR substr( $vars, -1 ) == '\'' ) {
					return str_replace( "//", "/", str_replace( "\\", "/", substr( $vars, 0, -1 ) ) );
	}else{
					return str_replace( "//", "/", str_replace( "\\", "/", $vars ) );
	}
}
if ( !isset( $vars['TRUST_PATH'] ) ) {
    $vars['TRUST_PATH'] = '';
}


?>
<?php if ( !empty( $error ) ) echo '<div class="x2-note error">' . $error . "</div>\n"; ?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method='post'>
<fieldset>
	<legend><?php echo TRUST_PATH_LABEL; ?></legend>
	<?php echo icmsFormField( 'TRUST_PATH',	$vars['TRUST_PATH'],	TRUST_PATH_LABEL, 255, TRUST_PATH_HELP ); ?>

</fieldset>
<input type="hidden" name="action" value="next" />
<input type="hidden" name="task" value="trust_path" />

<div class="xo-formbuttons">
    <button type="submit"><?php echo _SUBMIT; ?></button>
</div>