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
// Modified by: Nathan Dial                                                  //
// Date: 20 March 2003                                                       //
// Desc: added experimental LDAP filter generation code                      //
//       also refactored to remove about 20 lines of redundant code.         //
// ------------------------------------------------------------------------- //
/**
 * Criteria Base Class for composing Where clauses in SQL Queries
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @author	modified by UnderDog <underdog@impresscms.org>
 */

defined("ICMS_ROOT_PATH") or die("ImpressCMS root path not defined");

/**
 * A criteria (grammar?) for a database query.
 *
 * Abstract base class should never be instantiated directly.
 *
 * @abstract
 * @package	ICMS\Database\Criteria
 *
 * @author	Kazumi Ono      <onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2007 XOOPS.org
 */
abstract class icms_db_criteria_Element {
	/**
	 * Sort order
	 * @var	string
	 */
	public $order = 'ASC';

	/**
	 * @var	string
	 */
	public $sort = '';

	/**
	 * Number of records to retrieve
	 * @var	int
	 */
	public $limit = 0;

	/**
	 * Offset of first record
	 * @var	int
	 */
	public $start = 0;

	/**
	 * @var	string
	 */
	public $groupby = '';

	/**
	 * Constructor
	 **/
	public function __construct(){}

	/**
	 * Render the criteria element
	 */
	abstract public function render();

	/**
	 * @param	string  $sort
	 */
	public function setSort($sort) {
		$this->sort = $sort;
	}

	/**
	 * @return	string
	 */
	public function getSort() {
		return $this->sort;
	}

	/**
	 * @param	string  $order
	 */
	public function setOrder($order) {
                $order = strtoupper($order);
                if ($order == 'DESC' || $order == 'ASC') {
                        $this->order = $order;
                }
	}

	/**
	 * @return	string
	 */
	public function getOrder() {
		return $this->order;
	}

	/**
	 * @param	int $limit
	 */
	public function setLimit($limit=0) {
		$this->limit = (int) ($limit);
	}

	/**
	 * @return	int
	 */
	public function getLimit() {
		return $this->limit;
	}

	/**
	 * @param	int $start
	 */
	public function setStart($start=0) {
		$this->start = (int) ($start);
	}

	/**
	 * @return	int
	 */
	public function getStart() {
		return $this->start;
	}

	/**
	 * @param	string  $group
	 */
	public function setGroupby($group) {
		$this->groupby = $group;
	}

	/**
	 * @return	string
	 */
	public function getGroupby() {
		return ' GROUP BY ' . $this->groupby;
	}
}

