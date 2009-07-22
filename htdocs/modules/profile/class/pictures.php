<?php

/**
* Classes responsible for managing profile pictures objects
*
* @copyright	GNU General Public License (GPL)
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.3
* @author		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
* @package		profile
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

// including the IcmsPersistabelSeoObject
include_once ICMS_ROOT_PATH . '/kernel/icmspersistableseoobject.php';
include_once(ICMS_ROOT_PATH . '/modules/profile/include/functions.php');
include ICMS_LIBRARIES_PATH.'/wideimage/lib/WideImage.inc.php';

class ProfilePictures extends IcmsPersistableSeoObject {

	/**
	 * Constructor
	 *
	 * @param object $handler ProfilePictureHandler object
	 */
	public function __construct(& $handler) {
		global $icmsConfig;

		$this->IcmsPersistableObject($handler);

		$this->quickInitVar('pictures_id', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('title', XOBJ_DTYPE_TXTBOX, true);
		$this->quickInitVar('creation_time', XOBJ_DTYPE_LTIME, false);
		$this->quickInitVar('update_time', XOBJ_DTYPE_TXTBOX, false);
		$this->quickInitVar('uid_owner', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('url', XOBJ_DTYPE_TXTBOX, true);
		$this->quickInitVar('private', XOBJ_DTYPE_TXTBOX, false);
		$this->initCommonVar('counter', false);
		$this->initCommonVar('dohtml', false, true);
		$this->initCommonVar('dobr', false, true);
		$this->initCommonVar('doimage', false, true);
		$this->initCommonVar('dosmiley', false, true);

		$this->setControl('uid_owner', 'user');
		$this->setControl('url', 'image');
		$this->setControl('private', 'yesno');
		$this->hideFieldFromForm('creation_time');
		$this->hideFieldFromForm('update_time');

		$this->IcmsPersistableSeoObject();
	}

	/**
	 * Overriding the IcmsPersistableObject::getVar method to assign a custom method on some
	 * specific fields to handle the value before returning it
	 *
	 * @param str $key key of the field
	 * @param str $format format that is requested
	 * @return mixed value of the field that is requested
	 */
	function getVar($key, $format = 's') {
		if ($format == 's' && in_array($key, array ())) {
			return call_user_func(array ($this,	$key));
		}
		return parent :: getVar($key, $format);
	}
	function getProfilePicture() {
		$ret = '<a href="' . ICMS_URL . '/uploads/profile/pictures/resized_' . $this->getVar ( 'url' ) . '" rel="lightbox" title="' . $this->getVar ( 'title' ) . '">
          <img class="thumb" src="' . ICMS_URL . '/uploads/profile/pictures/thumb_' . $this->getVar ( 'url' ) . '" rel="lightbox" title="' . $this->getVar ( 'title' ) . '" />
        </a>';
		return $ret;
	}
	
	function getPictureSender() {
		return icms_getLinkedUnameFromId($this->getVar('uid_owner', 'e'));
	}
	/**
	 * Check to see wether the current user can edit or delete this picture
	 *
	 * @return bool true if he can, false if not
	 */
	function userCanEditAndDelete() {
		global $icmsUser, $profile_isAdmin;
		if (!is_object($icmsUser)) {
			return false;
		}
		if ($profile_isAdmin) {
			return true;
		}
		return $this->getVar('uid_owner', 'e') == $icmsUser->uid();
	}

	/**
	 * Check to see wether the current user can view this picture
	 *
	 * @return bool true if he can, false if not
	 */
	function userCanView() {
		if($this->getVar('private', 'e') == 0 ){
			return true;
		}
		return false;
	}

	/**
	 * Overridding IcmsPersistable::toArray() method to add a few info
	 *
	 * @return array of picture info
	 */
	function toArray() {
		$ret = parent :: toArray();
		$ret['creation_time'] = formatTimestamp($this->getVar('creation_time', 'e'), 'm');
		$ret['picture_content'] = $this->getProfilePicture();
		$ret['picture_title'] = $this->getVar('title','e');
		$ret['editItemLink'] = $this->getEditItemLink(false, true, true);
		$ret['deleteItemLink'] = $this->getDeleteItemLink(false, true, true);
		$ret['userCanEditAndDelete'] = $this->userCanEditAndDelete();
		$ret['userCanView'] = $this->userCanView();
		$ret['picture_senderid'] = $this->getVar('uid_owner','e');
		$ret['picture_sender_link'] = $this->getPictureSender();
		return $ret;
	}
}
class ProfilePicturesHandler extends IcmsPersistableObjectHandler {

	/**
	 * Constructor
	 */
	public function __construct(& $db) {
		global $icmsModuleConfig;
		$this->IcmsPersistableObjectHandler($db, 'pictures', 'pictures_id', 'title', '', 'profile');
		$this->setUploaderConfig(false, array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png'), $icmsModuleConfig['maxfilesize'], $icmsModuleConfig['max_original_width'], $icmsModuleConfig['max_original_height']);
	}
	

	/**
	 * Create the criteria that will be used by getPictures and getPicturesCount
	 *
	 * @param int $start to which record to start
	 * @param int $limit limit of pictures to return
	 * @param int $uid_owner if specifid, only the pictures of this user will be returned
	 * @param int $picture_id ID of a single picture to retrieve
	 * @return CriteriaCompo $criteria
	 */
	function getPicturesCriteria($start = 0, $limit = 0, $uid_owner = false, $picture_id = false) {
		global $icmsUser;

		$criteria = new CriteriaCompo();
		if ($start) {
			$criteria->setStart($start);
		}
		if ($limit) {
			$criteria->setLimit(intval($limit));
		}
		$criteria->setSort('creation_time');
		$criteria->setOrder('DESC');

		$criteria->add(new Criteria('private', 0));
		
		if ($uid_owner) {
			$criteria->add(new Criteria('uid_owner', $uid_owner));
		}
		if ($picture_id) {
			$criteria->add(new Criteria('pictures_id', $picture_id));
		}
		return $criteria;
	}

	/**
	 * Get single picture object
	 *
	 * @param int $pictures_id
	 * @return object ProfilePicture object
	 */
	function getPicture($pictures_id=false, $uid_owner=false) {
		$ret = $this->getPictures(0, 0, $uid_owner, $pictures_id);
		return isset($ret[$pictures_id]) ? $ret[$pictures_id] : false;
	}

	/**
	 * Get pictures as array, ordered by creation_time DESC
	 *
	 * @param int $start to which record to start
	 * @param int $limit max pictures to display
	 * @param int $uid_owner if specifid, only the picture of this user will be returned
	 * @param int $pictures_id ID of a single picture to retrieve
	 * @return array of pictures
	 */
	function getPictures($start = 0, $limit = 0, $uid_owner = false, $pictures_id = false) {
		$criteria = $this->getPicturesCriteria($start, $limit, $uid_owner, $pictures_id);
		$ret = $this->getObjects($criteria, true, false);
		return $ret;
	}

	/**
	* Resize a picture and save it to $path_upload
	* 
	* @param text $img the path to the file
	* @param int $width the width in pixels that the pic will have
	* @param int $height the height in pixels that the pic will have
	* @param text $path_upload The path to where the files should be saved after resizing
	* @param text $prefix The prefix used to recognize files and avoid multiple files.
	* @return nothing
	*/	
	function imageResizer($img, $width=320, $height=240, $path_upload=ICMS_UPLOAD_PATH, $prefix='') {
		$prefix = (isset($prefix) && $prefix != '')?$prefix:time();
		$path = pathinfo($img);
		$img = wiImage::load($img);
		$img->resize($width, $height)->saveToFile($path_upload.'/'.$prefix.'_'.$path['basename']);
	}
	
	/**
	* Resize a picture and save it to $path_upload
	* 
	* @param text $img the path to the file
	* @param text $path_upload The path to where the files should be saved after resizing
	* @param int $thumbwidth the width in pixels that the thumbnail will have
	* @param int $thumbheight the height in pixels that the thumbnail will have
	* @param int $pictwidth the width in pixels that the pic will have
	* @param int $pictheight the height in pixels that the pic will have
	* @return nothing
	*/	
	function resizeImage($img, $thumbwidth, $thumbheight, $pictwidth, $pictheight,$path_upload) {
		$this->imageResizer($img, $thumbwidth, $thumbheight, $path_upload, 'thumb');
		$this->imageResizer($img, $pictwidth, $pictheight, $path_upload, 'resized');
	}
	
	function getLastPictures($limit)
	{
		$ret = array();
		
		$sql = 'SELECT uname, t.uid_owner, t.url FROM '.$this->table.' AS t, '.$this->db->prefix('users');
		
		$sql .= " WHERE uid_owner = uid AND private=0 ORDER BY cod_img DESC" ;
		$result = $this->db->query($sql, $limit, 0);
		$answer = array();
		$i=0;
		while ($myrow = $this->db->fetchArray($result)) {
			
			$answer[$i]['uid_voted']= $myrow['uid_owner'];
			$answer[$i]['uname']= $myrow['uname'];
			$answer[$i]['user_avatar']= $myrow['url'];
			$i++;
		}
				
		return $answer;

	}
	
		function getLastPicturesForBlock($limit)
	{
		$ret = array();
		
		$sql = 'SELECT uname, t.uid_owner, t.url, t.title FROM '.$this->table.' AS t, '.$this->db->prefix('users');
		
		$sql .= " WHERE uid_owner = uid AND private=0 ORDER BY cod_img DESC" ;
		$result = $this->db->query($sql, $limit, 0);
		$answer = array();
		$i=0;
		while ($myrow = $this->db->fetchArray($result)) {
			
			$answer[$i]['uid_voted']= $myrow['uid_owner'];
			$answer[$i]['uname']= $myrow['uname'];
			$answer[$i]['img_filename']= $myrow['url'];
			$answer[$i]['caption']= $myrow['title'];
			
			$i++;
		}
				
		return $answer;

	}
	
	
	/**
	* Resize a picture and save it to $path_upload
	* 
	* @param text $img the path to the file
	* @param text $path_upload The path to where the files should be saved after resizing
	* @param int $thumbwidth the width in pixels that the thumbnail will have
	* @param int $thumbheight the height in pixels that the thumbnail will have
	* @param int $pictwidth the width in pixels that the pic will have
	* @param int $pictheight the height in pixels that the pic will have
	* @return nothing
	*/	
	function makeAvatar($img, $uid) {
		global $icmsConfigUser;
		$path = pathinfo($img);
		$prefix = date();
		$user_avatar = $prefix.'_'.$path['basename'];
		$this->imageResizer($img, $icmsConfigUser['avatar_width'], $icmsConfigUser['avatar_height'], false, $prefix);
		$sql = sprintf("UPDATE %s SET user_avatar = %s WHERE uid = '%u'", $this->db->prefix('users'), $user_avatar, intval($uid));
		$this->query($sql);
	}
	
	/**
	 * Check wether the current user can submit a new picture or not
	 *
	 * @return bool true if he can false if not
	 */
	function userCanSubmit() {
		global $icmsUser;
		if (!is_object($icmsUser)) {
			return false;
		}
		return true;
	}


	/**
	 * Update the counter field of the post object
	 *
	 * @todo add this in directly in the IPF
	 * @param int $post_id
	 *
	 * @return VOID
	 */
	function updateCounter($id) {
		$sql = 'UPDATE ' . $this->table . ' SET counter = counter + 1 WHERE ' . $this->keyName . ' = ' . $id;
		$this->query($sql, null, true);
	}

	/**
	 * AfterSave event
	 *
	 * Event automatically triggered by IcmsPersistable Framework after the object is inserted or updated
	 *
	 * @param object $obj ProfilePictures object
	 * @return true
	 */
	function afterSave(& $obj) {
		global $icmsModuleConfig;
		// Resizing Images!
		$imgPath = ICMS_UPLOAD_PATH.'/profile/pictures/';
		$img = $imgPath . $obj->getVar('url');
		$this->resizeImage($img, $icmsModuleConfig['thumb_width'], $icmsModuleConfig['thumb_height'], $icmsModuleConfig['resized_width'], $icmsModuleConfig['resized_height'],$imgPath);
		return true;
	}

}
?>