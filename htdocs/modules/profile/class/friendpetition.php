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
* profile_friendpetition class.  
* $this class is responsible for providing data access mechanisms to the data source 
* of XOOPS user class objects.
*/


class Friendpetition extends XoopsObject
{ 
	var $db;

// constructor
	function Friendpetition ($id=null)
	{
		$this->db =& Database::getInstance();
		$this->initVar("friendpet_id",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("petitioner_uid",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("petioned_uid",XOBJ_DTYPE_INT,null,false,10);
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
		$sql = 'SELECT * FROM '.$this->db->prefix("profile_friendpetition").' WHERE friendpet_id='.$id;
		$myrow = $this->db->fetchArray($this->db->query($sql));
		$this->assignVars($myrow);
		if (!$myrow) {
			$this->setNew();
		}
	}

	function getAllprofile_friendpetitions($criteria=array(), $asobject=false, $sort="friendpet_id", $order="ASC", $limit=0, $start=0)
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
			$sql = "SELECT friendpet_id FROM ".$db->prefix("profile_friendpetition")."$where_query ORDER BY $sort $order";
			$result = $db->query($sql,$limit,$start);
			while ( $myrow = $db->fetchArray($result) ) {
				$ret[] = $myrow['profile_friendpetition_id'];
			}
		} else {
			$sql = "SELECT * FROM ".$db->prefix("profile_friendpetition")."$where_query ORDER BY $sort $order";
			$result = $db->query($sql,$limit,$start);
			while ( $myrow = $db->fetchArray($result) ) {
				$ret[] = new Friendpetition ($myrow);
			}
		}
		return $ret;
	}
}
// -------------------------------------------------------------------------
// ------------------profile_friendpetition user handler class -------------------
// -------------------------------------------------------------------------
/**
* profile_friendpetitionhandler class.  
* This class provides simple mecanisme for profile_friendpetition object
*/

class ProfileFriendpetitionHandler extends XoopsObjectHandler
{

	/**
	* create a new Friendpetition
	* 
	* @param bool $isNew flag the new objects as "new"?
	* @return object profile_friendpetition
	*/
	function &create($isNew = true)	{
		$profile_friendpetition = new Friendpetition();
		if ($isNew) {
			$profile_friendpetition->setNew();
		}
		else{
		$profile_friendpetition->unsetNew();
		}

		
		return $profile_friendpetition;
	}

	/**
	* retrieve a profile_friendpetition
	* 
	* @param int $id of the profile_friendpetition
	* @return mixed reference to the {@link profile_friendpetition} object, FALSE if failed
	*/
	function &get($id)	{
			$sql = 'SELECT * FROM '.$this->db->prefix('profile_friendpetition').' WHERE friendpet_id='.$id;
			if (!$result = $this->db->query($sql)) {
				return false;
			}
			$numrows = $this->db->getRowsNum($result);
			if ($numrows == 1) {
				$profile_friendpetition = new Friendpetition();
				$profile_friendpetition->assignVars($this->db->fetchArray($result));
				return $profile_friendpetition;
			}
				return false;
	}

/**
* insert a new Friendpetition in the database
* 
* @param object $profile_friendpetition reference to the {@link profile_friendpetition} object
* @param bool $force
* @return bool FALSE if failed, TRUE if already present and unchanged or successful
*/
	function insert(&$profile_friendpetition, $force = false) {
		Global $xoopsConfig;
		if (get_class($profile_friendpetition) != 'Friendpetition') {
				return false;
		}
		if (!$profile_friendpetition->isDirty()) {
				return true;
		}
		if (!$profile_friendpetition->cleanVars()) {
				return false;
		}
		foreach ($profile_friendpetition->cleanVars as $k => $v) {
				${$k} = $v;
		}
		$now = "date_add(now(), interval ".$xoopsConfig['server_TZ']." hour)";
		if ($profile_friendpetition->isNew()) {
			// ajout/modification d'un profile_friendpetition
			$profile_friendpetition = new Friendpetition();
			$format = "INSERT INTO %s (friendpet_id, petitioner_uid, petioned_uid)";
			$format .= "VALUES (%u, %u, %u)";
			$sql = sprintf($format , 
			$this->db->prefix('profile_friendpetition'), 
			$friendpet_id
			,$petitioner_uid
			,$petioned_uid
			);
			$force = true;
		} else {
			$format = "UPDATE %s SET ";
			$format .="friendpet_id=%u, petitioner_uid=%u, petioned_uid=%u";
			$format .=" WHERE friendpet_id = %u";
			$sql = sprintf($format, $this->db->prefix('profile_friendpetition'),
			$friendpet_id
			,$petitioner_uid
			,$petioned_uid
			, $friendpet_id);
		}
		if (false != $force) {
			$result = $this->db->queryF($sql);
		} else {
			$result = $this->db->query($sql);
		}
		if (!$result) {
			return false;
		}
		if (empty($friendpet_id)) {
			$friendpet_id = $this->db->getInsertId();
		}
		$profile_friendpetition->assignVar('friendpet_id', $friendpet_id);
		return true;
	}

	/**
	 * delete a profile_friendpetition from the database
	 * 
	 * @param object $profile_friendpetition reference to the profile_friendpetition to delete
	 * @param bool $force
	 * @return bool FALSE if failed.
	 */
	function delete(&$profile_friendpetition, $force = false)
	{
		if (get_class($profile_friendpetition) != 'Friendpetition') {
			return false;
		}
		$sql = sprintf("DELETE FROM %s WHERE friendpet_id = %u", $this->db->prefix("profile_friendpetition"), $profile_friendpetition->getVar('friendpet_id'));
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
	* retrieve profile_friendpetitions from the database
	* 
	* @param object $criteria {@link CriteriaElement} conditions to be met
	* @param bool $id_as_key use the UID as key for the array?
	* @return array array of {@link profile_friendpetition} objects
	*/
	function &getObjects($criteria = null, $id_as_key = false)
	{
		$ret = array();
		$limit = $start = 0;
		$sql = 'SELECT * FROM '.$this->db->prefix('profile_friendpetition');
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
			$profile_friendpetition = new Friendpetition();
			$profile_friendpetition->assignVars($myrow);
			if (!$id_as_key) {
				$ret[] =& $profile_friendpetition;
			} else {
				$ret[$myrow['friendpet_id']] =& $profile_friendpetition;
			}
			unset($profile_friendpetition);
		}
		return $ret;
	}

	/**
	* count profile_friendpetitions matching a condition
	* 
	* @param object $criteria {@link CriteriaElement} to match
	* @return int count of profile_friendpetitions
	*/
	function getCount($criteria = null)
	{
		$sql = 'SELECT COUNT(*) FROM '.$this->db->prefix('profile_friendpetition');
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
	* delete profile_friendpetitions matching a set of conditions
	* 
	* @param object $criteria {@link CriteriaElement} 
	* @return bool FALSE if deletion failed
	*/
	function deleteAll($criteria = null)
	{
		$sql = 'DELETE FROM '.$this->db->prefix('profile_friendpetition');
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