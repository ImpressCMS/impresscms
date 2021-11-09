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
 * @license		https://www.gnu.org/licenses/old-licenses/gpl-2.0.html GPLv2 or later license
 */

namespace ImpressCMS\Core\View\Theme;

use icms;
use ImpressCMS\Core\Extensions\ExtensionDescriber\ExtensionDescriberInterface;
use League\Flysystem\Filesystem;
use League\Flysystem\StorageAttributes;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;

/**
 * Theme Factory
 *
 * @author	Skalpa Keo <skalpa@xoops.org>
 * @copyright	Copyright (c) 2000 XOOPS.org
 * @package	ICMS\View\Theme
 */
class ThemeFactory {

	public $xoBundleIdentifier = 'icms_view_theme_Factory';

	/**
	 * Currently enabled themes (if empty, all the themes in themes/ are allowed)
	 * @public array
	 */
	public $allowedThemes = [];

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
	 * @return ThemeFactory Returns preconfigured instance
	 * @global      array $icmsConfig ICMS Configuration array
	 * @staticvar   ThemeFactory $themeFactory  Current instance of theme factory
	 */
	public static function getInstance() {
		static $themeFactory = null;
		if ($themeFactory === null) {
			global $icmsConfig;
			$themeFactory = new self();
			$themeFactory->allowedThemes = $icmsConfig['theme_set_allowed'];
			$themeFactory->defaultTheme = $icmsConfig['theme_set'];
		}

		return $themeFactory;
	}

	/**
	 * Gets theme info
	 *
	 * @param string $path Path where is theme located
	 *
	 * @return array
	 *
	 * @throws InvalidArgumentException
	 */
	protected static function getThemeInfo(string $path) {
		global $icmsConfig;

		/**
		 * @var CacheItemPoolInterface $cache
		 */
		$cache = icms::getInstance()->get('cache');
		$cachedThemeInfo = $cache->getItem('theme.' . $icmsConfig['language'] . '.' . sha1($path));

		if (!$cachedThemeInfo->isHit()) {
			$info = [];
			/**
			 * @var ExtensionDescriberInterface $extensionDescriber
			 */
			foreach (icms::getInstance()->get('extension_describer.theme') as $extensionDescriber) {
				if (!$extensionDescriber->canDescribe($path)) {
					continue;
				}
				$info += $extensionDescriber->describe($path);
			}

			$cachedThemeInfo->set($info);
			$cache->save($cachedThemeInfo);
		} else {
			$info = $cachedThemeInfo->get();
		}

		return $info;
	}

	/**
	 * Gets list of themes folder from themes directory, excluding any directories that do not have theme.html
	 *
	 * @return	array
	 */
	public static function getThemesList()
	{
		$dirlist = [];
		/**
		 * @var Filesystem $fs
		 */
		$fs = icms::getInstance()->get('filesystem.themes');
		/**
		 * @var StorageAttributes $fileInfo
		 */
		foreach ($fs->listContents('') as $fileInfo) {
			if (!$fileInfo->isDir()) {
				continue;
			}
			$themeInfo = self::getThemeInfo(ICMS_THEME_PATH . DIRECTORY_SEPARATOR . $fileInfo->path());
			if (!$themeInfo['hasUser']) {
				continue;
			}
			$file = $fileInfo->path();
			$dirlist[$file] = $themeInfo['name'];
		}
		return $dirlist;
	}

	/**
	 * Gets list of administration themes folder from themes directory, excluding any directories that do not have theme_admin.html
	 *
	 * @return	array
	 */
	public static function getAdminThemesList()
	{
		$items = [];
		/**
		 * @var Filesystem $fs
		 */
		$fs = icms::getInstance()->get('filesystem.themes');
		/**
		 * @var StorageAttributes $fileInfo
		 */
		foreach ($fs->listContents('') as $fileInfo) {
			if (!$fileInfo->isDir()) {
				continue;
			}
			$themeInfo = self::getThemeInfo(ICMS_THEME_PATH . DIRECTORY_SEPARATOR . $fileInfo->path());
			if (!$themeInfo['hasAdmin']) {
				continue;
			}
			$file = $fileInfo->path();
			$items[$file] = $themeInfo['name'];
		}

		/**
		 * @var Filesystem $fm
		 */
		$fm = icms::getInstance()->get('filesystem.modules');
		foreach ($fm->listContents('system/themes') as $fileInfo) {
			if (!$fileInfo->isDir()) {
				continue;
			}
			$filename = mb_substr($fileInfo->path(), mb_strlen('system/themes/'));

			$themeInfo = self::getThemeInfo(ICMS_MODULES_PATH . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'themes' . DIRECTORY_SEPARATOR . $filename);
			if (!$themeInfo['hasAdmin']) {
				continue;
			}
			$file = $filename;
			$items[$file] = $themeInfo['name'];
		}

		return $items;
	}

	/**
	 * Instanciate the specified theme
	 * @param array $options
	 * @param array $initArgs
	 * @return ThemeComponent
	 */
	public function &createInstance($options = [], $initArgs = [])
	{

		if (!isset( $options['folderName'])) {
			$options['folderName'] = $GLOBALS['icmsConfig']['theme_set'];
		}

		$options['path'] = (is_dir(ICMS_MODULES_PATH . '/system/themes/' . $options['folderName'])) ? ICMS_MODULES_PATH . '/system/themes/' . $options['folderName'] : ICMS_THEME_PATH . '/' . $options['folderName'];

		$inst = new ThemeComponent();
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
	public function isThemeAllowed($name)
	{
		return (empty($this->allowedThemes) || in_array($name, $this->allowedThemes, true));
	}

}
