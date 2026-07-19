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

namespace Icms\Form;

defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');

/**
 * Abstract base class for form elements
 *
 * @copyright  http://www.impresscms.org/ The ImpressCMS Project
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 *
 * @category   ICMS
 * @package    Form
 * @subpackage Element
 *
 * @author     Kazumi Ono      <onokazu@xoops.org>
 * @author     Taiwen Jiang    <phppp@users.sourceforge.net>
 * @copyright  copyright (c) 2000-2003 XOOPS.org
 *
 * @version    $Id: Element.php 12313 2013-09-15 21:14:35Z skenow $
 */

abstract class Element
{
	/**
	 * Javascript performing additional validation of this element data
	 *
	 * This property contains a list of Javascript snippets that will be sent to
	 * Form::renderValidationJS().
	 * NB: All elements are added to the output one after the other, so don't forget
	 * to add a ";" after each to ensure no Javascript syntax error is generated.
	 *
	 * @var array<string>
	 */
	public array $customValidationCode = [];

	/**#@+
	 * @access private
	 */

	/**
	 * "name" attribute of the element
	 *
	 * @var string
	 */
	protected string $_name;

	/**
	 * caption of the element
	 *
	 * @var string
	 */
	protected string $_caption = '';

	/**
	 * Accesskey for this element
	 *
	 * @var string
	 */
	private string $_accesskey = '';

	/**
	 * HTML classes for this element
	 *
	 * @var array<string>
	 */
	private array $_class = [];

	/**
	 * hidden?
	 *
	 * @var bool
	 */
	private bool $_hidden = false;

	/**
	 * extra attributes to go in the tag
	 *
	 * @var array<string>
	 */
	private array $_extra = [];

	/**
	 * required field?
	 *
	 * @var bool
	 */
	private bool $_required = false;

	/**
	 * description of the field
	 *
	 * @var string
	 */
	private string $_description = '';

	/**#@-*/

	/**
	 * constructor
	 *
	 * @throws \Exception
	 */
	public function __construct()
	{
		throw new \Exception(_CORE_CLASSNOTINSTANIATED);
	}

	/**
	 * Is this element a container of other elements?
	 *
	 * @return bool
	 */
	public function isContainer(): bool
	{
		return false;
	}

	/**
	 * set the "name" attribute for the element
	 *
	 * @param string $name "name" attribute for the element
	 */
	public function setName(string $name): void
	{
		$this->_name = trim($name);
	}

	/**
	 * get the "name" attribute for the element
	 *
	 * @param bool $encode encode?
	 * @return string "name" attribute
	 */
	public function getName(bool $encode = true): string
	{
		if (false !== $encode) {
			return str_replace('&', '&', htmlspecialchars($this->_name, ENT_QUOTES));
		}
		return $this->_name;
	}

	/**
	 * set the "accesskey" attribute for the element
	 *
	 * @param string $key "accesskey" attribute for the element
	 */
	public function setAccessKey(string $key): void
	{
		$this->_accesskey = trim($key);
	}

	/**
	 * get the "accesskey" attribute for the element
	 *
	 * @return string "accesskey" attribute value
	 */
	public function getAccessKey(): string
	{
		return $this->_accesskey;
	}

	/**
	 * If the accesskey is found in the specified string, underlines it
	 *
	 * @param string $str String where to search the accesskey occurence
	 * @return string Enhanced string with the 1st occurence of accesskey underlined
	 */
	public function getAccessString(string $str): string
	{
		$access = $this->getAccessKey();
		if (!empty($access) && (false !== ($pos = strpos($str, $access)))) {
			return htmlspecialchars(substr($str, 0, $pos), ENT_QUOTES)
				. '<span style="text-decoration:underline">'
				. htmlspecialchars(substr($str, $pos, 1), ENT_QUOTES)
				. '</span>' . htmlspecialchars(substr($str, $pos + 1), ENT_QUOTES);
		}
		return htmlspecialchars($str, ENT_QUOTES);
	}

	/**
	 * set the "class" attribute for the element
	 *
	 * @param string $key "class" attribute for the element
	 */
	public function setClass(string $key): void
	{
		$class = trim($key);
		if (!empty($class)) {
			$this->_class[] = $class;
		}
	}

	/**
	 * get the "class" attribute for the element
	 *
	 * @return string "class" attribute value
	 */
	public function getClass(): string
	{
		if (empty($this->_class)) {
			return '';
		}
		$class = [];
		foreach ($this->_class as $c) {
			$class[] = htmlspecialchars($c, ENT_QUOTES);
		}
		return implode(' ', $class);
	}

	/**
	 * set the caption for the element
	 *
	 * @param string $caption
	 */
	public function setCaption(string $caption): void
	{
		$this->_caption = trim($caption);
	}

	/**
	 * get the caption for the element
	 *
	 * @param bool $encode To sanitizer the text?
	 * @return string
	 */
	public function getCaption(bool $encode = false): string
	{
		return $encode ? htmlspecialchars($this->_caption, ENT_QUOTES) : $this->_caption;
	}

	/**
	 * set the element's description
	 *
	 * @param string $description
	 */
	public function setDescription(string $description): void
	{
		$this->_description = trim($description);
	}

	/**
	 * get the element's description
	 *
	 * @param bool $encode To sanitizer the text?
	 * @return string
	 */
	public function getDescription(bool $encode = false): string
	{
		return $encode
			? htmlspecialchars($this->_description, ENT_QUOTES)
			: $this->_description;
	}

	/**
	 * flag the element as "hidden"
	 *
	 */
	public function setHidden(): void
	{
		$this->_hidden = true;
	}

	/**
	 * Find out if an element is "hidden".
	 *
	 * @return bool
	 */
	public function isHidden(): bool
	{
		return $this->_hidden;
	}

	/**
	 * Find out if an element is required.
	 *
	 * @return bool
	 */
	public function isRequired(): bool
	{
		return $this->_required;
	}

	/**
	 * @return void
	 */
	public function setRequired(): void
	{
		$this->_required = true;
	}

	/**
	 * Add extra attributes to the element.
	 *
	 * This string will be inserted verbatim and unvalidated in the
	 * element's tag. Know what you are doing!
	 *
	 * @param string  $extra
	 * @param bool    $replace If true, passed string will replace current content otherwise it will be appended to it
	 * @return array<string> New content of the extra string
	 */
	public function setExtra(string $extra, bool $replace = false): array
	{
		if ($replace) {
			$this->_extra = [trim($extra)];
		} else {
			$this->_extra[] = trim($extra);
		}
		return $this->_extra;
	}

	/**
	 * Get the extra attributes for the element
	 *
	 * @param bool $encode To sanitizer the text?
	 * @return string
	 */
	public function getExtra(bool $encode = false): string
	{
		if (!$encode) {
			return ' ' . implode(' ', $this->_extra);
		}
		$value = [];
		foreach ($this->_extra as $val) {
			$value[] = str_replace('>', '>', str_replace('<', '<', $val));
		}
		return empty($value) ? '' : ' ' . implode(' ', $value);
	}

	/**
	 * Render custom javascript validation code
	 *
	 * @see Form::renderValidationJS
	 */
	public function renderValidationJS(): string
	{
		// render custom validation code if any
		if (!empty($this->customValidationCode)) {
			return implode("\n", $this->customValidationCode);
		}
		// generate validation code if required
		if ($this->isRequired()) {
			$eltname    = $this->getName();
			$eltcaption = $this->getCaption();
			$eltmsg = empty($eltcaption)
				? sprintf(_FORM_ENTER, $eltname)
				: sprintf(_FORM_ENTER, $eltcaption);
			$eltmsg = str_replace('"', '\"', stripslashes($eltmsg));
			if ($eltname) {
				return "if (myform.{$eltname}.value == \"\") { window.alert(\"{$eltmsg}\"); myform.{$eltname}.focus(); return false; }";
			}
		}
		return '';
	}

	/**
	 * Generates output for the element.
	 *
	 * This method is abstract and must be overwritten by the child classes.
	 */
	abstract public function render(): string;
}

/**
 * Legacy class alias for backward compatibility
 */
class_alias(Element::class, 'icms_form_Element');