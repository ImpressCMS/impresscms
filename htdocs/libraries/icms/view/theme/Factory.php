<?php
/**
 * icms_view_theme_Object component class file
 *
 * @license		http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author		Skalpa Keo <skalpa@xoops.org>
 * @category	ICMS
 * @package 	View
 * @subpackage 	Theme
 * @version		SVN: $Id$
 */

/**
 * icms_view_theme_Factory
 *
 * @author		Skalpa Keo
 * @category	ICMS
 * @package		View
 * @subpackage	Theme
 */
class icms_view_theme_Factory {

	public $xoBundleIdentifier = 'icms_view_theme_Factory';
	/**
	 * Currently enabled themes (if empty, all the themes in themes/ are allowed)
	 * @public array
	 */
	public $allowedThemes = array();
	/**
	 * Default theme to instanciate if none specified
	 * @public string
	 */
	public $defaultTheme = 'iTheme';
	/**
	 * If users are allowed to choose a custom theme
	 * @public bool
	 */
	public $allowUserSelection = true;

	/**
	 * Instanciate the specified theme
	 */
	public function &createInstance($options = array(), $initArgs = array()) {
		// Grab the theme folder from request vars if present
		if (@empty($options['folderName'])) {
			// xoops_theme_select still exists to keep compatibilitie ...
			if (($req = @$_REQUEST['xoops_theme_select']) && $this->isThemeAllowed($req)) {
				$options['folderName'] = $req;
				if (isset($_SESSION) && $this->allowUserSelection) {
					$_SESSION[$this->xoBundleIdentifier]['defaultTheme'] = $req;
				}
			} elseif (($req = @$_REQUEST['theme_select']) && $this->isThemeAllowed($req)) {
				$options['folderName'] = $req;
				if (isset($_SESSION) && $this->allowUserSelection) {
					$_SESSION[$this->xoBundleIdentifier]['defaultTheme'] = $req;
				}
			} elseif (isset($_SESSION[$this->xoBundleIdentifier]['defaultTheme'])) {
				$options['folderName'] = $_SESSION[$this->xoBundleIdentifier]['defaultTheme'];
			} elseif (@empty($options['folderName'] ) || ! $this->isThemeAllowed($options ['folderName'])) {
				$options['folderName'] = $this->defaultTheme;
			}
			$GLOBALS['xoopsConfig']['theme_set'] = $options['folderName'];
		}
		$options['path'] =
			(is_dir(ICMS_MODULES_PATH . '/system/themes/' . $options['folderName']))
			? ICMS_MODULES_PATH . '/system/themes/' . $options['folderName']
			: ICMS_THEME_PATH . '/' . $options['folderName'];
		$inst = new icms_view_theme_Object();
		foreach ($options as $k => $v) {
			$inst->$k = $v;
		}
		$inst->xoInit();
		return $inst;
	}

	/**
	 * Checks if the specified theme is enabled or not
	 * @param string $name
	 * @return bool
	 */
	public function isThemeAllowed($name) {
		return (empty($this->allowedThemes) || in_array($name, $this->allowedThemes));
	}
}

