<?php

//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //
/**
 * icms_view_theme_Object component class file
 *
 * @license		http://www.fsf.org/copyleft/gpl.html GNU public license
 */

/**
 * icms_view_theme_Factory
 *
 * @author	Skalpa Keo <skalpa@xoops.org>
 * @copyright	Copyright (c) 2000 XOOPS.org
 * @package	ICMS\View\Theme
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
	 * Gets theme factory for existing configuration
	 *
	 * @global      array                    $icmsConfig    ICMS Configuration array
	 * @staticvar   \icms_view_theme_Factory $themeFactory  Current instance of theme factory
	 * @return      \icms_view_theme_Factory                Returns preconfigured icms_view_theme_Factory object
	 */
	public static function getInstance() {
		static $themeFactory = null;
		if ($themeFactory === null) {
			global $icmsConfig;
			$themeFactory = new \icms_view_theme_Factory();
			$themeFactory->allowedThemes = $icmsConfig['theme_set_allowed'];
			$themeFactory->defaultTheme = $icmsConfig['theme_set'];
		}

		return $themeFactory;
	}

	/**
	 * Instanciate the specified theme
	 */
	public function &createInstance($options = array(), $initArgs = array()) {
		// Grab the theme folder from request vars if present
		if (!isset($options['folderName']) || empty($options['folderName'])) {
			// xoops_theme_select still exists to keep compatibilitie ...
			if ((!empty($_REQUEST['theme_select'])) && $this->isThemeAllowed($_REQUEST['theme_select'])) {
				$options['folderName'] = $_REQUEST['theme_select'];
				if (isset($_SESSION) && $this->allowUserSelection) {
					$_SESSION[$this->xoBundleIdentifier]['defaultTheme'] = $_REQUEST['theme_select'];
				}
			} elseif (isset($_SESSION[$this->xoBundleIdentifier]['defaultTheme'])) {
				$options['folderName'] = $_SESSION[$this->xoBundleIdentifier]['defaultTheme'];
			} elseif ((isset($options['folderName']) && empty($options['folderName'])) || !$this->isThemeAllowed($options ['folderName'])) {
				$options['folderName'] = $this->defaultTheme;
			}
			$GLOBALS['icmsConfig']['theme_set'] = $options['folderName'];
		}
		$options['path'] = (is_dir(ICMS_MODULES_PATH . '/system/themes/' . $options['folderName']))? ICMS_MODULES_PATH . '/system/themes/' . $options['folderName']:ICMS_THEME_PATH . '/' . $options['folderName'];
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

	/**
	 * Gets list of themes folder from themes directory, excluding any directories that do not have theme.html
	 * @return	array
	 */
	static public function getThemesList() {
		$dirlist = [];
		$fs = icms::getInstance()->get('filesystem');
		foreach ($fs->listContents('themes://') as $fileInfo) {
			$file = $fileInfo['basename'];
			if (substr($file, 0, 1) == '.' || $fs->has('themes://' . $file . '/theme.html') === false) {
				continue;
			}
			$dirlist[$file] = $file;
		}
		return $dirlist;
	}

	/**
	 * Gets list of administration themes folder from themes directory, excluding any directories that do not have theme_admin.html
	 * @return	array
	 */
	static public function getAdminThemesList() {
		$items = [];
		$fs = icms::getInstance()->get('filesystem');
		foreach ($fs->listContents('themes://') as $fileInfo) {
			$file = $fileInfo['basename'];
			if (substr($file, 0, 1) == '.' || $fs->has('themes://' . $file . '/theme_admin.html') === false) {
				continue;
			}
			$items[$file] = $file;
		}

		foreach ($fs->listContents('modules://system/themes') as $fileInfo) {
			$file = $fileInfo['basename'];
			if (substr($file, 0, 1) == '.' || $fs->has('modules://system/themes/' . $file . '/theme.html') === false) {
				continue;
			}
			$items[$file] = $file;
		}

		return $items;
	}

}