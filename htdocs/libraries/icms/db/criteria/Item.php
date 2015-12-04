<?php
// $Id: Item.php 12313 2013-09-15 21:14:35Z skenow $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Softsware Foundation; either version 2 of the License, or        //
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
 * A single criteria for a database query
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 */
defined("ICMS_ROOT_PATH") or die("ImpressCMS root path not defined");

/**
 * A single criteria
 *
 * @package	ICMS\Database\Criteria
 * @author	Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2007 XOOPS.org
 */
class icms_db_criteria_Item extends icms_db_criteria_Element {

	/**
         * Prefix for column
         * 
	 * @var	string
	 */
	public $prefix = '';
        
        /**
         * Function used for column
         *
         * @var string
         */
	public $function = '';
        
        /**
         * Column name
         *
         * @var string
         */
	public $column = '';
        
        /**
         * Operator used in comparision
         *
         * @var string
         */
	public $operator = '';
        
        /**
         * Value used in comparision
         *
         * @var mixed
         */
	public $value = null;

	/**
	 * Constructor
	 *
	 * @param   string  $column
	 * @param   mixed  $value
	 * @param   string  $operator
	 **/
	public function __construct($column, $value='', $operator='=', $prefix = '', $function = '') {
		$this->prefix = $prefix;
		$this->function = $function;
		$this->column = $column;
		$this->value = $value;
		$this->operator = $operator;
	}

	/**
	 * Make a sql condition string
	 *
	 * @return  string
	 **/
	public function render() {
		$clause = (!empty($this->prefix) ? "{$this->prefix}." : "") . $this->column;
		if (!empty($this->function)) {
			$clause = sprintf($this->function, $clause);
		}
		if (in_array( strtoupper($this->operator), array('IS NULL', 'IS NOT NULL'))) {
			$clause .= ' ' . $this->operator;
		} else {
                        if (is_bool($this->value)) {
                            $value = (int)$this->value;
                        } else if (is_object($this->value)) {
                            $value = (string)$this->value;
                        } else if (is_array($this->value)) {
                            if (!empty($this->value)) {                                
                                $value = '(\'' . implode('\', \'', $this->value) . '\')';
                            } else {
                                $value = '()';
                            }
                        } else if (is_null($this->value)) {
                            $value = 'NULL';
                        } else {
                            if ('' === ($value = trim($this->value))) {
				return '';
                            }
                            if (!in_array(strtoupper($this->operator), array('IN', 'NOT IN'))) {
                                    if (( substr($value, 0, 1) != '`' ) && ( substr($value, -1) != '`' )) {
                                            $value = "'$value'";
                                    } elseif (!preg_match('/^[a-zA-Z0-9_\.\-`]*$/', $value)) {
                                            $value = '``';
                                    }
                            }
                        }
                    
			
			$clause .= " {$this->operator} $value";
		}
		return $clause;
	}

	/**
	 * Generate an LDAP filter from criteria
	 *
	 * @return string
	 * @author Nathan Dial ndial@trillion21.com, improved by Pierre-Eric MENUET pemen@sourceforge.net
	 */
	public function renderLdap() {
		if ($this->operator == '>') {
			$this->operator = '>=';
		}
		if ($this->operator == '<') {
			$this->operator = '<=';
		}

		if ($this->operator == '!=' || $this->operator == '<>') {
			$operator = '=';
			$clause = "(!(" . $this->column . $operator . $this->value . "))";
		}
		else {
			if ($this->operator == 'IN') {
				$newvalue = str_replace(array('(', ')'), '', $this->value);
				$tab = explode(',', $newvalue);
				foreach ($tab as $uid) {
					$clause .= '(' . $this->column . '=' . $uid
					.')';
				}
				$clause = '(|' . $clause . ')';
			} else {
				$clause = "(" . $this->column . $this->operator . $this->value . ")";
			}
		}
		return $clause;
	}

	/**
	 * Make a SQL "WHERE" clause
	 *
	 * @return	string
	 */
	public function renderWhere() {
		$cond = $this->render();
		return empty($cond) ? '' : "WHERE $cond";
	}
}

