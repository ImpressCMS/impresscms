<?php
// priv_msgscat.php,v 1
//  ---------------------------------------------------------------- //
// Author: Bruno Barthez	                                           //
// ----------------------------------------------------------------- //

include_once XOOPS_ROOT_PATH."/class/xoopsobject.php";
/**
* priv_msgscat class.  
* $this class is responsible for providing data access mechanisms to the data source 
* of XOOPS user class objects.
*/


class priv_msgscat extends XoopsObject
{ 
	var $db;

// constructor
	function priv_msgscat ($id=null)
	{
		$this->db =& Database::getInstance();
		$this->initVar("cid",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("pid",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("title",XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar("uid",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("ver",XOBJ_DTYPE_INT,null,false,10);
		if ( !empty($id) ) {
			if ( is_array($id) ) {
				$this->assignVars($id);
			} else {
					$this->load(intval($id));
			}
		} else {
			$this->setNew();
		}
		
	}

	function load($id)
	{
		$sql = 'SELECT * FROM '.$this->db->prefix("priv_msgscat").' WHERE cid='.$id;
		$myrow = $this->db->fetchArray($this->db->query($sql));
		$this->assignVars($myrow);
		if (!$myrow) {
			$this->setNew();
		}
	}

	function getAllpriv_msgscats($criteria=array(), $asobject=false, $sort="cid", $order="ASC", $limit=0, $start=0)
	{
		$db =& Database::getInstance();
		$ret = array();
		$where_query = "";
		if ( is_array($criteria) && count($criteria) > 0 ) {
			$where_query = " WHERE";
			foreach ( $criteria as $c ) {
				$where_query .= " $c AND";
			}
			$where_query = substr($where_query, 0, -4);
		} elseif ( !is_array($criteria) && $criteria) {
			$where_query = " WHERE ".$criteria;
		}
		if ( !$asobject ) {
			$sql = "SELECT cid FROM ".$db->prefix("priv_msgscat")."$where_query ORDER BY $sort $order";
			$result = $db->query($sql,$limit,$start);
			while ( $myrow = $db->fetchArray($result) ) {
				$ret[] = $myrow['priv_msgscat_id'];
			}
		} else {
			$sql = "SELECT * FROM ".$db->prefix("priv_msgscat")."$where_query ORDER BY $sort $order";
			$result = $db->query($sql,$limit,$start);
			while ( $myrow = $db->fetchArray($result) ) {
				$ret[] = new priv_msgscat ($myrow);
			}
		}
		return $ret;
	}
}
// -------------------------------------------------------------------------
// ------------------priv_msgscat user handler class -------------------
// -------------------------------------------------------------------------
/**
* priv_msgscathandler class.  
* This class provides simple mecanisme for priv_msgscat object
*/

class XoopsPriv_msgscatHandler extends XoopsObjectHandler
{

	/**
	* create a new priv_msgscat
	* 
	* @param bool $isNew flag the new objects as "new"?
	* @return object priv_msgscat
	*/
	function &create($isNew = true)	{
		$priv_msgscat = new priv_msgscat();
		if ($isNew) {
			$priv_msgscat->setNew();
		}
		return $priv_msgscat;
	}

	/**
	* retrieve a priv_msgscat
	* 
	* @param int $id of the priv_msgscat
	* @return mixed reference to the {@link priv_msgscat} object, FALSE if failed
	*/
	function &get($id)	{
			$sql = 'SELECT * FROM '.$this->db->prefix('priv_msgscat').' WHERE cid='.$id;
			if (!$result = $this->db->query($sql)) {
				return false;
			}
			$numrows = $this->db->getRowsNum($result);
			if ($numrows == 1) {
				$priv_msgscat = new priv_msgscat();
				$priv_msgscat->assignVars($this->db->fetchArray($result));
				return $priv_msgscat;
			}
				return false;
	}

/**
* insert a new priv_msgscat in the database
* 
* @param object $priv_msgscat reference to the {@link priv_msgscat} object
* @param bool $force
* @return bool FALSE if failed, TRUE if already present and unchanged or successful
*/
	function insert(&$priv_msgscat, $force = false) {
		Global $xoopsConfig;
		if (get_class($priv_msgscat) != 'priv_msgscat') {
				return false;
		}
		if (!$priv_msgscat->isDirty()) {
				return true;
		}
		if (!$priv_msgscat->cleanVars()) {
				return false;
		}
		foreach ($priv_msgscat->cleanVars as $k => $v) {
				${$k} = $v;
		}
		$now = "date_add(now(), interval ".$xoopsConfig['server_TZ']." hour)";
		if ($priv_msgscat->isNew()) {
			// ajout/modification d'un priv_msgscat
			$priv_msgscat = new priv_msgscat();
			$format = "INSERT INTO %s (cid, pid, title, uid, ver)";
			$format .= "VALUES (%u, %u, %s, %u, %u)";
			$sql = sprintf($format , 
			$this->db->prefix('priv_msgscat'), 
			$cid
			,$pid
			,$this->db->quoteString($title)
			,$uid
			,$ver
			);
			$force = true;
		} else {
			$format = "UPDATE %s SET ";
			$format .="cid=%u, pid=%u, title=%s, uid=%u, ver=%u";
			$format .=" WHERE cid = %u";
			$sql = sprintf($format, $this->db->prefix('priv_msgscat'),
			$cid
			,$pid
			,$this->db->quoteString($title)
			,$uid
			,$ver
			, $cid);
		}
		if (false != $force) {
			$result = $this->db->queryF($sql);
		} else {
			$result = $this->db->query($sql);
		}
		if (!$result) {
			return false;
		}
		if (empty($cid)) {
			$cid = $this->db->getInsertId();
		}
		$priv_msgscat->assignVar('cid', $cid);
		return true;
	}

	/**
	 * delete a priv_msgscat from the database
	 * 
	 * @param object $priv_msgscat reference to the priv_msgscat to delete
	 * @param bool $force
	 * @return bool FALSE if failed.
	 */
	function delete(&$priv_msgscat, $force = false)
	{
		if (get_class($priv_msgscat) != 'priv_msgscat') {
			return false;
		}
		$sql = sprintf("DELETE FROM %s WHERE cid = %u  AND ver != 1", $this->db->prefix("priv_msgscat"), $priv_msgscat->getVar('cid'));
		if (false != $force) {
			$result = $this->db->queryF($sql);
		} else {
			$result = $this->db->query($sql);
		}
		if (!$result) {
			return false;
		}
		return true;
	}

	/**
	* retrieve priv_msgscats from the database
	* 
	* @param object $criteria {@link CriteriaElement} conditions to be met
	* @param bool $id_as_key use the UID as key for the array?
	* @return array array of {@link priv_msgscat} objects
	*/
	function &getObjects($criteria = null, $id_as_key = false)
	{
		$ret = array();
		$limit = $start = 0;
		$sql = 'SELECT * FROM '.$this->db->prefix('priv_msgscat');
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
		$sql .= ' '.$criteria->renderWhere();
		$sort = !in_array($criteria->getSort(), array('cid', 'pid', 'title')) ? 'cid' : $criteria->getSort();
        $sql .= ' ORDER BY '.$sort.' '.$criteria->getOrder();
	    $limit = $criteria->getLimit();
		$start = $criteria->getStart();
		}
		$result = $this->db->query($sql, $limit, $start);
		if (!$result) {
			return $ret;
		}
		while ($myrow = $this->db->fetchArray($result)) {
			$priv_msgscat = new priv_msgscat();
			$priv_msgscat->assignVars($myrow);
			if (!$id_as_key) {
				$ret[] =& $priv_msgscat;
			} else {
				$ret[$myrow['cid']] =& $priv_msgscat;
			}
			unset($priv_msgscat);
		}
		return $ret;
	}

	/**
	* count priv_msgscats matching a condition
	* 
	* @param object $criteria {@link CriteriaElement} to match
	* @return int count of priv_msgscats
	*/
	function getCount($criteria = null)
	{
		$sql = 'SELECT COUNT(*) FROM '.$this->db->prefix('priv_msgscat');
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
		}
		$result = $this->db->query($sql);
		if (!$result) {
			return 0;
		}
		list($count) = $this->db->fetchRow($result);
		return $count;
	} 

	/**
	* delete priv_msgscats matching a set of conditions
	* 
	* @param object $criteria {@link CriteriaElement} 
	* @return bool FALSE if deletion failed
	*/
	function deleteAll($criteria = null)
	{
		$sql = 'DELETE FROM '.$this->db->prefix('priv_msgscat');
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
		}
		if (!$result = $this->db->query($sql)) {
			return false;
		}
		return true;
	}
}


?>