<?php
/**
 * Setting to add SALT to mainfile.php
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		upgrader
 * @since		1.1
 * @author	   Sina Asghari <pesian_stranger@users.sourceforge.net>
 * @version		$Id: setting_salt.php 1747 2008-04-20 19:42:15Z pesian_stranger $
 */

if (!defined( 'ICMS_ROOT_PATH' )) {
    die( 'Bad installation: please add this folder to the ImpressCMS install you want to upgrade');
}

$vars =& $_SESSION['settings'];

function xoFormField( $name, $value, $label, $maxlength, $help = '' )
{
    $label = htmlspecialchars( $label );
    $name = htmlspecialchars( $name, ENT_QUOTES );
    $value = htmlspecialchars( $value, ENT_QUOTES );
    $maxlength = (int) $maxlength;

    $field = "<label for='$name'>$label</label>\n";
    if ($help) {
        $field .= '<div class="xoform-help1">' . $help . "</div>\n";
    }
    $field .= "<input type='text' name='$name' id='$name' value='$value' />";

    return $field;
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && @$_POST['task'] == 'salt') {
	$params = array( 'DB_SALT' );
	foreach ( $params as $name) {
		$vars[$name] = isset($_POST[$name]) ? $_POST[$name] : "";
	}

	return $vars;
}

if (!isset($vars['DB_SALT']))
{
    require_once ICMS_ROOT_PATH.'/class/icms_Password.php' ;
    $icmspass = new icms_Password();
    $vars['DB_SALT'] = $icmspass->icms_createSalt();
}


?>
<?php if (!empty( $error ) ) echo '<div class="x2-note error">' . $error . "</div>\n"; ?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method='post'>
<fieldset>
	<legend><?php echo DB_SALT_LABEL; ?></legend>
	<?php echo xoFormField( 'DB_SALT',	$vars['DB_SALT'],	DB_SALT_LABEL, 255, DB_SALT_HELP ); ?>

</fieldset>
<input type="hidden" name="action" value="next" />
<input type="hidden" name="task" value="salt" />

<div class="xo-formbuttons">
    <button type="submit"><?php echo _SUBMIT; ?></button>
</div>