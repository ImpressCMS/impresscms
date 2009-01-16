<?php
// priv_msgscont.php,v 1
//  ---------------------------------------------------------------- //
// Author: Bruno Barthez	                                           //
// ----------------------------------------------------------------- //

include_once XOOPS_ROOT_PATH."/class/xoopsobject.php";
/**
* priv_msgscont class.  
* $this class is responsible for providing data access mechanisms to the data source 
* of XOOPS user class objects.
*/


class priv_msgscont extends XoopsObject
{ 
	var $db;

// constructor
	function priv_msgscont ($id=null)
	{
		$this->db =& Database::getInstance();
		$this->initVar("ct_userid",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("ct_contact",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("ct_name",XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar("ct_uname",XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar("ct_regdate",XOBJ_DTYPE_INT,null,false,10);
		if ( !empty($id) ) {
			if ( is_array($id) ) {
				$this->assignVars($id);
			} else {
					$this->load($id);
			}
		} else {
			$this->setNew();
		}
		
	}

	function load($id)
	{
		$sql = 'SELECT * FROM '.$this->db->prefix("priv_msgscont").' WHERE ='.$this->db->quoteString($id);
		$myrow = $this->db->fetchArray($this->db->query($sql));
		$this->assignVars($myrow);
		if (!$myrow) {
			$this->setNew();
		}
	}

	function getAllpriv_msgsconts($criteria=array(), $asobject=false, $sort="", $order="ASC", $limit=0, $start=0)
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
			$sql = "SELECT  FROM ".$db->prefix("priv_msgscont")."$where_query ORDER BY $sort $order";
			$result = $db->query($sql,$limit,$start);
			while ( $myrow = $db->fetchArray($result) ) {
				$ret[] = $myrow['priv_msgscont_id'];
			}
		} else {
			$sql = "SELECT * FROM ".$db->prefix("priv_msgscont")."$where_query ORDER BY $sort $order";
			$result = $db->query($sql,$limit,$start);
			while ( $myrow = $db->fetchArray($result) ) {
				$ret[] = new priv_msgscont ($myrow);
			}
		}
		return $ret;
	}
}
// -------------------------------------------------------------------------
// ------------------priv_msgscont user handler class -------------------
// -------------------------------------------------------------------------
/**
* priv_msgsconthandler class.  
* This class provides simple mecanisme for priv_msgscont object
*/

class Xoopspriv_msgscontHandler extends XoopsObjectHandler
{

	/**
	* create a new priv_msgscont
	* 
	* @param bool $isNew flag the new objects as "new"?
	* @return object priv_msgscont
	*/
	function &create($isNew = true)	{
		$priv_msgscont = new priv_msgscont();
		if ($isNew) {
			$priv_msgscont->setNew();
		}
		return $priv_msgscont;
	}

	/**
	* retrieve a priv_msgscont
	* 
	* @param int $id of the priv_msgscont
	* @return mixed reference to the {@link priv_msgscont} object, FALSE if failed
	*/
	function &get($id)	{
			$sql = 'SELECT * FROM '.$this->db->prefix('priv_msgscont').' WHERE ct_contact ='.$id;
			if (!$result = $this->db->query($sql)) {
				return false;
			}
			$numrows = $this->db->getRowsNum($result);
			if ($numrows == 1) {
				$priv_msgscont = new priv_msgscont();
				$priv_msgscont->assignVars($this->db->fetchArray($result));
				return $priv_msgscont;
			}
				return false;
	}



	
	function getList()
    {
	global $xoopsUser;
	    $criteria = new CriteriaCompo(new Criteria('ct_userid', $xoopsUser->getVar('uid')));
        $list =& $this->getObjects($criteria, false, true);
        $ret = array();
        foreach (array_keys($list) as $i) {
            $ret[$list[$i]->getVar('ct_contact')] = $list[$i]->getVar('ct_uname');
        }
        return $ret;
    }
/**
* insert a new priv_msgscont in the database
* 
* @param object $priv_msgscont reference to the {@link priv_msgscont} object
* @param bool $force
* @return bool FALSE if failed, TRUE if already present and unchanged or successful
*/
	function insert(&$priv_msgscont, $force = false) {
		Global $xoopsConfig;
		if (get_class($priv_msgscont) != 'priv_msgscont') {
				return false;
		}
		if (!$priv_msgscont->isDirty()) {
				return true;
		}
		if (!$priv_msgscont->cleanVars()) {
				return false;
		}
		foreach ($priv_msgscont->cleanVars as $k => $v) {
				${$k} = $v;
		}
		$now = "date_add(now(), interval ".$xoopsConfig['server_TZ']." hour)";
		if ($priv_msgscont->isNew()) {
			// ajout/modification d'un priv_msgscont
			$priv_msgscont = new priv_msgscont();
			$format = "INSERT INTO %s (ct_userid, ct_contact, ct_name, ct_uname, ct_regdate)";
			$format .= "VALUES (%u, %u, %s, %s, %u)";
			$sql = sprintf($format , 
			$this->db->prefix('priv_msgscont'), 
			$ct_userid
			,$ct_contact
			,$this->db->quoteString($ct_name)
			,$this->db->quoteString($ct_uname)
			,$ct_regdate
			);
			$force = true;
		} else {
			$format = "UPDATE %s SET ";
			$format .="ct_userid=%u, ct_contact=%u, ct_name=%s, ct_uname=%s, ct_regdate=%u";
			$format .=" WHERE  = %u";
			$sql = sprintf($format, $this->db->prefix('priv_msgscont'),
			$ct_userid
			,$ct_contact
			,$this->db->quoteString($ct_name)
			,$this->db->quoteString($ct_uname)
			,$ct_regdate
			);
		}
		if (false != $force) {
			$result = $this->db->queryF($sql);
		} else {
			$result = $this->db->query($sql);
		}
		if (!$result) {
			return false;
		}
		if (empty($ct_userid)) {
			$ct_userid = $this->db->getInsertId();
		}
		$priv_msgscont->assignVar('ct_userid', $ct_userid);
		return true;
	}

	/**
	 * delete a priv_msgscont from the database
	 * 
	 * @param object $priv_msgscont reference to the priv_msgscont to delete
	 * @param bool $force
	 * @return bool FALSE if failed.
	 */
	function delete(&$priv_msgscont, $force = false)
	{
		if (get_class($priv_msgscont) != 'priv_msgscont') {
			return false;
		}
		$sql = sprintf("DELETE FROM %s WHERE ct_contact = %u", $this->db->prefix("priv_msgscont"), $priv_msgscont->getVar('ct_contact'));
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
	* retrieve priv_msgsconts from the database
	* 
	* @param object $criteria {@link CriteriaElement} conditions to be met
	* @param bool $id_as_key use the UID as key for the array?
	* @return array array of {@link priv_msgscont} objects
	*/
	function &getObjects($criteria = null, $id_as_key = false)
	{
		$ret = array();
		$limit = $start = 0;
		$sql = 'SELECT * FROM '.$this->db->prefix('priv_msgscont');
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
			$priv_msgscont = new priv_msgscont();
			$priv_msgscont->assignVars($myrow);
			if (!$id_as_key) {
				$ret[] =& $priv_msgscont;
			} else {
				$ret[$myrow['']] =& $priv_msgscont;
			}
			unset($priv_msgscont);
		}
		return $ret;
	}

	/**
	* count priv_msgsconts matching a condition
	* 
	* @param object $criteria {@link CriteriaElement} to match
	* @return int count of priv_msgsconts
	*/
	function getCount($criteria = null)
	{
		$sql = 'SELECT COUNT(*) FROM '.$this->db->prefix('priv_msgscont');
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
	* delete priv_msgsconts matching a set of conditions
	* 
	* @param object $criteria {@link CriteriaElement} 
	* @return bool FALSE if deletion failed
	*/
	function deleteAll($criteria = null)
	{
		$sql = 'DELETE FROM '.$this->db->prefix('priv_msgscont');
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