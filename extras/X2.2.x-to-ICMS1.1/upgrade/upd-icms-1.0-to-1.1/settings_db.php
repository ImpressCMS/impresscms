<?php
/**
 * Database character set configuration page
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	XOOPS_copyrights.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		upgrader
 * @since		XOOPS
 * @author		http://www.xoops.org/ The XOOPS Project
 * @author		Sina Asghari <pesian_stranger@users.sourceforge.net>
 * @version		$Id: setting_db.php 1747 2008-04-20 19:42:15Z pesian_stranger $
 */

if ( !defined( 'XOOPS_ROOT_PATH' ) ) {
    die( 'Bad installation: please add this folder to the ImpressCMS install you want to upgrade');
}

$vars =& $_SESSION['settings'];

function getDbCharsets()
{
    $charsets = array();
    
    $charsets["utf8"] = array();
    $ut8_available = false;
    if ( $result = $GLOBALS["xoopsDB"]->queryF("SHOW CHARSET") ) {
	    while ( $row = $GLOBALS["xoopsDB"]->fetchArray($result) ) {
            $charsets[$row["Charset"]]["desc"] = $row["Description"];
    	    if ($row["Charset"] == "utf8") {
                $ut8_available = true;
    	    }
	    }
    }
    if (!$ut8_available) {
        unset($charsets["utf8"]);
    }
    
    return $charsets;
}

function getDbCollations()
{
    $collations = array();
    $charsets = getDbCharsets();
    
    if ( $result = $GLOBALS["xoopsDB"]->queryF("SHOW COLLATION") ) {
	    while ( $row = $GLOBALS["xoopsDB"]->fetchArray($result) ) {
    	    $charsets[$row["Charset"]]["collation"][] = $row["Collation"];
	    }
    }
    
    return $charsets;
}

function xoFormFieldCollation( $name, $value, $label, $help = '' )
{
    $collations = getDbCollations();
    
	$label = htmlspecialchars( $label );
	$name = htmlspecialchars( $name, ENT_QUOTES );
	$value = htmlspecialchars( $value, ENT_QUOTES );
	
	$field = "<label for='$name'>$label</label>\n";
	if ( $help ) {
		$field .= '<div class="xoform-help">' . $help . "</div>\n";
	}
	$field .= "<select name='$name' id='$name'\">";
	$field .= "<option value=''>None</option>";
    
    $collation_default = "";
    $options = "";
    foreach ($collations as $key => $charset) {
    	$field .= "<optgroup label='{$key} - ({$charset['desc']})'>";
    	foreach ($charset['collation'] as $collation) {
    	    $field .= "<option value='{$collation}'" . ( ($value == $collation) ? " selected='selected'" : "" ) . ">{$collation}</option>";
	    }
    	$field .= "</optgroup>";
    }
	$field .= "</select>";
	
	return $field;
}

if ( $_SERVER['REQUEST_METHOD'] == 'POST' && @$_POST['task'] == 'db' ) {
	$params = array( 'DB_COLLATION' );
	foreach ( $params as $name ) {
		$vars[$name] = isset($_POST[$name]) ? $_POST[$name] : "";
	}
	
	return $vars;
}

if ( !isset( $vars['DB_COLLATION'] ) ) {
    $vars['DB_COLLATION'] = '';
}


?>
<?php if ( !empty( $error ) ) echo '<div class="x2-note error">' . $error . "</div>\n"; ?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method='post'>
<fieldset>
	<legend><?php echo LEGEND_DATABASE; ?></legend>
	<?php echo xoFormFieldCollation( 'DB_COLLATION',	$vars['DB_COLLATION'],	DB_COLLATION_LABEL, DB_COLLATION_HELP ); ?>
	
</fieldset>
<input type="hidden" name="action" value="next" />
<input type="hidden" name="task" value="db" />
    
<div class="xo-formbuttons">
    <button type="submit"><?php echo _SUBMIT; ?></button>
</div>