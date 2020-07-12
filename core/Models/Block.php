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
 * @copyright    The ImpressCMS Project <http://www.impresscms.org>
 * @license    GNU General Public License (GPL) <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @author    Gustavo Pilla (aka nekro) <nekro@impresscms.org>
 */

namespace ImpressCMS\Core\Models;

use ImpressCMS\Core\DataFilter;
use ImpressCMS\Core\IPF\AbstractDatabaseModel;
use ImpressCMS\Core\Textsanitizer;

/**
 * ImpressCMS Core Block Object Class
 *
 * @since    ImpressCMS 1.2
 * @author    Gustavo Pilla (aka nekro) <nekro@impresscms.org>
 * @package     ICMS\View\Block
 *
 * @property string $name           Name
 * @property int $bid            Block ID
 * @property int $mid            Module ID
 * @property int $func_num
 * @property string $title          Title
 * @property string $content        Content
 * @property int $side           Side
 * @property int $weight         Weight used for sorting positions
 * @property int $visible        Is visible?
 * @property string $block_type     Type
 * @property string $c_type
 * @property int $isactive       Is active?
 * @property string $dirname        Directory name
 * @property string $func_file      Function file
 * @property string $show_func      Show function
 * @property string $edit_func      Edit function
 * @property string $template       Template
 * @property int $bcachetime     Cache time
 * @property int $last_modified  When it was last modified?
 * @property string $options        Options
 */
class Block extends AbstractDatabaseModel
{

	/**
	 * System block
	 */
	public const BLOCK_TYPE_SYSTEM = 'S';

	/**
	 * Block from a Module (other than system)
	 */
	public const BLOCK_TYPE_MODULE = 'M';

	/**
	 * Custom block
	 */
	public const BLOCK_TYPE_CUSTOM = 'C';

	/**
	 * Block cloned from another block
	 */
	public const BLOCK_TYPE_DUPLICATED = 'K';

	/**
	 * Block cloned from another block
	 *
	 * @deprecated will be removed in 2.1. Use BLOCK_TYPE_DUPLICATED
	 */
	public const BLOCK_TYPE_LEGACY_DUPLICATED = 'D';

	/**
	 * Custom block
	 *
	 * @deprecated will be removed in 2.1. Use BLOCK_TYPE_CUSTOM
	 */
	public const BLOCK_TYPE_LEGACY_CUSTOM = 'E';

	/**
	 * Block uses HTML for displaying content
	 */
	public const CONTENT_TYPE_HTML = 'H';

	/**
	 * Block uses PHP for displaying content
	 */
	public const CONTENT_TYPE_PHP = 'P';

	/**
	 * Block uses Auto Format (smilies and HTML enabled)
	 */
	public const CONTENT_TYPE_AUTO_FORMAT = 'S';

	/**
	 * Block uses no Auto Format (smilies and HTML disabled)
	 */
	public const CONTENT_TYPE_NO_AUTO_FORMAT = 'T';

	/**
	 * Visible areas for block
	 *
	 * @var array
	 */
	public $visiblein = [];

	/**
	 * Constructor for the block object
	 * @param $handler
	 * @param array $data
	 */
	public function __construct(&$handler, $data = array())
	{

		$this->initVar('name', self::DTYPE_STRING, '', false, 150);
		$this->initVar('bid', self::DTYPE_INTEGER, 0, true, 8);
		$this->initVar('mid', self::DTYPE_INTEGER, 0, true, 5);
		$this->initVar('func_num', self::DTYPE_INTEGER, 0, false, 3);
		$this->initVar('title', self::DTYPE_STRING, '', false, 255);
		$this->initVar('content', self::DTYPE_STRING);
		$this->initVar('side', self::DTYPE_INTEGER, 0, true, 1);
		$this->initVar('weight', self::DTYPE_INTEGER, 0, true, 5);
		$this->initVar('visible', self::DTYPE_INTEGER, 0, true, 1);
		$this->initVar('block_type', self::DTYPE_STRING, '', true, 1);
		$this->initVar('c_type', self::DTYPE_STRING, static::CONTENT_TYPE_AUTO_FORMAT, true, 1);
		$this->initVar('isactive', self::DTYPE_INTEGER, 0, false, 1);
		$this->initVar('dirname', self::DTYPE_STRING, '', false, 50);
		$this->initVar('func_file', self::DTYPE_STRING, '', false, 50);
		$this->initVar('show_func', self::DTYPE_STRING, '', false, 50);
		$this->initVar('edit_func', self::DTYPE_STRING, '', false, 50);
		$this->initVar('template', self::DTYPE_STRING, '', false, 50);
		$this->initVar('bcachetime', self::DTYPE_INTEGER, 0, false, 10);
		$this->initVar('last_modified', self::DTYPE_INTEGER, 0, false, 10);
		$this->initVar('options', self::DTYPE_STRING, '', false, 255);

		parent::__construct($handler, $data);
	}

	/**
	 * @inheritDoc
	 */
	public function setVar($name, $value, $options = null)
	{
		if ($name === 'visiblein') {
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
	public function getOptions()
	{
		if (
			($this->block_type === static::BLOCK_TYPE_CUSTOM) ||
			!($edit_func = $this->edit_func) ||
			!file_exists($func_file = ICMS_ROOT_PATH . '/modules/' . $this->dirname . '/blocks/' . $this->func_file)
		) {
			return false;
		}

		icms_loadLanguageFile($this->dirname, 'blocks');
		include_once $func_file;

		$options = explode('|', $this->getVar('options'));
		$edit_form = $edit_func($options);
		if (!$edit_form) {
			return false;
		}

		return $edit_form;
	}

	// The next Methods are for backward Compatibility

	/**
	 * @inheritDoc
	 */
	public function getVar($name, $format = 's')
	{
		if ($name === 'visiblein') {
			return $this->visiblein;
		} else {
			return parent::getVar($name, $format);
		}
	}

	/**
	 * Builds the block
	 *
	 * @return array $block the block information
	 */
	public function buildBlock()
	{
		$block = [];

		if ($this->isCustom()) {
			$block['content'] = $this->getContent(self::BLOCK_TYPE_SYSTEM, $this->c_type);

			return empty($block['content']) ? false : $block;
		}

		if (!$this->show_func) {
			return false;
		}

		$block_template_file = ICMS_ROOT_PATH . '/modules/' . $this->dirname . '/blocks/' . $this->func_file;
		if (!file_exists($block_template_file)) {
			return false;
		}

		icms_loadLanguageFile($this->dirname, 'blocks');
		global $icmsConfig, $xoopsOption;
		/** @noinspection PhpIncludeInspection */
		include_once $block_template_file;
		$options = explode('|', $this->getVar('options'));
		if (!function_exists($this->show_func)) {
			return false;
		}

		$show_func = $this->show_func;
		$block = $show_func($options);
		if (!$block) {
			return false;
		}

		return $block;
	}

	/**
	 * Gets if block is of type custom
	 *
	 * For backward compatibility
	 *
	 * @return bool
	 */
	public function isCustom()
	{
		return ($this->block_type === static::BLOCK_TYPE_CUSTOM || $this->block_type === static::BLOCK_TYPE_LEGACY_CUSTOM);
	}

	/**
	 * Gets content for block
	 *
	 * @param string $format Block type
	 * @param string $c_type Content type
	 *
	 * @return array|bool|false|mixed|string|string[]
	 */
	public function getContent($format = self::BLOCK_TYPE_SYSTEM, $c_type = self::CONTENT_TYPE_NO_AUTO_FORMAT)
	{
		switch ($format) {
			case self::BLOCK_TYPE_SYSTEM:
				if ($c_type === static::CONTENT_TYPE_HTML) {
					$content = $this->content;
					$content = str_replace(['{X_SITEURL}', env('DB_SALT')], [ICMS_URL . '/', ''], $content);
					return $content;
				} elseif ($c_type === static::CONTENT_TYPE_PHP) {
					ob_start();
					echo eval(DataFilter::undoHtmlSpecialChars($this->getVar('content', 'e')));
					$content = ob_get_contents();
					ob_end_clean();
					$content = str_replace(['{X_SITEURL}', env('DB_SALT')], [ICMS_URL . '/', ''], $content);
					return $content;
				} elseif ($c_type === static::CONTENT_TYPE_AUTO_FORMAT) {
					$myts = Textsanitizer::getInstance();
					$content = str_replace('{X_SITEURL}', ICMS_URL . '/', $this->content);
					return $myts->displayTarea($content, 1, 1);
				} else {
					$content = str_replace('{X_SITEURL}', ICMS_URL . '/', $this->content);
					return DataFilter::checkVar($content, 'text', 'output');
				}
				break;

			case static::BLOCK_TYPE_CUSTOM:
				return $this->getVar('content', 'e');

			default:
				return $this->content;
		}
	}
}