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
 * ImpressCMS Block Persistable Class
 * This is a highly rewritten class for defining blocks
 *
 * @copyright 	The ImpressCMS Project <http://www.impresscms.org>
 * @license	GNU General Public License (GPL) <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @author	Gustavo Pilla (aka nekro) <nekro@impresscms.org>
 */

/**
 * ImpressCMS Core Block Object Class
 *
 * @since	ImpressCMS 1.2
 * @author	Gustavo Pilla (aka nekro) <nekro@impresscms.org>
 * @package     ICMS\View\Block
 *
 * @property string $name           Name
 * @property int    $bid            Block ID
 * @property int    $mid            Module ID
 * @property int    $func_num
 * @property string $title          Title
 * @property string $content        Content
 * @property int    $side           Side
 * @property int    $weight         Weight used for sorting positions
 * @property int    $visible        Is visible?
 * @property string $block_type     Type
 * @property string $c_type
 * @property int    $isactive       Is active?
 * @property string $dirname        Directory name
 * @property string $func_file      Function file
 * @property string $show_func      Show function
 * @property string $edit_func      Edit function
 * @property string $template       Template
 * @property int    $bcachetime     Cache time
 * @property int    $last_modified  When it was last modified?
 * @property string $options        Options
 */
class icms_view_block_Object extends icms_ipf_Object {

		public $visiblein = [];

	/**
	 * Constructor for the block object
	 * @param $handler
	 */
	public function __construct(& $handler, $data = array()) {

		$this->initVar('name', self::DTYPE_STRING, '', false, 150);
		$this->initVar('bid', self::DTYPE_INTEGER, 0, true, 8);
		$this->initVar('mid', self::DTYPE_INTEGER, 0, true, 5);
		$this->initVar('func_num', self::DTYPE_INTEGER, 0, false, 3);
		$this->initVar('title', self::DTYPE_STRING, '', false, 255);
		$this->initVar('content', self::DTYPE_STRING);
		$this->initVar('side', self::DTYPE_INTEGER, 0, true, 1);
		$this->initVar('weight', self::DTYPE_INTEGER, 0, true, 5);
		$this->initVar('visible', self::DTYPE_INTEGER, 0, true, 1);
		/**
		 * @var string $block_type Holds the type of block
		 * 	S - System block
		 * 	M - block from a Module (other than system)
		 * 	C - Custom block (legacy type 'E')
		 * 	K - block cloned from another block (legacy type 'D')
		 */
		$this->initVar('block_type', self::DTYPE_STRING, '', true, 1);
		/**
		 * @var	string	$c_type	The type of content in the block
		 * 	H - HTML
		 * 	P - PHP
		 * 	S - Auto Format (smilies and HTML enabled)
		 *  T - Auto Format (smilies and HTML disabled)
		 */
		$this->initVar('c_type', self::DTYPE_STRING, 'S', true, 1);
		$this->initVar('isactive', self::DTYPE_INTEGER, 0, false, 1);
		$this->initVar('dirname', self::DTYPE_STRING, '', false, 50);
		$this->initVar('func_file', self::DTYPE_STRING, '', false, 50);
		$this->initVar('show_func', self::DTYPE_STRING, '', false, 50);
		$this->initVar('edit_func', self::DTYPE_STRING, '', false, 50);
		$this->initVar('template', self::DTYPE_STRING, '', false, 50);
		$this->initVar('bcachetime', self::DTYPE_INTEGER, 0, false, 10);
		$this->initVar('last_modified', self::DTYPE_INTEGER, 0, false, 10);
		$this->initVar('options', self::DTYPE_STRING, '', false, 255);

	  //  $this->quickInitVar('visiblein', self::DTYPE_ARRAY, 'visiblein', FALSE, FALSE, FALSE, TRUE);
				parent::__construct($handler, $data);

	}

		/**
		 * sets var
		 *
		 * @todo We use this function only for visiblein code. We should find a better way to deal with this issue.
		 *
		 * @param string $name
		 * @param string $format
		 * @return mixed
		 */
		public function setVar($name, $value, $options = null) {
			if ($name == 'visiblein') {
				$this->visiblein = $value;
			} else {
				parent::setVar($name, $value, $options);
			}
		}

	/**
	 * (HTML-) form for setting the options of the block
	 *
	 * @return string|FALSE $edit_form is HTML for the form, FALSE if no options defined for this block
	 */
	public function getOptions() {
		if ($this->getVar('block_type') != 'C') {
			$edit_func = $this->getVar('edit_func');
			if (!$edit_func) {
				return false;
			}
			icms_loadLanguageFile($this->getVar('dirname'), 'blocks');
			include_once ICMS_ROOT_PATH . '/modules/' . $this->getVar('dirname') . '/blocks/' . $this->getVar('func_file');
			$options = explode('|', $this->getVar('options'));
			$edit_form = $edit_func($options);
			if (!$edit_form) {
				return false;
			}
			return $edit_form;
		} else {
			return false;
		}
	}

	// The next Methods are for backward Compatibility

	/**
	 * gets var
	 *
	 * @todo We use this function only for visiblein code. We should find a better way to deal with this issue.
	 *
	 * @param string $name
	 * @param string $format
	 * @return mixed
	 */
	public function getVar($name, $format = 's')
	{
		if ($name == 'visiblein') {
			return $this->visiblein;
		} else {
			return parent::getVar($name, $format);
		}
		}

	/**
	 * Builds the block
	 *
	 * @return array $block the block information array
	 *
	 * @todo improve with IPF
	 */
	public function buildBlock() {
		global $icmsConfig, $xoopsOption;
		$block = array();
		// M for module block, S for system block C for Custom
		if ($this->isCustom()) {
			// it is a custom block, so just return the contents
			$block['content'] = $this->getContent("S", $this->getVar("c_type"));
			if (empty($block['content'])) {
				return false;
			}
		} else {
			// get block display function
			$show_func = $this->getVar('show_func');
			if (!$show_func) {
				return false;
			}
			// Must get lang files before execution of the function.
			if (!file_exists(ICMS_ROOT_PATH . "/modules/" . $this->getVar('dirname') . "/blocks/" . $this->getVar('func_file'))) {
				return false;
			} else {
				icms_loadLanguageFile($this->getVar('dirname'), 'blocks');
				include_once ICMS_ROOT_PATH . "/modules/" . $this->getVar('dirname') . "/blocks/" . $this->getVar('func_file');
				$options = explode("|", $this->getVar("options"));
				if (!function_exists($show_func)) {
					return false;
				} else {
					// execute the function
					$block = $show_func($options);
					if (!$block) {
						return false;
					}
				}
			}
		}
		return $block;
	}

	/**
	 * For backward compatibility
	 *
	 * @todo improve with IPF
	 * @return unknown
	 */
	public function isCustom()
	{
		if ($this->getVar("block_type") == "C" || $this->getVar("block_type") == "E") {
			return true;
		}
		return false;
	}

	public function getContent($format = 'S', $c_type = 'T')
	{
		switch ($format) {
			case 'S':
				if ($c_type == 'H') {
					$content = $this->content;
					$content = str_replace('{X_SITEURL}', ICMS_URL . '/', $content);
					$content = str_replace(env('DB_SALT'), '', $content);
					return $content;
				} elseif ($c_type == 'P') {
					ob_start();
					echo eval(icms_core_DataFilter::undoHtmlSpecialChars($this->getVar('content', 'e')));
					$content = ob_get_contents();
					ob_end_clean();
					$content = str_replace('{X_SITEURL}', ICMS_URL . '/', $content);
					$content = str_replace(env('DB_SALT'), '', $content);
					return $content;
				} elseif ($c_type == 'S') {
					$myts = icms_core_Textsanitizer::getInstance();
					$content = str_replace('{X_SITEURL}', ICMS_URL . '/', $this->content);
					return $myts->displayTarea($content, 1, 1);
				} else {
					$content = str_replace('{X_SITEURL}', ICMS_URL . '/', $this->content);
					return icms_core_DataFilter::checkVar($content, 'text', 'output');
				}
				break;

			case 'E':
				return $this->getVar('content', 'e');
				break;

			default:
				return $this->content;
				break;
		}
	}

	/**
	 * Aligns the content of a block
	 * If position is 0, content in DB is positioned
	 * before the original content
	 * If position is 1, content in DB is positioned
	 * after the original content
	 *
	 * @todo remove this? It is not found anywhere else in the core
	 */
	public function buildContent($position, $content = "", $contentdb = "") {
		if ($position == 0) {
			$ret = $contentdb . $content;
		} elseif ($position == 1) {
			$ret = $content . $contentdb;
		}
		return $ret;
	}

	/**
	 * Build Block Title
	 *
	 * @param string $originaltitle
	 * @param string $newtitle
	 * @return string
	 *
	 * @todo remove this? it is not found anywhere else in the core
	 */
	public function buildTitle($originaltitle, $newtitle = "") {
		if ($newtitle != "") {
			$ret = $newtitle;
		} else {
			$ret = $originaltitle;
		}
		return $ret;
	}
}