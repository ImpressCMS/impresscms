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
 * @copyright	Copyright (c) 2000 XOOPS.org
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 *
 * @category	ICMS
 * @package		Config
 * @subpackage	Item
 * @author		Kazumi Ono (aka onokazo)
 * @version		SVN: $Id: Object.php 12313 2013-09-15 21:14:35Z skenow $
 */


if (!defined('ICMS_ROOT_PATH')) die("ImpressCMS root path not defined");

/**
 *
 *
 * @category	ICMS
 * @package		Config
 * @subpackage	Item
 *
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 * 				You should have received a copy of XOOPS_copyrights.txt with
 * 				this file. If not, you may obtain a copy from xoops.org
 */
class icms_config_Item_Object extends icms_core_Object {
	/**
	 * Config options
	 *
	 * @var	array
	 * @access	private
	 */
	public $_confOptions = array();

	/**
	 * Constructor
	 *
	 * @todo	Cannot set the data type of the conf_value on instantiation - the data type must be retrieved from the db.
	 */
	public function __construct() {
		$this->initVar('conf_id', XOBJ_DTYPE_INT, null, false);
		$this->initVar('conf_modid', XOBJ_DTYPE_INT, null, false);
		$this->initVar('conf_catid', XOBJ_DTYPE_INT, null, false);
		$this->initVar('conf_name', XOBJ_DTYPE_OTHER);
		$this->initVar('conf_title', XOBJ_DTYPE_TXTBOX);
		$this->initVar('conf_value', XOBJ_DTYPE_TXTAREA);
		$this->initVar('conf_desc', XOBJ_DTYPE_OTHER);
		$this->initVar('conf_formtype', XOBJ_DTYPE_OTHER);
		$this->initVar('conf_valuetype', XOBJ_DTYPE_OTHER);
		$this->initVar('conf_order', XOBJ_DTYPE_INT);
	}

	/**
	 * Get a config value in a format ready for output
	 *
	 * @return	string
	 */
	public function getConfValueForOutput() {
		switch($this->getVar('conf_valuetype')) {
			case 'int':
				return (int) ($this->getVar('conf_value', 'N'));
				break;

			case 'array':
				$value = @ $this->getVar('conf_value', 'N');
				return $value ? $value : array();

			case 'float':
				$value = $this->getVar('conf_value', 'N');
				return (float) $value;
				break;

			case 'textsarea':
				return icms_core_DataFilter::checkVar($this->getVar('conf_value'), 'text', 'output');
				break;

			case 'textarea':
				return icms_core_DataFilter::checkVar($this->getVar('conf_value'), 'html', 'output');
			default:
				return $this->getVar('conf_value', 'N');
				break;
		}
	}

	/**
	 * Set a config value
	 *
	 * @param	mixed   &$value Value
	 * @param	bool    $force_slash
	 */
	public function setConfValueForInput($value, $force_slash = false) {
		if ($this->getVar('conf_formtype') == 'textarea' && $this->getVar('conf_valuetype') !== 'array') {
            if (!is_int($value) && !empty($value)) {
                $value = icms_core_DataFilter::checkVar($value, 'html', 'input');
            }
		} elseif ($this->getVar('conf_formtype') == 'textsarea' && $this->getVar('conf_valuetype') !== 'array') {
			$value = icms_core_DataFilter::checkVar($value, 'text', 'input');
		} elseif ($this->getVar('conf_formtype') == 'password') {
			$value = filter_var($value, FILTER_SANITIZE_URL);
		} else {
			$value = StopXSS($value);
		}
		switch($this->getVar('conf_valuetype')) {
			case 'array':
				if (!is_array($value)) {
					$value = explode('|', trim($value));
				}
				$this->setVar('conf_value', serialize($value), $force_slash);
				break;

			case 'text':
				$this->setVar('conf_value', trim($value), $force_slash);
				break;

			default:
				$this->setVar('conf_value', $value, $force_slash);
				break;
		}
	}

	/**
	 * Assign one or more {@link icms_config_Item_ObjectOption}s
	 *
	 * @param	mixed   $option either a {@link icms_config_Item_ObjectOption} object or an array of them
	 */
	public function setConfOptions($option) {
		if (is_array($option)) {
			$count = count($option);
			for ($i = 0; $i < $count; $i++) {
				$this->setConfOptions($option[$i]);
			}
		} else {
			if (is_object($option)) {
				$this->_confOptions[] =& $option;
			}
		}
	}

	/**
	 * Get the {@link icms_config_Item_ObjectOption}s of this Config
	 *
	 * @return	array   array of {@link icms_config_Item_ObjectOption}
	 */
	public function &getConfOptions() {
		return $this->_confOptions;
	}

	/**
	 * This function will properly set the data type for each config item, overriding the
	 * default in the __construct method
	 *
	 * @since	1.3.3
	 * @param	string	$newType	data type of the config item
	 * @return	void
	 */
	public function setType($newType) {
		$types = array(
			'text' => XOBJ_DTYPE_TXTBOX,
			'textarea' => XOBJ_DTYPE_TXTAREA,
			'int' => XOBJ_DTYPE_INT,
			'url' => XOBJ_DTYPE_URL,
			'email' => XOBJ_DTYPE_EMAIL,
			'array' => XOBJ_DTYPE_ARRAY,
			'other' => XOBJ_DTYPE_OTHER,
			'source' => XOBJ_DTYPE_SOURCE,
			'float' => XOBJ_DTYPE_FLOAT,
		);

		$this->vars['conf_value']['data_type'] = $types[$newType];
	}
}
