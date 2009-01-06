<?php
// $Id: configitem.php 1121 2007-10-23 01:17:53Z dugris $
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

if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}

/**
 * @package     kernel
 *
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */

/**#@+
 * Config type
 */
define('XOOPS_CONF', 1);
define('XOOPS_CONF_USER', 2);
define('XOOPS_CONF_METAFOOTER', 3);
define('XOOPS_CONF_CENSOR', 4);
define('XOOPS_CONF_SEARCH', 5);
define('XOOPS_CONF_MAILER', 6);
define('XOOPS_CONF_AUTH', 7);
define('IM_CONF_MULILANGUAGE', 8);
define('IM_CONF_CONTENT', 9);
define('XOOPS_CONF_PERSONA', 10);
define('ICMS_CONF_CAPTCHA', 11);
/**#@-*/

/**
 *
 *
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
class XoopsConfigItem extends XoopsObject
{

    /**
     * Config options
     *
     * @var	array
     * @access	private
     */
    var $_confOptions = array();

    /**
     * Constructor
     */
    function XoopsConfigItem()
    {
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
    function getConfValueForOutput()
    {
        switch ($this->getVar('conf_valuetype')) {
        case 'int':
            return intval($this->getVar('conf_value', 'N'));
            break;
        case 'array':
            return unserialize($this->getVar('conf_value', 'N'));
        case 'float':
            $value = $this->getVar('conf_value', 'N');
            return (float)$value;
            break;
        case 'textarea':
            return $this->getVar('conf_value');
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
    function setConfValueForInput(&$value, $force_slash = false)
    {
		if($this->getVar('conf_formtype') == 'textarea')
		{
//			include_once XOOPS_ROOT_PATH.'/class/module.textsanitizer.php';
			$myts =& MyTextSanitizer::getInstance();
			$value = $myts->displayTarea($value, 1);
		}
		else {$value = StopXSS($value);}
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
     * Assign one or more {@link XoopsConfigItemOption}s
     *
     * @param	mixed   $option either a {@link XoopsConfigItemOption} object or an array of them
     */
    function setConfOptions($option)
    {
        if (is_array($option)) {
            $count = count($option);
            for ($i = 0; $i < $count; $i++) {
                $this->setConfOptions($option[$i]);
            }
        } else {
            if(is_object($option)) {
                $this->_confOptions[] =& $option;
            }
        }
    }





    /**
     * Get the {@link XoopsConfigItemOption}s of this Config
     *
     * @return	array   array of {@link XoopsConfigItemOption}
     */
    function &getConfOptions()
    {
        return $this->_confOptions;
    }
}





/**
* XOOPS configuration handler class.
*
* This class is responsible for providing data access mechanisms to the data source
* of XOOPS configuration class objects.
*
* @author       Kazumi Ono <onokazu@xoops.org>
* @copyright    copyright (c) 2000-2003 XOOPS.org
* @package     kernel
* @subpackage  config
*/
class XoopsConfigItemHandler extends XoopsObjectHandler
{

    /**
     * Create a new {@link XoopsConfigItem}
     *
     * @see     XoopsConfigItem
     * @param	bool    $isNew  Flag the config as "new"?
     * @return	object  reference to the new config
     */
    function &create($isNew = true)
    {
        $config = new XoopsConfigItem();
        if ($isNew) {
            $config->setNew();
        }
        return $config;
    }




    /**
     * Load a config from the database
     *
     * @param	int $id ID of the config
     * @return	object  reference to the config, FALSE on fail
     */
    function &get($id)
    {
        $config = false;
    	$id = intval($id);
        if ($id > 0) {
            $sql = "SELECT * FROM ".$this->db->prefix('config')." WHERE conf_id='".$id."'";
            if (!$result = $this->db->query($sql)) {
                return $config;
            }
            $numrows = $this->db->getRowsNum($result);
            if ($numrows == 1) {
                $myrow = $this->db->fetchArray($result);
                $config = new XoopsConfigItem();
                $config->assignVars($myrow);
            }
        }
        return $config;
    }




    /**
     * Insert a config to the database
     *
     * @param	object  &$config    {@link XoopsConfigItem} object
     * @return  mixed   FALSE on fail.
     */
    function insert(&$config)
    {
        /**
        * @TODO: Change to if (!(class_exists($this->className) && $obj instanceof $this->className)) when going fully PHP5
        */
        if (!is_a($config, 'xoopsconfigitem')) {
            return false;
        }
        if (!$config->isDirty()) {
            return true;
        }
        if (!$config->cleanVars()) {
            return false;
        }
        foreach ($config->cleanVars as $k => $v) {
            ${$k} = $v;
        }
        if ($config->isNew()) {
            $conf_id = $this->db->genId('config_conf_id_seq');
            $sql = sprintf("INSERT INTO %s (conf_id, conf_modid, conf_catid, conf_name, conf_title, conf_value, conf_desc, conf_formtype, conf_valuetype, conf_order) VALUES ('%u', '%u', '%u', %s, %s, %s, %s, %s, %s, '%u')", $this->db->prefix('config'), intval($conf_id), intval($conf_modid), intval($conf_catid), $this->db->quoteString($conf_name), $this->db->quoteString($conf_title), $this->db->quoteString($conf_value), $this->db->quoteString($conf_desc), $this->db->quoteString($conf_formtype), $this->db->quoteString($conf_valuetype), intval($conf_order));
        } else {
            $sql = sprintf("UPDATE %s SET conf_modid = '%u', conf_catid = '%u', conf_name = %s, conf_title = %s, conf_value = %s, conf_desc = %s, conf_formtype = %s, conf_valuetype = %s, conf_order = '%u' WHERE conf_id = '%u'", $this->db->prefix('config'), intval($conf_modid), intval($conf_catid), $this->db->quoteString($conf_name), $this->db->quoteString($conf_title), $this->db->quoteString($conf_value), $this->db->quoteString($conf_desc), $this->db->quoteString($conf_formtype), $this->db->quoteString($conf_valuetype), intval($conf_order), intval($conf_id));
        }
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        if (empty($conf_id)) {
            $conf_id = $this->db->getInsertId();
        }
        $config->assignVar('conf_id', $conf_id);
        return true;
    }




    /**
     * Delete a config from the database
     *
     * @param	object  &$config    Config to delete
     * @return	bool    Successful?
     */
    function delete(&$config)
    {
        /**
        * @TODO: Change to if (!(class_exists($this->className) && $obj instanceof $this->className)) when going fully PHP5
        */
        if (!is_a($config, 'xoopsconfigitem')) {
            return false;
        }
        $sql = sprintf("DELETE FROM %s WHERE conf_id = '%u'", $this->db->prefix('config'), intval($config->getVar('conf_id')));
        if (!$result = $this->db->query($sql)) {
            return false;
        }
        return true;
    }




    /**
     * Get configs from the database
     *
     * @param	object  $criteria   {@link CriteriaElement}
     * @param	bool    $id_as_key  return the config's id as key?
     * @return	array   Array of {@link XoopsConfigItem} objects
     */
    function getObjects($criteria = null, $id_as_key = false)
    {
        $ret = array();
        $limit = $start = 0;
        $sql = 'SELECT * FROM '.$this->db->prefix('config');
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' '.$criteria->renderWhere();
            $sql .= ' ORDER BY conf_order ASC';
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            return false;
        }
        while ($myrow = $this->db->fetchArray($result)) {
            $config = new XoopsConfigItem();
            $config->assignVars($myrow);
            if (!$id_as_key) {
                $ret[] =& $config;
            } else {
                $ret[$myrow['conf_id']] =& $config;
            }
            unset($config);
        }
        return $ret;
    }




    /**
     * Count configs
     *
     * @param	object  $criteria   {@link CriteriaElement}
     * @return	int     Count of configs matching $criteria
     */
    function getCount($criteria = null)
    {
        $ret = array();
        $limit = $start = 0;
        $sql = 'SELECT * FROM '.$this->db->prefix('config');
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' '.$criteria->renderWhere();
        }
        $result =& $this->db->query($sql);
        if (!$result) {
            return false;
        }
        list($count) = $this->db->fetchRow($result);
        return $count;
    }
}

?>