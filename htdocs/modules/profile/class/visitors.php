<?php
/**
 * Extended User Profile
 *
 *
 *
 * @copyright       The ImpressCMS Project http://www.impresscms.org/
 * @license         LICENSE.txt
 * @license			GNU General Public License (GPL) http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * @package         modules
 * @since           1.2
 * @author          Jan Pedersen
 * @author          Marcello Brandao <marcello.brandao@gmail.com>
 * @author          Bruno Barthez
 * @author	   		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version         $Id$
 */

if (!defined("ICMS_ROOT_PATH")) {
    die("ICMS root path not defined");
}
include_once ICMS_ROOT_PATH."/class/xoopsobject.php";
/**
* profile_visitors class.  
* $this class is responsible for providing data access mechanisms to the data source 
* of XOOPS user class objects.
*/


class Visitors extends XoopsObject
{ 
	var $db;

// constructor
	function Visitors ($id=null)
	{
		$this->db =& Database::getInstance();
		$this->initVar("cod_visit",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("uid_owner",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("uid_visitor",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("uname_visitor",XOBJ_DTYPE_TXTBOX, null, false);
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
		$sql = 'SELECT * FROM '.$this->db->prefix("profile_visitors").' WHERE cod_visit='.$id;
		$myrow = $this->db->fetchArray($this->db->query($sql));
		$this->assignVars($myrow);
		if (!$myrow) {
			$this->setNew();
		}
	}

	function getAllprofile_visitorss($criteria=array(), $asobject=false, $sort="cod_visit", $order="ASC", $limit=0, $start=0)
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
			$sql = "SELECT cod_visit FROM ".$db->prefix("profile_visitors")."$where_query ORDER BY $sort $order";
			$result = $db->query($sql,$limit,$start);
			while ( $myrow = $db->fetchArray($result) ) {
				$ret[] = $myrow['profile_visitors_id'];
			}
		} else {
			$sql = "SELECT * FROM ".$db->prefix("profile_visitors")."$where_query ORDER BY $sort $order";
			$result = $db->query($sql,$limit,$start);
			while ( $myrow = $db->fetchArray($result) ) {
				$ret[] = new Visitors ($myrow);
			}
		}
		return $ret;
	}
}
// -------------------------------------------------------------------------
// ------------------profile_visitors user handler class -------------------
// -------------------------------------------------------------------------
/**
* profile_visitorshandler class.  
* This class provides simple mecanisme for profile_visitors object
*/

class ProfileVisitorsHandler extends XoopsObjectHandler
{

	/**
	* create a new Visitors
	* 
	* @param bool $isNew flag the new objects as "new"?
	* @return object profile_visitors
	*/
	function &create($isNew = true)	{
		$profile_visitors = new Visitors();
		if ($isNew) {
			$profile_visitors->setNew();
		}
		else{
		$profile_visitors->unsetNew();
		}

		
		return $profile_visitors;
	}

	/**
	* retrieve a profile_visitors
	* 
	* @param int $id of the profile_visitors
	* @return mixed reference to the {@link profile_visitors} object, FALSE if failed
	*/
	function &get($id)	{
			$sql = 'SELECT * FROM '.$this->db->prefix('profile_visitors').' WHERE cod_visit='.$id;
			if (!$result = $this->db->query($sql)) {
				return false;
			}
			$numrows = $this->db->getRowsNum($result);
			if ($numrows == 1) {
				$profile_visitors = new Visitors();
				$profile_visitors->assignVars($this->db->fetchArray($result));
				return $profile_visitors;
			}
				return false;
	}

/**
* insert a new Visitors in the database
* 
* @param object $profile_visitors reference to the {@link profile_visitors} object
* @param bool $force
* @return bool FALSE if failed, TRUE if already present and unchanged or successful
*/
	function insert(&$profile_visitors, $force = false) {
		Global $icmsConfig;
		if (get_class($profile_visitors) != 'Visitors') {
				return false;
		}
		if (!$profile_visitors->isDirty()) {
				return true;
		}
		if (!$profile_visitors->cleanVars()) {
				return false;
		}
		foreach ($profile_visitors->cleanVars as $k => $v) {
				${$k} = $v;
		}
		$now = "date_add(now(), interval ".$icmsConfig['server_TZ']." hour)";
		if ($profile_visitors->isNew()) {
			// ajout/modification d'un profile_visitors
			$profile_visitors = new Visitors();
			$format = "INSERT INTO %s (cod_visit, uid_owner, uid_visitor,uname_visitor)";
			$format .= "VALUES (%u, %u, %u, %s)";
			$sql = sprintf($format , 
			$this->db->prefix('profile_visitors'), 
			$cod_visit
			,$uid_owner
			,$uid_visitor
			,$this->db->quoteString($uname_visitor)
			);
			$force = true;
		} else {
			$format = "UPDATE %s SET ";
			$format .="cod_visit=%u, uid_owner=%u, uid_visitor=%u, uname_visitor=%s ";
			$format .=" WHERE cod_visit = %u";
			$sql = sprintf($format, $this->db->prefix('profile_visitors'),
			$cod_visit
			,$uid_owner
			,$uid_visitor
			,$this->db->quoteString($uname_visitor)
			, $cod_visit);
		}
		if (false != $force) {
			$result = $this->db->queryF($sql);
		} else {
			$result = $this->db->query($sql);
		}
		if (!$result) {
			return false;
		}
		if (empty($cod_visit)) {
			$cod_visit = $this->db->getInsertId();
		}
		$profile_visitors->assignVar('cod_visit', $cod_visit);
		return true;
	}

	/**
	 * delete a profile_visitors from the database
	 * 
	 * @param object $profile_visitors reference to the profile_visitors to delete
	 * @param bool $force
	 * @return bool FALSE if failed.
	 */
	function delete(&$profile_visitors, $force = false)
	{
		if (get_class($profile_visitors) != 'Visitors') {
			return false;
		}
		$sql = sprintf("DELETE FROM %s WHERE cod_visit = %u", $this->db->prefix("profile_visitors"), $profile_visitors->getVar('cod_visit'));
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
	* retrieve profile_visitorss from the database
	* 
	* @param object $criteria {@link CriteriaElement} conditions to be met
	* @param bool $id_as_key use the UID as key for the array?
	* @return array array of {@link profile_visitors} objects
	*/
	function &getObjects($criteria = null, $id_as_key = false)
	{
		$ret = array();
		$limit = $start = 0;
		$sql = 'SELECT * FROM '.$this->db->prefix('profile_visitors');
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
			$profile_visitors = new Visitors();
			$profile_visitors->assignVars($myrow);
			if (!$id_as_key) {
				$ret[] =& $profile_visitors;
			} else {
				$ret[$myrow['cod_visit']] =& $profile_visitors;
			}
			unset($profile_visitors);
		}
		return $ret;
	}

	/**
	* count profile_visitorss matching a condition
	* 
	* @param object $criteria {@link CriteriaElement} to match
	* @return int count of profile_visitorss
	*/
	function getCount($criteria = null)
	{
		$sql = 'SELECT COUNT(*) FROM '.$this->db->prefix('profile_visitors');
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
	* delete profile_visitorss matching a set of conditions
	* 
	* @param object $criteria {@link CriteriaElement} 
	* @return bool FALSE if deletion failed
	*/
	function deleteAll($criteria = null, $force=false)
	{
		$sql = 'DELETE FROM '.$this->db->prefix('profile_visitors');
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
		}
		if (false != $force) {
			if (!$result = $this->db->queryF($sql)) {
			return false;
		};
		} else {
			if (!$result = $this->db->query($sql)) {
			return false;
		}
		}
		
		return true;
	}
	
	function purgeVisits(){
		
		$sql = 'DELETE FROM '.$this->db->prefix('profile_visitors').' WHERE (datetime<(DATE_SUB(NOW(), INTERVAL 7 DAY))) ';

			if (!$result = $this->db->queryF($sql)) {
			return false;
		}
		
		
		return true;
		
	}
}


?>