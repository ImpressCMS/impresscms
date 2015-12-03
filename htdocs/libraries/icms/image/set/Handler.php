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
 * Manage of imagesets baseclass
 * Image sets - the image directory within a module - are part of templates
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	XOOPS_copyrights.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	LICENSE.txt
 * @since	XOOPS
 * @author	http://www.xoops.org The XOOPS Project
 * @author	modified by UnderDog <underdog@impresscms.org>
 */
defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * XOOPS imageset handler class.
 * This class is responsible for providing data access mechanisms to the data source
 * of XOOPS imageset class objects.
 *
 * @package	ICMS\Image\Set
 * @author      Kazumi Ono <onokazu@xoops.org>
 * @copyright	Copyright (c) 2000 XOOPS.org
 */
class icms_image_set_Handler extends \icms_ipf_Handler {

        /**
         * Constructor
         * 
         * @param \icms_db_IConnection $db              Database connection
         */
        public function __construct(&$db) {                
                parent::__construct($db, 'image_set', 'imgset_id', 'imgset_name', '', 'icms', 'imgset');
        }

        /**
         * This event executes after deletion
         * 
         * @param \icms_image_set_Object $obj           Instance of icms_image_set_Object
         * 
         * @return boolean
         */
        protected function afterDelete($obj) {
                $sql = sprintf("DELETE FROM %s WHERE imgset_id = '%u'", $this->db->prefix('imgset_tplset_link'), (int) $imgset->getVar('imgset_id'));
                $this->db->query($sql);
                return true;
        }

    /**
     * retrieve array of {@link icms_image_set_Object}s meeting certain conditions
     * @param object $criteria {@link CriteriaElement} with conditions for the imagesets
     * @param bool $id_as_key should the imageset's imgset_id be the key for the returned array?
     * @return array {@link icms_image_set_Object}s matching the conditions
     * */
    function &getObjects($criteria = NULL, $id_as_key = FALSE) {
        $ret = array();
        $limit = $start = 0;
        $sql = 'SELECT DISTINCT i.* FROM ' . $this->db->prefix('imgset') . ' i LEFT JOIN ' . $this->db->prefix('imgset_tplset_link') . ' l ON l.imgset_id=i.imgset_id';
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' ' . $criteria->renderWhere();
            $limit = $criteria->getLimit();
            $start = $criteria->getStart();
        }
        $result = $this->db->query($sql, $limit, $start);
        if (!$result) {
            return $ret;
        }
        while ($myrow = $this->db->fetchArray($result)) {
            $imgset = new icms_image_set_Object();
            $imgset->assignVars($myrow);
            if (!$id_as_key) {
                $ret[] = & $imgset;
            } else {
                $ret[$myrow['imgset_id']] = & $imgset;
            }
            unset($imgset);
        }
        return $ret;
    }

    /**
     * Links a {@link icms_image_set_Object} to a themeset (tplset)
     * @param int $imgset_id image set id to link
     * @param int $tplset_name theme set to link
     * @return bool TRUE if succesful FALSE if unsuccesful
     * */
    function linkThemeset($imgset_id, $tplset_name) {
        $imgset_id = (int) $imgset_id;
        $tplset_name = trim($tplset_name);
        if ($imgset_id <= 0 || $tplset_name == '') {
            return false;
        }
        if (!$this->unlinkThemeset($imgset_id, $tplset_name)) {
            return false;
        }
        $sql = sprintf("INSERT INTO %s (imgset_id, tplset_name) VALUES ('%u', %s)", $this->db->prefix('imgset_tplset_link'), $imgset_id, $this->db->quoteString($tplset_name));
        $result = $this->db->query($sql);
        if (!$result) {
            return false;
        }
        return true;
    }

    /**
     * Unlinks a {@link icms_image_set_Object} from a themeset (tplset)
     *
     * @param int $imgset_id image set id to unlink
     * @param int $tplset_name theme set to unlink
     * @return bool TRUE if succesful FALSE if unsuccesful
     * */
    function unlinkThemeset($imgset_id, $tplset_name) {
        $imgset_id = (int) $imgset_id;
        $tplset_name = trim($tplset_name);
        if ($imgset_id <= 0 || $tplset_name == '') {
            return false;
        }
        $sql = sprintf("DELETE FROM %s WHERE imgset_id = '%u' AND tplset_name = %s", $this->db->prefix('imgset_tplset_link'), $imgset_id, $this->db->quoteString($tplset_name));
        $result = $this->db->query($sql);
        if (!$result) {
            return false;
        }
        return true;
    }

    /**
     * get a list of {@link icms_image_set_Object}s matching certain conditions
     *
     * @param int $refid conditions to match
     * @param int $tplset conditions to match
     * @return array array of {@link icms_image_set_Object}s matching the conditions
     * */
    function getList($refid = null, $tplset = null) {
        $criteria = new CriteriaCompo();
        if (isset($refid)) {
            $criteria->add(new Criteria('imgset_refid', (int) $refid));
        }
        if (isset($tplset)) {
            $criteria->add(new Criteria('tplset_name', $tplset));
        }
        $imgsets = & $this->getObjects($criteria, true);
        $ret = array();
        foreach (array_keys($imgsets) as $i) {
            $ret[$i] = $imgsets[$i]->getVar('imgset_name');
        }
        return $ret;
    }

}
