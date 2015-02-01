<?php
// $Id: ObjectHandler.php 12313 2013-09-15 21:14:35Z skenow $
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
 * Manage of original Objects
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @category	ICMS
 * @package		Core
 * @version		SVN: $Id: ObjectHandler.php 12313 2013-09-15 21:14:35Z skenow $
 */

/**
 * Abstract object handler class.
 *
 * This class is an abstract class of handler classes that are responsible for providing
 * data access mechanisms to the data source of its corresponsing data objects
 *
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 *
 * @category	ICMS
 * @package		Core
 *
 * @since		XOOPS
 * @author		Kazumi Ono <onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 *
 * @abstract
 */
abstract class icms_core_ObjectHandler {

	/**
	 * holds referenced to {@link icms_db_legacy_Database} class object
	 *
	 * @var object
	 * @see icms_db_legacy_Database
	 * @access protected
	 */
	protected $db;

	//
	/**
	* called from child classes only
	*
	* @param object $db reference to the {@link icms_db_legacy_Database} object
	* @access protected
	*/
	function __construct(&$db) {
		$this->db =& $db;
	}

	/**
	 * creates a new object
	 *
	 * @abstract
	 */
	abstract function &create();

	/**
	 * gets a value object
	 *
	 * @param int $int_id
	 * @abstract
	 */
	abstract function &get($int_id);

	/**
	 * insert/update object
	 *
	 * @param object $object
	 * @abstract
	 */
	abstract function insert(&$object);

	/**
	 * delete object from database
	 *
	 * @param object $object
	 * @abstract
	 */
	abstract function delete(&$object);

}
