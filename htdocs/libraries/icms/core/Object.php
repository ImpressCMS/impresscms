<?php
// $Id: Object.php 12313 2013-09-15 21:14:35Z skenow $
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
 * Manage Objects
 *
 * @copyright	Copyright (c) 2000 XOOPS.org
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @category	ICMS
 * @package		Core
 * @version		SVN: $Id: Object.php 12112 2012-11-09 02:15:50Z skenow $
 */
/* * #@+
 * Object datatype
 *
 * */
define('XOBJ_DTYPE_TXTBOX', icms_properties_Handler::DTYPE_DEP_TXTBOX);
define('XOBJ_DTYPE_TXTAREA', icms_properties_Handler::DTYPE_STRING);
define('XOBJ_DTYPE_STRING', icms_properties_Handler::DTYPE_STRING);
define('XOBJ_DTYPE_INT', icms_properties_Handler::DTYPE_INTEGER); // shorthund
define('XOBJ_DTYPE_INTEGER', icms_properties_Handler::DTYPE_INTEGER);
define('XOBJ_DTYPE_URL', icms_properties_Handler::DTYPE_DEP_URL);
define('XOBJ_DTYPE_EMAIL', icms_properties_Handler::DTYPE_DEP_EMAIL);
define('XOBJ_DTYPE_ARRAY', icms_properties_Handler::DTYPE_ARRAY);
define('XOBJ_DTYPE_OTHER', icms_properties_Handler::DTYPE_DEP_OTHER);
define('XOBJ_DTYPE_SOURCE', icms_properties_Handler::DTYPE_DEP_SOURCE);
define('XOBJ_DTYPE_STIME', icms_properties_Handler::DTYPE_DEP_STIME);
define('XOBJ_DTYPE_MTIME', icms_properties_Handler::DTYPE_DEP_MTIME);
define('XOBJ_DTYPE_DATETIME', icms_properties_Handler::DTYPE_DATETIME);
define('XOBJ_DTYPE_LTIME', icms_properties_Handler::DTYPE_DATETIME);


define('XOBJ_DTYPE_SIMPLE_ARRAY', icms_properties_Handler::DTYPE_LIST);
define('XOBJ_DTYPE_CURRENCY', icms_properties_Handler::DTYPE_DEP_CURRENCY);
define('XOBJ_DTYPE_FLOAT', icms_properties_Handler::DTYPE_FLOAT);
define('XOBJ_DTYPE_TIME_ONLY', icms_properties_Handler::DTYPE_DEP_TIME_ONLY);
define('XOBJ_DTYPE_URLLINK', icms_properties_Handler::DTYPE_DEP_URLLINK);
define('XOBJ_DTYPE_FILE', icms_properties_Handler::DTYPE_DEP_FILE);
define('XOBJ_DTYPE_IMAGE', icms_properties_Handler::DTYPE_DEP_IMAGE);
define('XOBJ_DTYPE_FORM_SECTION', icms_properties_Handler::DTYPE_DEP_FORM_SECTION);
define('XOBJ_DTYPE_FORM_SECTION_CLOSE', icms_properties_Handler::DTYPE_DEP_FORM_SECTION_CLOSE);

/* * #@- */

/**
 * Base class for all objects in the kernel (and beyond)
 *
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 *
 * @category	ICMS
 * @package		Core
 *
 * @since		XOOPS
 * @author		Kazumi Ono (AKA onokazu)
 * */
class icms_core_Object extends icms_properties_Handler {

    /**
     * is it a newly created object?
     *
     * @var bool
     * @access private
     */
    private $_isNew = false;

    /**
     * errors
     *
     * @var array
     * @access private
     */
    private $_errors = array();

    /**
     * additional filters registered dynamically by a child class object
     *
     * @access private
     */
    private $_filters = array();

    /**
     * constructor
     *
     * normally, this is called from child classes only
     * @access public
     */
    public function __construct() {
        
    }

    /*     * #@+
     * used for new/clone objects
     *
     * @access public
     */

    public function setNew() {
        $this->_isNew = true;
    }

    public function unsetNew() {
        $this->_isNew = false;
    }

    public function isNew() {
        return $this->_isNew;
    }

    /*     * #@- */

    /**
     * initialize variables for the object
     *
     * @access public
     * @param string $key
     * @param int $data_type  set to one of self::DTYPE_XXX constants
     * @param mixed
     * @param bool $required  require html form input?
     * @param int $maxlength  for self::DTYPE_STRING, self::DTYPE_INTERGER types only
     * @param string $option  does this data have any select options?
     */
    public function initVar($key, $data_type, $value = null, $required = false, $maxlength = null, $options = '') {
        parent::initVar($key, $data_type, $value, $required, array(
            parent::VARCFG_MAX_LENGTH => $maxlength,
            'options' => $options
                )
        );
    }

    /**
     * Assign values to multiple variables in a batch
     *
     * Meant for a CGI context:
     * - prefixed CGI args are considered safe
     * - avoids polluting of namespace with CGI args
     *
     * @access public
     * @param array $var_arr associative array of values to assign
     * @param string $pref prefix (only keys starting with the prefix will be set)
     */
    public function setFormVars($var_arr = null, $pref = 'xo_', $not_gpc = false) {
        $len = strlen($pref);
        foreach ($var_arr as $key => $value) {
            if ($pref == substr($key, 0, $len)) {
                $this->setVar(substr($key, $len), $value, $not_gpc);
            }
        }
    }

    /**
     * dynamically register additional filter for the object
     *
     * @param string $filtername name of the filter
     * @access public
     */
    public function registerFilter($filtername) {
        $this->_filters[] = $filtername;
    }

    /**
     * load all additional filters that have been registered to the object
     *
     * @access private
     */
    private function _loadFilters() {
        
    }
    
    /**
     * Clone current instance
     * 
     * @return object
     * 
     * @deprecated since version 2.0
     */
    public function xoopsClone() {
        trigger_error('Deprecached method xoopsClone', E_USER_DEPRECATED);
        return clone $this;
    }
    
    /**
     * Sets object modified
     * 
     * @deprecated since version 2.0
     */
    public function setDirty() {
        trigger_error('Deprecached method setDirty', E_USER_DEPRECATED);
        $this->setVarInfo(null, parent::VARCFG_CHANGED, true); 
    }
    
    /**
     * Sets object unmodified
     * 
     * @deprecated since version 2.0
     */
    public function unsetDirty() {
        trigger_error('Deprecached method unsetDirty', E_USER_DEPRECATED);
        $this->setVarInfo(null, parent::VARCFG_CHANGED, false); 
    }
    
    /**
     * Is object modified?
     * 
     * @deprecated since version 2.0
     */
    public function isDirty() {
        trigger_error('Deprecached method isDirty', E_USER_DEPRECATED);
        return count($this->getChangedVars()) > 0;
    }

    /**
     * Create cloned copy of current object
     */
    public function __clone() {
        $this->setNew();
    }

    /**
     * add an error
     *
     * @param string $value error to add
     * @access public
     */
    public function setErrors($err_str, $prefix = false) {
        if (is_array($err_str)) {
            foreach ($err_str as $str) {
                $this->setErrors($str, $prefix);
            }
        } else {
            if ($prefix) {
                $err_str = "[" . $prefix . "] " . $err_str;
            }
            $this->_errors[] = trim($err_str);
        }
    }

    /**
     * return the errors for this object as an array
     *
     * @return array an array of errors
     * @access public
     */
    public function getErrors() {
        return $this->_errors;
    }

    /**
     * return the errors for this object as html
     *
     * @return string html listing the errors
     * @access public
     */
    public function getHtmlErrors() {
        $ret = '<h4>' . _ERROR . '</h4>';
        if (empty($this->_errors)) {
            $ret .= _NONE . '<br />';
        } else {
            $ret .= implode('<br />', $this->_errors);
        }
        return $ret;
    }

    /**
     *
     */
    public function hasError() {
        return count($this->_errors) > 0;
    }

}
