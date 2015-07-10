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
 * Template file object
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @category	ICMS
 * @package		View
 * @subpackage	Template
 * @version		SVN: $Id$
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * Base class for all templates
 *
 * @author Kazumi Ono (AKA onokazu)
 * @copyright	Copyright (c) 2000 XOOPS.org
 * @category	ICMS
 * @package		View
 * @subpackage	Template
 **/
class icms_view_template_file_Object extends icms_ipf_Object {

        public $tpl_source = false;    
    
	/**
	 * constructor
         * 
         * @todo: move here tpl_source
	 */
	public function __construct($handler, $data = array()) {		
		$this->initVar('tpl_id', self::DTYPE_INTEGER, null, false);
		$this->initVar('tpl_refid', self::DTYPE_INTEGER, 0, false);
		$this->initVar('tpl_tplset', self::DTYPE_DEP_OTHER, null, false);
		$this->initVar('tpl_file', self::DTYPE_DEP_TXTBOX, null, true, 100);
		$this->initVar('tpl_desc', self::DTYPE_DEP_TXTBOX, null, false, 100);
		$this->initVar('tpl_lastmodified', self::DTYPE_INTEGER, 0, false);
		$this->initVar('tpl_lastimported', self::DTYPE_INTEGER, 0, false);
		$this->initVar('tpl_module', self::DTYPE_DEP_OTHER, null, false);
		$this->initVar('tpl_type', self::DTYPE_DEP_OTHER, null, false);
		//$this->initVar('tpl_source', self::DTYPE_DEP_SOURCE, null, false);
                
                parent::__construct($handler, $data);
	}

	/**
	 * Gets Template Source
	 */
	public function getSource()	{
		$sql = "SELECT tpl_source FROM " . $this->handler->db->prefix('tplsource')
				. " WHERE tpl_id='" . $this->getVar('tpl_id') . "'";
		if (!$result = $this->handler->db->query($sql)) {
                    return false;
		}
                $myrow = $this->handler->db->fetchArray($result);
                return $myrow['tpl_source'];
	}
        
        public function getVar($name, $format = 's') {
            if ($name == 'tpl_source') {
                if ($this->tpl_source === false) {
                    $this->tpl_source = $this->getSource();
                }
                return $this->tpl_source;
            } else {
                return parent::getVar($name, $format);
            }
        }
        
        public function assignVar($name, &$value) {            
            if ($name == 'tpl_source') {
                $this->tpl_source = $value;
            } else {
                parent::assignVar($key, $value);
            }
        }
        
        public function setVar($name, $value, $options = null) {
            if ($name == 'tpl_source') {
                $this->tpl_source = $value;
            } else {
                parent::setVar($key, $value, $options);
            }
        }

	/**
	 * Gets Last Modified timestamp
	 */
	public function getLastModified()	{
		return $this->getVar('tpl_lastmodified');
	}
}

