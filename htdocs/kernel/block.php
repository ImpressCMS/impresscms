<?php
/**
 * ImpressCMS Block Persistable Class
 *
 * @copyright 	The ImpressCMS Project <http://www.impresscms.org>
 * @copyright 	The XOOPS Project <http://www.xoops.org>
 * @license		GNU General Public License (GPL) <http://www.gnu.org/licenses/old-licenses/gpl-2.0.html>
 *
 * @version		$Id$
 * @since 		XOOPS
 *
 * @author		The XOOPS Project Community <http://www.xoops.org>
 * @author		Gustavo Pilla (aka nekro) <nekro@impresscms.org>
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
		$this->quickInitVar('bid', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('mid', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('func_num', XOBJ_DTYPE_INT);
		$this->quickInitVar('title', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('content', XOBJ_DTYPE_TXTAREA);
		$this->quickInitVar('side', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('weight', XOBJ_DTYPE_INT, true, false, false, 0);
		$this->quickInitVar('visible', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('block_type', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('c_type', XOBJ_DTYPE_TXTBOX, true);
		$this->quickInitVar('isactive', XOBJ_DTYPE_INT);
		$this->quickInitVar('dirname', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('func_file', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('show_func', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('edit_func', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('template', XOBJ_DTYPE_TXTBOX);
		$this->quickInitVar('bcachetime', XOBJ_DTYPE_INT);
		$this->quickInitVar('last_modified', XOBJ_DTYPE_INT);
		$this->quickInitVar('options', XOBJ_DTYPE_TXTBOX);
	}

	// The next Methods are for backward Compatibility

	public function getContent($format = 'S', $c_type = 'T'){
		switch ( $format ) {
			case 'S':
				if ( $c_type == 'H' ) {
					return str_replace('{X_SITEURL}', XOOPS_URL.'/', $this->getVar('content', 'n'));
				} elseif ( $c_type == 'P' ) {
					ob_start();
					echo eval($this->getVar('content', 'n'));
					$content = ob_get_contents();
					ob_end_clean();
					return str_replace('{X_SITEURL}', XOOPS_URL.'/', $content);
				} elseif ( $c_type == 'S' ) {
					$myts =& MyTextSanitizer::getInstance();
					$content = str_replace('{X_SITEURL}', XOOPS_URL.'/', $this->getVar('content', 'n'));
					return $myts->displayTarea($content, 1, 1);
				} else {
					$myts =& MyTextSanitizer::getInstance();
					$content = str_replace('{X_SITEURL}', XOOPS_URL.'/', $this->getVar('content', 'n'));
					return $myts->displayTarea($content, 0, 0);
				}
				break;
			case 'E':
				return $this->getVar('content', 'e');
				break;
			default:
				return $this->getVar('content', 'n');
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
	 * @todo improve with IPF
	 * @return unknown
	 */
	public function isCustom(){
		if ( $this->getVar("block_type") == "C" || $this->getVar("block_type") == "E" ) {
			return true;
		}
		return false;
	}

	/**
	 * Builds the block
	 *
	 * @return array $block the block information array
	 *
	 * @todo improve with IPF
	 */
	public function buildBlock(){
		global $icmsConfig, $xoopsOption;
		$block = array();
		// M for module block, S for system block C for Custom
		if ( !$this->isCustom() ) {
			// get block display function
			$show_func = $this->getVar('show_func');
			if ( !$show_func ) {
				return false;
			}
			// Must get lang files before execution of the function.
			if ( file_exists(ICMS_ROOT_PATH."/modules/".$this->getVar('dirname')."/blocks/".$this->getVar('func_file')) ) {
				icms_loadLanguageFile($this->getVar('dirname'), 'blocks');
				include_once ICMS_ROOT_PATH."/modules/".$this->getVar('dirname')."/blocks/".$this->getVar('func_file');
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

	/**
	 * Aligns the content of a block
	 * If position is 0, content in DB is positioned
	 * before the original content
	 * If position is 1, content in DB is positioned
	 * after the original content
	 *
	 * @todo remove this? It is not found anywhere else in the core
	 */
	public function buildContent($position,$content="",$contentdb=""){
		if ( $position == 0 ) {
			$ret = $contentdb.$content;
		} elseif ( $position == 1 ) {
			$ret = $content.$contentdb;
		}
		return $ret;
	}

	/**
	 * Build Block Title
	 *
	 * @param string $originaltitle
	 * @param string $newtitle
	 * @return string
	 *
	 * @todo remove this? it is not found anywhere else in the core
	 */
	public function buildTitle($originaltitle, $newtitle=""){
		if ($newtitle != "") {
			$ret = $newtitle;
		} else {
			$ret = $originaltitle;
		}
		return $ret;
	}

	/**
	 * Get Block Positions
	 *
	 * @param boolean $full
	 * @return array
	 *
	 * @deprecated use the handler method, instead
	 */
	public function getBlockPositions($full=false){
		icms_deprecated('IcmsBlockHandler->getBlockPositions');
		return $this->handler->getBlockPositions($full);
	}

	/**
	 * Load a Block
	 *
	 * @param integer $id
	 *
	 * @deprecated use the handler method, instead
	 */
	public function load($id){
		icms_deprecated('IcmsBlockHandler->getObject');
		$this->$this->handler->getObject($id);
	}

	/**
	 * Save this block
	 *
	 * @return integer
	 *
	 * @deprecated use the handler method 'insert', instead
	 */
	public function store(){
		icms_deprecated('IcmsBlockHandler->insert');
		$this->handler->insert( $this );
		return $this->getVar('bid');
	}

	/**
	 * Delete this block
	 *
	 * @return boolean
	 *
	 * @deprecated use the handler method, instead
	 */
	public function delete(){
		icms_deprecated('IcmsBlockHandler->delete');
		return $this->handler->delete( $this );
	}

	/**
	 * Get all the blocks that match the supplied parameters
	 *
	 * @param $side   0: sideblock - left
	 *		1: sideblock - right
	 *		2: sideblock - left and right
	 *		3: centerblock - left
	 *		4: centerblock - right
	 *		5: centerblock - center
	 *		6: centerblock - left, right, center
	 * @param $groupid   groupid (can be an array)
	 * @param $visible   0: not visible 1: visible
	 * @param $orderby   order of the blocks
	 * @return array of block objects
	 *
	 * @deprecated use the handler method, instead
	 */
	public function getAllBlocksByGroup($groupid, $asobject=true, $side=null, $visible=null, $orderby="b.weight,b.bid", $isactive=1){
		icms_deprecated('IcmsBlockHandler->getAllBlocksByGroup');
		return $this->handler->getAllBlocksByGroup( $groupid, $asobject, $side, $visible, $orderby, $isactive );
	}

	/**
	 * Get All Blocks
	 *
	 * @since XOOPS
	 *
	 * @param unknown_type $rettype
	 * @param unknown_type $side
	 * @param unknown_type $visible
	 * @param unknown_type $orderby
	 * @param unknown_type $isactive
	 * @return unknown
	 *
	 * @deprecated use the handler method, instead
	 */
	public function getAllBlocks( $rettype = "object", $side = null, $visible = null, $orderby = "side,weight,bid", $isactive = 1 ){
		icms_deprecated('IcmsBlockHandler->getAllBlocks');
		return $this->handler->getAllBlocks( $rettype, $side, $visible, $orderby, $isactive );
	}

	/**
	 * Get Block By Module ID (mid)
	 *
	 * @since XOOPS
	 *
	 * @param integer $moduleid
	 * @param boolean $asobject
	 * @return unknown
	 *
	 * @deprecated use the handler method, instead
	 */
	public function getByModule($moduleid, $asobject=true){
		icms_deprecated('IcmsBlockHandler->getByModule');
		return $this->handler->getByModule( $moduleid, $asobject );
	}

	/**
	 * Get All Blocks By Group and Module
	 *
	 * @since XOOPS
	 *
	 * @param integer $groupid
	 * @param integer $module_id
	 * @param boolean $toponlyblock
	 * @param boolean $visible
	 * @param string $orderby
	 * @param booelan $isactive
	 * @return unknown
	 *
	 * @deprecated use the handler method, instead
	 *
	 */
	public function getAllByGroupModule($groupid, $module_id='0-0', $toponlyblock=false, $visible=null, $orderby='b.weight,b.bid', $isactive=1){
		icms_deprecated( 'IcmsBlockHandler->getAllByGroupModule');
		return $this->handler->getAllByGroupModule( $groupid, $module_id, $toponlyblock, $visible, $orderby, $isactive );
	}

	/**
	 * Get Non Grouped Blocks
	 *
	 * @param integer $module_id
	 * @param unknown_type $toponlyblock
	 * @param boolean $visible
	 * @param string $orderby
	 * @param boolean $isactive
	 * @return array
	 *
	 * @deprecated use the handler method, instead
	 */
	public function getNonGroupedBlocks($module_id=0, $toponlyblock=false, $visible=null, $orderby='b.weight,b.bid', $isactive=1){
		icms_deprecated( 'IcmsBlockHandler->getNonGroupedBlocks' );
		return $this->handler->getNonGroupedBlocks( $module_id, $toponlyblock, $visible, $orderby, $isactive );
	}

	/**
	 * Count Similar Blocks
	 *
	 * This method has been implemented in the block handler, because is was thought as usefull.
	 *
	 * @since XOOPS
	 *
	 * @param integer $moduleId
	 * @param integer $funcNum
	 * @param string $showFunc
	 *
	 * @return integer
	 *
	 * @deprecated use the handler method, instead
	 */
	public function countSimilarBlocks($moduleId, $funcNum, $showFunc = null) {
		icms_deprecated( 'IcmsBlockHandler->getCountSimilarBlocks' );
		return $this->handler->getCountSimilarBlocks( $moduleId, $funcNum, $showFunc );
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
			$icms_blockposition_handler = xoops_gethandler('blockposition');
			//			$sql = 'SELECT * FROM '.$this->db->prefix('block_positions').' ORDER BY id ASC';
			//			$result = $this->db->query($sql);
			//			while ($row = $this->db->fetchArray($result)) {
			$block_positions = $icms_blockposition_handler->getObjects();
			foreach( $block_positions as $bp){
				$this->block_positions[$bp->getVar('id')]['pname'] = $bp->getVar('pname');
				$this->block_positions[$bp->getVar('id')]['title'] = $bp->getVar('title');
				$this->block_positions[$bp->getVar('id')]['description'] = $bp->getVar('description');
				$this->block_positions[$bp->getVar('id')]['block_default'] = $bp->getVar('block_default');
				$this->block_positions[$bp->getVar('id')]['block_type'] = $bp->getVar('block_type');
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
	 * @see $this->getObjects($criteria, false, $asObject);
	 * @todo Rewrite all the core to dont use any more this method.
	 */
	public function getByModule($mid, $asObject = true){
		$mid = (int) ($mid);
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
	 * @todo Implement IPF for block_positions.
	 * @todo Rewrite all the core to dont use any more this method.
	 */
	public function getAllBlocks($rettype="object", $side=null, $visible=null, $orderby="side,weight,bid", $isactive=1)
	{
		$ret = array();
		$where_query = " WHERE isactive='". (int) $isactive . "'";

		if ( isset($side) ) {
			// get both sides in sidebox? (some themes need this)
			$tp = ($side == -2)?'L':($side == -6)?'C':'';
			if ( $tp != '') {
			 	$q_side = "";
				$icms_blockposition_handler = xoops_gethandler('blockposition');
				$criteria = new CriteriaCompo();
				$criteria->add( new Criteria('block_type', $tp) );
				$blockpositions = $icms_blockposition_handler->getObjects($criteria);
				foreach( $blockpositions as $bp ){
					$q_side .= "side='". (int) $bp->getVar('id') . "' OR ";
				}
				$q_side = "('".substr($q_side,0,strlen($q_side)-4)."')";
			} else {
				$q_side = "side='". (int) $side . "'";
			}
			$where_query .= " AND ". $q_side;
		}

		if ( isset($visible) ) {
			$where_query .= " AND visible='". (int) ($visible)."'";
		}
		$where_query .= " ORDER BY $orderby";
		switch ($rettype) {
			case "object":
				$sql = "SELECT * FROM ".$this->db->prefix("newblocks")."".$where_query;
				$result = $this->db->query($sql);
				while ( $myrow = $this->db->fetchArray($result) ) {
					// @todo this is causing to many SQL queries. In case this section is still needed,
					// we should switch it just like it's done in the list case
					$ret[] = $this->get($myrow['bid']);
				}
				break;
			case "list":
				$sql = "SELECT * FROM ".$this->db->prefix("newblocks")."".$where_query;
				$result = $this->db->query($sql);
				if ($this->db->getRowsNum($result) > 0) {
					$blockids = array();
					while ( $myrow = $this->db->fetchArray($result) ) {
						$blockids[] = $myrow['bid'];
					}
					$criteria = new CriteriaCompo();
					$criteria->add(new Criteria('bid', '('.implode(',', $blockids).')', 'IN'));
					$blocks = $this->getObjects($criteria, true, true);
					foreach ($blocks as $block) {
						$ret[$block->getVar("bid")] = $block->getVar("title");
					}
					unset($blockids, $blocks);
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
		return $ret;
	}

	/**
	 * getAllByGroupModule gets all blocks visible on a page, based on group permissions
	 *
	 * @param unknown_type $groupid
	 * @param unknown_type $module_id
	 * @param unknown_type $toponlyblock
	 * @param unknown_type $visible
	 * @param unknown_type $orderby
	 * @param unknown_type $isactive
	 * @return unknown
	 *
	 * @todo rewrite
	 */
	function getAllByGroupModule($groupid, $module_id='0-0', $toponlyblock=false, $visible=null, $orderby='b.weight,b.bid', $isactive=1) {
		// TODO: use $this->getObjects($criteria);

		$isactive = (int)$isactive;
		$ret = array();
		$sql = "SELECT DISTINCT gperm_itemid FROM ".$this->db->prefix('group_permission')." WHERE gperm_name = 'block_read' AND gperm_modid = '1'";
		if ( is_array($groupid) ) {
			$gid = array_map(create_function('$a', '$r = "\'" . intval($a) . "\'"; return($r);'), $groupid);
			$sql .= " AND gperm_groupid IN (".implode(',', $gid).")";
		} else {
			if ( (int) $groupid > 0) {
				$sql .= " AND gperm_groupid='" . (int) $groupid . "'";
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
				$sql .= " AND b.visible='". (int) ($visible)."'";
			}

			$arr = explode('-',$module_id);
			$module_id = (int) ($arr[0]);
			$page_id = (int) ($arr[1]);
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

			// old method of gathering block data. Since this could result in a whole bunch of queries, a new method was introduced
			/*while ( $myrow = $this->db->fetchArray($result) ) {
				$block =& $this->get($myrow['bid']);
				$ret[$myrow['bid']] =& $block;
				unset($block);
			}*/

			if ($this->db->getRowsNum($result) > 0) {
				unset($blockids);
				while ($myrow = $this->db->fetchArray($result)) {
					$blockids[] = $myrow['bid'];
				}
				$ret = $this->getMultiple($blockids);
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
	 * @todo remove - this is the only instance in the core
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
			$sql .= " AND b.isactive='". (int) ($isactive)."'";
			if (isset($visible)) {
				$sql .= " AND b.visible='". (int) ($visible)."'";
			}
			$module_id = (int) ($module_id);
			if (!empty($module_id)) {
				$sql .= " AND m.module_id IN ('0','". (int) ($module_id)."'";
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

			// old method of gathering block data. Since this could result in a whole bunch of queries, a new method was introduced
			/*while ( $myrow = $this->db->fetchArray($result) ) {
				$block =& $this->get($myrow['bid']);
				$ret[$myrow['bid']] =& $block;
				unset($block);
			}*/

			if ($this->db->getRowsNum($result) > 0) {
				unset($blockids);
				while ($myrow = $this->db->fetchArray($result)) {
					$blockids[] = $myrow['bid'];
				}
				$ret = $this->getMultiple($blockids);
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
			$sql = sprintf("DELETE FROM %s WHERE block_id = '%u'", $this->db->prefix('block_module_link'), (int) ($obj->getVar('bid')));
			if (false != $force) {
				$this->db->queryF($sql);
			} else {
				$this->db->query($sql);
			}
		}else{
			icms_loadLanguageFile('system', 'blocksadmin', true);
			if ($obj->getVar('block_type') == 'K'){
				$obj->setVar('name', _AM_CLONE);
			} else {
				switch ($obj->getVar('c_type')) {
					case 'H':
						$obj->setVar('name', _AM_CUSTOMHTML);
						break;
					case 'P':
						$obj->setVar('name', _AM_CUSTOMPHP);
						break;
					case 'S':
						$obj->setVar('name', _AM_CUSTOMSMILE);
						break;
					case 'T':
						$obj->setVar('name', _AM_CUSTOMNOSMILE);
						break;
				}
			}
		}
		$status = parent::insert( $obj, $force, $checkObject, $debug );
		// TODO: Make something to no query here... implement IPF for block_module_link
		$page = $obj->getVar('visiblein', 'e');
		if(!empty($page)){
			if(is_array($obj->getVar('visiblein', 'e'))){
				foreach ($obj->getVar('visiblein', 'e') as $bmid) {
					$page = explode('-', $bmid);
					$mid = $page[0];
					$pageid = $page[1];
					$sql = "INSERT INTO ".$this->db->prefix('block_module_link')." (block_id, module_id, page_id) VALUES ('". (int) ($obj->getVar("bid"))."', '". (int) ($mid)."', '". (int) ($pageid)."')";
					if (false != $force) {
						$this->db->queryF($sql);
					} else {
						$this->db->query($sql);
					}
				}
			}else{
				$page = explode('-', $obj->getVar('visiblein', 'e'));
				$mid = $page[0];
				$pageid = $page[1];
				$sql = "INSERT INTO ".$this->db->prefix('block_module_link')." (block_id, module_id, page_id) VALUES ('". (int) ($obj->getVar("bid"))."', '". (int) ($mid)."', '". (int) ($pageid)."')";
				if (false != $force) {
					$this->db->queryF($sql);
				} else {
					$this->db->query($sql);
				}
			}
		}
		return $status;

	}

	public function &get($id, $as_object = true, $debug=false, $criteria=false) {
		$obj = parent::get($id, $as_object, $debug, $criteria);
		$sql = "SELECT module_id,page_id FROM ".$this->db->prefix('block_module_link')." WHERE block_id='". (int) ($obj->getVar('bid'))."'";
		$result = $this->db->query($sql);
		$modules = $bcustomp = array();
		while ($row = $this->db->fetchArray($result)) {
			$modules[] = (int) ($row['module_id']).'-'. (int) ($row['page_id']);
		}
		$obj->setVar('visiblein', $modules);
		return $obj;
	}

	/**
	 * Get block data for multiple block ids
	 *
	 * @param array $blockids
	 *
	 * @todo can be removed together with getAllByGroupModule and getNonGroupedBlocks. (used in theme_blocks)
	 */
	private function &getMultiple($blockids) {
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('bid', '('.implode(',', $blockids).')', 'IN'));
		$ret = $this->getObjects($criteria, true, true);
		$sql = "SELECT block_id, module_id, page_id FROM ".$this->db->prefix('block_module_link')." WHERE block_id IN (".implode(',', array_keys($ret)).") ORDER BY block_id";
		$result = $this->db->query($sql);
		$modules = array();
		$last_block_id = 0;
		while ($row = $this->db->fetchArray($result)) {
			$modules[] = (int)($row['module_id']).'-'.(int)($row['page_id']);
			$ret[$row['block_id']]->setVar('visiblein', $modules);
			if ($row['block_id'] != $last_block_id) $modules = array();
			$last_block_id = $row['block_id'];
		}
		return $ret;
	}

	public function getCountSimilarBlocks($moduleId, $funcNum, $showFunc = null) {
		$funcNum = (int) ($funcNum);
		$moduleId = (int) ($moduleId);
		if ($funcNum < 1 || $moduleId < 1) {
			return 0;
		}
		$criteria = new CriteriaCompo();
		if (isset($showFunc)) {
			// showFunc is set for more strict comparison
			$criteria->add( new Criteria( 'mid', $moduleId ) );
			$criteria->add( new Criteria( 'func_num', $funcNum ) );
			$criteria->add( new Criteria( 'show_func', $showFunc ) );
		} else {
			$criteria->add( new Criteria( 'mid', $moduleId ) );
			$criteria->add( new Criteria( 'func_num', $funcNum ) );
		}
		$count = $this->handler->getCount($criteria);
		return $count;

	}

}

/**
 * XoopsBlock - for backwards compatibility
 *
 * @since XOOPS
 * @copyright The XOOPS Project <http://www.xoops.org>
 * @author The XOOPS Project Community <http://www.xoops.org>
 *
 * @see IcmsBlock
 *
 * @deprecated use IcmsBlock instead
 */
class XoopsBlock extends IcmsBlock {

	public function __construct(&$db) {
		icms_deprecated('IcmsBlock');
		parent::__construct(&$db);
	}

}

/**
 * XoopsBlockHandler - For backwards compatibility
 *
 * @since XOOPS
 * @copyright The XOOPS Project <http://www.xoops.org>
 * @author The XOOPS Project Community <http://www.xoops.org>
 *
 * @see IcmsBlockHandler
 *
 * @deprecated  use IcmsBlockHandler instead
 */
class XoopsBlockHandler extends IcmsBlockHandler {

	public function __construct(&$db) {
		icms_deprecated('IcmsBlockHandler');
		parent::__construct(&$db);
	}

}
