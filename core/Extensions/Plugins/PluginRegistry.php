<?php
namespace ImpressCMS\Core\Extensions\Plugins;

use ImpressCMS\Core\Database\Criteria\CriteriaCompo;
use ImpressCMS\Core\Database\Criteria\CriteriaItem;
use ImpressCMS\Core\File\Filesystem;

/**
 * Handler for plugins
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since	1.2
 * @author	ImpressCMS
 * @author	Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @package	ICMS\Plugins
 *
 * @deprecated  2.0 use services instead!
 */
class PluginRegistry {

	public $pluginPatterns = false;

	/**
	 * Get a plugin object from a path and dirname
	 * @param string $path
	 * @param string $dirname
	 * @return	mixed	A plugin object or False
	 */
	public function getPlugin($path, $dirname) {
		$pluginName = ICMS_PLUGINS_PATH.'/'.$path.'/'.$dirname.'.php';
		if (file_exists($pluginName)) {
			include_once $pluginName;
			$function = 'icms_plugin_'.$dirname;
			if (function_exists($function)) {
				$array = $function();
				return new Plugin($array);
			}
		}
		return false;
	}

	/**
	 * Get an array of plugins
	 * @param string $path
	 * @return array
	 */
	public function getPluginsArray($path) {

		$module_handler = \icms::handler('icms_module');
		$criteria = new CriteriaCompo();
		$criteria->add(new CriteriaItem('isactive', 1));
		$tempModulesObj = $module_handler->getObjects($criteria);
		$modulesObj = array();
		foreach ($tempModulesObj as $moduleObj) {
			$modulesObj[$moduleObj->dirname] = $moduleObj;
		}

		$aFiles = str_replace('.php', '', Filesystem::getFileList(ICMS_PLUGINS_PATH.'/'.$path.'/', '', array('php')));
		$ret = array();
		foreach ($aFiles as $pluginName) {
			$module_xoops_version_file = ICMS_MODULES_PATH."/$pluginName/xoops_version.php";
			$module_icms_version_file = ICMS_MODULES_PATH."/$pluginName/icms_version.php";
			if ((file_exists($module_xoops_version_file) || file_exists($module_icms_version_file)) && isset($modulesObj[$pluginName])) {
				$ret[$pluginName] = $modulesObj[$pluginName]->name;
			}
		}
		return $ret;
	}
}
