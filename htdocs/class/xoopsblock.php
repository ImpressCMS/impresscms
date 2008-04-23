<?php
// $Id: xoopsblock.php 1099 2007-10-19 01:08:14Z dugris $
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
require_once XOOPS_ROOT_PATH."/kernel/object.php";

class XoopsBlock extends XoopsObject
{
    var $db;

    function XoopsBlock($id = null)
    {
        $this->db =& Database::getInstance();
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

        if ( !empty($id) ) {
            if ( is_array($id) ) {
                $this->assignVars($id);
            } else {
                $this->load(intval($id));
            }
        }
    }

    function load($id)
    {
    	$id = intval($id);
        $sql = "SELECT * FROM ".$this->db->prefix('newblocks')." WHERE bid = '".$id."'";
        $arr = $this->db->fetchArray($this->db->query($sql));
        $this->assignVars($arr);
    }

    function store()
    {
        if ( !$this->cleanVars() ) {
            return false;
        }
        foreach ( $this->cleanVars as $k=>$v ) {
            ${$k} = $v;
        }
        if ( empty($bid) ) {
            $bid = $this->db->genId($this->db->prefix("newblocks")."_bid_seq");
            $sql = sprintf("INSERT INTO %s (bid, mid, func_num, options, name, title, content, side, weight, visible, block_type, c_type, isactive, dirname, func_file, show_func, edit_func, template, bcachetime, last_modified) VALUES ('%u', '%u', '%u', %s, %s, %s, %s, '%u', '%u', '%u', %s, %s, '%u', %s, %s, %s, %s, %s, '%u', '%u')", $this->db->prefix('newblocks'), intval($bid), intval($mid), intval($func_num), $this->db->quoteString($options), $this->db->quoteString($name), $this->db->quoteString($title), $this->db->quoteString($content), intval($side), intval($weight), intval($visible), $this->db->quoteString($block_type), $this->db->quoteString($c_type), 1, $this->db->quoteString($dirname), $this->db->quoteString($func_file), $this->db->quoteString($show_func), $this->db->quoteString($edit_func), $this->db->quoteString($template), intval($bcachetime), time());
        } else {
            $sql = "UPDATE ".$this->db->prefix("newblocks")." SET options=".$this->db->quoteString($options);
            // a custom block needs its own name
            if ( $block_type == "C" ) {
                $sql .= ", name=".$this->db->quoteString($name);
            }
            $sql .= ", isactive='".intval($isactive)."', title=".$this->db->quoteString($title).", content=".$this->db->quoteString($content).", side='".intval($side)."', weight='".intval($weight)."', visible='".intval($visible)."', c_type=".$this->db->quoteString($c_type).", template=".$this->db->quoteString($template).", bcachetime='".intval($bcachetime)."', last_modified='".time()."' WHERE bid='".intval($bid)."'";
        }
        if ( !$this->db->queryF($sql) ) {
            $this->setErrors("Could not save block data into database");
            return false;
        }
        if ( empty($bid) ) {
            $bid = $this->db->getInsertId();
        }
        return $bid;
    }

    function delete()
    {
        $sql = sprintf("DELETE FROM %s WHERE bid = '%u'", $this->db->prefix('newblocks'), intval($this->getVar('bid')));
        if ( !$this->db->query($sql) ) {
            return false;
        }
        $sql = sprintf("DELETE FROM %s WHERE gperm_name = 'block_read' AND gperm_itemid = '%u' AND gperm_modid = '1'", $this->db->prefix('group_permission'), intval($this->getVar('bid')));
        $this->db->query($sql);
        $sql = sprintf("DELETE FROM %s WHERE block_id = '%u'", $this->db->prefix('block_module_link'), intval($this->getVar('bid')));
        $this->db->query($sql);
        return true;
    }

    /**
    * do stripslashes/htmlspecialchars according to the needed output
    *
    * @param $format      output use: S for Show and E for Edit
    * @param $c_type    type of block content
    * @returns string
    */
    function getContent($format = 'S', $c_type = 'T')
    {
        switch ( $format ) {
        case 'S':
            // check the type of content
            // H : custom HTML block
            // P : custom PHP block
            // S : use text sanitizater (smilies enabled)
            // T : use text sanitizater (smilies disabled)
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
                return $myts->displayTarea($content, 1, 1);
            } else {
                $myts =& MyTextSanitizer::getInstance();
                $content = str_replace('{X_SITEURL}', XOOPS_URL.'/', $this->getVar('content', 'N'));
                return $myts->displayTarea($content, 1, 0);
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

    function buildBlock()
    {
        global $xoopsConfig, $xoopsOption;
        $block = array();
        // M for module block, S for system block C for Custom
        if ( !$this->isCustom() ) {
            // get block display function
            $show_func = $this->getVar('show_func');
            if ( !$show_func ) {
                return false;
            }
            // must get lang files b4 execution of the function
            if ( file_exists(XOOPS_ROOT_PATH."/modules/".$this->getVar('dirname')."/blocks/".$this->getVar('func_file')) ) {
                if ( file_exists(XOOPS_ROOT_PATH."/modules/".$this->getVar('dirname')."/language/".$xoopsConfig['language']."/blocks.php") ) {
                    include_once XOOPS_ROOT_PATH."/modules/".$this->getVar('dirname')."/language/".$xoopsConfig['language']."/blocks.php";
                } elseif ( file_exists(XOOPS_ROOT_PATH."/modules/".$this->getVar('dirname')."/language/english/blocks.php") ) {
                    include_once XOOPS_ROOT_PATH."/modules/".$this->getVar('dirname')."/language/english/blocks.php";
                }
                include_once XOOPS_ROOT_PATH."/modules/".$this->getVar('dirname')."/blocks/".$this->getVar('func_file');
                $options = explode("|", $this->getVar("options"));
                if ( function_exists($show_func) ) {
                    // execute the function
                    $block = $show_func($options);
                    if ( !$block ) {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            // it is a custom block, so just return the contents
            $block['content'] = $this->getContent("S",$this->getVar("c_type"));
            if (empty($block['content'])) {
                return false;
            }
        }
        return $block;
    }

    /*
    * Aligns the content of a block
    * If position is 0, content in DB is positioned
    * before the original content
    * If position is 1, content in DB is positioned
    * after the original content
    */
    function buildContent($position,$content="",$contentdb="")
    {
        if ( $position == 0 ) {
            $ret = $contentdb.$content;
        } elseif ( $position == 1 ) {
            $ret = $content.$contentdb;
        }
        return $ret;
    }

    function buildTitle($originaltitle, $newtitle="")
    {
        if ($newtitle != "") {
            $ret = $newtitle;
        } else {
            $ret = $originaltitle;
        }
        return $ret;
    }

    function isCustom()
    {
        if ( $this->getVar("block_type") == "C" || $this->getVar("block_type") == "E" ) {
            return true;
        }
        return false;
    }

    /**
    * gets html form for editting block options
    *
    */
    function getOptions()
    {
        global $xoopsConfig;
        if ( !$this->isCustom() ) {
            $edit_func = $this->getVar('edit_func');
            if ( !$edit_func ) {
                return false;
            }
            if ( file_exists(XOOPS_ROOT_PATH."/modules/".$this->getVar('dirname')."/blocks/".$this->getVar('func_file')) ) {
                if ( file_exists(XOOPS_ROOT_PATH."/modules/".$this->getVar('dirname')."/language/".$xoopsConfig['language']."/blocks.php") ) {
                    include_once XOOPS_ROOT_PATH."/modules/".$this->getVar('dirname')."/language/".$xoopsConfig['language']."/blocks.php";
                } elseif ( file_exists(XOOPS_ROOT_PATH."/modules/".$this->getVar('dirname')."/language/english/blocks.php") ) {
                    include_once XOOPS_ROOT_PATH."/modules/".$this->getVar('dirname')."/language/english/blocks.php";
                }
                include_once XOOPS_ROOT_PATH.'/modules/'.$this->getVar('dirname').'/blocks/'.$this->getVar('func_file');
                $options = explode("|", $this->getVar("options"));
                $edit_form = $edit_func($options);
                if ( !$edit_form ) {
                    return false;
                }
                return $edit_form;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
    * get all the blocks that match the supplied parameters
    * @param $side   0: sideblock - left
    *        1: sideblock - right
    *        2: sideblock - left and right
    *        3: centerblock - left
    *        4: centerblock - right
    *        5: centerblock - center
    *        6: centerblock - left, right, center
    * @param $groupid   groupid (can be an array)
    * @param $visible   0: not visible 1: visible
    * @param $orderby   order of the blocks
    * @returns array of block objects
    */
    function getAllBlocksByGroup($groupid, $asobject=true, $side=null, $visible=null, $orderby="b.weight,b.bid", $isactive=1)
    {
        $db =& Database::getInstance();
        $ret = array();
        if ( !$asobject ) {
            $sql = "SELECT b.bid ";
        } else {
            $sql = "SELECT b.* ";
        }
        $sql .= "FROM ".$db->prefix("newblocks")." b LEFT JOIN ".$db->prefix("group_permission")." l ON l.gperm_itemid=b.bid WHERE gperm_name = 'block_read' AND gperm_modid = '1'";
        if ( is_array($groupid) ) {
            $sql .= " AND (l.gperm_groupid='".intval($groupid[0])."'";
            $size = count($groupid);
            if ( $size  > 1 ) {
                for ( $i = 1; $i < $size; $i++ ) {
                    $sql .= " OR l.gperm_groupid='".intval($groupid[$i])."'";
                }
            }
            $sql .= ")";
        } else {
            $sql .= " AND l.gperm_groupid='".intval($groupid)."'";
        }
        $sql .= " AND b.isactive='".intval($isactive)."'";
        # Adding dynamic block area/position system - TheRpLima - 2007-10-21
        /*
        if ( isset($side) ) {
            // get both sides in sidebox? (some themes need this)
            if ( $side == XOOPS_SIDEBLOCK_BOTH ) {
                $side = "(b.side=0 OR b.side=1)";
            } elseif ( $side == XOOPS_CENTERBLOCK_ALL ) {
                $side = "(b.side=3 OR b.side=4 OR b.side=5 OR b.side=7 OR b.side=8 OR b.side=9 )";
            } else {
                $side = "b.side=".$side;
            }
            $sql .= " AND ".$side;
        }
        */
        if ( isset($side) ) {
            // get both sides in sidebox? (some themes need this)
            $tp = ($side == -2)?'L':($side == -6)?'C':'';
            if ( $tp != '') {
              $side = "";
            	$s1 = "SELECT id FROM ".$db->prefix('block_positions')." WHERE block_type='".$tp."' ORDER BY id ASC";
            	$res = $db->query($s1);
            	while ( $myrow = $db->fetchArray($res) ) {
                $side .= "side='".intval($myrow['id'])."' OR ";
            	}
            	$side = "('".substr($side,0,strlen($side)-4)."')";
            } else {
                $side = "side='".intval($side)."'";
            }
            $where_query .= " AND '".intval($side)."'";
        }
        #

        if ( isset($visible) ) {
            $sql .= " AND b.visible='".intval($visible)."'";
        }
        $sql .= " ORDER BY $orderby";
        $result = $db->query($sql);
        $added = array();
        while ( $myrow = $db->fetchArray($result) ) {
            if ( !in_array($myrow['bid'], $added) ) {
                if (!$asobject) {
                    $ret[] = $myrow['bid'];
                } else {
                    $ret[] = new XoopsBlock($myrow);
                }
                array_push($added, $myrow['bid']);
            }
        }
        //echo $sql;
        return $ret;
    }

    function getAllBlocks($rettype="object", $side=null, $visible=null, $orderby="side,weight,bid", $isactive=1)
    {
        $db =& Database::getInstance();
        $ret = array();
        $where_query = " WHERE isactive='".intval($isactive)."'";

        # Adding dynamic block area/position system - TheRpLima - 2007-10-21
        /*
        if ( isset($side) ) {
            // get both sides in sidebox? (some themes need this)
            if ( $side == 2 ) {
                $side = "(side=0 OR side=1)";
            } elseif ( $side == 6 ) {
                $side = "(side=3 OR side=4 OR side=5 OR side=7 OR side=8 OR side=9)";
            } else {
                $side = "side=".$side;
            }
            $where_query .= " AND ".$side;
        }
        */
        if ( isset($side) ) {
            // get both sides in sidebox? (some themes need this)
            $tp = ($side == -2)?'L':($side == -6)?'C':'';
            if ( $tp != '') {
              $side = "";
            	$s1 = "SELECT id FROM ".$db->prefix('block_positions')." WHERE block_type='".$tp."' ORDER BY id ASC";
            	$res = $db->query($s1);
            	while ( $myrow = $db->fetchArray($res) ) {
                $side .= "side='".intval($myrow['id'])."' OR ";
            	}
            	$side = "('".substr($side,0,strlen($side)-4)."')";
            } else {
                $side = "side='".intval($side)."'";
            }
            $where_query .= " AND ".$side;
        }
        #

        if ( isset($visible) ) {
            $where_query .= " AND visible='".intval($visible)."'";
        }
        $where_query .= " ORDER BY $orderby";
        switch ($rettype) {
        case "object":
            $sql = "SELECT * FROM ".$db->prefix("newblocks")."".$where_query;
            $result = $db->query($sql);
            while ( $myrow = $db->fetchArray($result) ) {
                $ret[] = new XoopsBlock($myrow);
            }
            break;
        case "list":
            $sql = "SELECT * FROM ".$db->prefix("newblocks")."".$where_query;
            $result = $db->query($sql);
            while ( $myrow = $db->fetchArray($result) ) {
                $block = new XoopsBlock($myrow);
                $name = $block->getVar("title");
                $ret[$block->getVar("bid")] = $name;
            }
            break;
        case "id":
            $sql = "SELECT bid FROM ".$db->prefix("newblocks")."".$where_query;
            $result = $db->query($sql);
            while ( $myrow = $db->fetchArray($result) ) {
                $ret[] = $myrow['bid'];
            }
            break;
        }
        //echo $sql;
        return $ret;
    }

    function getByModule($moduleid, $asobject=true)
    {
    	$moduleid = intval($moduleid);
        $db =& Database::getInstance();
        if ( $asobject == true ) {
            $sql = "SELECT * FROM ".$db->prefix('newblocks')." WHERE mid='".$moduleid."'";
        } else {
            $sql = "SELECT bid FROM ".$db->prefix('newblocks')." WHERE mid='".$moduleid."'";
        }
        $result = $db->query($sql);
        $ret = array();
        while( $myrow = $db->fetchArray($result) ) {
            if ( $asobject ) {
                $ret[] = new XoopsBlock($myrow);
            } else {
                $ret[] = $myrow['bid'];
            }
        }
        return $ret;
    }

    function getAllByGroupModule($groupid, $module_id='0-0', $toponlyblock=false, $visible=null, $orderby='b.weight,b.bid', $isactive=1)
    { 	
    	$isactive = intval($isactive);
        $db =& Database::getInstance();
        $ret = array();
        $sql = "SELECT DISTINCT gperm_itemid FROM ".$db->prefix('group_permission')." WHERE gperm_name = 'block_read' AND gperm_modid = '1'";
        if ( is_array($groupid) ) {
            $gid = array_map(create_function('$a', '$r = "\'" . intval($a) . "\'"; return($r);'), $groupid);
            $sql .= " AND gperm_groupid IN (".implode(',', $gid).")";
        } else {
            if (intval($groupid) > 0) {
                $sql .= " AND gperm_groupid='".intval($groupid)."'";
            }
        }
        $result = $db->query($sql);
        $blockids = array();
        while ( $myrow = $db->fetchArray($result) ) {
            $blockids[] = $myrow['gperm_itemid'];
        }
          
        if (!empty($blockids)) {
            $sql = "SELECT b.* FROM ".$db->prefix('newblocks')." b, ".$db->prefix('block_module_link')." m WHERE m.block_id=b.bid";
            $sql .= " AND b.isactive='".$isactive."'";
            if (isset($visible)) {
                $sql .= " AND b.visible='".intval($visible)."'";
            }
            
            $arr = explode('-',$module_id);
            $module_id = intval($arr[0]);
            $page_id = intval($arr[1]);            
            if ($module_id == 0){ //Entire Site
            	if ($page_id == 0){ //All pages
            		$sql .= " AND m.module_id='0' AND m.page_id=0";
            	}elseif ($page_id == 1){ //Top Page
            		$sql .= " AND ((m.module_id='0' AND m.page_id=0) OR (m.module_id='0' AND m.page_id=1))";
            	}
            }else{ //Specific Module (including system)
            	if ($page_id == 0){ //All pages of this module
            		$sql .= " AND ((m.module_id='0' AND m.page_id=0) OR (m.module_id='$module_id' AND m.page_id=0))";
            	}else{ //Specific Page of this module
            		$sql .= " AND ((m.module_id='0' AND m.page_id=0) OR (m.module_id='$module_id' AND m.page_id=0) OR (m.module_id='$module_id' AND m.page_id=$page_id))";
            	}
            }
            
            $sql .= " AND b.bid IN (".implode(',', $blockids).")";
            $sql .= " ORDER BY ".$orderby;
            $result = $db->query($sql);
            while ( $myrow = $db->fetchArray($result) ) {
                $block =& new XoopsBlock($myrow);
                $ret[$myrow['bid']] =& $block;
                unset($block);
            }
        }
        return $ret;
    }

    function getNonGroupedBlocks($module_id=0, $toponlyblock=false, $visible=null, $orderby='b.weight,b.bid', $isactive=1)
    {
        $db =& Database::getInstance();
        $ret = array();
        $bids = array();
        $sql = "SELECT DISTINCT(bid) from ".$db->prefix('newblocks');
        if ($result = $db->query($sql)) {
            while ( $myrow = $db->fetchArray($result) ) {
                $bids[] = $myrow['bid'];
            }
        }
        $sql = "SELECT DISTINCT(p.gperm_itemid) from ".$db->prefix('group_permission')." p, ".$db->prefix('groups')." g WHERE g.groupid=p.gperm_groupid AND p.gperm_name='block_read'";
        $grouped = array();
        if ($result = $db->query($sql)) {
            while ( $myrow = $db->fetchArray($result) ) {
                $grouped[] = $myrow['gperm_itemid'];
            }
        }
        $non_grouped = array_diff($bids, $grouped);
        if (!empty($non_grouped)) {
            $sql = "SELECT b.* FROM ".$db->prefix('newblocks')." b, ".$db->prefix('block_module_link')." m WHERE m.block_id=b.bid";
            $sql .= " AND b.isactive='".intval($isactive)."'";
            if (isset($visible)) {
                $sql .= " AND b.visible='".intval($visible)."'";
            }
            $module_id = intval($module_id);
            if (!empty($module_id)) {
                $sql .= " AND m.module_id IN ('0','".intval($module_id)."'";
                if ($toponlyblock) {
                    $sql .= ",'-1'";
                }
                $sql .= ")";
            } else {
                if ($toponlyblock) {
                    $sql .= " AND m.module_id IN ('0','-1')";
                } else {
                    $sql .= " AND m.module_id='0'";
                }
            }
            $sql .= " AND b.bid IN (".implode(',', $non_grouped).")";
            $sql .= " ORDER BY ".$orderby;
            $result = $db->query($sql);
            while ( $myrow = $db->fetchArray($result) ) {
                $block =& new XoopsBlock($myrow);
                $ret[$myrow['bid']] =& $block;
                unset($block);
            }
        }
        return $ret;
    }

    function countSimilarBlocks($moduleId, $funcNum, $showFunc = null)
    {
        $funcNum = intval($funcNum);
        $moduleId = intval($moduleId);
        if ($funcNum < 1 || $moduleId < 1) {
            // invalid query
            return 0;
        }
        $db =& Database::getInstance();
        if (isset($showFunc)) {
            // showFunc is set for more strict comparison
            $sql = sprintf("SELECT COUNT(*) FROM %s WHERE mid = '%d' AND func_num = '%d' AND show_func = %s", $db->prefix('newblocks'), $moduleId, $funcNum, $db->quoteString(trim($showFunc)));
        } else {
            $sql = sprintf("SELECT COUNT(*) FROM %s WHERE mid = '%d' AND func_num = '%d'", $db->prefix('newblocks'), $moduleId, $funcNum);
        }
        if (!$result = $db->query($sql)) {
            return 0;
        }
        list($count) = $db->fetchRow($result);
        return $count;
    }

    # Adding dynamic block area/position system - TheRpLima - 2007-10-21
    function getBlockPositions($full=false){
    	$db =& Database::getInstance();

    	$ret = array();

    	$sql = 'SELECT * FROM '.$db->prefix('block_positions').' ORDER BY id ASC';
    	$result = $db->query($sql);
    	while ($row = $db->fetchArray($result)) {
    		if (!$full){
    		  $ret[$row['id']] = $row['pname'];
    		}else{
    			$ret[$row['id']]['pname'] = $row['pname'];
    			$ret[$row['id']]['title'] = $row['title'];
    			$ret[$row['id']]['description'] = $row['description'];
    			$ret[$row['id']]['block_default'] = $row['block_default'];
    			$ret[$row['id']]['block_type'] = $row['block_type'];
    		}
    	}

    	return $ret;
    }
    #
}
?>