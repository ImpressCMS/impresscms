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
namespace ImpressCMS\Core\Extensions\Editors;

use Exception;

/**
 * Editor framework
 *
 * @license	http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author	Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @copyright	Copyright (c) 2000 XOOPS.org
 * @package	ICMS\Plugins
 */
class EditorsRegistry {

	/**
	 * No HTML mode?
	 *
	 * @var bool
	 */
	public $nohtml = false;

	/**
	 * What editors to allow?
	 *
	 * @var array
	 */
	public $allowed_editors = array();

	/**
	 * Editor type
	 *
	 * @var string
	 */
	private $_type;

	/**
	 * Constructor
	 *
	 * @param string $type Editor type
	 */
	public function __construct($type = 'content')
	{
		$this->_type = $type;
	}

	/**
	 * Access the only instance of this class
	 *
	 * @param string    type
	 * @return    self
	 * @static
	 * @staticvar   object
	 */
	public static function &getInstance($type = 'content')
	{
		static $instances = array();
		if (empty($type)) {
			$type = 'content';
		}
		if (!isset($instances[$type])) {
			$instances[$type] = new self($type);
		}
		return $instances[$type];
	}

	/**
	 * Checks if such editor exists
	 *
	 * @param string $name Editor name
	 *
	 * @return bool
	 */
	public function has(string $name): bool
	{
		return $this->_loadEditor($name) ? true : false;
	}

	/**
	 * @param string $name Editor name which is actually the folder name
	 * @param array $options editor options: $key => $val
	 * @param string $OnFailure a pre-validated editor that will be used if the required editor is failed to create
	 * @param bool $noHtml dohtml disabled
	 * @return object
	 * @throws Exception
	 */
	public function &get($name = '', $options = null, $noHtml = false, $OnFailure = '')
	{
		if (empty($options)) {
			$options = [];
		}
		if ($editor = $this->_loadEditor($name, $options)) {
			return $editor->create($options);
		}
		$list = array_keys($this->getList($noHtml));
		if (empty($OnFailure) || !in_array($OnFailure, $list, true)) {
			$OnFailure = $list[0];
		}

		return $this->_loadEditor($OnFailure, $options)->create($options);
	}

	/**
	 * Gets list of available editors
	 *
	 * @param bool $noHtml is this an editor with no html options?
	 * @return  array   $_list    list of available editors that are allowed (through admin config)
	 * @throws Exception
	 */
	public function &getList($noHtml = false)
	{
		$editors = [];
		$sort = [
			'titles' => [],
			'order' => []
		];
		/**
		 * @var EditorInterface $editor
		 */
		foreach (\icms::getInstance()->get('editor.' . $this->_type) as $editor) {
			if (!($editor instanceof EditorInterface)) {
				continue;
			}
			$name = \icms::getInstance()->getServiceDefinition(get_class($editor))->getAlias();
			if (!empty($this->allowed_editors) && !in_array($name, $this->allowed_editors, true)) {
				continue;
			}
			if ($noHtml && !$editor->supportsHTML()) {
				continue;
			}
			$sort['titles'][] = $editor->getTitle();
			$sort['order'][] = $editor->getOrder() ?? PHP_INT_MAX;
			$editors[$name] = $editor->getTitle();
		}

		array_multisort(
			$sort['order'],
			SORT_ASC,
			$sort['titles'],
			SORT_ASC,
			$editors
		);

		return $editors;
	}

	/**
	 * Render the editor
	 * @param   string    &$editor    Reference to the editor object
	 * @return  string    The rendered Editor string
	 */
	public function render(&$editor) {
		return $editor->render();
	}

	/**
	 * Sets the config of the editor
	 *
	 * @param   string    &$editor    Reference to the editor object
	 * @param   string    $options    Options in the configuration to set
	 */
	public function setConfig(&$editor, $options) {
		if (method_exists($editor, 'setConfig')) {
			$editor->setConfig($options);
		} else {
			foreach ($options as $key => $val) {
				$editor->$key = $val;
			}
		}
	}

	/**
	 * Loads the editor
	 *
	 * @param string $name Name of the editor to load
	 * @return  EditorInterface                The loaded Editor object
	 *
	 */
	public function _loadEditor($name)
	{
		if (empty($name)) {
			return null;
		}

		/**
		 * @var EditorInterface $editor
		 */
		$editor = \icms::getInstance()->get($name);
		return ($editor instanceof EditorInterface) ? $editor : null;
	}

	/**
	 * Retrieve a list of the available editors, by type
	 * @param string $type
	 * @return    array    Available editors
	 * @throws Exception
	 */
	public static function getListByType($type = 'content')
	{
		$editor = self::getInstance($type);
		return $editor->getList();
	}
}
