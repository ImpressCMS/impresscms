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
 * @license		GNU General Public License (GPL) <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @category	ICMS
 * @package		View
 * @subpackage	Block
 * @author		Gustavo Pilla (aka nekro) <nekro@impresscms.org>
 * @version		SVN: $Id: Object.php 12313 2013-09-15 21:14:35Z skenow $
 */

defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');

/**
 * ImpressCMS Core Block Object Class
 *
 * @category	ICMS
 * @package		View
 * @subpackage	Block
 * @since		ImpressCMS 1.2
 * @author		Gustavo Pilla (aka nekro) <nekro@impresscms.org>
 */
class icms_view_block_Object extends icms_ipf_Object {

	/**
	 * Constructor for the block object
	 * @param $handler
	 */
	public function __construct(& $handler) {

		parent::__construct($handler);

		$this->quickInitVar('name', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('bid', XOBJ_DTYPE_INT, TRUE);
		$this->quickInitVar('mid', XOBJ_DTYPE_INT, TRUE);
		$this->quickInitVar('func_num', XOBJ_DTYPE_INT);
		$this->quickInitVar('title', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('content', XOBJ_DTYPE_TXTAREA);
		$this->quickInitVar('side', XOBJ_DTYPE_INT, TRUE);
		$this->quickInitVar('weight', XOBJ_DTYPE_INT, TRUE, FALSE, FALSE, 0);
		$this->quickInitVar('visible', XOBJ_DTYPE_INT, TRUE);
		/**
		 * @var string $block_type Holds the type of block
		 * 	S - System block
		 * 	M - block from a Module (other than system)
		 * 	C - Custom block (legacy type 'E')
		 * 	K - block cloned from another block (legacy type 'D')
		 */
		$this->quickInitVar('block_type', XOBJ_DTYPE_TXTBOX);
		/**
		 * @var	string	$c_type	The type of content in the block
		 * 	H - HTML
		 * 	P - PHP
		 * 	S - Auto Format (smilies and HTML enabled)
		 *  T - Auto Format (smilies and HTML disabled)
		 */
		$this->quickInitVar('c_type', XOBJ_DTYPE_TXTBOX, TRUE, FALSE, FALSE, "S");
		$this->quickInitVar('isactive', XOBJ_DTYPE_INT);
		$this->quickInitVar('dirname', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('func_file', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('show_func', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('edit_func', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('template', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('bcachetime', XOBJ_DTYPE_INT);
		$this->quickInitVar('last_modified', XOBJ_DTYPE_INT);
		$this->quickInitVar('options', XOBJ_DTYPE_TXTBOX);
	}

	// The next Methods are for backward Compatibility

	public function getContent($format = 'S', $c_type = 'T') {
		switch ($format) {
			case 'S':
				if ($c_type == 'H') {
                    $content = $this->getVar('content', 'n');
                    $content = str_replace('{X_SITEURL}', ICMS_URL . '/', $content);
                    $content = str_replace(XOOPS_DB_SALT, '', $content);
					return $content;
				} elseif ($c_type == 'P') {
					ob_start();
					echo eval(icms_core_DataFilter::undoHtmlSpecialChars($this->getVar('content', 'e')));
					$content = ob_get_contents();
					ob_end_clean();
                    $content = str_replace('{X_SITEURL}', ICMS_URL . '/', $content);
                    $content = str_replace(XOOPS_DB_SALT, '', $content);
					return $content;
				} elseif ($c_type == 'S') {
					$myts = icms_core_Textsanitizer::getInstance();
					$content = str_replace('{X_SITEURL}', ICMS_URL . '/', $this->getVar('content', 'n'));
					return $myts->displayTarea($content, 1, 1);
				} else {
					$content = str_replace('{X_SITEURL}', ICMS_URL . '/', $this->getVar('content', 'n'));
					return icms_core_DataFilter::checkVar($content, 'text', 'output');
				}
				break;

			case 'E':
				return $this->getVar('content', 'e');
				break;

			default:
				return $this->getVar('content', 'n');
				break;
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
				return FALSE;
			}
			icms_loadLanguageFile($this->getVar('dirname'), 'blocks');
			include_once ICMS_ROOT_PATH . '/modules/' . $this->getVar('dirname') . '/blocks/' . $this->getVar('func_file');
			$options = explode('|', $this->getVar('options'));
			$edit_form = $edit_func($options);
			if (!$edit_form) {
				return FALSE;
			}
			return $edit_form;
		} else {
			return FALSE;
		}
	}

	/**
	 * For backward compatibility
	 *
	 * @todo improve with IPF
	 * @return unknown
	 */
	public function isCustom() {
		if ($this->getVar("block_type") == "C" || $this->getVar("block_type") == "E") {
			return TRUE;
		}
		return FALSE;
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
				return FALSE;
			}
		} else {
			// get block display function
			$show_func = $this->getVar('show_func');
			if (!$show_func) {
				return FALSE;
			}
			// Must get lang files before execution of the function.
			if (!file_exists(ICMS_ROOT_PATH . "/modules/" . $this->getVar('dirname') . "/blocks/" . $this->getVar('func_file'))) {
				return FALSE;
			} else {
				icms_loadLanguageFile($this->getVar('dirname'), 'blocks');
				include_once ICMS_ROOT_PATH . "/modules/" . $this->getVar('dirname') . "/blocks/" . $this->getVar('func_file');
				$options = explode("|", $this->getVar("options"));
				if (!function_exists($show_func)) {
					return FALSE;
				} else {
					// execute the function
					$block = $show_func($options);
					if (!$block) {
						return FALSE;
					}
				}
			}
		}
		return $block;
	}
}