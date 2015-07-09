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
 * Manage template files
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @category	ICMS
 * @package		View
 * @subpackage	Template
 * @version		SVN: $Id$
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * Template file handler class.
 * This class is responsible for providing data access mechanisms to the data source
 * of template file class objects.
 *
 * @category	ICMS
 * @package		View
 * @subpackage	Template
 * @author		Kazumi Ono <onokazu@xoops.org>
 * @copyright	Copyright (c) 2000 XOOPS.org
 */
class icms_view_template_file_Handler extends icms_ipf_Handler {
	private $_prefetch_cache = array();

        public function __construct(&$db) {
            parent::__construct($db, 'view_template_file', 'tpl_id', 'tpl_tplset', 'tpl_file', 'icms', 'tplfile', 'tpl_id');
        }        	

	/**
	 * Loads Template source from DataBase
	 *
	 * @see icms_view_template_file_Object
	 * @param object $tplfile {@link icms_view_template_file_Object} object of the template file to load
	 * @return bool TRUE on success, FALSE if fail
	 **/
	public function loadSource(icms_view_template_file_Object &$tplfile) {
		if (!$tplfile->getVar('tpl_source')) {
			$sql = "SELECT tpl_source FROM " . $this->table
				. " WHERE tpl_id='" . $tplfile->getVar('tpl_id') . "'";
			if (!$result = $this->db->query($sql)) {
				return false;
			}
			$myrow = $this->db->fetchArray($result);
			$tplfile->assignVar('tpl_source', $myrow['tpl_source']);
		}
		return true;
	}

	/**
	 * forces Template source into the DataBase
	 * @param object $tplfile {@link icms_view_template_file_Object} object of the template file to load
	 * @return bool TRUE on success, FALSE if fail
	 **/
	public function forceUpdate(&$tplfile) {
            if ($tplfile->isNew())
                return false;
            return $tplfile->store(true);		
	}

	/**
	 * Count some tplfiles for a module
	 *
	 * @param   string  $tplset Template Set
	 * @return  array $ret containing number of templates in the tpl_set or empty array if fails
	 **/
	public function getModuleTplCount($tplset) {
		$ret = array();
		$sql = "SELECT tpl_module, COUNT(tpl_id) AS count FROM " . $this->db->prefix('tplfile')
			. " WHERE tpl_tplset='" . $tplset . "' GROUP BY tpl_module";
		$result = $this->db->query($sql);
		if (!$result) {
			return $ret;
		}
		while ($myrow = $this->db->fetchArray($result)) {
			if ($myrow['tpl_module'] != '') {
				$ret[$myrow['tpl_module']] = $myrow['count'];
			}
		}
		return $ret;
	}

	/**
	 * find tplfiles matching criteria
	 *
	 * @param   string  $tplset             template set
	 * @param   string  $type               template type
	 * @param   int     $refid              ref id
	 * @param   string  $module             module
	 * @param   string  $file               template file
	 * @param   bool    $getsource = false  get source or not
	 * @return  array $ret containing number of templates in the tpl_set or empty array if fails
	 **/
	public function find($tplset = null, $type = null, $refid = null, $module = null, $file = null, $getsource = false) {
		$criteria = new icms_db_criteria_Compo();
		if (isset($tplset)) {
			$criteria->add(new icms_db_criteria_Item('tpl_tplset', $tplset));
		}
		if (isset($module)) {
			$criteria->add(new icms_db_criteria_Item('tpl_module', $module));
		}
		if (isset($refid)) {
			$criteria->add(new icms_db_criteria_Item('tpl_refid', $refid));
		}
		if (isset($file)) {
			$criteria->add(new icms_db_criteria_Item('tpl_file', $file));
		}
		if (isset($type)) {
			if (is_array($type)) {
				$criteria2 = new icms_db_criteria_Compo();
				foreach ( $type as $t) {
					$criteria2->add(new icms_db_criteria_Item('tpl_type', $t), 'OR');
				}
				$criteria->add($criteria2);
			} else {
				$criteria->add(new icms_db_criteria_Item('tpl_type', $type));
			}
		}
		return $this->getObjects($criteria, false, true);
	}

	/**
	 * Does the template exist in the database in the template set
	 *
	 * @param   string  $tplname        template name
	 * @param   string  $tplset_name    template set name
	 * @return  bool true if exists, false if not
	 **/
	public function templateExists($tplname, $tplset_name) {
		$criteria = new icms_db_criteria_Compo(new icms_db_criteria_Item('tpl_file', trim($tplname)));
		$criteria->add(new icms_db_criteria_Item('tpl_tplset', trim($tplset_name)));
		if ($this->getCount($criteria) > 0) {
			return true;
		}
		return false;
	}

	/**
	 * Prefetch blocks to reduce the amount of queries required by Smarty to generate all blocks
	 * This function is called exclusively in icms_view_PageBuilder
	 *
	 * @global	array	$icmsConfig		icms configuration array
	 * @param	array	$block_arr		array of blocks to prefetch
	 * @return	bool					false if there are no blocks to prefetch, otherwise true
	 */
	public function prefetchBlocks(&$block_arr) {
		global $icmsConfig;

		if (count($block_arr) == 0) return false;
		$tplNames = array();

		/**
		 * @todo As soon as the criteria object is capable of rendering complex conditions,
		 * this should be converted into the criteria approach
		 */
		$sql = "SELECT f.* FROM " . $this->table . " f "		     
		     . "WHERE (tpl_tplset = '" . $icmsConfig["template_set"] . "' "
		     // always load the default templates as a fallback
		     . "OR tpl_tplset = 'default') AND (";

		foreach ($block_arr as $block) {
			$tplName = ($tplName = $block->getVar("template")) ? "$tplName" : "system_block_dummy.html";
			$tplNames[] = "tpl_file = '" . $tplName . "'";
		}
		$sql .= implode(" OR ", $tplNames);
		$sql .= ") ORDER BY tpl_refid";

		$result = $this->db->query($sql);
		if (!$result) return false;
		while ($myrow = $this->db->fetchArray($result)) {
			$tplfile = new icms_view_template_file_Object($this, $myrow);
			$this->_prefetch_cache[] =& $tplfile;
			unset($tplfile);
		}
		return true;
	}

	/**
	 * Return a prefetched block. This function only works if prefetchBlocks was called in advance.
	 * This function is used in the user function smarty_resource_db_tplinfo().
	 *
	 * @param	str		$tplset		template set that's currently in use
	 * @param	str		$tpl_name	name of the template
	 * @return	array				array of templates (just one item)
	 */
	public function getPrefetchedBlock($tplset, $tpl_name) {
		foreach($this->_prefetch_cache as $block) {
			if ($block->getVar("tpl_tplset") == $tplset && $block->getVar("tpl_file") == $tpl_name) {
				return array($block);
			}
		}

		/**
		 * try to get the template from the default template set (we've also prefetched it) if the
		 * template set is different from default
		 */
		if ($tplset != 'default') {
			foreach($this->_prefetch_cache as $block) {
				if ($block->getVar("tpl_tplset") == "default" && $block->getVar("tpl_file") == $tpl_name) {
					return array($block);
				}
			}
		}

		/**
		 * In case nothing was found, the following fallback tries to read the template again.
		 * This is the case for all non-block templates since blocks are prefetched before the
		 * content template(s) are required.
		 * To avoid further queries for the same block, we're adding it to the cache
		 */
		$blocks = $this->find($tplset, null, null, null, $tpl_name, true);
		array_merge($this->_prefetch_cache, $blocks);
                return $blocks;
	}
}