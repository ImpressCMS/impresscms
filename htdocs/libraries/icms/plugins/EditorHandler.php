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
 * Editor framework
 *
 * @license		http://www.fsf.org/copyleft/gpl.html GNU public license
 * @copyright
 * @category	ICMS
 * @package		Plugins
 * @subpackage	Editor
 * @version		SVN: $Id: EditorHandler.php 12313 2013-09-15 21:14:35Z skenow $
 */

/**
 * Handler for editors
 *
 * @author		Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @copyright	Copyright (c) 2000 XOOPS.org
 * @category	ICMS
 * @package		Plugins
 * @subpackage	Editor
 */
class icms_plugins_EditorHandler {
	private $root_path = "";
	public $nohtml = FALSE;
	public $allowed_editors = array();
	private $_type = "";

	/**
	 * Constructor
	 *
	 * @param	string	type
	 */
	public function __construct($type = '') {
		$this->root_path = self::_getRootPath($type);
		$this->_type = $type;
	}

	/**
	 * Access the only instance of this class
	 *
	 * @param	    string	type
	 * @return	object
	 * @static
	 * @staticvar   object
	 */
	static public function &getInstance($type = '') {
		static $instances = array();
		if (!isset($instances[$type])) {
			$instances[$type] = new self($type);
		}
		return $instances[$type];
	}

	/**
	 * @param	string	$name		Editor name which is actually the folder name
	 * @param	array 	$options	editor options: $key => $val
	 * @param	string	$OnFailure  a pre-validated editor that will be used if the required editor is failed to create
	 * @param	bool	$noHtml		dohtml disabled
	 */
	public function &get($name = "", $options = NULL, $noHtml = FALSE, $OnFailure = "") {
		if ($editor = $this->_loadEditor($name, $options)) {
			return $editor;
		}
		$list = array_keys($this->getList($noHtml));
		if (empty($OnFailure) || !in_array($OnFailure, $list)) {
			$OnFailure = $list[0];
		}
		$editor = $this->_loadEditor($OnFailure, $options);
		return $editor;
	}

	/**
	 * Gets list of available editors
	 *
	 * @param   bool    $noHtml   is this an editor with no html options?
	 * @return  array   $_list    list of available editors that are allowed (through admin config)
	 */
	public function &getList($noHtml = FALSE) {
		$list = @include_once ICMS_CACHE_PATH . $this->_type . 'editor_list.php';
		
		if (empty($list)) {

			$list = array();
			$order = array();
			$_list = icms_core_Filesystem::getDirList($this->root_path . '/');

			foreach ($_list as $item) {
				if (@include $this->root_path . '/' . $item . '/editor_registry.php') {
					if (empty($config['order'])) continue;
					$order[] = $config['order'];
					$list[$item] = array("title" => $config["title"], "nohtml" => @$config["nohtml"]);
				}
			}

			array_multisort($order, $list);
			$contents = "<?php\n return " . var_export($list, TRUE) . "\n?>";
			icms_core_Filesystem::writeFile($contents, $this->_type . 'editor_list', 'php', ICMS_CACHE_PATH);
		}

		$editors = array_keys($list);
		if (!empty($this->allowed_editors)) {
			$editors = array_intersect($editors, $this->allowed_editors);
		}

		$_list = array();
		foreach ($editors as $name) {
			if (!empty($noHtml) && empty($list[$name]['nohtml'])) continue;
			$_list[$name] = $list[$name]['title'];
		}
		return $_list;
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
	 * @param   string    $name       Name of the editor to load
	 * @param   string    $options    Options in the editor to load (configuration)
	 * @return  object                The loaded Editor object
	 *
	 */
	public function &_loadEditor($name, $options = NULL) {
		$editor = NULL;

		if (empty($name)) {
			return $editor;
		}
		$editor_path = $this->root_path . "/" . $name;

		if (!include $editor_path . "/editor_registry.php") {
			return $editor;
		}
		if (empty($config['order'])) {
			return NULL;
		}
		require_once $config['file'];
		$editor = new $config['class']($options);
		return $editor;
	}

	/**
	 * Determines the root path of the editor type
	 * @param string $type
	 * @return string
	 */
	private function _getRootPath($type = '') {
		if ($type == '') {
			return ICMS_EDITOR_PATH;
		} else {
			return ICMS_PLUGINS_PATH . '/' . strtolower($type) . 'editors/';
		}
	}
	
	/**
	 * Retrieve a list of the available editors, by type
	 * @param	string	$type
	 * @return	array	Available editors
	 */
	static public function getListByType($type = '') {
		$editor = self::getInstance($type);
		return $editor->getList();
	}
}
