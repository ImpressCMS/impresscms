<?php
// $Id$
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

if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}

/**
 * @author  Kazumi Ono <onokazu@xoops.org>
 * @copyright copyright (c) 2000 XOOPS.org
 **/

/**
 * A block
 *
 * @author Kazumi Ono <onokazu@xoops.org>
 * @copyright copyright (c) 2000 XOOPS.org
 *
 * @package kernel
 **/
class XoopsBlock extends XoopsObject
{

	/**
	 * constructor
	 * @param mixed $id
	 **/
	function XoopsBlock($id = null)
	{
		$this->initVar('bid', XOBJ_DTYPE_INT, null, false);
		$this->initVar('mid', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('func_num', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('options', XOBJ_DTYPE_TXTBOX, null, false, 255);
		$this->initVar('name', XOBJ_DTYPE_TXTBOX, null, true, 150);
		//$this->initVar('position', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('title', XOBJ_DTYPE_TXTBOX, null, false, 150);
		$this->initVar('content', XOBJ_DTYPE_TXTAREA, null, false);
		$this->initVar('side', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('weight', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('visible', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('block_type', XOBJ_DTYPE_OTHER, null, false);
		$this->initVar('c_type', XOBJ_DTYPE_OTHER, null, false);
		$this->initVar('isactive', XOBJ_DTYPE_INT, null, false);
		$this->initVar('dirname', XOBJ_DTYPE_TXTBOX, null, false, 50);
		$this->initVar('func_file', XOBJ_DTYPE_TXTBOX, null, false, 50);
		$this->initVar('show_func', XOBJ_DTYPE_TXTBOX, null, false, 50);
		$this->initVar('edit_func', XOBJ_DTYPE_TXTBOX, null, false, 50);
		$this->initVar('template', XOBJ_DTYPE_OTHER, null, false);
		$this->initVar('bcachetime', XOBJ_DTYPE_INT, 0, false);
		$this->initVar('last_modified', XOBJ_DTYPE_INT, 0, false);

		// for backward compatibility
		if (isset($id)) {
			if (is_array($id)) {
				$this->assignVars($id);
			} else {
				$blkhandler =& xoops_gethandler('block');
				$obj =& $blkhandler->get($id);
				foreach (array_keys($obj->getVars() ) as $i) {
					$this->assignVar($obj->getVar($i, 'n') );
				}
			}
		}
	}

	/**
	 * return the content of the block for output
	 * @param string $format
	 * @param string $c_type type of content
	 * Legal value for the type of content
	 * <ul>
	 * <li>H : custom HTML block
	 * <li>P : custom PHP block
	 * <li>S : use text TextSanitizer (smilies enabled)
	 * <li>T : use text TextSanitizer (smilies disabled)
	 * </ul>
	 * @return string content for output
	 **/
	function getContent($format = 'S', $c_type = 'T')
	{
		switch ( $format ) {
			case 'S':
				if ( $c_type == 'H' ) {
					return str_replace('{X_SITEURL}', XOOPS_URL.'/', $this->getVar('content', 'N'));
				} elseif ( $c_type == 'P' ) {
					ob_start();
					echo eval($this->getVar('content', 'N'));
					$content = ob_get_contents();
					ob_end_clean();
					return str_replace('{X_SITEURL}', XOOPS_URL.'/', $content);
				} elseif ( $c_type == 'S' ) {
					$myts =& MyTextSanitizer::getInstance();
					$content = str_replace('{X_SITEURL}', XOOPS_URL.'/', $this->getVar('content', 'N'));
					return $myts->displayTarea($content, 0, 1);
				} else {
					$myts =& MyTextSanitizer::getInstance();
					$content = str_replace('{X_SITEURL}', XOOPS_URL.'/', $this->getVar('content', 'N'));
					return $myts->displayTarea($content, 0, 0);
				}
				break;
			case 'E':
				return $this->getVar('content', 'E');
				break;
			default:
				return $this->getVar('content', 'N');
				break;
		}
	}

	/**
	 * (HTML-) form for setting the options of the block
	 *
	 * @return string|false $edit_form is HTML for the form, FALSE if no options defined for this block
	 **/
	function getOptions()
	{
		if ($this->getVar('block_type') != 'C') {
			$edit_func = $this->getVar('edit_func');
			if (!$edit_func) {
				return false;
			}
			icms_loadLanguageFile($this->getVar('dirname'), 'blocks');
			include_once XOOPS_ROOT_PATH.'/modules/'.$this->getVar('dirname').'/blocks/'.$this->getVar('func_file');
			$options = explode('|', $this->getVar('options'));
			$edit_form = $edit_func($options);
			if (!$edit_form) {
				return false;
			}
			return $edit_form;
		} else {
			return false;
		}
	}
}

/**
 * XOOPS block handler class. (Singelton)
 *
 * This class is responsible for providing data access mechanisms to the data source
 * of XOOPS block class objects.
 *
 * @author  Kazumi Ono <onokazu@xoops.org>
 * @copyright copyright (c) 2000 XOOPS.org
 * @package kernel
 * @subpackage block
 */
class XoopsBlockHandler extends XoopsObjectHandler
{

	/**
	 * create a new block
	 *
	 * @see XoopsBlock
	 * @param bool $isNew is the new block new??
	 * @return object {@link XoopsBlock} reference to the new block
	 **/
	function &create($isNew = true)
	{
		$block = new XoopsBlock();
		if ($isNew) {
			$block->setNew();
		}
		return $block;
	}

	/**
	 * retrieve a specific {@link XoopsBlock}
	 *
	 * @see XoopsBlock
	 * @param integer $id BlockID (bid) of the block to retrieve
	 * @return object {@link XoopsBlock} reference to the block
	 **/
	function &get($id)
	{
		$block = false;
		$id = intval($id);
		if ($id > 0) {
			$sql = "SELECT * FROM ".$this->db->prefix('newblocks')." WHERE bid='".$id."'";
			if ( $result = $this->db->query($sql) ) {
				$numrows = $this->db->getRowsNum($result);
				if ($numrows == 1) {
					$block = new XoopsBlock();
					$block->assignVars($this->db->fetchArray($result));
				}
			}
		}
		return $block;
	}

	/**
	 * Insert a new block into the database
	 *
	 * @param object {@link XoopsBlock} $block reference to the block to insert
	 * @return bool TRUE if succesful
	 **/
	function insert(&$block)
	{
		/**
		 * @TODO: Change to if (!(class_exists($this->className) && $obj instanceof $this->className)) when going fully PHP5
		 */
		if (!is_a($block, 'xoopsblock')) {
			return false;
		}
		if (!$block->isDirty()) {
			return true;
		}
		if (!$block->cleanVars()) {
			return false;
		}
		foreach ($block->cleanVars as $k => $v) {
			${$k} = $v;
		}
		if ($block->isNew()) {
			$bid = $this->db->genId('newblocks_bid_seq');
			$sql = sprintf("INSERT INTO %s (bid, mid, func_num, options, name, title, content, side, weight, visible, block_type, c_type, isactive, dirname, func_file, show_func, edit_func, template, bcachetime, last_modified) VALUES ('%u', '%u', '%u', '%s', '%s', '%s', '%s', '%u', '%u', '%u', '%s', '%s', '%u', '%s', '%s', '%s', '%s', '%s', '%u', '%u')", $this->db->prefix('newblocks'), intval($bid), intval($mid), intval($func_num), $options, $name, $title, $content, intval($side), intval($weight), intval($visible), $block_type, $c_type, 1, $dirname, $func_file, $show_func, $edit_func, $template, intval($bcachetime), time());
		} else {
			$sql = sprintf("UPDATE %s SET func_num = '%u', options = '%s', name = '%s', title = '%s', content = '%s', side = '%u', weight = '%u', visible = '%u', c_type = '%s', isactive = '%u', func_file = '%s', show_func = '%s', edit_func = '%s', template = '%s', bcachetime = '%u', last_modified = '%u' WHERE bid = '%u'", $this->db->prefix('newblocks'), intval($func_num), $options, $name, $title, $content, intval($side), intval($weight), intval($visible), $c_type, intval($isactive), $func_file, $show_func, $edit_func, $template, intval($bcachetime), time(), intval($bid));
		}
		if (!$result = $this->db->query($sql)) {
			return false;
		}
		if (empty($bid)) {
			$bid = $this->db->getInsertId();
		}
		$block->assignVar('bid', $bid);
		return true;
	}

	/**
	 * delete a block from the database
	 *
	 * @param object {@link XoopsBlock} $block reference to the block to delete
	 * @return bool TRUE if succesful
	 **/
	function delete(&$block)
	{
		/**
		 * @TODO: Change to if (!(class_exists($this->className) && $obj instanceof $this->className)) when going fully PHP5
		 */
		if (!is_a($block, 'xoopsblock')) {
			return false;
		}
		$id = intval($block->getVar('bid'));
		$sql = sprintf("DELETE FROM %s WHERE bid = '%u'", $this->db->prefix('newblocks'), $id);
		if (!$result = $this->db->query($sql)) {
			return false;
		}
		$sql = sprintf("DELETE FROM %s WHERE block_id = '%u'", $this->db->prefix('block_module_link'), $id);
		$this->db->query($sql);
		return true;
	}

	/**
	 * retrieve array of {@link XoopsBlock}s meeting certain conditions
	 * @param object $criteria {@link CriteriaElement} with conditions for the blocks
	 * @param bool $id_as_key should the blocks' bid be the key for the returned array?
	 * @return array {@link XoopsBlock}s matching the conditions
	 **/
	function getObjects($criteria = null, $id_as_key = false)
	{
		$ret = array();
		$limit = $start = 0;
		$sql = 'SELECT DISTINCT(b.*) FROM '.$this->db->prefix('newblocks').' b LEFT JOIN '.$this->db->prefix('block_module_link').' l ON b.bid=l.block_id';
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
			$limit = $criteria->getLimit();
			$start = $criteria->getStart();
		}
		$result = $this->db->query($sql, $limit, $start);
		if (!$result) {
			return $ret;
		}
		while ($myrow = $this->db->fetchArray($result)) {
			$block = new XoopsBlock();
			$block->assignVars($myrow);
			if (!$id_as_key) {
				$ret[] =& $block;
			} else {
				$ret[$myrow['bid']] =& $block;
			}
			unset($block);
		}
		return $ret;
	}

	/**
	 * get a list of blocks matching certain conditions
	 *
	 * @param string $criteria conditions to match
	 * @return array array of blocks matching the conditions
	 **/
	function getList($criteria = null)
	{
		$blocks =& $this->getObjects($criteria, true);
		$ret = array();
		foreach (array_keys($blocks) as $i) {
			$name = ($blocks[$i]->getVar('block_type') != 'C') ? $blocks[$i]->getVar('name') : $blocks[$i]->getVar('title');
			$ret[$i] = $name;
		}
		return $ret;
	}
}
?>