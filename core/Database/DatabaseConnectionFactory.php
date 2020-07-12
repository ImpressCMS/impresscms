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

namespace ImpressCMS\Core\Database;

use icms;

/**
 * Establishes database class and connection
 *
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	ICMS\Database
 * @copyright	copyright (c) 2000-2007 XOOPS.org
 * @copyright   The ImpressCMS Project <http://www.impresscms.org>
 */
class DatabaseConnectionFactory
{

	/**
	 * Just making DatabaseConnectionFactory constructor private.
	 */
	private function __construct()
	{

	}

	/**
	 * Get a reference to the only instance of database class and connects to DB
	 *
	 * if the class has not been instantiated yet, this will also take
	 * care of that
	 *
	 * @copyright    copyright (c) 2000-2007 XOOPS.org
	 * @author        modified by arcandier, The ImpressCMS Project
	 *
	 * @static
	 * @return      object  Reference to the only instance of database class
	 */
	public static function instance()
	{
		return icms::getInstance()->get('db');
	}

	/**
	 * Instanciate the PDO compatible DB adapter (if appropriate).
	 *
	 * @copyright	The ImpressCMS Project <http://www.impresscms.org>
	 *
	 * @throws RuntimeException
	 */
	public static function pdoInstance() {
		return icms::getInstance()->get('db');
	}
}
