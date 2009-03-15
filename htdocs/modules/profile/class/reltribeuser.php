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
* profile_reltribeuser class.  
* $this class is responsible for providing data access mechanisms to the data source 
* of XOOPS user class objects.
*/


class Reltribeuser extends XoopsObject
{ 
	var $db;

// constructor
	function Reltribeuser ($id=null)
	{
		$this->db =& Database::getInstance();
		$this->initVar("rel_id",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("rel_tribe_id",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("rel_user_uid",XOBJ_DTYPE_INT,null,false,10);
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
		$sql = 'SELECT * FROM '.$this->db->prefix("profile_reltribeuser").' WHERE rel_id='.$id;
		$myrow = $this->db->fetchArray($this->db->query($sql));
		$this->assignVars($myrow);
		if (!$myrow) {
			$this->setNew();
		}
	}

	function getAllprofile_reltribeusers($criteria=array(), $asobject=false, $sort="rel_id", $order="ASC", $limit=0, $start=0)
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
			$sql = "SELECT rel_id FROM ".$db->prefix("profile_reltribeuser")."$where_query ORDER BY $sort $order";
			$result = $db->query($sql,$limit,$start);
			while ( $myrow = $db->fetchArray($result) ) {
				$ret[] = $myrow['profile_reltribeuser_id'];
			}
		} else {
			$sql = "SELECT * FROM ".$db->prefix("profile_reltribeuser")."$where_query ORDER BY $sort $order";
			$result = $db->query($sql,$limit,$start);
			while ( $myrow = $db->fetchArray($result) ) {
				$ret[] = new Reltribeuser ($myrow);
			}
		}
		return $ret;
	}
}
// -------------------------------------------------------------------------
// ------------------profile_reltribeuser user handler class -------------------
// -------------------------------------------------------------------------
/**
* profile_reltribeuserhandler class.  
* This class provides simple mecanisme for profile_reltribeuser object
*/

class ProfileReltribeuserHandler extends XoopsObjectHandler
{

	/**
	* create a new Reltribeuser
	* 
	* @param bool $isNew flag the new objects as "new"?
	* @return object profile_reltribeuser
	*/
	function &create($isNew = true)	{
		$profile_reltribeuser = new Reltribeuser();
		if ($isNew) {
			$profile_reltribeuser->setNew();
		}
		else{
		$profile_reltribeuser->unsetNew();
		}

		
		return $profile_reltribeuser;
	}

	/**
	* retrieve a profile_reltribeuser
	* 
	* @param int $id of the profile_reltribeuser
	* @return mixed reference to the {@link profile_reltribeuser} object, FALSE if failed
	*/
	function &get($id)	{
			$sql = 'SELECT * FROM '.$this->db->prefix('profile_reltribeuser').' WHERE rel_id='.$id;
			if (!$result = $this->db->query($sql)) {
				return false;
			}
			$numrows = $this->db->getRowsNum($result);
			if ($numrows == 1) {
				$profile_reltribeuser = new Reltribeuser();
				$profile_reltribeuser->assignVars($this->db->fetchArray($result));
				return $profile_reltribeuser;
			}
				return false;
	}

/**
* insert a new Reltribeuser in the database
* 
* @param object $profile_reltribeuser reference to the {@link profile_reltribeuser} object
* @param bool $force
* @return bool FALSE if failed, TRUE if already present and unchanged or successful
*/
	function insert(&$profile_reltribeuser, $force = false) {
		Global $xoopsConfig;
		if (get_class($profile_reltribeuser) != 'Reltribeuser') {
				return false;
		}
		if (!$profile_reltribeuser->isDirty()) {
				return true;
		}
		if (!$profile_reltribeuser->cleanVars()) {
				return false;
		}
		foreach ($profile_reltribeuser->cleanVars as $k => $v) {
				${$k} = $v;
		}
		$now = "date_add(now(), interval ".$xoopsConfig['server_TZ']." hour)";
		if ($profile_reltribeuser->isNew()) {
			// ajout/modification d'un profile_reltribeuser
			$profile_reltribeuser = new Reltribeuser();
			$format = "INSERT INTO %s (rel_id, rel_tribe_id, rel_user_uid)";
			$format .= "VALUES (%u, %u, %u)";
			$sql = sprintf($format , 
			$this->db->prefix('profile_reltribeuser'), 
			$rel_id
			,$rel_tribe_id
			,$rel_user_uid
			);
			$force = true;
		} else {
			$format = "UPDATE %s SET ";
			$format .="rel_id=%u, rel_tribe_id=%u, rel_user_uid=%u";
			$format .=" WHERE rel_id = %u";
			$sql = sprintf($format, $this->db->prefix('profile_reltribeuser'),
			$rel_id
			,$rel_tribe_id
			,$rel_user_uid
			, $rel_id);
		}
		if (false != $force) {
			$result = $this->db->queryF($sql);
		} else {
			$result = $this->db->query($sql);
		}
		if (!$result) {
			return false;
		}
		if (empty($rel_id)) {
			$rel_id = $this->db->getInsertId();
		}
		$profile_reltribeuser->assignVar('rel_id', $rel_id);
		return true;
	}

	/**
	 * delete a profile_reltribeuser from the database
	 * 
	 * @param object $profile_reltribeuser reference to the profile_reltribeuser to delete
	 * @param bool $force
	 * @return bool FALSE if failed.
	 */
	function delete(&$profile_reltribeuser, $force = false)
	{
		if (get_class($profile_reltribeuser) != 'Reltribeuser') {
			return false;
		}
		$sql = sprintf("DELETE FROM %s WHERE rel_id = %u", $this->db->prefix("profile_reltribeuser"), $profile_reltribeuser->getVar('rel_id'));
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
	* retrieve profile_reltribeusers from the database
	* 
	* @param object $criteria {@link CriteriaElement} conditions to be met
	* @param bool $id_as_key use the UID as key for the array?
	* @return array array of {@link profile_reltribeuser} objects
	*/
	function &getObjects($criteria = null, $id_as_key = false)
	{
		$ret = array();
		$limit = $start = 0;
		$sql = 'SELECT * FROM '.$this->db->prefix('profile_reltribeuser');
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
			$profile_reltribeuser = new Reltribeuser();
			$profile_reltribeuser->assignVars($myrow);
			if (!$id_as_key) {
				$ret[] =& $profile_reltribeuser;
			} else {
				$ret[$myrow['rel_id']] =& $profile_reltribeuser;
			}
			unset($profile_reltribeuser);
		}
		return $ret;
	}

	/**
	* count profile_reltribeusers matching a condition
	* 
	* @param object $criteria {@link CriteriaElement} to match
	* @return int count of profile_reltribeusers
	*/
	function getCount($criteria = null)
	{
		$sql = 'SELECT COUNT(*) FROM '.$this->db->prefix('profile_reltribeuser');
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
	* delete profile_reltribeusers matching a set of conditions
	* 
	* @param object $criteria {@link CriteriaElement} 
	* @return bool FALSE if deletion failed
	*/
	function deleteAll($criteria = null)
	{
		$sql = 'DELETE FROM '.$this->db->prefix('profile_reltribeuser');
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
		}
		if (!$result = $this->db->query($sql)) {
			return false;
		}
		return true;
	}
	
	function getTribes($nbtribes, $criteria = null, $shuffle=1)
	{
		$ret = array();
		
		$sql = 'SELECT rel_id, rel_tribe_id, rel_user_uid, tribe_title, tribe_desc, tribe_img, owner_uid FROM '.$this->db->prefix('profile_tribes').', '.$this->db->prefix('profile_reltribeuser');
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
			//attention here this is kind of a hack
			$sql .= " AND tribe_id = rel_tribe_id " ;
			if ($criteria->getSort() != '') {
				$sql .= ' ORDER BY '.$criteria->getSort().' '.$criteria->getOrder();
			}
			$limit = $criteria->getLimit();
			$start = $criteria->getStart();

			$result = $this->db->query($sql, $limit, $start);
			$vetor = array();
			$i=0;

			while ($myrow = $this->db->fetchArray($result)) {

				$vetor[$i]['title']	= $myrow['tribe_title'];
				$vetor[$i]['desc']	= $myrow['tribe_desc'];
				$vetor[$i]['img']	= $myrow['tribe_img'];
				$vetor[$i]['id']	= $myrow['rel_id'];
				$vetor[$i]['uid']	= $myrow['owner_uid'];
				$vetor[$i]['tribe_id']	= $myrow['rel_tribe_id'];
				
				$i++;
			}

			if ($shuffle==1){
				shuffle($vetor);
				$vetor = array_slice($vetor,0,$nbtribes);
			}
			return $vetor;

		}
	}
	
		function getUsersFromTribe($tribeId,$start,$nbUsers,$isShuffle=0)
	{
		$ret = array();
		
		$sql = 'SELECT rel_tribe_id, rel_user_uid, owner_uid, uname, user_avatar, uid FROM '.$this->db->prefix('users').', '.$this->db->prefix('profile_tribes').', '.$this->db->prefix('profile_reltribeuser');
		$sql .= " WHERE rel_user_uid = uid AND rel_tribe_id = tribe_id AND tribe_id =".$tribeId." GROUP BY rel_user_uid " ;
			

			$result = $this->db->query($sql, $nbUsers, $start);
			$ret = array();
			$i=0;

			while ($myrow = $this->db->fetchArray($result)) {

				$ret[$i]['uid']	= $myrow['uid'];
				$ret[$i]['uname']	= $myrow['uname'];
				$ret[$i]['avatar']	= $myrow['user_avatar'];
				$isOwner = ($myrow['rel_user_uid']==$myrow['owner_uid'])?1:0;
				$ret[$i]['isOwner']	= $isOwner;
				$i++;
			}

			if ($isShuffle==1){
				shuffle($ret);
				$ret = array_slice($ret,0,$nbUsers);
			}
			
			return $ret;

		}
	
	
	
}


?>