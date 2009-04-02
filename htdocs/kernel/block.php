<?php
/**
 * ImpressCMS Block Persistable Class
 * 
 * @since 		XOOPS
 * @copyright 	The ImpressCMS Project <http://www.impresscms.org>
 * @copyright 	The XOOPS Project <http://www.xoops.org>
 * @license		GNU General Public License (GPL) <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 * @version		$Id$
 * @author		Gustavo Pilla (aka nekro) <nekro@impresscms.org>
 * @author		The XOOPS Project Community <http://www.xoops.org>
 */

defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');

include_once ICMS_ROOT_PATH . '/kernel/icmspersistableseoobject.php';
include_once ICMS_ROOT_PATH . '/class/xoopsformloader.php';

/**
 * ImpressCMS Core Block Object Class
 * 
 * @since ImpressCMS 1.2
 * @author Gustavo Pilla (aka nekro) <nekro@impresscms.org>
 */
class IcmsBlock extends IcmsPersistableObject {
	
	public function __construct(& $handler) {
		
		$this->IcmsPersistableObject($handler);

		$this->quickInitVar('name', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('bid', XOBJ_DTYPE_INT);
		$this->quickInitVar('mid', XOBJ_DTYPE_INT);
		$this->quickInitVar('func_num', XOBJ_DTYPE_INT);
		$this->quickInitVar('options', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('title', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('content', XOBJ_DTYPE_TXTAREA);
		$this->quickInitVar('side', XOBJ_DTYPE_INT);
		$this->quickInitVar('weight', XOBJ_DTYPE_INT);
		$this->quickInitVar('visible', XOBJ_DTYPE_INT);
		$this->quickInitVar('block_type', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('c_type', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('isactive', XOBJ_DTYPE_INT);
		$this->quickInitVar('dirname', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('func_file', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('show_func', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('edit_func', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('template', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('bcachetime', XOBJ_DTYPE_INT);
		$this->quickInitVar('last_modified', XOBJ_DTYPE_INT);
		
	}
	
	// The next Methods are for backward Compatibility
	
	public function getContent($format = 'S', $c_type = 'T'){
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
	public function getOptions() {
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
	
	/**
	 * For backward compatibility
	 * 
	 * @deprecated 
	 * @return unknown
	 */
	public function isCustom(){
        if ( $this->getVar("block_type") == "C" || $this->getVar("block_type") == "E" ) {
            return true;
        }
        return false;
    }
	
	function buildBlock(){
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
				icms_loadLanguageFile($this->getVar('dirname'), 'blocks');
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
    
}

/**
 * ImpressCMS Core Block Object Handler Class
 * 
 * @copyright The ImpressCMS Project <http://www.impresscms.org>
 * @license GNU GPL v2
 * 
 * @since ImpressCMS 1.2
 * @author Gustavo Pilla (aka nekro) <nekro@impresscms.org>
 */
class IcmsBlockHandler extends IcmsPersistableObjectHandler {
	
	private $block_positions;
	private $modules_name;
	
	public function __construct(& $db) {
		$this->IcmsPersistableObjectHandler($db, 'block', 'bid', 'title', 'content', 'icms');
		$this->table = $this->db->prefix('newblocks');
	}

	// The next methods are for backwards compatibility
	
	/**
	 * getBlockPositions
	 *
	 * @param bool $full
	 * @return array
	 */
	public function getBlockPositions($full=false){
		if( !count($this->block_positions ) ){
			// TODO: Implement IPF for block_positions 
	    	$sql = 'SELECT * FROM '.$this->db->prefix('block_positions').' ORDER BY id ASC';
	    	$result = $this->db->query($sql);
	    	while ($row = $this->db->fetchArray($result)) {	    		
    			$this->block_positions[$row['id']]['pname'] = $row['pname'];
    			$this->block_positions[$row['id']]['title'] = $row['title'];
    			$this->block_positions[$row['id']]['description'] = $row['description'];
    			$this->block_positions[$row['id']]['block_default'] = $row['block_default'];
    			$this->block_positions[$row['id']]['block_type'] = $row['block_type'];
	    	}
    	}
    	if (!$full)
    		foreach($this->block_positions as $k => $block_position)
	    		$rtn[ $k ] = $block_position['pname'];
	    else 
	    	$rtn = $this->block_positions;
    	return $rtn;
    }
    
    
   /**
    * getByModule
    *
    * @param unknown_type $mid
    * @param boolean $asObject
    * @return array
    * 
    * @deprecated 
    * @see $this->getObjects($criteria, false, $asObject);
    * @todo Rewrite all the core to dont use any more this method.
    */ 
	public function getByModule($mid, $asObject = true){
    	$mid = intval($mid);
    	$criteria = new CriteriaCompo();
  		$criteria->add(new Criteria('mid', $mid));
		$ret = $this->getObjects($criteria, false, $asObject);
        return $ret;
    }
    
    /**
     * getAllBlocks
     *
     * @param string $rettype
     * @param string $side
     * @param bool $visible
     * @param string $orderby
     * @param bool $isactive
     * @return array
     * 
     * @deprecated
     * 
     * @todo Implement IPF for block_positions.
     * @todo Rewrite all the core to dont use any more this method.
     */
	public function getAllBlocks($rettype="object", $side=null, $visible=null, $orderby="side,weight,bid", $isactive=1)
    {
        $ret = array();
        $where_query = " WHERE isactive='".intval($isactive)."'";

        if ( isset($side) ) {
            // get both sides in sidebox? (some themes need this)
            $tp = ($side == -2)?'L':($side == -6)?'C':'';
            if ( $tp != '') {
              $side = "";
            	$s1 = "SELECT id FROM ".$this->db->prefix('block_positions')." WHERE block_type='".$tp."' ORDER BY id ASC";
            	$res = $db->query($s1);
            	while ( $myrow = $this->db->fetchArray($res) ) {
                $side .= "side='".intval($myrow['id'])."' OR ";
            	}
            	$side = "('".substr($side,0,strlen($side)-4)."')";
            } else {
                $side = "side='".intval($side)."'";
            }
            $where_query .= " AND ".$side;
        }

        if ( isset($visible) ) {
            $where_query .= " AND visible='".intval($visible)."'";
        }
        $where_query .= " ORDER BY $orderby";
        switch ($rettype) {
        case "object":
            $sql = "SELECT * FROM ".$this->db->prefix("newblocks")."".$where_query;
            $result = $this->db->query($sql);
            while ( $myrow = $this->db->fetchArray($result) ) {
                $ret[] = $this->get($myrow['bid']);
            }
            break;
        case "list":
            $sql = "SELECT * FROM ".$this->db->prefix("newblocks")."".$where_query;
            $result = $this->db->query($sql);
            while ( $myrow = $this->db->fetchArray($result) ) {
                $block = $this->get($myrow['bid']);
                $name = $block->getVar("title");
                $ret[$block->getVar("bid")] = $name;
            }
            break;
        case "id":
            $sql = "SELECT bid FROM ".$this->db->prefix("newblocks")."".$where_query;
            $result = $this->db->query($sql);
            while ( $myrow = $this->db->fetchArray($result) ) {
                $ret[] = $myrow['bid'];
            }
            break;
        }
        //echo $sql;
        return $ret;
    }
    
    /**
     * getAllByGroupModule
     *
     * @param unknown_type $groupid
     * @param unknown_type $module_id
     * @param unknown_type $toponlyblock
     * @param unknown_type $visible
     * @param unknown_type $orderby
     * @param unknown_type $isactive
     * @return unknown
     * 
     * @deprecated 
     */
	function getAllByGroupModule($groupid, $module_id='0-0', $toponlyblock=false, $visible=null, $orderby='b.weight,b.bid', $isactive=1) {
    	// TODO: use $this->getObjects($criteria);
		
		$isactive = intval($isactive);
        $ret = array();
        $sql = "SELECT DISTINCT gperm_itemid FROM ".$this->db->prefix('group_permission')." WHERE gperm_name = 'block_read' AND gperm_modid = '1'";
        if ( is_array($groupid) ) {
            $gid = array_map(create_function('$a', '$r = "\'" . intval($a) . "\'"; return($r);'), $groupid);
            $sql .= " AND gperm_groupid IN (".implode(',', $gid).")";
        } else {
            if (intval($groupid) > 0) {
                $sql .= " AND gperm_groupid='".intval($groupid)."'";
            }
        }
        $result = $this->db->query($sql);
        $blockids = array();
        while ( $myrow = $this->db->fetchArray($result) ) {
            $blockids[] = $myrow['gperm_itemid'];
        }

        if (!empty($blockids)) {
            $sql = "SELECT b.* FROM ".$this->db->prefix('newblocks')." b, ".$this->db->prefix('block_module_link')." m WHERE m.block_id=b.bid";
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
            $result = $this->db->query($sql);
            while ( $myrow = $this->db->fetchArray($result) ) {
                $block =& $this->get($myrow['bid']);
                $ret[$myrow['bid']] =& $block;
                unset($block);
            }
        }
        return $ret;

    }

    /**
     * getNonGroupedBlocks
     *
     * @param unknown_type $module_id
     * @param unknown_type $toponlyblock
     * @param unknown_type $visible
     * @param unknown_type $orderby
     * @param unknown_type $isactive
     * @return unknown
     * 
     * @deprecated 
     */
    function getNonGroupedBlocks($module_id=0, $toponlyblock=false, $visible=null, $orderby='b.weight,b.bid', $isactive=1) {
        $ret = array();
        $bids = array();
        $sql = "SELECT DISTINCT(bid) from ".$this->db->prefix('newblocks');
        if ($result = $this->db->query($sql)) {
            while ( $myrow = $this->db->fetchArray($result) ) {
                $bids[] = $myrow['bid'];
            }
        }
        $sql = "SELECT DISTINCT(p.gperm_itemid) from ".$this->db->prefix('group_permission')." p, ".$this->db->prefix('groups')." g WHERE g.groupid=p.gperm_groupid AND p.gperm_name='block_read'";
        $grouped = array();
        if ($result = $this->db->query($sql)) {
            while ( $myrow = $this->db->fetchArray($result) ) {
                $grouped[] = $myrow['gperm_itemid'];
            }
        }
        $non_grouped = array_diff($bids, $grouped);
        if (!empty($non_grouped)) {
            $sql = "SELECT b.* FROM ".$this->db->prefix('newblocks')." b, ".$this->db->prefix('block_module_link')." m WHERE m.block_id=b.bid";
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
            $result = $this->db->query($sql);
            while ( $myrow = $this->db->fetchArray($result) ) {
                $block =& $this->get($myrow['bid']);
                $ret[$myrow['bid']] =& $block;
                unset($block);
            }
        }
        return $ret;
    }
    
    /**
     * Save a IcmsBlock Object
     *
     * Overwrited Method
     * 
     * @param unknown_type $obj
     * @param unknown_type $force
     * @param unknown_type $checkObject
     * @param unknown_type $debug
     * @return unknown
     */
	public function insert(& $obj, $force = false, $checkObject = true, $debug=false){
		$new = $obj->isNew();
		$obj->setVar('last_modified', time());
		$obj->setVar('isactive', true);
		if(!$new){
			$sql = sprintf("DELETE FROM %s WHERE block_id = '%u'", $this->db->prefix('block_module_link'), intval($obj->getVar('bid')));
            $this->db->query($sql);
		}else{
			// TODO: Set diferents name depending the block c_type
            $obj->setVar('name', 'Custom Block');
		}
		$status = parent::insert( $obj, $force, $checkObject, $debug );
		// TODO: Make something to no query here... implement IPF for block_module_link
        foreach ($obj->getVar('visiblein', 'e') as $bmid) {
            $page = explode('-', $bmid);
            $mid = $page[0];
            $pageid = $page[1];
            $sql = "INSERT INTO ".$this->db->prefix('block_module_link')." (block_id, module_id, page_id) VALUES ('".intval($obj->getVar("bid"))."', '".intval($mid)."', '".intval($pageid)."')";
            $this->db->query($sql);
        }
        if($new){
	        $groups = array(XOOPS_GROUP_ADMIN, XOOPS_GROUP_USERS, XOOPS_GROUP_ANONYMOUS);
	        $count = count($groups);
	        for ($i = 0; $i < $count; $i++) {
	            $sql = "INSERT INTO ".$this->db->prefix('group_permission')." (gperm_groupid, gperm_itemid, gperm_name, gperm_modid) VALUES ('".$groups[$i]."', '".intval($obj->getVar('bid'))."', 'block_read', '1')";
	            $this->db->query($sql);
	        }
        }
        return $status; 
        
	}
	
	public function &get($id, $as_object = true, $debug=false, $criteria=false) {
		$obj = parent::get($id, $as_object, $debug, $criteria);
		$sql = "SELECT module_id,page_id FROM ".$this->db->prefix('block_module_link')." WHERE block_id='".intval($obj->getVar('bid'))."'";
        $result = $this->db->query($sql);
        $modules = $bcustomp = array();
        while ($row = $this->db->fetchArray($result)) {
            $modules[] = intval($row['module_id']).'-'.intval($row['page_id']);
        }
        $obj->setVar('visiblein', $modules);
        return $obj;
	}
	
	
    
}

/**
 * XoopsBlock
 * 
 * @since XOOPS
 * @copyright The XOOPS Project <http://www.xoops.org>
 * @author The XOOPS Project Community <http://www.xoops.org>
 * 
 * @see IcmsBlock
 * 
 * @deprecated 
 */
class XoopsBlock extends IcmsBlock { /* For backwards compatibility */ }

/**
 * XoopsBlockHandler
 * 
 * @since XOOPS
 * @copyright The XOOPS Project <http://www.xoops.org>
 * @author The XOOPS Project Community <http://www.xoops.org>
 * 
 * @see IcmsBlockHandler
 * 
 * @deprecated 
 */
class XoopsBlockHandler extends IcmsBlockHandler { /* For backwards compatibility */ }
?>