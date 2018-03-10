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
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @author	Kazumi Ono (aka onokazo)
 */
if (!defined('ICMS_ROOT_PATH')) die("ImpressCMS root path not defined");

/**
 * Config item
 *
 * @author	Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 * @package	ICMS\Config\Item
 *
 * @property int        $conf_id            Item ID
 * @property int        $conf_modid         Module ID
 * @property int        $conf_catid         Category ID
 * @property string     $conf_name          Name (used for programmers)
 * @property string     $conf_title         Title (shown on forms)
 * @property string     $conf_value         Value
 * @property string     $conf_desc          Description (shown on forms)
 * @property string     $conf_formtype      What control use for displaying field on form?
 * @property string     $conf_valuetype     Type of value
 * @property int        $conf_order         Order (used to sorting fields before displaying on form)
 */
class icms_config_item_Object extends icms_ipf_Object {
	/**
	 * Config options
	 *
	 * @var	array
	 * @access	private
	 */
	public $_confOptions = array();

        /**
       * is it a newly created config object?
        *
       * @var bool
       * @access protected
       */
        protected $_isNewConfig = false;

	/**
	 * Constructor
	 *
	 * @todo	Cannot set the data type of the conf_value on instantiation - the data type must be retrieved from the db.
	 */
	public function __construct(&$handler, $data = array()) {
		$this->initVar('conf_id', self::DTYPE_INTEGER, null, false);
		$this->initVar('conf_modid', self::DTYPE_INTEGER, null, false);
		$this->initVar('conf_catid', self::DTYPE_INTEGER, null, false);
		$this->initVar('conf_name',  self::DTYPE_STRING, false, '',  75);
		$this->initVar('conf_title', self::DTYPE_STRING, false, '',  255);
		$this->initVar('conf_value', self::DTYPE_STRING);
		$this->initVar('conf_desc',  self::DTYPE_STRING, false, '',  255);
		$this->initVar('conf_formtype',  self::DTYPE_STRING, false, '',  15);
		$this->initVar('conf_valuetype',  self::DTYPE_STRING, false, '',  10);
		$this->initVar('conf_order', self::DTYPE_INTEGER);

                parent::__construct($handler, $data);
	}

        /**
        * #@+
        * used for new config objects when installing/updating module(s)
         *
        * @access public
        */

        public function setNewConfig() {
            $this->_isNewConfig = true;
        }

        public function unsetNewConfig() {
            $this->_isNewConfig = false;
        }

        public function isNewConfig() {
            return $this->_isNewConfig;
        }

        /*    * #@- */

        /*    * #@+

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
                $value = $this->getVar('conf_value', 'N');
                if ($value === null || strlen($value) < 2 || (substr($value, 1, 1) != ':'))
                	return array();
                $value = @unserialize($value);
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
			$value = icms_core_DataFilter::checkVar($value, 'html', 'input');
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
        * @todo        Remove param $dummy once after removing setType from icms_properties_Handler (this is hack to bypass PHP strict message)
	 *
	 * @since	1.3.3
	 * @param	string	$newType	data type of the config item
	 * @return	void
	 */
	public function setType($newType, $dummy = null) {
		$types = array(
			'text' => self::DTYPE_DEP_TXTBOX,
			'textarea' => self::DTYPE_STRING,
			'int' => self::DTYPE_INTEGER,
			'url' => self::DTYPE_DEP_URL,
			'email' => self::DTYPE_DEP_EMAIL,
			'array' => self::DTYPE_ARRAY,
			'other' => self::DTYPE_DEP_OTHER,
			'source' => self::DTYPE_DEP_SOURCE,
			'float' => self::DTYPE_FLOAT,
		);

		$this->setVarInfo('conf_value', 'data_type',$types[$newType]);
	}
}
