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
	 * @param object $handler ProfilePostHandler object
	 */
	public function __construct(& $handler) {
		global $icmsConfig;

		$this->IcmsPersistableObject($handler);

		$this->quickInitVar('pictures_id', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('title', XOBJ_DTYPE_TXTBOX, true);
		$this->quickInitVar('creation_time', XOBJ_DTYPE_TXTBOX, false);
		$this->quickInitVar('update_time', XOBJ_DTYPE_TXTBOX, false);
		$this->quickInitVar('user_id', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('url', XOBJ_DTYPE_TXTBOX, true);
		$this->quickInitVar('private', XOBJ_DTYPE_TXTBOX, false);
		$this->initCommonVar('counter', false);
		$this->initCommonVar('dohtml', false, true);
		$this->initCommonVar('dobr', false, true);
		$this->initCommonVar('doimage', false, true);
		$this->initCommonVar('dosmiley', false, true);

		$this->setControl('user_id', 'user');
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
		$ret = '<img src="' . ICMS_URL . '/uploads/profile/pictures/' . $this->getVar ( 'url' ) . '" />';
		return $ret;
	}
	
	function getPictureSender() {
		return icms_getLinkedUnameFromId($this->getVar('user_id', 'e'));
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
	* Resize a picture and save it to $path_upload
	* 
	* @param text $img the path to the file
	* @param int $width the width in pixels that the pic will have
	* @param int $height the height in pixels that the pic will have
	* @param text $path_upload The path to where the files should be saved after resizing
	* @param text $prefix The prefix used to recognize files and avoid multiple files.
	* @return nothing
	*/	
	function imageResizer($img, $width=320, $height=240, $path_upload=ICMS_UPLOAD_PATH, $prefix=time()) {
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
		imageResizer($img, $thumbwidth, $thumbheight, $path_upload, 'thumb');
		imageResizer($img, $pictwidth, $pictheight, $path_upload, 'resized');
	}
	
	function getLastPictures($limit)
	{
		$ret = array();
		
		$sql = 'SELECT uname, t.user_id, t.url FROM '.$this->table.' AS t, '.$this->db->prefix('users');
		
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
		
		$sql = 'SELECT uname, t.user_id, t.url, t.title FROM '.$this->table.' AS t, '.$this->db->prefix('users');
		
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
		imageResizer($img, $icmsConfigUser['avatar_width'], $icmsConfigUser['avatar_height'], false, $prefix);
		$sql = sprintf("UPDATE %s SET user_avatar = %s WHERE uid = '%u'", $this->db->prefix('users'), $user_avatar, intval($uid));
		$this->query($sql);
	}
	
	
}
?>