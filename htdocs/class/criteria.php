<?php
// $Id: criteria.php 785 2006-11-06 06:11:17Z skalpa $
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
// Modified by: Nathan Dial                                                  //
// Date: 20 March 2003                                                       //
// Desc: added experimental LDAP filter generation code                      //
//       also refactored to remove about 20 lines of redundant code.         //
// ------------------------------------------------------------------------- //
/**
 * Criteria Base Class for composing Where clauses in SQL Queries
 *
 * @copyright	http://www.xoops.org/ The XOOPS Project
 * @copyright	XOOPS_copyrights.txt
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	core
 * @since	XOOPS
 * @author	http://www.xoops.org The XOOPS Project
 * @author	modified by UnderDog <underdog@impresscms.org>
 * @version	$Id: criteria.php 19118 2010-03-27 17:46:23Z skenow $
 * @deprecated
 * @todo	Remove completely in version 1.4
 */

/**
 *
 *
 * @package     kernel
 * @subpackage  database
 *
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */

/**
 * A criteria (grammar?) for a database query.
 *
 * Abstract base class should never be instantiated directly.
 *
 * @abstract
 *
 * @package     kernel
 * @subpackage  database
 *
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 * @deprecated	Use icms_db_criteria_Element, instead
 * @todo		Remove in version 1.4
 */
abstract class CriteriaElement extends icms_db_criteria_Element
{
	public function __construct() {
	}
}

/**
 * Collection of multiple {@link CriteriaElement}s
 *
 * @package     kernel
 * @subpackage  database
 *
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 * @deprecated	Use icms_db_criteria_Compo, instead
 * @todo		Remove in version 1.4
 */
class CriteriaCompo extends icms_db_criteria_Compo
{
	private $_errors;
	public function __construct($ele=null, $condition='AND') {
		parent::__construct($ele, $condition);
		$this->_errors = icms_core_Debug::setDeprecated('icms_db_criteria_Compo', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}

}

/**
 * A single criteria
 *
 * @package     kernel
 * @subpackage  database
 *
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 * @deprecated	Use icms_db_criteria_Item, instead
 * @todo		Remove in version 1.4
 */
class Criteria extends icms_db_criteria_Item
{
	private $_errors;
	public function __construct($column, $value='', $operator='=', $prefix = '', $function = '') {
		parent::__construct($column, $value, $operator, $prefix, $function);
		$this->_errors = icms_core_Debug::setDeprecated('icms_db_criteria_Item', sprintf(_CORE_REMOVE_IN_VERSION, '1.4'));
	}
}

