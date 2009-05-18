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
include_once ICMS_ROOT_PATH."/class/uploader.php";      
/**
* profile_audio class.  
* $this class is responsible for providing data access mechanisms to the data source 
* of XOOPS user class objects.
*/


class Audio extends XoopsObject
{ 
	var $db;

// constructor
	function Audio ($id=null)
	{
		$this->db =& Database::getInstance();
		$this->initVar("audio_id",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("title",XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar("author",XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar("url",XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar("uid_owner",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("data_creation",XOBJ_DTYPE_TXTBOX,null,false);
		$this->initVar("data_update",XOBJ_DTYPE_TXTBOX,null,false);
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
		$sql = 'SELECT * FROM '.$this->db->prefix("profile_audio").' WHERE audio_id='.$id;
		$myrow = $this->db->fetchArray($this->db->query($sql));
		$this->assignVars($myrow);
		if (!$myrow) {
			$this->setNew();
		}
	}

	function getAllprofile_audios($criteria=array(), $asobject=false, $sort="audio_id", $order="ASC", $limit=0, $start=0)
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
			$sql = "SELECT audio_id FROM ".$db->prefix("profile_audio")."$where_query ORDER BY $sort $order";
			$result = $db->query($sql,$limit,$start);
			while ( $myrow = $db->fetchArray($result) ) {
				$ret[] = $myrow['profile_audio_id'];
			}
		} else {
			$sql = "SELECT * FROM ".$db->prefix("profile_audio")."$where_query ORDER BY $sort $order";
			$result = $db->query($sql,$limit,$start);
			while ( $myrow = $db->fetchArray($result) ) {
				$ret[] = new Audio ($myrow);
			}
		}
		return $ret;
	}
}
// -------------------------------------------------------------------------
// ------------------profile_audio user handler class -------------------
// -------------------------------------------------------------------------
/**
* profile_audiohandler class.  
* This class provides simple mecanisme for profile_audio object
*/

class ProfileAudioHandler extends XoopsObjectHandler
{

	/**
	* create a new Audio
	* 
	* @param bool $isNew flag the new objects as "new"?
	* @return object profile_audio
	*/
	function &create($isNew = true)	{
		$profile_audio = new Audio();
		if ($isNew) {
			$profile_audio->setNew();
		}
		else{
		$profile_audio->unsetNew();
		}

		
		return $profile_audio;
	}

	/**
	* retrieve a profile_audio
	* 
	* @param int $id of the profile_audio
	* @return mixed reference to the {@link profile_audio} object, FALSE if failed
	*/
	function &get($id)	{
			$sql = 'SELECT * FROM '.$this->db->prefix('profile_audio').' WHERE audio_id='.$id;
			if (!$result = $this->db->query($sql)) {
				return false;
			}
			$numrows = $this->db->getRowsNum($result);
			if ($numrows == 1) {
				$profile_audio = new Audio();
				$profile_audio->assignVars($this->db->fetchArray($result));
				return $profile_audio;
			}
				return false;
	}

/**
* insert a new Audio in the database
* 
* @param object $profile_audio reference to the {@link profile_audio} object
* @param bool $force
* @return bool FALSE if failed, TRUE if already present and unchanged or successful
*/
	function insert(&$profile_audio, $force = false) {
		Global $icmsConfig;
		if (get_class($profile_audio) != 'Audio') {
				return false;
		}
		if (!$profile_audio->isDirty()) {
				return true;
		}
		if (!$profile_audio->cleanVars()) {
				return false;
		}
		foreach ($profile_audio->cleanVars as $k => $v) {
				${$k} = $v;
		}
		$now = "date_add(now(), interval ".$icmsConfig['server_TZ']." hour)";
		if ($profile_audio->isNew()) {
			// ajout/modification d'un profile_audio
			$profile_audio = new Audio();
			$format = "INSERT INTO %s (audio_id, title, author, url, uid_owner, data_creation, data_update)";
			$format .= " VALUES (%u, %s, %s, %s, %u, %s, %s)";
			$sql = sprintf($format , 
			$this->db->prefix('profile_audio'), 
			$audio_id
			,$this->db->quoteString($title)
			,$this->db->quoteString($author)
			,$this->db->quoteString($url)
			,$uid_owner
			,$now
			,$now
			);
			$force = true;
		} else {
			$format = "UPDATE %s SET ";
			$format .="audio_id=%u, title=%s, author=%s, url=%s, uid_owner=%u, data_creation=%s, data_update=%s";
			$format .=" WHERE audio_id = %u";
			$sql = sprintf($format, $this->db->prefix('profile_audio'),
			$audio_id
			,$this->db->quoteString($title)
			,$this->db->quoteString($author)
			,$this->db->quoteString($url)
			,$uid_owner
			,$now
			,$now
			, $audio_id);
		}
		if (false != $force) {
			$result = $this->db->queryF($sql);
		} else {
			$result = $this->db->query($sql);
		}
		if (!$result) {
			return false;
		}
		if (empty($audio_id)) {
			$audio_id = $this->db->getInsertId();
		}
		$profile_audio->assignVar('audio_id', $audio_id);
		return true;
	}

	/**
	 * delete a profile_audio from the database
	 * 
	 * @param object $profile_audio reference to the profile_audio to delete
	 * @param bool $force
	 * @return bool FALSE if failed.
	 */
	function delete(&$profile_audio, $force = false)
	{
		if (get_class($profile_audio) != 'Audio') {
			return false;
		}
		$sql = sprintf("DELETE FROM %s WHERE audio_id = %u", $this->db->prefix("profile_audio"), $profile_audio->getVar('audio_id'));
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
	* retrieve profile_audios from the database
	* 
	* @param object $criteria {@link CriteriaElement} conditions to be met
	* @param bool $id_as_key use the UID as key for the array?
	* @return array array of {@link profile_audio} objects
	*/
	function &getObjects($criteria = null, $id_as_key = false)
	{
		$ret = array();
		$limit = $start = 0;
		$sql = 'SELECT * FROM '.$this->db->prefix('profile_audio');
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
			$profile_audio = new Audio();
			$profile_audio->assignVars($myrow);
			if (!$id_as_key) {
				$ret[] =& $profile_audio;
			} else {
				$ret[$myrow['audio_id']] =& $profile_audio;
			}
			unset($profile_audio);
		}
		return $ret;
	}

	/**
	* count profile_audios matching a condition
	* 
	* @param object $criteria {@link CriteriaElement} to match
	* @return int count of profile_audios
	*/
	function getCount($criteria = null)
	{
		$sql = 'SELECT COUNT(*) FROM '.$this->db->prefix('profile_audio');
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
	* delete profile_audios matching a set of conditions
	* 
	* @param object $criteria {@link CriteriaElement} 
	* @return bool FALSE if deletion failed
	*/
	function deleteAll($criteria = null)
	{
		$sql = 'DELETE FROM '.$this->db->prefix('profile_audio');
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
		}
		if (!$result = $this->db->query($sql)) {
			return false;
		}
		return true;
	}
    
    
    /**
    * Upload the file and Save into database
    * 
    * @param text $title A litle description of the file
    * @param text $path_upload The path to where the file should be uploaded
    * @param text $author the author of the music or audio file
    * @return bool FALSE if upload fails or database fails
    */
    function receiveAudio($title,$path_upload, $author, $maxfilebytes)
    {
        
        global $icmsUser, $xoopsDB, $_POST, $_FILES;
        //busca id do user logado
        $uid = $icmsUser->getVar('uid');
        //create a hash so it does not erase another file
        //$hash1 = date();
        //$hash = substr($hash1,0,4);
        
        // mimetypes and settings put this in admin part later
        $allowed_mimetypes = array( "audio/mp3" , "audio/x-mp3", "audio/mpeg");
        $maxfilesize = $maxfilebytes;
        
        // create the object to upload
        $uploader = new XoopsMediaUploader($path_upload, $allowed_mimetypes, $maxfilesize);
        // fetch the media
        if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
            //lets create a name for it
            $uploader->setPrefix('aud_'.$uid);
            //now let s upload the file
            if (!$uploader->upload()) {
            // if there are errors lets return them
              echo "<div style=\"color:#FF0000; background-color:#FFEAF4; border-color:#FF0000; border-width:thick; border-style:solid; text-align:center\"><p>".$uploader->getErrors()."</p></div>";
              return false;
            } else {
            // now let s create a new object audio and set its variables
            //echo "passei aqui";
            $audio = $this->create();
            $url = $uploader->getSavedFileName();
            $audio->setVar("url",$url);
            $audio->setVar("title",$title);
            $audio->setVar("author",$author);
            $uid = $icmsUser->getVar('uid');
            $audio->setVar("uid_owner",$uid);
            $this->insert($audio);
            $saved_destination = $uploader->getSavedDestination();
            //print_r($_FILES);
            }
        } else {
          echo "<div style=\"color:#FF0000; background-color:#FFEAF4; border-color:#FF0000; border-width:thick; border-style:solid; text-align:center\"><p>".$uploader->getErrors()."</p></div>";
          return false;
        }
        return true;
    }         
}
?>