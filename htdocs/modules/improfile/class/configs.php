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
* profile_configs class.  
* $this class is responsible for providing data access mechanisms to the data source 
* of XOOPS user class objects.
*/


class Configs extends XoopsObject
{ 
	var $db;

// constructor
	function Configs ($id=null)
	{
		$this->db =& Database::getInstance();
		$this->initVar("config_id",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("config_uid",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("pictures",XOBJ_DTYPE_INT,null,false,10);
        $this->initVar("audio",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("videos",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("tribes",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("scraps",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("friends",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("profile_contact",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("profile_general",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("profile_stats",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("suspension",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("backup_password",XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar("backup_email",XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar("end_suspension",XOBJ_DTYPE_TXTBOX, null, false);
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
		$sql = 'SELECT * FROM '.$this->db->prefix("profile_configs").' WHERE config_id='.$id;
		$myrow = $this->db->fetchArray($this->db->query($sql));
		$this->assignVars($myrow);
		if (!$myrow) {
			$this->setNew();
		}
	}

	function getAllprofile_configss($criteria=array(), $asobject=false, $sort="config_id", $order="ASC", $limit=0, $start=0)
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
			$sql = "SELECT config_id FROM ".$db->prefix("profile_configs")."$where_query ORDER BY $sort $order";
			$result = $db->query($sql,$limit,$start);
			while ( $myrow = $db->fetchArray($result) ) {
				$ret[] = $myrow['profile_configs_id'];
			}
		} else {
			$sql = "SELECT * FROM ".$db->prefix("profile_configs")."$where_query ORDER BY $sort $order";
			$result = $db->query($sql,$limit,$start);
			while ( $myrow = $db->fetchArray($result) ) {
				$ret[] = new Configs ($myrow);
			}
		}
		return $ret;
	}
}
// -------------------------------------------------------------------------
// ------------------profile_configs user handler class -------------------
// -------------------------------------------------------------------------
/**
* profile_configshandler class.  
* This class provides simple mecanisme for profile_configs object
*/

class ProfileConfigsHandler extends XoopsObjectHandler
{

	/**
	* create a new Configs
	* 
	* @param bool $isNew flag the new objects as "new"?
	* @return object profile_configs
	*/
	function &create($isNew = true)	{
		$profile_configs = new Configs();
		if ($isNew) {
			$profile_configs->setNew();
		}
		else{
		$profile_configs->unsetNew();
		}

		
		return $profile_configs;
	}

	/**
	* retrieve a profile_configs
	* 
	* @param int $id of the profile_configs
	* @return mixed reference to the {@link profile_configs} object, FALSE if failed
	*/
	function &get($id)	{
			$sql = 'SELECT * FROM '.$this->db->prefix('profile_configs').' WHERE config_id='.$id;
			if (!$result = $this->db->query($sql)) {
				return false;
			}
			$numrows = $this->db->getRowsNum($result);
			if ($numrows == 1) {
				$profile_configs = new Configs();
				$profile_configs->assignVars($this->db->fetchArray($result));
				return $profile_configs;
			}
				return false;
	}

/**
* insert a new Configs in the database
* 
* @param object $profile_configs reference to the {@link profile_configs} object
* @param bool $force
* @return bool FALSE if failed, TRUE if already present and unchanged or successful
*/
	function insert(&$profile_configs, $force = false) {
		Global $xoopsConfig;
		if (get_class($profile_configs) != 'Configs') {
				return false;
		}
		if (!$profile_configs->isDirty()) {
				return true;
		}
		if (!$profile_configs->cleanVars()) {
				return false;
		}
		foreach ($profile_configs->cleanVars as $k => $v) {
				${$k} = $v;
		}
		$now = "date_add(now(), interval ".$xoopsConfig['server_TZ']." hour)";
		if ($profile_configs->isNew()) {
			// ajout/modification d'un profile_configs
			$profile_configs = new Configs();
			$format = "INSERT INTO %s (config_id, config_uid, pictures, audio, videos, tribes, scraps, friends, profile_contact, profile_general, profile_stats, suspension, backup_password, backup_email, end_suspension)";
			$format .= "VALUES (%u, %u, %u, %u, %u, %u, %u, %u, %u, %u, %u, %u, %s, %s, %s)";
			$sql = sprintf($format , 
			$this->db->prefix('profile_configs'), 
			$config_id
			,$config_uid
			,$pictures
            ,$audio
			,$videos
			,$tribes
			,$scraps
			,$friends
			,$profile_contact
			,$profile_general
			,$profile_stats
			,$suspension
			,$this->db->quoteString($backup_password)
			,$this->db->quoteString($backup_email)
			,$this->db->quoteString($end_suspension)
			);
			$force = true;
		} else {
			$format = "UPDATE %s SET ";
			$format .="config_id=%u, config_uid=%u, pictures=%u, audio=%u, videos=%u, tribes=%u, scraps=%u, friends=%u, profile_contact=%u, profile_general=%u, profile_stats=%u, suspension=%u, backup_password=%s, backup_email=%s, end_suspension=%s";
			$format .=" WHERE config_id = %u";
			$sql = sprintf($format, $this->db->prefix('profile_configs'),
			$config_id
			,$config_uid
			,$pictures
            ,$audio
			,$videos
			,$tribes
			,$scraps
			,$friends
			,$profile_contact
			,$profile_general
			,$profile_stats
			,$suspension
			,$this->db->quoteString($backup_password)
			,$this->db->quoteString($backup_email)
			,$this->db->quoteString($end_suspension)
			, $config_id);
		}
		if (false != $force) {
			$result = $this->db->queryF($sql);
		} else {
			$result = $this->db->query($sql);
		}
		if (!$result) {
			return false;
		}
		if (empty($config_id)) {
			$config_id = $this->db->getInsertId();
		}
		$profile_configs->assignVar('config_id', $config_id);
		return true;
	}

	/**
	 * delete a profile_configs from the database
	 * 
	 * @param object $profile_configs reference to the profile_configs to delete
	 * @param bool $force
	 * @return bool FALSE if failed.
	 */
	function delete(&$profile_configs, $force = false)
	{
		if (get_class($profile_configs) != 'Configs') {
			return false;
		}
		$sql = sprintf("DELETE FROM %s WHERE config_id = %u", $this->db->prefix("profile_configs"), $profile_configs->getVar('config_id'));
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
	* retrieve profile_configss from the database
	* 
	* @param object $criteria {@link CriteriaElement} conditions to be met
	* @param bool $id_as_key use the UID as key for the array?
	* @return array array of {@link profile_configs} objects
	*/
	function &getObjects($criteria = null, $id_as_key = false)
	{
		$ret = array();
		$limit = $start = 0;
		$sql = 'SELECT * FROM '.$this->db->prefix('profile_configs');
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
			$profile_configs = new Configs();
			$profile_configs->assignVars($myrow);
			if (!$id_as_key) {
				$ret[] =& $profile_configs;
			} else {
				$ret[$myrow['config_id']] =& $profile_configs;
			}
			unset($profile_configs);
		}
		return $ret;
	}

	/**
	* count profile_configss matching a condition
	* 
	* @param object $criteria {@link CriteriaElement} to match
	* @return int count of profile_configss
	*/
	function getCount($criteria = null)
	{
		$sql = 'SELECT COUNT(*) FROM '.$this->db->prefix('profile_configs');
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
	* delete profile_configss matching a set of conditions
	* 
	* @param object $criteria {@link CriteriaElement} 
	* @return bool FALSE if deletion failed
	*/
	function deleteAll($criteria = null)
	{
		$sql = 'DELETE FROM '.$this->db->prefix('profile_configs');
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