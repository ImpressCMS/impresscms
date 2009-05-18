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
include_once ICMS_ROOT_PATH."/class/module.textsanitizer.php";
/**
* profile_scraps class.  
* $this class is responsible for providing data access mechanisms to the data source 
* of XOOPS user class objects.
*/


class Scraps extends XoopsObject
{ 
	var $db;

// constructor
	function Scraps ($id=null)
	{
		$this->db =& Database::getInstance();
		$this->initVar("scrap_id",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("scrap_text",XOBJ_DTYPE_TXTAREA, null, false);
		$this->initVar("scrap_from",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("scrap_to",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("private",XOBJ_DTYPE_INT,null,false,10);
		
		
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
		$sql = 'SELECT * FROM '.$this->db->prefix("profile_scraps").' WHERE scrap_id='.$id;
		$myrow = $this->db->fetchArray($this->db->query($sql));
		$this->assignVars($myrow);
		if (!$myrow) {
			$this->setNew();
		}
	}

	function getAllprofile_scrapss($criteria=array(), $asobject=false, $sort="scrap_id", $order="ASC", $limit=0, $start=0)
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
			$sql = "SELECT scrap_id FROM ".$db->prefix("profile_scraps")."$where_query ORDER BY $sort $order";
			$result = $db->query($sql,$limit,$start);
			while ( $myrow = $db->fetchArray($result) ) {
				$ret[] = $myrow['profile_scraps_id'];
			}
		} else {
			$sql = "SELECT * FROM ".$db->prefix("profile_scraps")."$where_query ORDER BY $sort $order";
			$result = $db->query($sql,$limit,$start);
			while ( $myrow = $db->fetchArray($result) ) {
				$ret[] = new Scraps ($myrow);
			}
		}
		return $ret;
	}
}
// -------------------------------------------------------------------------
// ------------------profile_scraps user handler class -------------------
// -------------------------------------------------------------------------
/**
* profile_scrapshandler class.  
* This class provides simple mecanisme for profile_scraps object
*/

class ProfileScrapsHandler extends XoopsObjectHandler
{

	/**
	* create a new Scraps
	* 
	* @param bool $isNew flag the new objects as "new"?
	* @return object profile_scraps
	*/
	function &create($isNew = true)	{
		$profile_scraps = new Scraps();
		if ($isNew) {
			$profile_scraps->setNew();
		}
		else{
		$profile_scraps->unsetNew();
		}

		
		return $profile_scraps;
	}

	/**
	* retrieve a profile_scraps
	* 
	* @param int $id of the profile_scraps
	* @return mixed reference to the {@link profile_scraps} object, FALSE if failed
	*/
	function &get($id)	{
			$sql = 'SELECT * FROM '.$this->db->prefix('profile_scraps').' WHERE scrap_id='.$id;
			if (!$result = $this->db->query($sql)) {
				return false;
			}
			$numrows = $this->db->getRowsNum($result);
			if ($numrows == 1) {
				$profile_scraps = new Scraps();
				$profile_scraps->assignVars($this->db->fetchArray($result));
				return $profile_scraps;
			}
				return false;
	}

/**
* insert a new Scraps in the database
* 
* @param object $profile_scraps reference to the {@link profile_scraps} object
* @param bool $force
* @return bool FALSE if failed, TRUE if already present and unchanged or successful
*/
	function insert(&$profile_scraps, $force = false) {
		Global $icmsConfig;
		if (get_class($profile_scraps) != 'Scraps') {
				return false;
		}
		if (!$profile_scraps->isDirty()) {
				return true;
		}
		if (!$profile_scraps->cleanVars()) {
				return false;
		}
		foreach ($profile_scraps->cleanVars as $k => $v) {
				${$k} = $v;
		}
		$now = "date_add(now(), interval ".$icmsConfig['server_TZ']." hour)";
		if ($profile_scraps->isNew()) {
			// ajout/modification d'un profile_scraps
			$profile_scraps = new Scraps();
			$format = "INSERT INTO %s (scrap_id, scrap_text, scrap_from, scrap_to, private)";
			$format .= "VALUES (%u, %s, %u, %u, %u)";
			$sql = sprintf($format , 
			$this->db->prefix('profile_scraps'), 
			$scrap_id
			,$this->db->quoteString($scrap_text)
			,$scrap_from
			,$scrap_to
			,$private
			
			);
			$force = true;
		} else {
			$format = "UPDATE %s SET ";
			$format .="scrap_id=%u, scrap_text=%s, scrap_from=%u, scrap_to=%u, private=%u";
			$format .=" WHERE scrap_id = %u";
			$sql = sprintf($format, $this->db->prefix('profile_scraps'),
			$scrap_id
			,$this->db->quoteString($scrap_text)
			,$scrap_from
			,$scrap_to
			,$private
			
			, $scrap_id);
		}
		if (false != $force) {
			$result = $this->db->queryF($sql);
		} else {
			$result = $this->db->query($sql);
		}
		if (!$result) {
			return false;
		}
		if (empty($scrap_id)) {
			$scrap_id = $this->db->getInsertId();
		}
		$profile_scraps->assignVar('scrap_id', $scrap_id);
		return true;
	}

	/**
	 * delete a profile_scraps from the database
	 * 
	 * @param object $profile_scraps reference to the profile_scraps to delete
	 * @param bool $force
	 * @return bool FALSE if failed.
	 */
	function delete(&$profile_scraps, $force = false)
	{
		if (get_class($profile_scraps) != 'Scraps') {
			return false;
		}
		$sql = sprintf("DELETE FROM %s WHERE scrap_id = %u", $this->db->prefix("profile_scraps"), $profile_scraps->getVar('scrap_id'));
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
	* retrieve profile_scrapss from the database
	* 
	* @param object $criteria {@link CriteriaElement} conditions to be met
	* @param bool $id_as_key use the UID as key for the array?
	* @return array array of {@link profile_scraps} objects
	*/
	function &getObjects($criteria = null, $id_as_key = false)
	{
		$ret = array();
		$limit = $start = 0;
		$sql = 'SELECT * FROM '.$this->db->prefix('profile_scraps');
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
			$profile_scraps = new Scraps();
			$profile_scraps->assignVars($myrow);
			if (!$id_as_key) {
				$ret[] =& $profile_scraps;
			} else {
				$ret[$myrow['scrap_id']] =& $profile_scraps;
			}
			unset($profile_scraps);
		}
		return $ret;
	}

	/**
	* count profile_scrapss matching a condition
	* 
	* @param object $criteria {@link CriteriaElement} to match
	* @return int count of profile_scrapss
	*/
	function getCount($criteria = null)
	{
		$sql = 'SELECT COUNT(*) FROM '.$this->db->prefix('profile_scraps');
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
	* delete profile_scrapss matching a set of conditions
	* 
	* @param object $criteria {@link CriteriaElement} 
	* @return bool FALSE if deletion failed
	*/
	function deleteAll($criteria = null)
	{
		$sql = 'DELETE FROM '.$this->db->prefix('profile_scraps');
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
		}
		if (!$result = $this->db->query($sql)) {
			return false;
		}
		return true;
	}
	
	
	function getScraps($nbscraps, $criteria)
	{
		$myts = new MyTextSanitizer();
		$ret = array();
		$sql = 'SELECT scrap_id, uid, uname, user_avatar, scrap_from, scrap_text FROM '.$this->db->prefix('profile_scraps').', '.$this->db->prefix('users');
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
		//attention here this is kind of a hack
		$sql .= " AND uid = scrap_from" ;
		if ($criteria->getSort() != '') {
			$sql .= ' ORDER BY '.$criteria->getSort().' '.$criteria->getOrder();
		}
		$limit = $criteria->getLimit();
		$start = $criteria->getStart();
		
		$result = $this->db->query($sql, $limit, $start);
		$vetor = array();
		$i=0;
		
		while ($myrow = $this->db->fetchArray($result)) {
			
			
			$vetor[$i]['uid']= $myrow['uid'];
			$vetor[$i]['uname']= $myrow['uname'];
			$vetor[$i]['user_avatar']= $myrow['user_avatar'];
            $temptext = $myts->xoopsCodeDecode($myrow['scrap_text'],1);
			$vetor[$i]['text'] = $myts->nl2Br($temptext);
			$vetor[$i]['id']= $myrow['scrap_id'];
			
			$i++;
		}
		
		return $vetor;

		}
	}
}


?>