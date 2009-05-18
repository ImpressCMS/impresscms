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

/**
* Protection against inclusion outside the site 
*/
if (!defined("ICMS_ROOT_PATH")) {
    die("ICMS root path not defined");
}

/**
* Includes of form objects and uploader 
*/
include_once ICMS_ROOT_PATH."/class/uploader.php";
include_once ICMS_ROOT_PATH."/class/xoopsobject.php";
include_once ICMS_ROOT_PATH."/class/xoopsformloader.php";
include_once ICMS_ROOT_PATH."/class/xoopsobject.php";


/**
* profile_video class.  
* $this class is responsible for providing data access mechanisms to the data source 
* of XOOPS user class objects.
*/
class Video extends XoopsObject
{ 
	var $db;

// constructor
	function Video ($id=null)
	{
		$this->db =& Database::getInstance();
		$this->initVar("video_id",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("uid_owner",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("video_desc",XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar("youtube_code",XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar("main_video",XOBJ_DTYPE_TXTBOX, null, false);
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
		$sql = 'SELECT * FROM '.$this->db->prefix("profile_video").' WHERE video_id='.$id;
		$myrow = $this->db->fetchArray($this->db->query($sql));
		$this->assignVars($myrow);
		if (!$myrow) {
			$this->setNew();
		}
	}

	function getAllprofile_videos($criteria=array(), $asobject=false, $sort="video_id", $order="ASC", $limit=0, $start=0)
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
			$sql = "SELECT video_id FROM ".$db->prefix("profile_video")."$where_query ORDER BY $sort $order";
			$result = $db->query($sql,$limit,$start);
			while ( $myrow = $db->fetchArray($result) ) {
				$ret[] = $myrow['profile_video_id'];
			}
		} else {
			$sql = "SELECT * FROM ".$db->prefix("profile_video")."$where_query ORDER BY $sort $order";
			$result = $db->query($sql,$limit,$start);
			while ( $myrow = $db->fetchArray($result) ) {
				$ret[] = new Video ($myrow);
			}
		}
		return $ret;
	}
}
// -------------------------------------------------------------------------
// ------------------profile_video user handler class -------------------
// -------------------------------------------------------------------------
/**
* profile_videohandler class.  
* This class provides simple mecanisme for profile_video object
*/

class ProfileVideoHandler extends XoopsObjectHandler
{

	/**
	* create a new Video
	* 
	* @param bool $isNew flag the new objects as "new"?
	* @return object profile_video
	*/
	function &create($isNew = true)	{
		$profile_video = new Video();
		if ($isNew) {
			$profile_video->setNew();
		}
		else{
		$profile_video->unsetNew();
		}

		
		return $profile_video;
	}

	/**
	* retrieve a profile_video
	* 
	* @param int $id of the profile_video
	* @return mixed reference to the {@link profile_video} object, FALSE if failed
	*/
	function &get($id)	{
			$sql = 'SELECT * FROM '.$this->db->prefix('profile_video').' WHERE video_id='.$id;
			if (!$result = $this->db->query($sql)) {
				return false;
			}
			$numrows = $this->db->getRowsNum($result);
			if ($numrows == 1) {
				$profile_video = new Video();
				$profile_video->assignVars($this->db->fetchArray($result));
				return $profile_video;
			}
				return false;
	}

        /**
	* insert a new Video in the database
	* 
	* @param object $profile_video reference to the {@link profile_video} object
	* @param bool $force
	* @return bool FALSE if failed, TRUE if already present and unchanged or successful
	*/
	function insert(&$profile_video, $force = false) {
		Global $icmsConfig;
		if (get_class($profile_video) != 'Video') {
				return false;
		}
		if (!$profile_video->isDirty()) {
				return true;
		}
		if (!$profile_video->cleanVars()) {
				return false;
		}
		foreach ($profile_video->cleanVars as $k => $v) {
				${$k} = $v;
		}
		$now = "date_add(now(), interval ".$icmsConfig['server_TZ']." hour)";
		if ($profile_video->isNew()) {
			// ajout/modification d'un profile_video
			$profile_video = new Video();
			$format = "INSERT INTO %s (video_id, uid_owner, video_desc, youtube_code, main_video)";
			$format .= "VALUES (%u, %u, %s, %s, %s)";
			$sql = sprintf($format , 
			$this->db->prefix('profile_video'), 
			$video_id
			,$uid_owner
			,$this->db->quoteString($video_desc)
			,$this->db->quoteString($youtube_code)
			,$this->db->quoteString($main_video)
			);
			$force = true;
		} else {
			$format = "UPDATE %s SET ";
			$format .="video_id=%u, uid_owner=%u, video_desc=%s, youtube_code=%s, main_video=%s";
			$format .=" WHERE video_id = %u";
			$sql = sprintf($format, $this->db->prefix('profile_video'),
			$video_id
			,$uid_owner
			,$this->db->quoteString($video_desc)
			,$this->db->quoteString($youtube_code)
			,$this->db->quoteString($main_video)
			, $video_id);
		}
		if (false != $force) {
			$result = $this->db->queryF($sql);
		} else {
			$result = $this->db->query($sql);
		}
		if (!$result) {
			return false;
		}
		if (empty($video_id)) {
			$video_id = $this->db->getInsertId();
		}
		$profile_video->assignVar('video_id', $video_id);
		return true;
	}

	/**
	 * delete a profile_video from the database
	 * 
	 * @param object $profile_video reference to the profile_video to delete
	 * @param bool $force
	 * @return bool FALSE if failed.
	 */
	function delete(&$profile_video, $force = false)
	{
		if (get_class($profile_video) != 'Video') {
			return false;
		}
		$sql = sprintf("DELETE FROM %s WHERE video_id = %u", $this->db->prefix("profile_video"), $profile_video->getVar('video_id'));
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
	* retrieve profile_videos from the database
	* 
	* @param object $criteria {@link CriteriaElement} conditions to be met
	* @param bool $id_as_key use the UID as key for the array?
	* @return array array of {@link profile_video} objects
	*/
	function &getObjects($criteria = null, $id_as_key = false)
	{
		$ret = array();
		$limit = $start = 0;
		$sql = 'SELECT * FROM '.$this->db->prefix('profile_video');
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
			$profile_video = new Video();
			$profile_video->assignVars($myrow);
			if (!$id_as_key) {
				$ret[] =& $profile_video;
			} else {
				$ret[$myrow['video_id']] =& $profile_video;
			}
			unset($profile_video);
		}
		return $ret;
	}

	/**
	* count profile_videos matching a condition
	* 
	* @param object $criteria {@link CriteriaElement} to match
	* @return int count of profile_videos
	*/
	function getCount($criteria = null)
	{
		$sql = 'SELECT COUNT(*) FROM '.$this->db->prefix('profile_video');
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
	* delete profile_videos matching a set of conditions
	* 
	* @param object $criteria {@link CriteriaElement} 
	* @return bool FALSE if deletion failed
	*/
	function deleteAll($criteria = null)
	{
		$sql = 'DELETE FROM '.$this->db->prefix('profile_video');
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
		}
		if (!$result = $this->db->query($sql)) {
			return false;
		}
		return true;
	}
/**
	* Render a form to send videos
	* 
	* @param int $maxbytes the maximum size of a picture
	* @param object $xoopsTpl the one in which the form will be rendered
	* @return bool TRUE
	*
	* obs: Some functions wont work on php 4 so edit lines down under acording to your version
	*/
	function renderFormSubmit($xoopsTpl)
	{
				
		$form 			= new XoopsThemeForm(_MD_PROFILE_ADDFAVORITEVIDEOS, "form_videos", "video_submited.php", "post", true);
		$field_code 	= new XoopsFormText(_MD_PROFILE_YOUTUBECODE, "codigo", 50, 250);
		$field_desc 	= new XoopsFormText(_MD_PROFILE_CAPTION, "caption",50,250);
		$form->setExtra('enctype="multipart/form-data"');
		$button_send 	= new XoopsFormButton("", "submit_button", _MD_PROFILE_ADDVIDEO, "submit");		
		$form->addElement($field_warning);
		$form->addElement($field_code,true);
		$form->addElement($field_desc);		
		$form->addElement($button_send);
		if ( (str_replace('.', '', PHP_VERSION)) > 499 ) {
		  $form->assign($xoopsTpl);
			//$form->display();
		} else {		 
		  $form->display(); //If your server is php 4.4 
		}		
		return true;
	}
/**
	* Render a form to edit the description of the pictures
	* 
	* @param string $caption The description of the picture
	* @param int $cod_img the id of the image in database
	* @param text $filename the url to the thumb of the image so it can be displayed
	* @return bool TRUE
	*/
	function renderFormEdit($caption,$cod_img,$filename)	{
				
		$form 			= new XoopsThemeForm(_MD_PROFILE_EDITDESC, "form_picture", "editdescvideo.php", "post", true);
		$field_desc 	= new XoopsFormDhtmlTextArea(_MD_PROFILE_EDITDESC, $caption,"caption", 15, 55);
		$form->setExtra('enctype="multipart/form-data"');
		$button_send 	= new XoopsFormButton("", "submit_button", _MD_PROFILE_SUBMIT, "submit");
		$field_warning 	= new XoopsFormLabel('<object width="425" height="353">
<param name="movie" value="http://www.youtube.com/v/'.$filename.'"></param>
<param name="wmode" value="transparent"></param>
<embed src="http://www.youtube.com/v/'.$filename.'" type="application/x-shockwave-flash" wmode="transparent" width="425" height="353"></embed>
</object>');
		$field_video_id = new XoopsFormHidden("video_id",$cod_img);
		$field_marker = new XoopsFormHidden("marker",1);
		$form->addElement($field_warning);
		$form->addElement($field_desc);
		$form->addElement($field_video_id,true);
		$form->addElement($field_marker);
		$form->addElement($button_send);
		$form->display();		
		return true;
	}
	
	function unsetAllMainsbyID($uid_owner=null)
	{
		$sql = 'UPDATE '.$this->db->prefix('profile_video').' SET main_video=0 WHERE uid_owner='.$uid_owner;
		
		if (!$result = $this->db->query($sql)) {
			return false;
		}
		return true;
	}
	

}


?>