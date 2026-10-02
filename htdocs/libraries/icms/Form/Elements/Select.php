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
declare(strict_types=1);
namespace Icms\Form\Elements;

use Icms\Form\Element;


/**
 * Creates a form select field (base class)
 *
 * @copyright  http://www.impresscms.org/ The ImpressCMS Project
 * @license    http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 *
 * @category   ICMS
 * @package    Form
 * @subpackage Elements
 * @version    SVN: $Id: Select.php 12313 2013-09-15 21:14:35Z skenow $
 */

if (!defined('ICMS_ROOT_PATH')) {
    die("ImpressCMS root path not defined");
}

/**
 * A select field
 *
 * @package Form
 * @subpackage Elements
 *
 * @author    Kazumi Ono <onokazu@xoops.org>
 * @copyright copyright (c) 2000-2003 XOOPS.org
 */
class Select extends Element
{
    /**
     * Options
     *
     * @var array<string,string>
     */
    private array $_options = [];

    /**
     * Allow multiple selections?
     *
     * @var bool
     */
    private bool $_multiple = false;

    /**
     * Number of rows. "1" makes a dropdown list.
     *
     * @var int
     */
    private int $_size;

    /**
     * Pre-selected values
     *
     * @var array<string>
     */
    private array $_value = [];

    /**
     * Constructor
     *
     * @param string $caption Caption
     * @param string $name "name" attribute
     * @param mixed $value Pre-selected value (or array of them).
     * @param int $size Number or rows. "1" makes a drop-down-list
     * @param bool $multiple Allow multiple selections?
     */
    public function __construct(
        string $caption,
        string $name,
        $value = null,
        int $size = 1,
        bool $multiple = false
    ) {
        $this->setCaption($caption);
        $this->setName($name);
        $this->_multiple = $multiple;
        $this->_size = (int) $size;
        if (is_array($value)) {
            foreach ($value as $v) {
                $this->_value[] = (string) $v;
            }
        } elseif ($value !== null) {
            $this->_value[] = (string) $value;
        }
    }

    /**
     * Are multiple selections allowed?
     *
     * @return bool
     */
    public function isMultiple(): bool
    {
        return $this->_multiple;
    }

    /**
     * Get the size
     *
     * @return int
     */
    public function getSize(): int
    {
        return $this->_size;
    }

    /**
     * Get an array of pre-selected values
     *
     * @param bool $encode To sanitize the text?
     * @return array<string>
     */
    public function getValue(bool $encode = false): array
    {
        if (!$encode) {
            return $this->_value;
        }
        $value = [];
        foreach ($this->_value as $val) {
            $value[] = htmlspecialchars((string) $val, ENT_QUOTES);
        }
        return $value;
    }

    /**
     * Set pre-selected values
     *
     * @param mixed $value
     */
    public function setValue($value): void
    {
        if (is_array($value)) {
            foreach ($value as $v) {
                $this->_value[] = (string) $v;
            }
        } else {
            $this->_value[] = (string) $value;
        }
    }

    /**
     * Add an option
     *
     * @param string $value "value" attribute
     * @param string $name "name" attribute
     */
    public function addOption(string $value, string $name = ''): void
    {
        if ($name !== '') {
            $this->_options[$value] = $name;
        } else {
            $this->_options[$value] = $value;
        }
    }

    /**
     * Add multiple options
     *
     * @param array<string,string> $options Associative array of value->name pairs
     */
    public function addOptionArray(array $options): void
    {
        if (is_array($options)) {
            foreach ($options as $k => $v) {
                $this->addOption((string) $k, (string) $v);
            }
        }
    }

    /**
     * Get an array with all the options
     *
     * Note: both name and value should be sanitized. However for backward compatibility, only value is sanitized for now.
     *
     * @param int $encode To sanitize the text? potential values: 0 - skip; 1 - only for value; 2 - for both value and name
     * @return array<string,string> Associative array of value->name pairs
     */
    public function getOptions(int $encode = 0): array
    {
        if ($encode === 0) {
            return $this->_options;
        }

        $value = [];
        foreach ($this->_options as $val => $name) {
            $value[$encode === 1 ? htmlspecialchars((string) $val, ENT_QUOTES) : (string) $val]
                = ($encode > 1) ? htmlspecialchars((string) $name, ENT_QUOTES) : (string) $name;
        }
        return $value;
    }

    /**
     * Prepare HTML for output
     *
     * @return string HTML
     */
    public function render(): string
    {
        $eleName = $this->getName();
        $eleValue = $this->getValue();
        $eleOptions = $this->getOptions();
        $ret = '<select size="' . $this->getSize() . '"' . $this->getExtra();

        if ($this->isMultiple()) {
            $ret .= ' name="' . htmlspecialchars($eleName, ENT_QUOTES) . '[]" id="' . htmlspecialchars($eleName, ENT_QUOTES) . '" multiple="multiple">';
        } else {
            $ret .= ' name="' . htmlspecialchars($eleName, ENT_QUOTES) . '" id="' . htmlspecialchars($eleName, ENT_QUOTES) . '">';
        }

        foreach ($eleOptions as $v => $n) {
            $ret .= '<option value="' . htmlspecialchars((string) $v, ENT_QUOTES) . '"';
            if (count($eleValue) > 0 && in_array((string) $v, (array) $eleValue, true)) {
                $ret .= ' selected="selected"';
            }
            $ret .= '>' . htmlspecialchars((string) $n, ENT_QUOTES) . "</option>\n";
        }
        $ret .= '</select>';
        return $ret;
    }
}

/**
 * Legacy class alias for backward compatibility
 */
class_alias(Select::class, 'icms_form_elements_Select');