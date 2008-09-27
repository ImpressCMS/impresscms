<?php
// priv_msgsup.php,v 1
//  ---------------------------------------------------------------- //
// Author: Bruno Barthez	                                           //
// ----------------------------------------------------------------- //

include_once XOOPS_ROOT_PATH."/class/xoopsobject.php";
/**
* priv_msgsup class.  
* $this class is responsible for providing data access mechanisms to the data source 
* of XOOPS user class objects.
*/


class priv_msgsup extends XoopsObject
{ 
	var $db;

// constructor
	function priv_msgsup ($id=null)
	{
		$this->db =& Database::getInstance();
		$this->initVar("msg_id",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("u_id",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("u_name",XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar("u_mimetype",XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar("u_file",XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar("u_weight",XOBJ_DTYPE_INT,null,false,10);
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
		$sql = 'SELECT * FROM '.$this->db->prefix("priv_msgsup").' WHERE msg_id='.$id;
		$myrow = $this->db->fetchArray($this->db->query($sql));
		$this->assignVars($myrow);
		if (!$myrow) {
			$this->setNew();
		}
	}

	function getAllpriv_msgsups($criteria=array(), $asobject=false, $sort="msg_id", $order="ASC", $limit=0, $start=0)
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
			$sql = "SELECT msg_id FROM ".$db->prefix("priv_msgsup")."$where_query ORDER BY $sort $order";
			$result = $db->query($sql,$limit,$start);
			while ( $myrow = $db->fetchArray($result) ) {
				$ret[] = $myrow['priv_msgsup_id'];
			}
		} else {
			$sql = "SELECT * FROM ".$db->prefix("priv_msgsup")."$where_query ORDER BY $sort $order";
			$result = $db->query($sql,$limit,$start);
			while ( $myrow = $db->fetchArray($result) ) {
				$ret[] = new priv_msgsup ($myrow);
			}
		}
		return $ret;
	}
}
// -------------------------------------------------------------------------
// ------------------priv_msgsup user handler class -------------------
// -------------------------------------------------------------------------
/**
* priv_msgsuphandler class.  
* This class provides simple mecanisme for priv_msgsup object
*/

class Xoopspriv_msgsupHandler extends XoopsObjectHandler
{

	/**
	* create a new priv_msgsup
	* 
	* @param bool $isNew flag the new objects as "new"?
	* @return object priv_msgsup
	*/
	function &create($isNew = true)	{
		$priv_msgsup = new priv_msgsup();
		if ($isNew) {
			$priv_msgsup->setNew();
		}
		return $priv_msgsup;
	}

	/**
	* retrieve a priv_msgsup
	* 
	* @param int $id of the priv_msgsup
	* @return mixed reference to the {@link priv_msgsup} object, FALSE if failed
	*/
	function &get($id)	{
			$sql = 'SELECT * FROM '.$this->db->prefix('priv_msgsup').' WHERE msg_id='.$id;
			if (!$result = $this->db->query($sql)) {
				return false;
			}
			$numrows = $this->db->getRowsNum($result);
			if ($numrows == 1) {
				$priv_msgsup = new priv_msgsup();
				$priv_msgsup->assignVars($this->db->fetchArray($result));
				return $priv_msgsup;
			}
				return false;
	}

/**
* insert a new priv_msgsup in the database
* 
* @param object $priv_msgsup reference to the {@link priv_msgsup} object
* @param bool $force
* @return bool FALSE if failed, TRUE if already present and unchanged or successful
*/
	function insert(&$priv_msgsup, $force = false) {
		Global $xoopsConfig;
		if (get_class($priv_msgsup) != 'priv_msgsup') {
				return false;
		}
		if (!$priv_msgsup->isDirty()) {
				return true;
		}
		if (!$priv_msgsup->cleanVars()) {
				return false;
		}
		foreach ($priv_msgsup->cleanVars as $k => $v) {
				${$k} = $v;
		}
		$now = "date_add(now(), interval ".$xoopsConfig['server_TZ']." hour)";
		if ($priv_msgsup->isNew()) {
			// ajout/modification d'un priv_msgsup
			$priv_msgsup = new priv_msgsup();
			$format = "INSERT INTO %s (msg_id, u_id, u_name, u_mimetype, u_file, u_weight)";
			$format .= "VALUES (%u, %u, %s, %s, %s, %u)";
			$sql = sprintf($format , 
			$this->db->prefix('priv_msgsup'), 
			$msg_id
			,$u_id
			,$this->db->quoteString($u_name)
			,$this->db->quoteString($u_mimetype)
			,$this->db->quoteString($u_file)
			,$u_weight
			);
			$force = true;
		} else {
			$format = "UPDATE %s SET ";
			$format .="msg_id=%u, u_id=%u, u_name=%s, u_mimetype=%s, u_file=%s, u_weight=%u";
			$format .=" WHERE msg_id = %u";
			$sql = sprintf($format, $this->db->prefix('priv_msgsup'),
			$msg_id
			,$u_id
			,$this->db->quoteString($u_name)
			,$this->db->quoteString($u_mimetype)
			,$this->db->quoteString($u_file)
			,$u_weight
			, $msg_id);
		}
		if (false != $force) {
			$result = $this->db->queryF($sql);
		} else {
			$result = $this->db->query($sql);
		}
		if (!$result) {
			return false;
		}
		if (empty($msg_id)) {
			$msg_id = $this->db->getInsertId();
		}
		$priv_msgsup->assignVar('msg_id', $msg_id);
		return true;
	}

	/**
	 * delete a priv_msgsup from the database
	 * 
	 * @param object $priv_msgsup reference to the priv_msgsup to delete
	 * @param bool $force
	 * @return bool FALSE if failed.
	 */
	function delete(&$priv_msgsup, $force = false)
	{
		if (get_class($priv_msgsup) != 'priv_msgsup') {
			return false;
		}
		$sql = sprintf("DELETE FROM %s WHERE msg_id = %u", $this->db->prefix("priv_msgsup"), $priv_msgsup->getVar('msg_id'));
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
	* retrieve priv_msgsups from the database
	* 
	* @param object $criteria {@link CriteriaElement} conditions to be met
	* @param bool $id_as_key use the UID as key for the array?
	* @return array array of {@link priv_msgsup} objects
	*/
	function &getObjects($criteria = null, $id_as_key = false)
	{
		$ret = array();
		$limit = $start = 0;
		$sql = 'SELECT * FROM '.$this->db->prefix('priv_msgsup');
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
			$priv_msgsup = new priv_msgsup();
			$priv_msgsup->assignVars($myrow);
			if (!$id_as_key) {
				$ret[] =& $priv_msgsup;
			} else {
				$ret[$myrow['msg_id']] =& $priv_msgsup;
			}
			unset($priv_msgsup);
		}
		return $ret;
	}

	/**
	* count priv_msgsups matching a condition
	* 
	* @param object $criteria {@link CriteriaElement} to match
	* @return int count of priv_msgsups
	*/
	function getCount($criteria = null)
	{
		$sql = 'SELECT COUNT(*) FROM '.$this->db->prefix('priv_msgsup');
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
	* delete priv_msgsups matching a set of conditions
	* 
	* @param object $criteria {@link CriteriaElement} 
	* @return bool FALSE if deletion failed
	*/
	function deleteAll($criteria = null)
	{
		$sql = 'DELETE FROM '.$this->db->prefix('priv_msgsup');
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
		}
		if (!$result = $this->db->query($sql)) {
			return false;
		}
		return true;
	}
}

function mp_upload() {

 global $xoopsDB, $xoopsModule, $xoopsModuleConfig;
 $upid = "0";
//upload 
if ($_FILES) {
 include_once XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->dirname().'/class/uploader.php';
 $allowed_mimetypes = $xoopsModuleConfig['mimetypes'];
 $maxfilesize = $xoopsModuleConfig['mimemax'];
 $uploaddir = XOOPS_ROOT_PATH . "/modules/".$xoopsModule->dirname()."/upload/";
 $uploader = new MPUploader($uploaddir, $allowed_mimetypes, $maxfilesize, null, null);
 $uploader->setPrefix('mp_') ;
 
 foreach( $_FILES as $val ) {
 
if (!empty($val['name'])) { 

 if ($uploader->fetchMedia($val)) {
  if (!$uploader->upload()) {
  $errors = $uploader->getErrors();
  redirect_header("javascript:history.go(-1)",20, $errors);
    }  else {  
    $up_handler  = & xoops_gethandler('priv_msgsup');
    $upid = $up_handler->getCount()+1;
    $up =& $up_handler->create();
    $up->setVar('u_id', $upid);
    $up->setVar('u_name', $val['name']);
    $up->setVar('u_mimetype', $uploader->getMediaType());
    $up->setVar('u_file', $uploader->getSavedFileName());
    $up->setVar('u_weight', $val['size']);
    $erreur = $up_handler->insert($up);
    }  
   } else {  
   $errors = $uploader->getErrors();
   redirect_header("javascript:history.go(-1)",20, $errors);
   $upid = "0";
 } 
 } else { $upid = "0"; }
 }
} 
return $upid;
}

function mp_delupload($filemsg) {

 global $xoopsDB, $xoopsModule, $xoopsModuleConfig;

 //upload 
 $up_handler  = & xoops_gethandler('priv_msgs');
 $criteria = new CriteriaCompo();
 $criteria->add(new Criteria('file_msg', $filemsg));
 $total = $up_handler->getCount($criteria); 
 
 if ($total == "1") {
 $up_handler  = & xoops_gethandler('priv_msgsup');
 $criteria = new CriteriaCompo();
 $criteria->add(new Criteria('u_id', $filemsg));
 $up = $up_handler->getObjects($criteria);
 foreach (array_keys($up) as $i) { 
 $file = XOOPS_ROOT_PATH . "/modules/".$xoopsModule->dirname()."/upload/".$up[$i]->getVar('u_file');
 if (is_file($file)) {
 @unlink($file);
 } }
 
 $erreur = $up_handler->deleteAll($criteria);
 
 }
 return @$erreur;
}
?>