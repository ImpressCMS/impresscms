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
 * Manage images
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	LICENSE.txt
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * Image handler class.
 *
 * This class is responsible for providing data access mechanisms to the data source
 * of image class objects.
 *
 * @package	ICMS\Image
 * @author	Kazumi Ono 	<onokazu@xoops.org>
 * @copyright	Copyright (c) 2000 XOOPS.org
 */
class icms_image_Handler extends \icms_ipf_Handler {
    
    /**
     * Handler for image bodies
     *
     * @var \icms_image_body_Handler
     */
    protected $imagebody_handler;


    /**
         * Constructor
         * 
         * @param \icms_db_IConnection $db              Database connection
         */
        public function __construct(&$db) {
            $this->imagebody_handler = \icms::handler('icms_image_body');
            
            parent::__construct($db, 'image', 'image_id', 'image_name', 'image_nicename', 'icms', 'image');
        }

	/**
	 * Load a {@link icms_image_Object} object from the database
	 *
	 * @param   int                             $id             ID
	 * @param   boolean                         $as_object      Get as object?
         * @param   bool                            $debug          Enable debug mode
         * @param   bool|\icms_db_criteria_Element  $criteria       A criteria used to filter data
         * 
	 * @return  object  {@link icms_image_Object}, FALSE on fail
	 **/
	public function &get($id, $as_object=true, $debug = false, $criteria = false) {            
            $this->generalSQL = 'SELECT i.*, b.image_body FROM '
				. $this->table . ' i LEFT JOIN '
				. $this->imagebody_handler->table
				. ' b ON b.image_id=i.image_id';
            return parent::get($id, $as_object, $debug, $criteria);
	}        
        
        /**
         * This event is executed when saving
         * 
         * @param   \icms_image_body_Object $obj        Saving object
         * 
         * @return  boolean
         */
        protected function afterSave(&$obj) {
            if ($obj->image_body) {
                $body = $this->imagebody_handler->get($obj->image_id);
                $body->image_body = $obj->image_body;
                return $body->store();
            }
            return true;
        }    
        
        /**
         * This event executes after deletion
         * 
         * @param \icms_image_body_Object $obj      Deleted object
         * 
         * @return boolean
         */
        protected function afterDelete(&$obj) {
            $sql = sprintf('DELETE FROM %s WHERE image_id = %d', $this->imagebody_handler->table, $obj->image_id);
            $this->db->query($sql);
            return true;
        }

	/**
	 * Load {@link icms_image_Object}s from the database
	 *
	 * @param   object  $criteria   {@link icms_db_criteria_Element}
	 * @param   boolean $id_as_key  Use the ID as key into the array
	 * @param   boolean $getbinary  Get binary image?
         * 
	 * @return  array   Array of {@link icms_image_Object} objects
	 **/
	public function getObjects($criteria = NULL, $id_as_key = FALSE, $getbinary = FALSE) {
		if ($getbinary) {
			$this->generalSQL = "SELECT i.*, b.image_body FROM ".$this->table." i LEFT JOIN ".$this->imagebody_handler->table." b ON b.image_id=i.image_id";
		} else {
			$this->generalSQL = '';
		}
		if (
                        ($criteria instanceof \icms_db_criteria_Element) &&
                        (!in_array($criteria->getSort(), array('image_id', 'image_created', 'image_mimetype', 'image_display', 'image_weight')))
                    ) {
                    $criteria->setSort('image_weight');		
		}		
		return parent::getObjects($criteria, $id_as_key, true);
	}

	/**
	 * Get a list of images
	 *
	 * @param   int         $imgcat_id      Image category ID
	 * @param   bool|null   $image_display  List only displaed images?
         * 
	 * @return  array                       Array of {@link icms_image_Object} objects
	 **/
	public function getList($imgcat_id, $image_display = NULL) {
		$criteria = new icms_db_criteria_Compo(new icms_db_criteria_Item('imgcat_id', (int) ($imgcat_id)));
		if (isset($image_display)) {
			$criteria->add(new icms_db_criteria_Item('image_display', (int) ($image_display)));
		}
		$images =& $this->getObjects($criteria, FALSE, TRUE);
		$ret = array();
		foreach (array_keys($images) as $i) {
			$ret[$images[$i]->getVar('image_name')] = $images[$i]->getVar('image_nicename');
		}
		return $ret;
	}
}
