<?php
// priv_msgsopt.php,v 1
//  ---------------------------------------------------------------- //
// Author: Bruno Barthez	                                           //
// ----------------------------------------------------------------- //

include_once XOOPS_ROOT_PATH."/class/xoopsobject.php";
/**
* priv_msgsopt class.  
* $this class is responsible for providing data access mechanisms to the data source 
* of XOOPS user class objects.
*/


class priv_msgsopt extends XoopsObject
{ 
	var $db;

// constructor
	function priv_msgsopt ($id=null)
	{
		$this->db =& Database::getInstance();
		$this->initVar("userid",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("notif",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("resend",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("limite",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("home",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("sortname",XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar("sortorder",XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar("vieworder",XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar("formtype",XOBJ_DTYPE_INT,null,false,10);
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
		$sql = 'SELECT * FROM '.$this->db->prefix("priv_msgsopt").' WHERE userid='.$id;
		$myrow = $this->db->fetchArray($this->db->query($sql));
		$this->assignVars($myrow);
		if (!$myrow) {
			$this->setNew();
		}
	}

	function getAllpriv_msgsopts($criteria=array(), $asobject=false, $sort="userid", $order="ASC", $limit=0, $start=0)
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
			$sql = "SELECT userid FROM ".$db->prefix("priv_msgsopt")."$where_query ORDER BY $sort $order";
			$result = $db->query($sql,$limit,$start);
			while ( $myrow = $db->fetchArray($result) ) {
				$ret[] = $myrow['priv_msgsopt_id'];
			}
		} else {
			$sql = "SELECT * FROM ".$db->prefix("priv_msgsopt")."$where_query ORDER BY $sort $order";
			$result = $db->query($sql,$limit,$start);
			while ( $myrow = $db->fetchArray($result) ) {
				$ret[] = new priv_msgsopt ($myrow);
			}
		}
		return $ret;
	}
}
// -------------------------------------------------------------------------
// ------------------priv_msgsopt user handler class -------------------
// -------------------------------------------------------------------------
/**
* priv_msgsopthandler class.  
* This class provides simple mecanisme for priv_msgsopt object
*/

class Xoopspriv_msgsoptHandler extends XoopsObjectHandler
{

	/**
	* create a new priv_msgsopt
	* 
	* @param bool $isNew flag the new objects as "new"?
	* @return object priv_msgsopt
	*/
	function &create($isNew = true)	{
		$priv_msgsopt = new priv_msgsopt();
		if ($isNew) {
			$priv_msgsopt->setNew();
		}
		return $priv_msgsopt;
	}

	/**
	* retrieve a priv_msgsopt
	* 
	* @param int $id of the priv_msgsopt
	* @return mixed reference to the {@link priv_msgsopt} object, FALSE if failed
	*/
	function &get($id)	{
    /* MusS : create a variable for the return value */  
	    $return=false;
			$sql = 'SELECT * FROM '.$this->db->prefix('priv_msgsopt').' WHERE userid='.$id;
			if (!$result = $this->db->query($sql)) {
				return $return;
			}
			$numrows = $this->db->getRowsNum($result);
			if ($numrows == 1) {
				$priv_msgsopt = new priv_msgsopt();
				$priv_msgsopt->assignVars($this->db->fetchArray($result));
				return $priv_msgsopt;
			}
				return $return;
	}

/**
* insert a new priv_msgsopt in the database
* 
* @param object $priv_msgsopt reference to the {@link priv_msgsopt} object
* @param bool $force
* @return bool FALSE if failed, TRUE if already present and unchanged or successful
*/
	function insert(&$priv_msgsopt, $force = false) {
		Global $xoopsConfig;
		if (get_class($priv_msgsopt) != 'priv_msgsopt') {
				return false;
		}
		if (!$priv_msgsopt->isDirty()) {
				return true;
		}
		if (!$priv_msgsopt->cleanVars()) {
				return false;
		}
		foreach ($priv_msgsopt->cleanVars as $k => $v) {
				${$k} = $v;
		}
		$now = "date_add(now(), interval ".$xoopsConfig['server_TZ']." hour)";
		if ($priv_msgsopt->isNew()) {
			// ajout/modification d'un priv_msgsopt
			$priv_msgsopt = new priv_msgsopt();
			$format = "INSERT INTO %s (userid, notif, resend, limite, home, sortname, sortorder, vieworder, formtype)";
			$format .= "VALUES (%u, %u, %u, %u, %u, %s, %s, %s, %u)";
			$sql = sprintf($format , 
			$this->db->prefix('priv_msgsopt'), 
			$userid
			,$notif
			,$resend
			,$limite
			,$home
			,$this->db->quoteString($sortname)
			,$this->db->quoteString($sortorder)
			,$this->db->quoteString($vieworder)
			,$formtype
			);
			$force = true;
		} else {
			$format = "UPDATE %s SET ";
			$format .="userid=%u, notif=%u, resend=%u, limite=%u, home=%u, sortname=%s, sortorder=%s, vieworder=%s, formtype=%u";
			$format .=" WHERE userid = %u";
			$sql = sprintf($format, $this->db->prefix('priv_msgsopt'),
			$userid
			,$notif
			,$resend
			,$limite
			,$home
			,$this->db->quoteString($sortname)
			,$this->db->quoteString($sortorder)
			,$this->db->quoteString($vieworder)
			,$formtype
			, $userid);
		}
		if (false != $force) {
			$result = $this->db->queryF($sql);
		} else {
			$result = $this->db->query($sql);
		}
		if (!$result) {
			return false;
		}
		if (empty($userid)) {
			$userid = $this->db->getInsertId();
		}
		$priv_msgsopt->assignVar('userid', $userid);
		return true;
	}


function update(&$priv_msgsopt, $force = false) {
		Global $xoopsConfig;
		if (get_class($priv_msgsopt) != 'priv_msgsopt') {
				return false;
		}
		if (!$priv_msgsopt->isDirty()) {
				return true;
		}
		if (!$priv_msgsopt->cleanVars()) {
				return false;
		}
		foreach ($priv_msgsopt->cleanVars as $k => $v) {
				${$k} = $v;
		}
		$now = "date_add(now(), interval ".$xoopsConfig['server_TZ']." hour)";

			$format = "UPDATE %s SET ";
			$format .="userid=%u, notif=%u, resend=%u, limite=%u, home=%u, sortname=%s, sortorder=%s, vieworder=%s, formtype=%u";
			$format .=" WHERE userid = %u";
			$sql = sprintf($format, $this->db->prefix('priv_msgsopt'),
			$userid
			,$notif
			,$resend
			,$limite
			,$home
			,$this->db->quoteString($sortname)
			,$this->db->quoteString($sortorder)
			,$this->db->quoteString($vieworder)
			,$formtype
			, $userid);
	
		if (false != $force) {
			$result = $this->db->queryF($sql);
		} else {
			$result = $this->db->query($sql);
		}
		if (!$result) {
			return false;
		}
		if (empty($userid)) {
			$userid = $this->db->getInsertId();
		}
		$priv_msgsopt->assignVar('userid', $userid);
		return true;
	}
	/**
	 * delete a priv_msgsopt from the database
	 * 
	 * @param object $priv_msgsopt reference to the priv_msgsopt to delete
	 * @param bool $force
	 * @return bool FALSE if failed.
	 */
	function delete(&$priv_msgsopt, $force = false)
	{
		if (get_class($priv_msgsopt) != 'priv_msgsopt') {
			return false;
		}
		$sql = sprintf("DELETE FROM %s WHERE userid = %u", $this->db->prefix("priv_msgsopt"), $priv_msgsopt->getVar('userid'));
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
	* retrieve priv_msgsopts from the database
	* 
	* @param object $criteria {@link CriteriaElement} conditions to be met
	* @param bool $id_as_key use the UID as key for the array?
	* @return array array of {@link priv_msgsopt} objects
	*/
	function &getObjects($criteria = null, $id_as_key = false)
	{
		$ret = array();
		$limit = $start = 0;
		$sql = 'SELECT * FROM '.$this->db->prefix('priv_msgsopt');
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
		if ($criteria->getSort() != '') {
			$sql .= ' ORDER BY '.$criteria->getSort().' '.$criteria->getOrder();
		}
		$limit = $criteria->getLimit();
		$start = $criteria->getStart();
		}
		$result = $this->db->query($sql, $limit, $start);
		if (!$result) {
			return $ret;
		}
		while ($myrow = $this->db->fetchArray($result)) {
			$priv_msgsopt = new priv_msgsopt();
			$priv_msgsopt->assignVars($myrow);
			if (!$id_as_key) {
				$ret[] =& $priv_msgsopt;
			} else {
				$ret[$myrow['userid']] =& $priv_msgsopt;
			}
			unset($priv_msgsopt);
		}
		return $ret;
	}

	/**
	* count priv_msgsopts matching a condition
	* 
	* @param object $criteria {@link CriteriaElement} to match
	* @return int count of priv_msgsopts
	*/
	function getCount($criteria = null)
	{
		$sql = 'SELECT COUNT(*) FROM '.$this->db->prefix('priv_msgsopt');
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
	* delete priv_msgsopts matching a set of conditions
	* 
	* @param object $criteria {@link CriteriaElement} 
	* @return bool FALSE if deletion failed
	*/
	function deleteAll($criteria = null)
	{
		$sql = 'DELETE FROM '.$this->db->prefix('priv_msgsopt');
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
