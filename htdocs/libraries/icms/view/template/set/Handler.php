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
 * Manage template sets
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	LICENSE.txt
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * Template set handler class.
 * This class is responsible for providing data access mechanisms to the data source
 * of template set class objects.
 *
 * @author	Kazumi Ono <onokazu@xoops.org>
 * @copyright	Copyright (c) 2000 XOOPS.org
 * @package	ICMS\View\Template\Set
 */
class icms_view_template_set_Handler extends icms_ipf_Handler {

        public function __construct(&$db) {
            parent::__construct($db, 'view_template_set', 'tplset_id', 'tplset_name', 'tplset_name', 'icms', 'tplset', 'tplset_id');
        }

	/**
	 * Gets templateset from database by Name
	 *
	 * @see icms_view_template_set_Object
	 * @param string $tplset_name of the tempateset to get
	 * @return object icms_view_template_set_Object {@link icms_view_template_set_Object} reference to the new template
	 **/
	public function &getByName($tplset_name) {
                $criteria = new icms_db_criteria_Item('tplset_name', trim($tplset_name));
                $criteria->setLimit(1);
                $objs = $this->getObjects($criteria);
                return isset($objs[0])?$objs[0]:null;		
	}

	/**
	 * Deletes templateset from the database
	 *
	 * @see icms_view_template_set_Object
	 * @param object $tplset {@link icms_view_template_set_Object} reference to the object of the tempateset to delete
	 * @return object icms_view_template_set_Object {@link icms_view_template_set_Object} reference to the new template
	 **/
	public function delete(&$tplset) {
		if (!parent::delete($tplset))
			return false;
		$sql = sprintf(
			"DELETE FROM %s WHERE tplset_name = %s",
			$this->db->prefix('imgset_tplset_link'),
			$this->db->quoteString($tplset->getVar('tplset_name'))
		);
		$this->db->query($sql);
		return true;
	}
}