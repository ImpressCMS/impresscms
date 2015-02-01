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
 * Database Base Class
 *
 * Defines abstract database wrapper class
 *
 * @copyright	The ImpressCMS Project <http://www.impresscms.org/>
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 *
 * @category	ICMS
 * @package		Database
 * @subpackage	Legacy
 *
 * @author		Gustavo Alejandro Pilla (aka nekro) <nekro@impresscms.org> <gpilla@nubee.com.ar>
 * @version		SVN: $Id: Database.php 12403 2014-01-26 21:35:08Z skenow $
 */

defined( 'ICMS_ROOT_PATH' ) or die();
/**
 * Abstract base class for Database access classes
 *
 * @package		Database
 * @subpackage  Legacy
 *
 * @author		Kazumi Ono  <onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 * @author		Gustavo Pilla  (aka nekro) <nekro@impresscms.org>
*/
abstract class icms_db_legacy_Database implements icms_db_legacy_IDatabase {
	
	/**
	 * Prefix for tables in the database
	 * @var string
	 */
	public $prefix = '';

	/**
	 * reference to a {@link icms_core_Logger} object
	 * @see icms_core_Logger
	 * @var object icms_core_Logger
	 */
	public $logger;

	/**
	 * If statements that modify the database are selected
	 * @var boolean
	 */
	public $allowWebChanges = FALSE;

	/**
	 * Create a legacy database object
	 *
	 * @param string $connection		Database connection resource
	 * @param string $allowWebChanges	set tp TRUE to allow inserts, updates or deletes
	 * @return	void
	 */
	public function __construct($connection = NULL, $allowWebChanges = FALSE) {
		$this->allowWebChanges = $allowWebChanges;
	}
	
	/**
	 * Setter for the logging class
	 * @see icms_db_legacy_IDatabase::setLogger()
	 * @return	void
	 */
	public function setLogger($logger) {
		$this->logger = $logger;
	}
	
	/**
	 * Setter for the table prefix
	 *
	 * @see icms_db_legacy_IDatabase::setPrefix()
	 * @return	void
	 */
	public function setPrefix($value) {
		$this->prefix = $value;
	}
	
	/**
	 * Prefix the database table name
	 * @see icms_db_legacy_IDatabase::prefix()
	 * @return	string
	 */
	public function prefix($tablename='') {
		if ( $tablename != '' ) {
			return $this->prefix .'_'. $tablename;
		} else {
			return $this->prefix;
		}
	}
}
