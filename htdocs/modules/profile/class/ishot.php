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
* profile_ishot class.  
* $this class is responsible for providing data access mechanisms to the data source 
* of XOOPS user class objects.
*/


class Ishot extends XoopsObject
{ 
	var $db;

// constructor
	function Ishot ($id=null)
	{
		$this->db =& Database::getInstance();
		$this->initVar("cod_ishot",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("uid_voter",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("uid_voted",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("ishot",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("date",XOBJ_DTYPE_TXTBOX, null, false);
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
		$sql = 'SELECT * FROM '.$this->db->prefix("profile_ishot").' WHERE cod_ishot='.$id;
		$myrow = $this->db->fetchArray($this->db->query($sql));
		$this->assignVars($myrow);
		if (!$myrow) {
			$this->setNew();
		}
	}

	function getAllprofile_ishots($criteria=array(), $asobject=false, $sort="cod_ishot", $order="ASC", $limit=0, $start=0)
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
			$sql = "SELECT cod_ishot FROM ".$db->prefix("profile_ishot")."$where_query ORDER BY $sort $order";
			$result = $db->query($sql,$limit,$start);
			while ( $myrow = $db->fetchArray($result) ) {
				$ret[] = $myrow['profile_ishot_id'];
			}
		} else {
			$sql = "SELECT * FROM ".$db->prefix("profile_ishot")."$where_query ORDER BY $sort $order";
			$result = $db->query($sql,$limit,$start);
			while ( $myrow = $db->fetchArray($result) ) {
				$ret[] = new Ishot ($myrow);
			}
		}
		return $ret;
	}
}
// -------------------------------------------------------------------------
// ------------------profile_ishot user handler class -------------------
// -------------------------------------------------------------------------
/**
* profile_ishothandler class.  
* This class provides simple mecanisme for profile_ishot object
*/

class ProfileIshotHandler extends XoopsObjectHandler
{

	/**
	* create a new Ishot
	* 
	* @param bool $isNew flag the new objects as "new"?
	* @return object profile_ishot
	*/
	function &create($isNew = true)	{
		$profile_ishot = new Ishot();
		if ($isNew) {
			$profile_ishot->setNew();
		}
		else{
		$profile_ishot->unsetNew();
		}

		
		return $profile_ishot;
	}

	/**
	* retrieve a profile_ishot
	* 
	* @param int $id of the profile_ishot
	* @return mixed reference to the {@link profile_ishot} object, FALSE if failed
	*/
	function &get($id)	{
			$sql = 'SELECT * FROM '.$this->db->prefix('profile_ishot').' WHERE cod_ishot='.$id;
			if (!$result = $this->db->query($sql)) {
				return false;
			}
			$numrows = $this->db->getRowsNum($result);
			if ($numrows == 1) {
				$profile_ishot = new Ishot();
				$profile_ishot->assignVars($this->db->fetchArray($result));
				return $profile_ishot;
			}
				return false;
	}

/**
* insert a new Ishot in the database
* 
* @param object $profile_ishot reference to the {@link profile_ishot} object
* @param bool $force
* @return bool FALSE if failed, TRUE if already present and unchanged or successful
*/
	function insert(&$profile_ishot, $force = false) {
		Global $icmsConfig;
		if (get_class($profile_ishot) != 'Ishot') {
				return false;
		}
		if (!$profile_ishot->isDirty()) {
				return true;
		}
		if (!$profile_ishot->cleanVars()) {
				return false;
		}
		foreach ($profile_ishot->cleanVars as $k => $v) {
				${$k} = $v;
		}
		$now = "date_add(now(), interval ".$icmsConfig['server_TZ']." hour)";
		if ($profile_ishot->isNew()) {
			// ajout/modification d'un profile_ishot
			$profile_ishot = new Ishot();
			$format = "INSERT INTO %s (cod_ishot, uid_voter, uid_voted, ishot, date)";
			$format .= "VALUES (%u, %u, %u, %u, %s)";
			$sql = sprintf($format , 
			$this->db->prefix('profile_ishot'), 
			$cod_ishot
			,$uid_voter
			,$uid_voted
			,$ishot
			,$this->db->quoteString($date)
			);
			$force = true;
		} else {
			$format = "UPDATE %s SET ";
			$format .="cod_ishot=%u, uid_voter=%u, uid_voted=%u, ishot=%u, date=%s";
			$format .=" WHERE cod_ishot = %u";
			$sql = sprintf($format, $this->db->prefix('profile_ishot'),
			$cod_ishot
			,$uid_voter
			,$uid_voted
			,$ishot
			,$this->db->quoteString($date)
			, $cod_ishot);
		}
		if (false != $force) {
			$result = $this->db->queryF($sql);
		} else {
			$result = $this->db->query($sql);
		}
		if (!$result) {
			return false;
		}
		if (empty($cod_ishot)) {
			$cod_ishot = $this->db->getInsertId();
		}
		$profile_ishot->assignVar('cod_ishot', $cod_ishot);
		return true;
	}

	/**
	 * delete a profile_ishot from the database
	 * 
	 * @param object $profile_ishot reference to the profile_ishot to delete
	 * @param bool $force
	 * @return bool FALSE if failed.
	 */
	function delete(&$profile_ishot, $force = false)
	{
		if (get_class($profile_ishot) != 'Ishot') {
			return false;
		}
		$sql = sprintf("DELETE FROM %s WHERE cod_ishot = %u", $this->db->prefix("profile_ishot"), $profile_ishot->getVar('cod_ishot'));
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
	* retrieve profile_ishots from the database
	* 
	* @param object $criteria {@link CriteriaElement} conditions to be met
	* @param bool $id_as_key use the UID as key for the array?
	* @return array array of {@link profile_ishot} objects
	*/
	function &getObjects($criteria = null, $id_as_key = false)
	{
		$ret = array();
		$limit = $start = 0;
		$sql = 'SELECT * FROM '.$this->db->prefix('profile_ishot');
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
			$profile_ishot = new Ishot();
			$profile_ishot->assignVars($myrow);
			if (!$id_as_key) {
				$ret[] =& $profile_ishot;
			} else {
				$ret[$myrow['cod_ishot']] =& $profile_ishot;
			}
			unset($profile_ishot);
		}
		return $ret;
	}

	/**
	* count profile_ishots matching a condition
	* 
	* @param object $criteria {@link CriteriaElement} to match
	* @return int count of profile_ishots
	*/
	function getCount($criteria = null)
	{
		$sql = 'SELECT COUNT(*) FROM '.$this->db->prefix('profile_ishot');
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
	* delete profile_ishots matching a set of conditions
	* 
	* @param object $criteria {@link CriteriaElement} 
	* @return bool FALSE if deletion failed
	*/
	function deleteAll($criteria = null)
	{
		$sql = 'DELETE FROM '.$this->db->prefix('profile_ishot');
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
		}
		if (!$result = $this->db->query($sql)) {
			return false;
		}
		return true;
	}

	function getHottest($criteria = null)
	{


		$sql = 'SELECT DISTINCTROW uname, user_avatar, uid_voted, COUNT(cod_ishot) AS qtd FROM '.$this->db->prefix('profile_ishot').', '.$this->db->prefix('users');
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
		}
		//attention here this is kind of a hack
		$sql .= " AND uid = uid_voted";
		if ($criteria->getGroupby() != '') {
			$sql .= $criteria->getGroupby();
		}
		if ($criteria->getSort() != '') {
			$sql .= ' ORDER BY '.$criteria->getSort().' '.$criteria->getOrder();
		}
		$limit = $criteria->getLimit();
		$start = $criteria->getStart();
		
		$result = $this->db->query($sql, $limit, $start);
		$vetor = array();
		$i=0;
		while ($myrow = $this->db->fetchArray($result)) {
			
			$vetor[$i]['qtd']= $myrow['qtd'];
			$vetor[$i]['uid_voted']= $myrow['uid_voted'];
			$vetor[$i]['uname']= $myrow['uname'];
			$vetor[$i]['user_avatar']= $myrow['user_avatar'];
			$i++;
		}
		
		
		return $vetor;
	} 

function getHotFriends($criteria = null, $id_as_key = false)
	{
		$ret = array();
		$limit = $start = 0;
		$sql = 'SELECT uname, user_avatar, uid_voted FROM '.$this->db->prefix('profile_ishot').', '.$this->db->prefix('users');;
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
		//attention here this is kind of a hack
		$sql .= " AND uid = uid_voted AND ishot=1" ;
		if ($criteria->getSort() != '') {
			$sql .= ' ORDER BY '.$criteria->getSort().' '.$criteria->getOrder();
		}
		$limit = $criteria->getLimit();
		$start = $criteria->getStart();
		
		$result = $this->db->query($sql, $limit, $start);
		$vetor = array();
		$i=0;
		while ($myrow = $this->db->fetchArray($result)) {
			
			$vetor[$i]['uid_voted']= $myrow['uid_voted'];
			$vetor[$i]['uname']= $myrow['uname'];
			$vetor[$i]['user_avatar']= $myrow['user_avatar'];
			$i++;
		}
		
		
		return $vetor;

		}
	}
}


?>