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
 * All functions for DHTML text area are here.
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 */
namespace ImpressCMS\Core\View\Form\Elements;

use ImpressCMS\Core\DataFilter;
use ImpressCMS\Core\Extensions\Editors\EditorsRegistry;
use icms;

/**
 * A textarea with bbcode formatting and smilie buttons
 *
 * @package	ICMS\Form\Elements
 * @author	Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
class DHTMLTextAreaElement extends TextAreaElement {
	/**
	 * Extended HTML editor definition
	 *
	 * Note: the PM window doesn't use \ImpressCMS\Core\Form\Elements\DhtmltextareaElement, so no need to report it doesn't work here
	 *
	 * array('className', 'classPath'):  To create an instance of "className", declared in the file ICMS_ROOT_PATH . $classPath
	 *
	 * Example:
	 * $htmlEditor = array('XoopsFormTinyeditorTextArea', '/class/xoopseditor/tinyeditor/formtinyeditortextarea.php');
	 */
	public $htmlEditor;

	/**
	 * Hidden text
	 * @var	string
	 * @access	private
	 */
	private $_hiddenText;

	/**
	 * Constructor
	 *
	 * @param string $caption Caption
	 * @param string $name "name" attribute
	 * @param string $value Initial text
	 * @param int $rows Number of rows
	 * @param int $cols Number of columns
	 * @param string $hiddentext Hidden Text
	 * @param array $options
	 * @throws Exception
	 */
	public function __construct($caption, $name, $value, $rows = 5, $cols = 50, $hiddentext = "hiddenText", $options = []) {
		parent::__construct($caption, $name, $value, $rows, $cols);
		$this->_hiddenText = $hiddentext;
		global $icmsConfig;

		if (isset($options['editor']) && $options['editor'] != '') {
			$editor_default = $options['editor'];
		} else {
			$editor_default = $icmsConfig['editor_default'];
		}

		$editorHandler = EditorsRegistry::getInstance('content');
		$this->htmlEditor = $editorHandler->get($editor_default);
	}

	/**
	 * Prepare HTML for output
	 *
	 * @return	string  HTML
	 */
	public function render() {
		return $this->htmlEditor->render();
	}

	/**
	 * Render Validation Javascript
	 *
	 * @return	mixed  rendered validation javascript or empty string
	 */
	public function renderValidationJS() {
		if (method_exists($this->htmlEditor, "renderValidationJS")) {
			return $this->htmlEditor->renderValidationJS();
		}
		return '';
	}
}

