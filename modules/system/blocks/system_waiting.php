<?php
/**
 * All the blocks that are awaiting approval or admin intervention
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		System
 * @subpackage	Blocks
 */

/**
 * EXTENSIBLE "waiting block" by plugins in both waiting and modules
 *
 * @param array $options The options for this block
 * @return mixed $block The generated waiting block or empty array
 */
function b_system_waiting_show($options) {
	global $icmsConfig;

	$userlang = $icmsConfig['language'];

	$cache = icms::getInstance()->get('cache');
	$sql_cache_min = empty($options[1])?0:(int) $options[1];
	$sql_cached_item = $cache->getItem('waiting_touch');
	if ($sql_cached_item->isHit()) {
		return [];
	}

	// read language files for plugins
	icms_loadLanguageFile('system', 'plugins');

	$plugins_path = ICMS_PLUGINS_PATH . "/waiting";
	$module_handler = icms::handler('icms_module');
	$block = array();

	// get module's list installed
	$mod_lists = $module_handler->getList(new icms_db_criteria_Item(1, 1), true);
	foreach ($mod_lists as $dirname => $name) {

		$plugin_info = system_get_plugin_info($dirname, $icmsConfig['language']);
		if (empty($plugin_info) || empty($plugin_info['plugin_path'])) {
			continue;
		}

		if (!empty($plugin_info['langfile_path'])) {
			include_once $plugin_info['langfile_path'];
		}
		include_once $plugin_info['plugin_path'];

		// call the plugin
		if (function_exists(@$plugin_info['func'])) {
			// get the list of waitings
			$_tmp = call_user_func($plugin_info['func'], $dirname);
			if (isset($_tmp["lang_linkname"])) {
				if (@$_tmp["pendingnum"] > 0 || $options[0] > 0) {
					$block["modules"][$dirname]["pending"][] = $_tmp;
				}
				unset($_tmp);
			} else {
				// Judging the plugin returns multiple items
				// if lang_linkname does not exist
				foreach ($_tmp as $_one) {
					if (@$_one["pendingnum"] > 0 || $options[0] > 0) {
						$block["modules"][$dirname]["pending"][] = $_one;
					}
				}
			}
		}

		// for older compatibilities
		// Hacked by GIJOE
		$i = 0;
		while (1) {
			$function_name = "b_waiting_{$dirname}_$i";
			if (function_exists($function_name)) {
				$_tmp = call_user_func($function_name);
				++$i;
				if ($_tmp["pendingnum"] > 0 || $options[0] > 0) {
					$block["modules"][$dirname]["pending"][] = $_tmp;
				}
				unset($_tmp);
			} else {
				break;
			}
		}
		// End of Hack

		// if (count($block["modules"][$dirname]) > 0) {
		if (!empty($block["modules"][$dirname])) {
			$block["modules"][$dirname]["name"] = $name;
		}
	}

	if (empty($block) && $sql_cache_min > 0) {
		$time = new DateTime();
		$time->add(
			date_interval_create_from_date_string($sql_cache_min . ' minutes')
		);
		$sql_cached_item->expiresAt($time);
		$cache->save($sql_cached_item);
	}

	return $block;
}

/**
 * The edit "waiting block" form
 *
 * @param array $options The options for this block
 * @return string $form The Edit waiting block form HTML string
 */
function b_system_waiting_edit($options) {

	$sql_cache_min = empty($options[1])?0:(int) $options[1];

	$form = _MB_SYSTEM_NOWAITING_DISPLAY . ":&nbsp;<input type='radio' name='options[0]' value='1'";
	if ($options[0] == 1) {
		$form .= " checked='checked'";
	}
	$form .= " />&nbsp;" . _YES . "<input type='radio' name='options[0]' value='0'";
	if ($options[0] == 0) {
		$form .= " checked='checked'";
	}
	$form .= " />&nbsp;" . _NO . "<br />\n";
	$form .= sprintf(_MINUTES, _MB_SYSTEM_SQL_CACHE . ":&nbsp;<input type='text' name='options[1]' value='$sql_cache_min' size='2' />");

	return $form;
}

/**
 * Gets the plugin information
 *
 * @param string $dirname The directory to get the plugin from
 * @param string $language The language for the plugin
 * @return array $ret The plugin information array or an empty array
 */
function system_get_plugin_info($dirname, $language = 'english') {
	$module_plugin_file = ICMS_MODULES_PATH . "/" . $dirname . "/include/waiting.plugin.php";
	$builtin_plugin_file = ICMS_PLUGINS_PATH . "/waiting/" . $dirname . ".php";

	if (file_exists($module_plugin_file)) {
		// module side (1st priority)
		$lang_files = array(
		ICMS_MODULES_PATH . "/$dirname/language/$language/waiting.php",
		ICMS_MODULES_PATH . "/$dirname/language/english/waiting.php",
		);
		$langfile_path = '';
		foreach ($lang_files as $lang_file) {
			if (file_exists($lang_file)) {
				$langfile_path = $lang_file;
				break;
			}
		}
		$ret = array(
			'plugin_path' => $module_plugin_file,
			'langfile_path' => $langfile_path,
			'func' => 'b_waiting_' . $dirname,
			'type' => 'module',
		);
	} else if (file_exists($builtin_plugin_file)) {
		// built-in plugin under modules/waiting (3rd priority)
		$ret = array(
			'plugin_path' => $builtin_plugin_file,
			'langfile_path' => '',
			'func' => 'b_waiting_' . $dirname,
			'type' => 'built-in',
		);
	} else {
		$ret = array();
	}

	return $ret;
}

