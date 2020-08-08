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
/**
 * Manage configuration items
 *
 * @copyright    Copyright (c) 2000 XOOPS.org
 * @copyright    http://www.impresscms.org/ The ImpressCMS Project
 * @license        http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @author        Kazumi Ono (aka onokazo)
 */

namespace ImpressCMS\Core\Facades;

use ImpressCMS\Core\Database\Criteria\CriteriaCompo;
use ImpressCMS\Core\Database\Criteria\CriteriaElement;
use ImpressCMS\Core\Database\Criteria\CriteriaItem;
use ImpressCMS\Core\Models\ConfigItem;
use ImpressCMS\Core\Models\ConfigItemHandler;
use ImpressCMS\Core\Models\ConfigOption;
use ImpressCMS\Core\Models\ConfigOptionHandler;

/**
 * Configuration handling class.
 * This class acts as an interface for handling general configurations
 * and its modules.
 *
 * @package    ICMS\Config
 * @author    Kazumi Ono <webmaster@myweb.ne.jp>
 * @copyright    copyright (c) 2000-2003 XOOPS.org
 * @todo    Tests that need to be made:
 *                  - error handling
 * @access    public
 */
class Config extends AbstractFacade
{
	static protected $instance;

	/**
	 * Main (default) category
	 */
	public const CATEGORY_MAIN = 1;

	/**
	 * User category
	 */
	public const CATEGORY_USER = 2;

	/**
	 * Meta & footer category
	 */
	public const CATEGORY_METAFOOTER = 3;

	/**
	 * Censorship category
	 */
	public const CATEGORY_CENSOR = 4;

	/**
	 * Search catageory
	 */
	public const CATEGORY_SEARCH = 5;

	/**
	 * Mailer category
	 */
	public const CATEGORY_MAILER = 6;

	/**
	 * Authentification category
	 */
	public const CATEGORY_AUTH = 7;

	/**
	 * Multilanguage configuration
	 */
	public const CATEGORY_MULILANGUAGE = 8;

	/**
	 * Content category
	 */
	public const CATEGORY_CONTENT = 9;

	/**
	 * Persona category
	 */
	public const CATEGORY_PERSONA = 10;

	/**
	 * Captcha category
	 */
	public const CATEGORY_CAPTCHA = 11;

	/**
	 * Plugins category
	 */
	public const CATEGORY_PLUGINS = 12;

	/**
	 * Autotasks category
	 */
	public const CATEGORY_AUTOTASKS = 13;

	/**
	 * Purifier category
	 */
	public const CATEGORY_PURIFIER = 14;

	/**
	 * holds reference to config item handler(DAO) class
	 *
	 * @var     object
	 * @access    private
	 */
	private $_cHandler;

	/**
	 * holds reference to config option handler(DAO) class
	 *
	 * @var        object
	 * @access    private
	 */
	private $_oHandler;

	/**
	 * holds an array of cached references to config value arrays,
	 *  indexed on module id and category id
	 *
	 * @var     array
	 * @access  private
	 */
	private $_cachedConfigs = array();

	/**
	 * Constructor
	 *
	 * @param object  &$db reference to database object
	 */
	public function __construct(&$db)
	{
		$this->_cHandler = new ConfigItemHandler($db);
		$this->_oHandler = new ConfigOptionHandler($db);
	}

	/**
	 * Create a config
	 *
	 * @return ConfigItem
	 * @see     ConfigItemHandler
	 */
	public function &createConfig()
	{
		return $this->_cHandler->create();
	}

	/**
	 * Get a config
	 *
	 * @param int $id ID of the config
	 * @param bool $withoptions load the config's options now?
	 * @return    ConfigItem
	 */
	public function &getConfig($id, $withoptions = false)
	{
		$config = &$this->_cHandler->get($id);
		if ($withoptions) {
			$config->setConfOptions($this->getConfigOptions(new CriteriaItem('conf_id', $id)));
		}
		return $config;
	}

	/**
	 * insert a new config in the database
	 *
	 * @param ConfigItem  &$config reference
	 * @return    true|false if inserting config succeeded or not
	 */
	public function insertConfig(&$config)
	{
		if (!$this->_cHandler->insert($config)) {
			return false;
		}
		$options = &$config->getConfOptions();
		$count = count($options);
		$conf_id = $config->getVar('conf_id');
		for ($i = 0; $i < $count; $i++) {
			$options[$i]->setVar('conf_id', $conf_id);
			if (!$this->_oHandler->insert($options[$i])) {
				foreach ($options[$i]->getErrors() as $msg) {
					$config->setErrors($msg);
				}
			}
		}

		if (!empty($this->_cachedConfigs[$config->getVar('conf_modid')][$config->getVar('conf_catid')])) {
			unset($this->_cachedConfigs[$config->getVar('conf_modid')][$config->getVar('conf_catid')]);
		}
		return true;
	}

	/**
	 * Delete a config from the database
	 *
	 * @param ConfigItem  &$config refence
	 * @return    bool if deleting config item succeeded or not
	 */
	public function deleteConfig(&$config)
	{
		if (!$this->_cHandler->delete($config)) {
			return false;
		}
		$options = &$config->getConfOptions();
		$count = count($options);
		if ($count === 0) {
			$options = $this->getConfigOptions(new CriteriaItem('conf_id', $config->getVar('conf_id')));
			$count = count($options);
		}
		if (is_array($options) && $count > 0) {
			for ($i = 0; $i < $count; $i++) {
				$this->_oHandler->delete($options[$i]);
			}
		}
		if (!empty($this->_cachedConfigs[$config->getVar('conf_modid')][$config->getVar('conf_catid')])) {
			unset($this->_cachedConfigs[$config->getVar('conf_modid')][$config->getVar('conf_catid')]);
		}
		return true;
	}

	/**
	 * get one or more Configs
	 *
	 * @param CriteriaElement $criteria Criteria
	 * @param bool $id_as_key Use the configs' ID as keys?
	 * @param bool $with_options get the options now?
	 *
	 * @return    ConfigItem[]
	 */
	public function getConfigs($criteria = null, $id_as_key = false, $with_options = false)
	{
		return $this->_cHandler->getObjects($criteria, $id_as_key);
	}

	/**
	 * Count some configs
	 *
	 * @param CriteriaElement $criteria Criteria
	 * @return    int count result
	 */
	public function getConfigCount($criteria = null)
	{
		return $this->_cHandler->getCount($criteria);
	}

	/**
	 * Get configs from a certain category
	 *
	 * @param int $category ID of a category
	 * @param int $module ID of a module
	 *
	 * @return    ConfigItem[]
	 */
	public function &getConfigsByCat($category, $module = 0)
	{
		if (is_array($category)) {
			$criteria = new CriteriaCompo(new CriteriaItem('conf_modid', (int)$module));
			$criteria->add(new CriteriaItem('conf_catid', $category, 'IN'));
			$configs = $this->getConfigs($criteria, true);
			if (is_array($configs)) {
				foreach (array_keys($configs) as $i) {
					$ret[$configs[$i]->getVar('conf_catid')][$configs[$i]->getVar('conf_name')] = $configs[$i]->getConfValueForOutput();
				}
				foreach ($ret as $key => $value) {
					$this->_cachedConfigs[$module][$key] = $value;
				}
				return $ret;
			}
		} else {
			if (!empty($this->_cachedConfigs[$module][$category])) {
				return $this->_cachedConfigs[$module][$category];
			}

			$criteria = new CriteriaCompo(new CriteriaItem('conf_modid', (int)$module));
			if (!empty($category)) {
				$criteria->add(new CriteriaItem('conf_catid', (int)$category));
			}
			$ret = array();
			$configs = $this->getConfigs($criteria, true);
			if (is_array($configs)) {
				foreach (array_keys($configs) as $i) {
					$ret[$configs[$i]->getVar('conf_name')] = $configs[$i]->getConfValueForOutput();
				}
			}
			$this->_cachedConfigs[$module][$category] = $ret;
			return $this->_cachedConfigs[$module][$category];
		}
	}

	/**
	 * Make a new
	 *
	 * @return    ConfigOption
	 */
	public function &createConfigOption()
	{
		return $this->_oHandler->create();
	}

	/**
	 * Get a option by id
	 *
	 * @param int $id ID of the config option
	 *
	 * @return    ConfigOption
	 */
	public function &getConfigOption($id)
	{
		return $this->_oHandler->get($id);
	}

	/**
	 * Get one or more object(s)
	 *
	 * @param CriteriaElement $criteria Criteria
	 * @param bool $id_as_key Use IDs as keys in the array?
	 *
	 * @return    ConfigOption[]
	 */
	public function getConfigOptions($criteria = null, $id_as_key = false)
	{
		return $this->_oHandler->getObjects($criteria, $id_as_key);
	}

	/**
	 * Count
	 *
	 * @param CriteriaElement|null $criteria Criteria or null if none criteria should be used
	 *
	 * @return    int
	 */
	public function getConfigOptionsCount($criteria = null)
	{
		return $this->_oHandler->getCount($criteria);
	}

	/**
	 * Get a list of configs
	 *
	 * @param int $conf_modid ID of the modules
	 * @param int $conf_catid ID of the category
	 *
	 * @return    array   Associative array of name=>value pairs.
	 */
	public function getConfigList($conf_modid, $conf_catid = 0)
	{
		if (!empty($this->_cachedConfigs[$conf_modid][$conf_catid])) {
			return $this->_cachedConfigs[$conf_modid][$conf_catid];
		} else {
			$criteria = new CriteriaCompo(new CriteriaItem('conf_modid', $conf_modid));
			if (empty($conf_catid)) {
				$criteria->add(new CriteriaItem('conf_catid', $conf_catid));
			}
			$configs = &$this->_cHandler->getObjects($criteria);
			$confcount = count($configs);
			$ret = array();
			for ($i = 0; $i < $confcount; $i++) {
				$ret[$configs[$i]->getVar('conf_name')] = $configs[$i]->getConfValueForOutput();
			}
			$this->_cachedConfigs[$conf_modid][$conf_catid] = &$ret;
			return $ret;
		}
	}
}

