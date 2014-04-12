<?php
// $Id: Compo.php 12313 2013-09-15 21:14:35Z skenow $
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
 * icms_db_criteria_Compo
 *
 * @copyright	Copyright (c) 2000 XOOPS.org
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 *
 * @category	ICMS
 * @package     Database
 * @subpackage  Criteria
 *
 * @since		1.3
 * @author		marcan <marcan@impresscms.org>
 * @version		SVN: $Id: Compo.php 12313 2013-09-15 21:14:35Z skenow $
 */

defined("ICMS_ROOT_PATH") or die("ImpressCMS root path not defined");

/**
 * Collection of multiple {@link icms_db_criteria_Element}s
 *
 * @category	ICMS
 * @package     Database
 * @subpackage  Criteria
 *
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	Copyright (c) 2000 XOOPS.org
 */
class icms_db_criteria_Compo extends icms_db_criteria_Element {

	/**
	 * The elements of the collection
	 * @var	array   Array of {@link icms_db_criteria_Element} objects
	 */
	public $criteriaElements = array();

	/**
	 * Conditions
	 * @var	array
	 */
	public $conditions = array();

	/**
	 * Constructor
	 *
	 * @param   object  $ele
	 * @param   string  $condition
	 **/
	public function __construct($ele=null, $condition='AND') {
		if (isset($ele) && is_object($ele)) {
			$this->add($ele, $condition);
		}
	}

	/**
	 * Add an element
	 *
	 * @param   object  &$criteriaElement
	 * @param   string  $condition
	 *
	 * @return  object  reference to this collection
	 **/
	public function &add(&$criteriaElement, $condition='AND') {
		$this->criteriaElements[] =& $criteriaElement;
		$this->conditions[] = $condition;
		return $this;
	}

	/**
	 * Make the criteria into a query string
	 *
	 * @return	string
	 */
	public function render() {
		$ret = '';
		$count = count($this->criteriaElements);
		if ($count > 0) {
			$ret = '(' . $this->criteriaElements[0]->render();
			for ($i = 1; $i < $count; $i++) {
				$ret .= ' ' . $this->conditions[$i] . ' ' . $this->criteriaElements[$i]->render();
			}
			$ret .= ')';
		}
		return $ret;
	}

	/**
	 * Make the criteria into a SQL "WHERE" clause
	 *
	 * @return	string
	 */
	public function renderWhere() {
		$ret = $this->render();
		$ret = ($ret != '') ? 'WHERE ' . $ret : $ret;
		return $ret;
	}

	/**
	 * Generate an LDAP filter from criteria
	 *
	 * @return string
	 * @author Nathan Dial ndial@trillion21.com
	 */
	public function renderLdap() {
		$retval = '';
		$count = count($this->criteriaElements);
		if ($count > 0) {
			$retval = $this->criteriaElements[0]->renderLdap();
			for ($i = 1; $i < $count; $i++) {
				$cond = $this->conditions[$i];
				if (strtoupper($cond) == 'AND') {
					$op = '&';
				} elseif (strtoupper($cond) == 'OR') {
					$op = '|';
				}
				$retval = "(" . $op . $retval . $this->criteriaElements[$i]->renderLdap() . ")";
			}
		}
		return $retval;
	}
}

