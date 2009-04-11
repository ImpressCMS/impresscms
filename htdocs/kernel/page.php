<?php
// $Id$
//  ------------------------------------------------------------------------ //
//                ImpressCMS - PHP Content Management System                 //
//                    Copyright (c) 2000 ImpressCMS.org                      //
//                       <http://www.impresscms.org/>                        //
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
// Author: Rodrigo Pereira Lima (AKA TheRplima)                              //
// URL: http://www.impresscms.org/                                           //
// Project: The ImpressCMS Project                                           //
// ------------------------------------------------------------------------- //

defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');

include_once ICMS_ROOT_PATH . '/kernel/icmspersistableseoobject.php';

class IcmsPage extends IcmsPersistableObject {
	
	public function __construct( & $handler ){

		$this->IcmsPersistableObject( $handler );
		
		$this->quickInitVar('page_id', XOBJ_DTYPE_INT);
        $this->quickInitVar('page_moduleid', XOBJ_DTYPE_INT);
        $this->quickInitVar('page_title', XOBJ_DTYPE_TXTBOX);
        $this->quickInitVar('page_url', XOBJ_DTYPE_TXTBOX);
        $this->quickInitVar('page_status', XOBJ_DTYPE_INT);

	}
	
}

class IcmsPageHandler extends IcmsPersistableObjectHandler {
	
	public function __construct( & $db ){
		$this->IcmsPersistableObjectHandler($db, 'page' ,'page_id' ,'page_title', '' , 'icms');
		$this->table = $db->prefix('icmspage');
	}
	
    function getList( $criteria = null ){
    	$rtn = array();
        $pages =& $this->getObjects( $criteria, true );
        foreach( $pages as $page ) {
            $rtn[$page->getVar('page_moduleid').'-'.$page->getVar('page_id')] = $page->getVar('page_title');
        }
        return $rtn;
    }
	
	function getPageSelOptions($value=null){
    	if (!is_array($value)){
    		$value = array($value);
    	}
    	$module_handler =& xoops_gethandler('module');
    	$criteria = new CriteriaCompo(new Criteria('hasmain', 1));
    	$criteria->add(new Criteria('isactive', 1));
    	$module_list =& $module_handler->getObjects($criteria);
    	$mods = '';
    	foreach ($module_list as $module){
    		$mods .= '<optgroup label="'.$module->getVar('name').'">';
    		$criteria = new CriteriaCompo(new Criteria('page_moduleid', $module->getVar('mid')));
    		$criteria->add(new Criteria('page_status', 1));
    		$pages =& $this->getObjects($criteria);
    		$sel = '';
    		if (in_array($module->getVar('mid').'-0',$value)){
    			$sel = ' selected=selected';
    		}
    		$mods .= '<option value="'.$module->getVar('mid').'-0"'.$sel.'>'._AM_ALLPAGES.'</option>';
    		foreach ($pages as $page){
    			$sel = '';
    			if (in_array($module->getVar('mid').'-'.$page->getVar('page_id'),$value)){
    				$sel = ' selected=selected';
    			}
    			$mods .= '<option value="'.$module->getVar('mid').'-'.$page->getVar('page_id').'"'.$sel.'>'.$page->getVar('page_title').'</option>';
    		}
    		$mods .= '</optgroup>';
    	}

    	$module = $module_handler->get(1);
    	$criteria = new CriteriaCompo(new Criteria('page_moduleid', 1));
    	$criteria->add(new Criteria('page_status', 1));
    	$pages =& $this->getObjects($criteria);
    	$cont = '';
    	if (count($pages) > 0){
    		$cont = '<optgroup label="'.$module->getVar('name').'">';
    		$sel = '';
    		if (in_array($module->getVar('mid').'-0',$value)){
    			$sel = ' selected=selected';
    		}
    		$cont .= '<option value="'.$module->getVar('mid').'-0"'.$sel.'>'._AM_ALLPAGES.'</option>';
    		foreach ($pages as $page){
    			$sel = '';
    			if (in_array($module->getVar('mid').'-'.$page->getVar('page_id'),$value)){
    				$sel = ' selected=selected';
    			}
    			$cont .= '<option value="'.$module->getVar('mid').'-'.$page->getVar('page_id').'"'.$sel.'>'.$page->getVar('page_title').'</option>';
    		}
    		$cont .= '</optgroup>';
    	}
    	$sel = $sel1 = '';
    	if (in_array('0-1',$value)){
    		$sel = ' selected=selected';
    	}
    	if (in_array('0-0',$value)){
    		$sel1 = ' selected=selected';
    	}
    	$ret = '<option value="0-1"'.$sel.'>'._AM_TOPPAGE.'</option><option value="0-0"'.$sel1.'>'._AM_ALLPAGES.'</option>';
    	$ret .= $cont.$mods;
    	
        return $ret;
    }
    	
}

class XoopsPage extends IcmsPage {/* For backwards compatibility */}

class XoopsPageHandler extends IcmsPageHandler {/* For backwards compatibility */}

//class XoopsPage extends XoopsObject{
//
//    function XoopsPage(){
//        $this->XoopsObject();
//        $this->initVar('page_id', XOBJ_DTYPE_INT, null, false);
//        $this->initVar('page_moduleid', XOBJ_DTYPE_INT, 0, true);
//        $this->initVar('page_title', XOBJ_DTYPE_TXTBOX, null, true, 255);
//        $this->initVar('page_url', XOBJ_DTYPE_TXTBOX, null, true, 255);
//        $this->initVar('page_status', XOBJ_DTYPE_INT, 1, false);        
//    }
//    
//}


/**
* ICMS page handler class.
* This class is responsible for providing data access mechanisms to the data source
* of ICMS page class objects.
*
*
* @author  TheRplima <therplima@impresscms.org>
*/

//class XoopsPageHandler extends XoopsObjectHandler
//{
//
//    function &create($isNew = true)
//    {
//        $page = new XoopsPage();
//        if ($isNew) {
//            $page->setNew();
//        }
//        return $page;
//    }
//
//    function &get($id)
//    {
//        $page = false;
//    	$id = intval($id);
//        if ($id > 0) {
//            $sql = "SELECT * FROM ".$this->db->prefix('icmspage')." WHERE page_id='".$id."'";
//            if (!$result = $this->db->query($sql)) {
//                return false;
//            }
//            $numrows = $this->db->getRowsNum($result);
//            if ($numrows == 1) {
//                $page = new XoopsPage();
//                $page->assignVars($this->db->fetchArray($result));
//                return $page;
//            }
//        }
//        return $page;
//    }
//
//    function insert(&$page)
//    {
//        /**
//        * @TODO: Change to if (!(class_exists($this->className) && $obj instanceof $this->className)) when going fully PHP5
//        */
//        if (strtolower(get_class($page)) != 'xoopspage') {
//            return false;
//        }
//        if (!$page->isDirty()) {
//            return true;
//        }
//        if (!$page->cleanVars()) {
//            return false;
//        }
//        foreach ($page->cleanVars as $k => $v) {
//            ${$k} = $v;
//        }
//        if ($page->isNew()) {
//            $page_id = $this->db->genId('page_page_id_seq');
//            $sql = sprintf("INSERT INTO %s (page_id, page_moduleid, page_title, page_url, page_status) VALUES (%u, %u, %s, %s, %u)", 
//            $this->db->prefix('icmspage'), 
//            intval($page_id), 
//            intval($page_moduleid), 
//            $this->db->quoteString($page_title),
//            $this->db->quoteString($page_url), 
//            intval($page_status));
//        } else {
//        	$sql = sprintf("UPDATE %s SET page_moduleid=%u, page_title=%s, page_url=%s, page_status=%u WHERE page_id=%u", 
//        	$this->db->prefix('icmspage'), 
//        	intval($page_moduleid), 
//        	$this->db->quoteString($page_title),
//        	$this->db->quoteString($page_url), 
//        	intval($page_status), 
//        	intval($page_id));
//        }
//        if (!$result = $this->db->queryF($sql)) {
//            return false;
//        }
//        if (empty($page_id)) {
//            $page_id = $this->db->getInsertId();
//        }
//        $page->assignVar('page_id', $page_id);
//        return true;
//    }
//
//    function delete(&$page)
//    {
//        /**
//        * @TODO: Change to if (!(class_exists($this->className) && $obj instanceof $this->className)) when going fully PHP5
//        */
//        if (strtolower(get_class($page)) != 'xoopspage') {
//            return false;
//        }
//
//        $id = intval($page->getVar('page_id'));
//        $sql = sprintf("DELETE FROM %s WHERE page_id = '%u'", $this->db->prefix('icmspage'), $id);
//        if (!$result = $this->db->queryF($sql)) {
//            return false;
//        }
//        
//        return true;
//    }
//
//    function &getObjects($criteria = null, $id_as_key = false)
//    {
//        $ret = array();
//        $limit = $start = 0;
//        $sql = "SELECT * FROM ".$this->db->prefix('icmspage');
//        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
//            $sql .= " ".$criteria->renderWhere();
//            $sql .= " ORDER BY ".($criteria->getSort()?$criteria->getSort():'page_moduleid,page_title')." ".($criteria->getOrder()?$criteria->getOrder():'ASC');
//            $limit = $criteria->getLimit();
//            $start = $criteria->getStart();
//        }
//        $result = $this->db->query($sql, $limit, $start);
//        if (!$result) {
//            return $ret;
//        }
//        while ($myrow = $this->db->fetchArray($result)) {
//            $page = new XoopsPage();
//            $page->assignVars($myrow);
//            if (!$id_as_key) {
//                $ret[] =& $page;
//            } else {
//                $ret[$myrow['page_id']] =& $page;
//            }
//            unset($page);
//        }
//        return $ret;
//    }
//
//    function getCount($criteria = null)
//    {
//        $sql = 'SELECT COUNT(*) FROM '.$this->db->prefix('icmspage');
//        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
//            $sql .= ' '.$criteria->renderWhere();
//        }
//        if (!$result =& $this->db->query($sql)) {
//            return 0;
//        }
//        list($count) = $this->db->fetchRow($result);
//        return $count;
//    }
//
//    function getList($criteria = null)
//    {
//    	$ret = array();
//        $pages =& $this->getObjects($criteria, true);
//        foreach (array_keys($pages) as $i) {
//            $ret[$pages[$i]->getVar('page_moduleid').'-'.$pages[$i]->getVar('page_id')] = $pages[$i]->getVar('page_title');
//        }
//        return $ret;
//    }
//    
//    function getPageSelOptions($value=null)
//    {
//    	if (!is_array($value)){
//    		$value = array($value);
//    	}
//    	$module_handler =& xoops_gethandler('module');
//    	$criteria = new CriteriaCompo(new Criteria('hasmain', 1));
//    	$criteria->add(new Criteria('isactive', 1));
//    	$module_list =& $module_handler->getObjects($criteria);
//    	$mods = '';
//    	foreach ($module_list as $module){
//    		$mods .= '<optgroup label="'.$module->getVar('name').'">';
//    		$criteria = new CriteriaCompo(new Criteria('page_moduleid', $module->getVar('mid')));
//    		$criteria->add(new Criteria('page_status', 1));
//    		$pages =& $this->getObjects($criteria);
//    		$sel = '';
//    		if (in_array($module->getVar('mid').'-0',$value)){
//    			$sel = ' selected=selected';
//    		}
//    		$mods .= '<option value="'.$module->getVar('mid').'-0"'.$sel.'>'._AM_ALLPAGES.'</option>';
//    		foreach ($pages as $page){
//    			$sel = '';
//    			if (in_array($module->getVar('mid').'-'.$page->getVar('page_id'),$value)){
//    				$sel = ' selected=selected';
//    			}
//    			$mods .= '<option value="'.$module->getVar('mid').'-'.$page->getVar('page_id').'"'.$sel.'>'.$page->getVar('page_title').'</option>';
//    		}
//    		$mods .= '</optgroup>';
//    	}
//
//    	$module = $module_handler->get(1);
//    	$criteria = new CriteriaCompo(new Criteria('page_moduleid', 1));
//    	$criteria->add(new Criteria('page_status', 1));
//    	$pages =& $this->getObjects($criteria);
//    	$cont = '';
//    	if (count($pages) > 0){
//    		$cont = '<optgroup label="'.$module->getVar('name').'">';
//    		$sel = '';
//    		if (in_array($module->getVar('mid').'-0',$value)){
//    			$sel = ' selected=selected';
//    		}
//    		$cont .= '<option value="'.$module->getVar('mid').'-0"'.$sel.'>'._AM_ALLPAGES.'</option>';
//    		foreach ($pages as $page){
//    			$sel = '';
//    			if (in_array($module->getVar('mid').'-'.$page->getVar('page_id'),$value)){
//    				$sel = ' selected=selected';
//    			}
//    			$cont .= '<option value="'.$module->getVar('mid').'-'.$page->getVar('page_id').'"'.$sel.'>'.$page->getVar('page_title').'</option>';
//    		}
//    		$cont .= '</optgroup>';
//    	}
//    	$sel = $sel1 = '';
//    	if (in_array('0-1',$value)){
//    		$sel = ' selected=selected';
//    	}
//    	if (in_array('0-0',$value)){
//    		$sel1 = ' selected=selected';
//    	}
//    	$ret = '<option value="0-1"'.$sel.'>'._AM_TOPPAGE.'</option><option value="0-0"'.$sel1.'>'._AM_ALLPAGES.'</option>';
//    	$ret .= $cont.$mods;
//    	
//        return $ret;
//    }
//    
//    function changestatus(&$page)
//    {
//        /**
//        * @TODO: Change to if (!(class_exists($this->className) && $obj instanceof $this->className)) when going fully PHP5
//        */
//        if (strtolower(get_class($page)) != 'xoopspage') {
//            return false;
//        }
//
//        $id = intval($page->getVar('page_id'));
//        
//        $sts = !$page->getVar('page_status');
//        
//        $sql = sprintf("UPDATE %s SET page_status='%u' WHERE page_id = '%u'", $this->db->prefix('icmspage'), $sts, $id);
//        if (!$result = $this->db->queryF($sql)) {
//            return false;
//        }
//        
//        return true;
//    }
//}
?>