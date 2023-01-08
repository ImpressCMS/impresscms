<?php
/**
 * Installer database configuration page
 *
 * See the enclosed file license.txt for licensing information.
 * If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
 *
 * @copyright   The XOOPS project http://www.xoops.org/
 * @license     http://www.fsf.org/copyleft/gpl.html GNU General Public License (GPL)
 * @package        installer
 * @since       2.3.0
 * @author        Haruki Setoyama  <haruki@planewave.org>
 * @author        Kazumi Ono <webmaster@myweb.ne.jp>
 * @author        Skalpa Keo <skalpa@xoops.org>
 * @author        Taiwen Jiang <phppp@users.sourceforge.net>
 */
/**
 *
 */
define('DB_NO_AUTO_SELECT', 1);
require_once 'common.inc.php';
if (!defined('XOOPS_INSTALL')) {
	exit ();
}

$wizard->setPage('dbsettings');
$pageHasForm = true;
$pageHasHelp = true;

$vars = &$_SESSION ['settings'];

/**
 * @var \icms_db_Connection $db
 */
try {
	$db = \icms::getInstance()->get('db');
} catch (Exception $ex) {
	$error = ERR_NO_DBCONNECTION;
}

if (isset($error)) {
	$wizard->redirectToPage('-1', $error);
	exit ();
}

function getDbCharsets()
{
	static $charsets = null;
	if ($charsets !== null) {
		return $charsets;
	}

	global $db;

	$charsets = [];
	foreach ($db->fetchAssoc("SHOW CHARSET") as $row) {
		$charsets [$row ["Charset"]] = $row ["Description"];
	}

	return $charsets;
}

/**
 * Get a list of collations supported by the database engine
 * @param    string $charset
 * @return    array    Character sets supported by the db, as strings
 */
function getDbCollations($charset)
{
	static $collations = array();

	global $db;

	if (!isset($collations [$charset])) {
		$collations[$charset] = [];
			foreach ($db->fetchAssoc('SHOW COLLATION WHERE Charset=:cht', ['cht' => $charset]) as $row) {
				$collations[$charset][$row ["Collation"]] = $row["Default"] ? 1 : 0;
			}
	}

	return $collations [$charset];
}

function validateDbCharset(&$charset, &$collation)
{
	$error = null;

	if (empty ($charset)) {
		$collation = "";
	}
	if (version_compare(getDBVersion(), "4.1.0", "lt")) {
		$charset = $collation = "";
	}
	if (empty ($charset) && empty ($collation)) {
		return $error;
	}

	$charsets = getDbCharsets();
	if (!isset ($charsets [$charset])) {
		$error = sprintf(ERR_INVALID_DBCHARSET, $charset);
	} else {
		$collations = getDbCollations($charset);
		if (!isset ($collations [$collation])) {
			$error = sprintf(ERR_INVALID_DBCOLLATION, $collation);
		}
	}

	return $error;
}

function getDBVersion()
{
	global $db;
	return $db->getAttribute(PDO::ATTR_SERVER_VERSION);
}

function xoFormFieldCollation($name, $value, $label, $help = '', $charset)
{
	if (version_compare(getDBVersion(), "4.1.0", "lt")) {
		return "";
	}
	if (empty ($charset) || !$collations = getDbCollations($charset)) {
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
		$options .= "<option value='{$key}'" . (($value == $key) ? " selected='selected'" : "") . ">{$key}</option>";
	}
	if ($collation_default) {
		$field .= "<option value='{$collation_default}'" . (($value == $collation_default || empty ($value)) ? " 'selected'" : "") . ">{$collation_default} (Default)</option>";
	}
	$field .= $options;
	$field .= "</select>";

	return $field;
}

function xoFormBlockCollation($name, $value, $label, $help = '', $charset)
{
	$block = '<div id="' . $name . '_div">';
	$block .= xoFormFieldCollation($name, $value, $label, $help, $charset);
	$block .= '</div>';

	return $block;
}

function select_db($db_name)
{
	global $db;
	try {
		$db->exec("use `" . $db_name . '`;');
		return true;
	} catch (PDOException $ex) {
		return false;
	}
}

if ($_SERVER ['REQUEST_METHOD'] == 'GET' && isset ($_GET ['charset']) && @$_GET ['action'] == 'updateCollation') {
	echo xoFormFieldCollation('DB_COLLATION', $vars ['DB_COLLATION'], DB_COLLATION_LABEL, DB_COLLATION_HELP, $_GET ['charset']);
	exit ();
}

if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
	$params = array('DB_NAME', 'DB_CHARSET', 'DB_COLLATION', 'DB_PREFIX', 'APP_KEY');
	foreach ($params as $name) {
		$vars [$name] = isset ($_POST [$name]) ? $_POST [$name] : "";
	}
}

$error = '';
if ($_SERVER ['REQUEST_METHOD'] == 'POST' && !empty ($vars ['DB_NAME'])) {
	$error = validateDbCharset($vars ['DB_CHARSET'], $vars ['DB_COLLATION']);
	$db_exist = false;
	if (empty ($error)) {
		if (!select_db($vars['DB_NAME'])) {
			// Database not here: try to create it
			try {
				$db->exec("CREATE DATABASE `" . $vars ['DB_NAME'] . '`');
				$error = sprintf(DATABASE_CREATED, $vars ['DB_NAME']);
				$db_exist = true;
			} catch (\Exception $exception) {
				$error = ERR_NO_DATABASE;
			}
		} else {
			$db_exist = true;
		}
		if ($db_exist && $vars['DB_CHARSET']) {
			/* Attempt to set the character set and collation to the selected */
			if (!$db->perform(
				"ALTER DATABASE `" . $vars['DB_NAME'] . "` DEFAULT CHARACTER SET :chr COLLATE :collation",
				[
					'chr' => $vars['DB_CHARSET'],
					'collation' => $vars['DB_COLLATION']
				])
			) {
				/* if the alter statement fails, set the constants to match existing */
				$result = $db->perform("USE :db;", ['db' => $vars["DB_NAME"]]);

				/* get the character set variables for the current database */
				$sql = "SHOW VARIABLES like 'character%'";
				foreach ($db->fetchAssoc($sql) as $row) {
					$character_sets[$row["Variable_name"]] = $row["Value"];
				}
				$vars["DB_CHARSET"] = $character_sets["character_set_database"]
					? $character_sets["character_set_database"]
					: $character_sets["character_set_server"];

				/* get the collation for the current database */
				$sql = "SHOW VARIABLES LIKE 'collation%'";
				foreach ($db->fetchAssoc($sql) as $row) {
					$collations[$row["Variable_name"]] = $row["Value"];
				}
				$vars["DB_COLLATION"] = $collations["collation_database"]
					? $collations["collation_database"]
					: $collations["collation_server"];
			}
		}
	}
	if (empty ($error)) {
		$_SESSION['settings'] = array_merge($_SESSION['settings'], $vars);
		$wizard->redirectToPage('+1');
		exit ();
	}
}

if (@empty ($vars ['DB_NAME'])) {
	// Fill with default values
	$vars = array_merge($vars, [
			'DB_NAME' => '',
			'DB_CHARSET' => 'utf8',
			'DB_COLLATION' => '',
			'DB_PREFIX' => 'i' . substr(md5(time()), 0, 8),
			'APP_KEY' => \Defuse\Crypto\Key::createNewRandomKey()->saveToAsciiSafeString(),
	]);
}

function xoFormField($name, $value, $label, $maxlength, $help = '')
{
	$label = htmlspecialchars($label);
	$name = htmlspecialchars($name, ENT_QUOTES);
	$value = htmlspecialchars($value, ENT_QUOTES);
	$maxlength = (int)($maxlength);

	$field = "<div class='dbconn_line'><label for='$name'>$label</label>\n";
	if ($help) {
		$field .= '<div class="xoform-help">' . $help . "</div><div class='clear'>&nbsp;</div>\n";
	}
	$field .= "<input type='text' name='$name' id='$name' value='$value' /></div>";

	return $field;
}

function xoFormFieldCharset($name, $value, $label, $help = '')
{
	if (version_compare(getDBVersion(), "4.1.0", "lt")) {
		return "";
	}

	if (!$chars = getDbCharsets()) {
		return "";
	}

	$charsets = array();
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
		$field .= "<option value='{$key}'" . (($value == $key) ? " selected='selected'" : "") . ">{$key} - {$desc}</option>";
	}
	$field .= "</select></div>";

	return $field;
}

ob_start();
?>

<?php
if (!empty ($error)) {
	echo '<div class="x2-note error">' . htmlentities($error) . "</div>\n";
}
?>
	<script type="text/javascript">
		function setFormFieldCollation(id, val) {
			if (val == '') {
				document.getElementById(id).style.display = 'inline';
			} else {
				document.getElementById(id).style.display = 'display';
			}
			new Ajax.Updater(
				id, '<?php
					echo $_SERVER ['PHP_SELF'];
					?>',
				{method: 'get', parameters: 'action=updateCollation&charset=' + val}
			);
		}
	</script>
	<div class="blokSQL">
		<fieldset>
			<h3><?php echo LEGEND_DATABASE; ?></h3>
			<?php
			echo xoFormField('DB_NAME', $vars ['DB_NAME'], DB_NAME_LABEL, 255, DB_NAME_HELP);
			?> <?php
			echo xoFormField('DB_PREFIX', $vars ['DB_PREFIX'], DB_PREFIX_LABEL, 10, DB_PREFIX_HELP);
			?> <?php
			echo xoFormField('APP_KEY', $vars ['APP_KEY'], APP_KEY_LABEL, 255, APP_KEY_HELP);
			?> <?php
			echo xoFormFieldCharset('DB_CHARSET', $vars['DB_CHARSET'], DB_CHARSET_LABEL, DB_CHARSET_HELP);
			?> <?php
			echo xoFormBlockCollation('DB_COLLATION', $vars ['DB_COLLATION'], DB_COLLATION_LABEL, DB_COLLATION_HELP, $vars ['DB_CHARSET']);
			?></fieldset>
	</div>
<?php
$content = ob_get_contents();
ob_end_clean();
include 'install_tpl.php';
