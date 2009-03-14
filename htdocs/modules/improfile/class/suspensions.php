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
* profile_suspensions class.  
* $this class is responsible for providing data access mechanisms to the data source 
* of XOOPS user class objects.
*/


class Suspensions extends XoopsObject
{ 
	var $db;

// constructor
	function Suspensions ($id=null)
	{
		$this->db =& Database::getInstance();
		$this->initVar("uid",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("old_pass",XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar("old_email",XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar("old_signature",XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar("suspension_time",XOBJ_DTYPE_INT,null,false,10);
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
		$sql = 'SELECT * FROM '.$this->db->prefix("profile_suspensions").' WHERE uid='.$id;
		$myrow = $this->db->fetchArray($this->db->query($sql));
		$this->assignVars($myrow);
		if (!$myrow) {
			$this->setNew();
		}
	}

	function getAllprofile_suspensionss($criteria=array(), $asobject=false, $sort="uid", $order="ASC", $limit=0, $start=0)
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
			$sql = "SELECT uid FROM ".$db->prefix("profile_suspensions")."$where_query ORDER BY $sort $order";
			$result = $db->query($sql,$limit,$start);
			while ( $myrow = $db->fetchArray($result) ) {
				$ret[] = $myrow['profile_suspensions_id'];
			}
		} else {
			$sql = "SELECT * FROM ".$db->prefix("profile_suspensions")."$where_query ORDER BY $sort $order";
			$result = $db->query($sql,$limit,$start);
			while ( $myrow = $db->fetchArray($result) ) {
				$ret[] = new Suspensions ($myrow);
			}
		}
		return $ret;
	}
}
// -------------------------------------------------------------------------
// ------------------profile_suspensions user handler class -------------------
// -------------------------------------------------------------------------
/**
* profile_suspensionshandler class.  
* This class provides simple mecanisme for profile_suspensions object
*/

class ProfileSuspensionsHandler extends XoopsObjectHandler
{

	/**
	* create a new Suspensions
	* 
	* @param bool $isNew flag the new objects as "new"?
	* @return object profile_suspensions
	*/
	function &create($isNew = true)	{
		$profile_suspensions = new Suspensions();
		if ($isNew) {
			$profile_suspensions->setNew();
		}
		else{
		$profile_suspensions->unsetNew();
		}

		
		return $profile_suspensions;
	}

	/**
	* retrieve a profile_suspensions
	* 
	* @param int $id of the profile_suspensions
	* @return mixed reference to the {@link profile_suspensions} object, FALSE if failed
	*/
	function &get($id)	{
			$sql = 'SELECT * FROM '.$this->db->prefix('profile_suspensions').' WHERE uid='.$id;
			if (!$result = $this->db->query($sql)) {
				return false;
			}
			$numrows = $this->db->getRowsNum($result);
			if ($numrows == 1) {
				$profile_suspensions = new Suspensions();
				$profile_suspensions->assignVars($this->db->fetchArray($result));
				return $profile_suspensions;
			}
				return false;
	}

/**
* insert a new Suspensions in the database
* 
* @param object $profile_suspensions reference to the {@link profile_suspensions} object
* @param bool $force
* @return bool FALSE if failed, TRUE if already present and unchanged or successful
*/
	function insert(&$profile_suspensions, $force = false) {
		Global $xoopsConfig;
		if (get_class($profile_suspensions) != 'Suspensions') {
				return false;
		}
		if (!$profile_suspensions->isDirty()) {
				return true;
		}
		if (!$profile_suspensions->cleanVars()) {
				return false;
		}
		foreach ($profile_suspensions->cleanVars as $k => $v) {
				${$k} = $v;
		}
		$now = "date_add(now(), interval ".$xoopsConfig['server_TZ']." hour)";
		if ($profile_suspensions->isNew()) {
			// ajout/modification d'un profile_suspensions
			$profile_suspensions = new Suspensions();
			$format = "INSERT INTO %s (uid, old_pass, old_email, old_signature, suspension_time)";
			$format .= "VALUES (%u, %s, %s, %s, %u)";
			$sql = sprintf($format , 
			$this->db->prefix('profile_suspensions'), 
			$uid
			,$this->db->quoteString($old_pass)
			,$this->db->quoteString($old_email)
			,$this->db->quoteString($old_signature)
			,$suspension_time
			);
			$force = true;
		} else {
			$format = "UPDATE %s SET ";
			$format .="uid=%u, old_pass=%s, old_email=%s, old_signature=%s, suspension_time=%u";
			$format .=" WHERE uid = %u";
			$sql = sprintf($format, $this->db->prefix('profile_suspensions'),
			$uid
			,$this->db->quoteString($old_pass)
			,$this->db->quoteString($old_email)
			,$this->db->quoteString($old_signature)
			,$suspension_time
			, $uid);
		}
		if (false != $force) {
			$result = $this->db->queryF($sql);
		} else {
			$result = $this->db->query($sql);
		}
		if (!$result) {
			return false;
		}
		if (empty($uid)) {
			$uid = $this->db->getInsertId();
		}
		$profile_suspensions->assignVar('uid', $uid);
		return true;
	}

	/**
	 * delete a profile_suspensions from the database
	 * 
	 * @param object $profile_suspensions reference to the profile_suspensions to delete
	 * @param bool $force
	 * @return bool FALSE if failed.
	 */
	function delete(&$profile_suspensions, $force = false)
	{
		if (get_class($profile_suspensions) != 'Suspensions') {
			return false;
		}
		$sql = sprintf("DELETE FROM %s WHERE uid = %u", $this->db->prefix("profile_suspensions"), $profile_suspensions->getVar('uid'));
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
	* retrieve profile_suspensionss from the database
	* 
	* @param object $criteria {@link CriteriaElement} conditions to be met
	* @param bool $id_as_key use the UID as key for the array?
	* @return array array of {@link profile_suspensions} objects
	*/
	function &getObjects($criteria = null, $id_as_key = false)
	{
		$ret = array();
		$limit = $start = 0;
		$sql = 'SELECT * FROM '.$this->db->prefix('profile_suspensions');
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
			$profile_suspensions = new Suspensions();
			$profile_suspensions->assignVars($myrow);
			if (!$id_as_key) {
				$ret[] =& $profile_suspensions;
			} else {
				$ret[$myrow['uid']] =& $profile_suspensions;
			}
			unset($profile_suspensions);
		}
		return $ret;
	}

	/**
	* count profile_suspensionss matching a condition
	* 
	* @param object $criteria {@link CriteriaElement} to match
	* @return int count of profile_suspensionss
	*/
	function getCount($criteria = null)
	{
		$sql = 'SELECT COUNT(*) FROM '.$this->db->prefix('profile_suspensions');
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
	* delete profile_suspensionss matching a set of conditions
	* 
	* @param object $criteria {@link CriteriaElement} 
	* @return bool FALSE if deletion failed
	*/
	function deleteAll($criteria = null)
	{
		$sql = 'DELETE FROM '.$this->db->prefix('profile_suspensions');
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
		}
		if (!$result = $this->db->queryF($sql)) {
			return false;
		}
		return true;
	}
}


?>