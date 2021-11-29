<?php
/**
 * Installer database configuration page
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
 */
/**
 *
 */
require_once 'common.inc.php';
if (!defined('XOOPS_INSTALL')) {
	exit ();
}

$wizard->setPage('dbsettings');
$pageHasForm = true;
$pageHasHelp = true;

$vars = & $_SESSION ['settings'];

switch ($vars['DB_TYPE']) {
	case 'mysql':
		$func_connect = empty($vars['DB_PCONNECT'])?"mysql_connect":"mysql_pconnect";
		if (!($link = @$func_connect($vars['DB_HOST'], $vars['DB_USER'], $vars['DB_PASS'], true))) {
			$error = ERR_NO_DBCONNECTION;
		}
		break;
	case 'pdo.mysql':
		try {
			$link = new PDO('mysql:host=' . $vars['DB_HOST'],
					$vars['DB_USER'],
					$vars['DB_PASS'],
					array(
							PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
							PDO::ATTR_PERSISTENT => !empty($vars['DB_PCONNECT'])
					));
		} catch (PDOException $ex) {
			$error = ERR_NO_DBCONNECTION;
		}
		break;
}
if (isset($error)) {
	$wizard->redirectToPage('-1', $error);
	exit ();
}

// Load config values from mainfile.php constants if 1st invocation, or reload has been asked
if (!isset ($vars ['DB_NAME']) || false !== @strpos($_SERVER ['HTTP_CACHE_CONTROL'], 'max-age=0')) {
	$keys = array('DB_NAME', 'DB_CHARSET', 'DB_COLLATION', 'DB_PREFIX', 'DB_SALT');
	foreach ($keys as $k) {
		$vars [$k] = defined("XOOPS_$k")? constant ("XOOPS_$k"):'';
	}
}

function exec_query($sql, $link) {
	if ($link instanceof PDO) {
		return $link->query($sql);
	} else {
		return mysql_query($sql, $link);
	}
}

function fetch_assoc($result) {
	if ($result instanceof PDOStatement) {
		return $result->fetch(PDO::FETCH_ASSOC);
	} else {
		return mysql_fetch_assoc($result);
	}
}

/**
 * Sanitize a database name by removing all non-alphanumerical characters
 *
 * @param $database_name
 * @return string|string[]|null
 */
function sanitize_database($database_name) {
	return preg_replace('/[^A-Za-z0-9_]+/', '', $database_name);
}

function quote_sql($sql) {
	global $link;
	if ($link instanceof PDO) {
		return $link->quote($sql);
	} else {
		return '\'' . mysql_real_escape_string($sql) . '\'';
	}
}

function getDbCharsets($link) {
	static $charsets = array( );
	if ($charsets) {
		return $charsets;
	}

	$charsets ["utf8"] = "UTF-8 Unicode";
	$ut8_available = false;
	if ($result = exec_query("SHOW CHARSET", $link)) {
		while ($row = fetch_assoc($result)) {
			$charsets [$row ["Charset"]] = $row ["Description"];
			if ($row ["Charset"] == "utf8") {
				$ut8_available = true;
			}
		}
	}
	if (!$ut8_available) {
		unset ($charsets ["utf8"]);
	}

	return $charsets;
}

/**
 * Get a list of collations supported by the database engine
 * @param 	database connection $link
 * @param 	string $charset
 * @return	array	Character sets supported by the db, as strings
 */
function getDbCollations($link, $charset) {
	static $collations = array( );

	if ($result = exec_query("SHOW COLLATION WHERE Charset=" . quote_sql($charset), $link)) {
		while ($row = fetch_assoc($result)) {
			$collations [$charset] [$row ["Collation"]] = $row ["Default"]?1:0;
		}
	}

	return $collations [$charset];
}

function validateDbCharset($link, &$charset, &$collation) {
	$error = null;

	if (empty ($charset)) {
		$collation = "";
	}
	if (version_compare(getDBVersion($link), "4.1.0", "lt")) {
		$charset = $collation = "";
	}
	if (empty ($charset) && empty ($collation)) {
		return $error;
	}

	$charsets = getDbCharsets($link);
	if (!isset ($charsets [$charset])) {
		$error = sprintf(ERR_INVALID_DBCHARSET, $charset);
	} else {
		$collations = getDbCollations($link, $charset);
		if (!isset ($collations [$collation])) {
			$error = sprintf(ERR_INVALID_DBCOLLATION, $collation);
		}
	}

	return $error;
}

function getDBVersion($link) {
	if ($link instanceof PDO) {
		return $link->getAttribute(PDO::ATTR_SERVER_VERSION);
	}
	return mysql_get_server_info($link);
}

function xoFormFieldCollation($name, $value, $label, $help = '', $link, $charset) {
	if (version_compare(getDBVersion($link), "4.1.0", "lt")) {
		return "";
	}
	if (empty ($charset) || !$collations = getDbCollations($link, $charset)) {
		return "";
	}

	$label = htmlspecialchars($label);
	$name = htmlspecialchars($name, ENT_QUOTES);
	$value = htmlspecialchars($value, ENT_QUOTES);

	$field = "<label for='$name'>$label</label>\n";
	if ($help) {
		$field .= '<div class="xoform-help">' . $help . "</div><div class='clear'>&nbsp;</div>\n";
	}
	$field .= "<select name='$name' id='$name'\">";

	$collation_default = "";
	$options = "";
	foreach ($collations as $key => $isDefault) {
		if ($isDefault) {
			$collation_default = $key;
			continue;
		}
		$options .= "<option value='{$key}'" . (($value == $key)?" selected='selected'":"") . ">{$key}</option>";
	}
	if ($collation_default) {
		$field .= "<option value='{$collation_default}'" . (($value == $collation_default || empty ($value))?" 'selected'":"") . ">{$collation_default} (Default)</option>";
	}
	$field .= $options;
	$field .= "</select>";

	return $field;
}

function xoFormBlockCollation($name, $value, $label, $help = '', $link, $charset) {
	$block = '<div id="' . $name . '_div">';
	$block .= xoFormFieldCollation($name, $value, $label, $help, $link, $charset);
	$block .= '</div>';

	return $block;
}

function select_db($db_name, $link) {
	if ($link instanceof PDO) {
		try {
			$link->exec("use `" . $db_name . '`;');
			return true;
		} catch (PDOException $ex) {
			return false;
		}
	}
	return @mysql_select_db($db_name, $link);
}

if ($_SERVER ['REQUEST_METHOD'] == 'GET' && isset ($_GET ['charset']) && @$_GET ['action'] == 'updateCollation') {
	echo xoFormFieldCollation('DB_COLLATION', $vars ['DB_COLLATION'], DB_COLLATION_LABEL, DB_COLLATION_HELP, $link, $_GET ['charset']);
	exit ();
}

if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
	$params = array('DB_NAME', 'DB_CHARSET', 'DB_COLLATION', 'DB_PREFIX', 'DB_SALT');
	foreach ($params as $name) {
		$vars [$name] = isset ($_POST [$name])?$_POST [$name]:"";
	}
}

$error = '';
if ($_SERVER ['REQUEST_METHOD'] == 'POST' && !empty ($vars ['DB_NAME'])) {
	$error = validateDbCharset($link, $vars ['DB_CHARSET'], $vars ['DB_COLLATION']);
	$db_exist = false;
	if (empty ($error)) {
		if (!select_db(sanitize_database($vars ['DB_NAME']), $link)) {
			// Database not here: try to create it
			$result = exec_query("CREATE DATABASE " . sanitize_database( $vars ['DB_NAME'] ), $link);
			if (!$result) {
				$error = ERR_NO_DATABASE;
			} else {
				$error = sprintf(DATABASE_CREATED, sanitize_database($vars ['DB_NAME']));
				$db_exist = true;

			}
		} else {
			$db_exist = true;
		}
		if ($db_exist && $vars['DB_CHARSET']) {
			/* Attempt to set the character set and collation to the selected */

			$sql = "ALTER DATABASE " . sanitize_database( $vars ['DB_NAME']) . " DEFAULT CHARACTER SET " . quote_sql($vars ['DB_CHARSET']) . ($vars ['DB_COLLATION']?" COLLATE " . quote_sql($vars ['DB_COLLATION']):"");
			if (!exec_query($sql, $link)) {
				/* if the alter statement fails, set the constants to match existing */
				$sql = "USE " . sanitize_database($vars["DB_NAME"]);
				$result = exec_query($sql, $link);

				/* get the character set variables for the current database */
				$sql = "SHOW VARIABLES like 'character%'";
				$result = exec_query($sql, $link);
				while ($row = fetch_assoc($result)) {
					$character_sets[$row["Variable_name"]] = $row["Value"];
				}
				$vars["DB_CHARSET"] = $character_sets["character_set_database"]
						?$character_sets["character_set_database"]
						: $character_sets["character_set_server"];

				/* get the collation for the current database */
				$sql = "SHOW VARIABLES LIKE 'collation%'";
				$result = exec_query($sql, $link);
				while ($row = fetch_assoc($result)) {
					$collations[$row["Variable_name"]] = $row["Value"];
				}
				$vars["DB_COLLATION"] = $collations["collation_database"]
						?$collations["collation_database"]
						: $collations["collation_server"];
			}
		}
	}
	if (empty ($error)) {
		$wizard->redirectToPage('+1');
		exit ();
	}
}

if (@empty ($vars ['DB_NAME'])) {
	// Fill with default values
	$vars = array_merge($vars, array('DB_NAME' => '', 'DB_CHARSET' => 'utf8', 'DB_COLLATION' => '', 'DB_PREFIX' => 'i' . substr(md5(time()), 0, 8), 'DB_SALT' => icms_core_Password::createSalt()));
}

function xoFormField($name, $value, $label, $maxlength, $help = '') {
	$label = htmlspecialchars($label);
	$name = htmlspecialchars($name, ENT_QUOTES);
	$value = htmlspecialchars($value, ENT_QUOTES);
	$maxlength = (int) ($maxlength);

	$field = "<div class='dbconn_line'><label for='$name'>$label</label>\n";
	if ($help) {
		$field .= '<div class="xoform-help">' . $help . "</div><div class='clear'>&nbsp;</div>\n";
	}
	$field .= "<input type='text' name='$name' id='$name' value='$value' /></div>";

	return $field;
}

function xoFormFieldCharset($name, $value, $label, $help = '', $link) {
	if (version_compare(getDBVersion($link), "4.1.0", "lt")) {
		return "";
	}
	if (!$chars = getDbCharsets($link)) {
		return "";
	}

	$charsets = array( );
	if (isset ($chars ["utf8"])) {
		$charsets ["utf8"] = $chars ["utf8"];
		unset ($chars ["utf8"]);
	}
	ksort($chars);
	$charsets = array_merge($charsets, $chars);

	$label = htmlspecialchars($label);
	$name = htmlspecialchars($name, ENT_QUOTES);
	$value = htmlspecialchars($value, ENT_QUOTES);

	$field = "<div class='dbconn_line'><label for='$name'>$label</label>\n";
	if ($help) {
		$field .= '<div class="xoform-help">' . $help . "</div><div class='clear'>&nbsp;</div>\n";
	}
	$field .= "<select name='$name' id='$name' onchange=\"setFormFieldCollation('DB_COLLATION_div', this.value)\">";
	$field .= "<option value=''>None</option>";
	foreach ($charsets as $key => $desc) {
		$field .= "<option value='{$key}'" . (($value == $key)?" selected='selected'":"") . ">{$key} - {$desc}</option>";
	}
	$field .= "</select></div>";

	return $field;
}

ob_start();
?>

<?php
if (!empty ($error)) {
	echo '<div class="x2-note error">' . $error . "</div>\n";
}
?>
	<script type="text/javascript" src="prototype.js"></script>
	<script type="text/javascript">
		function setFormFieldCollation(id, val) {
			if (val == '') {
				document.getElementById(id).style.display='none';
			} else {
				document.getElementById(id).style.display='display';
			}
			new Ajax.Updater(
					id, '<?php
							echo $_SERVER ['PHP_SELF'];
							?>',
					{ method:'get',parameters:'action=updateCollation&charset='+val }
			);
		}
	</script>
	<div class="blokSQL">
		<fieldset>
			<h3><?php echo LEGEND_DATABASE; ?></h3>
			<?php
			echo xoFormField('DB_NAME', sanitize_database($vars ['DB_NAME']), DB_NAME_LABEL, 255, DB_NAME_HELP);
			?> <?php
			echo xoFormField('DB_PREFIX', $vars ['DB_PREFIX'], DB_PREFIX_LABEL, 10, DB_PREFIX_HELP);
			?> <?php
			echo xoFormField('DB_SALT', $vars ['DB_SALT'], DB_SALT_LABEL, 255, DB_SALT_HELP);
			?> <?php
			echo xoFormFieldCharset('DB_CHARSET', $vars ['DB_CHARSET'], DB_CHARSET_LABEL, DB_CHARSET_HELP, $link);
			?> <?php
			echo xoFormBlockCollation('DB_COLLATION', $vars ['DB_COLLATION'], DB_COLLATION_LABEL, DB_COLLATION_HELP, $link, $vars ['DB_CHARSET']);
			?></fieldset>
	</div>
<?php
$content = ob_get_contents();
ob_end_clean();
include 'install_tpl.php';
