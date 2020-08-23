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
 * The templates class that extends Smarty
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	LICENSE.txt
 * @author	modified by UnderDog <underdog@impresscms.org>
 */

namespace ImpressCMS\Core\View;

use icms;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use SmartyBC;
use SmartyException;

/**
 * Template engine
 *
 * @package	ICMS\View
 * @author	Kazumi Ono 	<onokazu@xoops.org>
 * @copyright	Copyright (c) 2000 XOOPS.org
 */
class Template extends SmartyBC
{

	public $left_delimiter = '<{';
	public $right_delimiter = '}>';

	public $template_dir = ICMS_THEME_PATH;
	public $cache_dir = ICMS_CACHE_PATH;
	public $compile_dir = ICMS_COMPILE_PATH;

	/**
	 * @var Theme\ThemeComponent
	 */
	public $currentTheme;

	public function __construct() {
		global $icmsConfig, $icmsModule;

		$this->compile_id = $icmsConfig['template_set'] . '-' . $icmsConfig['theme_set'];
		$this->compile_check = ( (int)$icmsConfig['theme_fromfile'] === 1 );

		parent::__construct();

		foreach (\icms::getInstance()->get('smarty.resource') as $plugin) {
			$this->registerResource(
				$plugin->getName(),
				$plugin
			);
		}
		foreach ([
					 'function' => 'register_function',
					 'modifier' => 'register_modifier',
					 'compiler' => 'register_compiler_function',
				 ] as $type => $function) {
			foreach (\icms::getInstance()->get('smarty.' . $type) as $plugin) {
				$this->$function(
					$plugin->getName(),
					[$plugin, 'execute']
				);
			}
		}

		if ($icmsConfig['debug_mode']) {
			$this->debugging_ctrl = 'URL';
			$groups = (is_object(icms::$user))? icms::$user->getGroups(): [ICMS_GROUP_ANONYMOUS];
			$moduleid = (isset($icmsModule) && is_object($icmsModule))?$icmsModule->getVar('mid'):1;
			$gperm_handler = icms::handler('icms_member_groupperm');
			if ((int)$icmsConfig['debug_mode'] === 3 && $gperm_handler->checkRight('enable_debug', $moduleid, $groups)) {
				$this->debugging = true;
			}
		}
		if (defined('_ADM_USE_RTL') && _ADM_USE_RTL) {
			$this->assign('icms_rtl', true);
		}

		$this->assign(
			[
			'icms_url' => ICMS_URL,
			'icms_rootpath' => ICMS_ROOT_PATH,
			'modules_url' => ICMS_MODULES_URL,
			'modules_rootpath' => ICMS_MODULES_PATH,
			'icms_langcode' => _LANGCODE,
			'icms_langname' => $GLOBALS['icmsConfig']['language'],
			'icms_charset' => _CHARSET,
			'icms_version' => ICMS_VERSION_NAME,
			'icms_upload_url' => ICMS_UPLOAD_URL,
			'xoops_url' => ICMS_URL,
			'xoops_rootpath' => ICMS_ROOT_PATH,
			'xoops_langcode' => _LANGCODE,
			'xoops_charset' => _CHARSET,
			'xoops_version' => ICMS_VERSION_NAME,
			'xoops_upload_url' => ICMS_UPLOAD_URL,
			'globals' => $GLOBALS
			]
		);

		$this->registerObject('icms', icms::getInstance(),null,false);
	}

	/**
	 * Renders output from template data
	 *
	 * @param string $tplSource The template to render
	 * @param bool $display If rendered text should be output or returned
	 * @return  string            Rendered output if $display was false
	 * @throws SmartyException
	 */
	public function fetchFromData($tplSource, $display = false, $vars = null) {
		if (isset($vars)) {
			$oldVars = $this->_tpl_vars;
			$this->assign($vars);
			$out = $this->fetch('eval:' . $tplSource);
			$this->_tpl_vars = $oldVars;
			return $out;
		}
		return $this->fetch('eval:' . $tplSource);
	}

	/**
	 * Touch the resource (file) which means get it to recompile the resource
	 *
	 * @param   string  $resourceName	Resource name to touch
	 *
	 * @return  int
	 */
	public function touch($resourceName) {
		return $this->clearCache($resourceName);
	}

	/**
	 * Touch template by id
	 *
	 * @param string $tpl_id
	 *
	 * @return  boolean
	 * @throws InvalidArgumentException
	 */
	public static function template_touch($tpl_id) {
		$tplFileHandler = & icms::handler('icms_view_template_file');
		$tplFile = & $tplFileHandler->get($tpl_id);

		if (!is_object($tplFile)) {
			return false;
		}

		$file = $tplFile->tpl_file;

		/**
		 * @var CacheItemPoolInterface $cache
		 */
		$cache = icms::getInstance()->get('cache');
		$cache->deleteItem('tpl_db_' . base64_encode($file));

		$tpl = new Template();

		$tpl->touch("db:$file");

		return true;
	}

	/**
	 * Clear the module cache
	 *
	 * @param   int $mid    Module ID
	 */
	public static function template_clear_module_cache($mid) {
		$icms_block_handler = icms::handler('icms_view_block');
		$block_arr = $icms_block_handler->getByModule($mid);
		$count = count($block_arr);
		if ($count > 0) {
			$tpl = new self();
			$tpl->caching = 2;
			for ($i = 0; $i < $count; $i++) {
				if ($block_arr[$i]->template) {
					$tpl->clear_cache('db:' . $block_arr[$i]->template, 'blk_' . $block_arr[$i]->bid);
				}
			}
		}
	}
}