<?php

/**
* Classes responsible for managing profile tribes objects
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

class ProfileTribes extends IcmsPersistableSeoObject {

	/**
	 * Constructor
	 *
	 * @param object $handler ProfilePostHandler object
	 */
	public function __construct(& $handler) {
		global $icmsConfig;

		$this->IcmsPersistableObject($handler);

		$this->quickInitVar('tribes_id', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('uid_owner', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('title', XOBJ_DTYPE_TXTBOX, true);
		$this->quickInitVar('tribe_desc', XOBJ_DTYPE_TXTAREA, true);
		$this->quickInitVar('tribe_img', XOBJ_DTYPE_TXTBOX, false);
		$this->quickInitVar('creation_time', XOBJ_DTYPE_LTIME, false);
		$this->initCommonVar('counter', false);
		$this->initCommonVar('dohtml', false, true);
		$this->initCommonVar('dobr', false, true);
		$this->initCommonVar('doimage', false, true);
		$this->initCommonVar('dosmiley', false, true);
		$this->initCommonVar('docxode', false, true);
		$this->setControl('uid_owner', 'user');
		$this->setControl('tribe_img', 'image');
		$this->hideFieldFromForm('creation_time');
		$this->setControl('tribe_desc', 'dhtmltextarea');


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
	function getProfileTribe() {
		$ret = '<img class="thumb" src="' . ICMS_URL . '/uploads/profile/tribes/thumb_' . $this->getVar ( 'tribe_img' ) . '"  title="' . $this->getVar ( 'title' ) . '" />';
		return $ret;
	}

	function getTribeShortenDesc() {
		$ret = '<a href="' . ICMS_URL . '/modules/profile/tribes.php?tribes_id=' . $this->id () . '">'.icms_wordwrap($this->getVar('tribe_desc', 'e'), 300, true).'</a>';
		return $ret;
	}

	function getProfileTribeSenderAvatar() {
		global $icmsUser;
		$friend = $this->getVar('uid_owner', 'e');
		$member_handler =& xoops_gethandler('member');
		$processUser =& $member_handler->getUser($friend);
		return '<img src="'.$processUser->gravatar().'" />';
	}
	function getTribeSender() {
		return icms_getLinkedUnameFromId($this->getVar('uid_owner', 'e'));
	}
	/**
	 * Check to see wether the current user can edit or delete this tribe
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
	 * Overridding IcmsPersistable::toArray() method to add a few info
	 *
	 * @return array of tribe info
	 */
	function toArray() {
		$profile_tribeuser_handler = icms_getModuleHandler('tribeuser');
		$tribe_members_count = $profile_tribeuser_handler->getTribeuserCounts($this->getVar('tribes_id', 'e'));
		$tribe_members_link = $profile_tribeuser_handler->getTribeuser($this->getVar('tribes_id', 'e'), $this->getVar('uid_owner', 'e'));
		$ret = parent :: toArray();
		$ret['creation_time'] = formatTimestamp($this->getVar('creation_time', 'e'), 'm');
		$ret['tribe_title'] = $this->getVar('title','e');
		$ret['tribe_content'] = $this->getTribeShortenDesc();
		$ret['tribe_picture'] = $this->getProfileTribe();
		$ret['tribe_members_count'] = $tribe_members_count;
		$ret['tribe_members_link'] = $tribe_members_link;
		$ret['editItemLink'] = $this->getEditItemLink(false, true, true);
		$ret['deleteItemLink'] = $this->getDeleteItemLink(false, true, true);
		$ret['userCanEditAndDelete'] = $this->userCanEditAndDelete();
		$ret['tribe_senderid'] = $this->getVar('uid_owner','e');
		$ret['tribe_sender_link'] = $this->getTribeSender();
		$ret['tribe_sender_avatar'] = $this->getProfileTribeSenderAvatar();
		return $ret;
	}
}

class ProfileTribesHandler extends IcmsPersistableObjectHandler {

	/**
	 * Constructor
	 */
	public function __construct(& $db) {
		global $icmsModuleConfig;
		$this->IcmsPersistableObjectHandler($db, 'tribes', 'tribes_id', 'title', '', 'profile');
		$this->setUploaderConfig(false, array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png'), $icmsModuleConfig['maxfilesize'], $icmsModuleConfig['max_original_width'], $icmsModuleConfig['max_original_height']);
	}

	/**
	 * Create the criteria that will be used by getTribes and getTribesCount
	 *
	 * @param int $start to which record to start
	 * @param int $limit limit of tribes to return
	 * @param int $uid_owner if specifid, only the tribes of this user will be returned
	 * @param int $tribe_id ID of a single tribe to retrieve
	 * @return CriteriaCompo $criteria
	 */
	function getTribesCriteria($start = 0, $limit = 0, $uid_owner = false, $tribe_id = false) {
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

		if ($uid_owner) {
			$criteria->add(new Criteria('uid_owner', $uid_owner));
		}
		if ($tribe_id) {
			$criteria->add(new Criteria('tribes_id', $tribe_id));
		}
		return $criteria;
	}

	/**
	 * Get single tribe object
	 *
	 * @param int $tribes_id
	 * @return object ProfileTribe object
	 */
	function getTribe($tribes_id=false, $uid_owner=false) {
		$ret = $this->getTribes(0, 0, $uid_owner, $tribes_id);
		return isset($ret[$tribes_id]) ? $ret[$tribes_id] : false;
	}

	/**
	 * Get tribes as array, ordered by creation_time DESC
	 *
	 * @param int $start to which record to start
	 * @param int $limit max tribes to display
	 * @param int $uid_owner if specifid, only the tribe of this user will be returned
	 * @param int $tribes_id ID of a single tribe to retrieve
	 * @return array of tribes
	 */
	function getTribes($start = 0, $limit = 0, $uid_owner = false, $tribes_id = false) {
		$criteria = $this->getTribesCriteria($start, $limit, $uid_owner, $tribes_id);
		$ret = $this->getObjects($criteria, true, false);
		return $ret;
	}

	/**
	* Resize a tribe and save it to $path_upload
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
	* Resize a tribe and save it to $path_upload
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
	
	/**
	 * Check wether the current user can submit a new tribe or not
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
	 * @param object $obj ProfileTribes object
	 * @return true
	 */
	function afterSave(& $obj) {
		global $icmsModuleConfig;
		// Resizing Images!
		$imgPath = ICMS_UPLOAD_PATH.'/profile/tribes/';
		$img = $imgPath . $obj->getVar('tribe_img');
		$this->resizeImage($img, $icmsModuleConfig['thumb_width'], $icmsModuleConfig['thumb_height'], $icmsModuleConfig['resized_width'], $icmsModuleConfig['resized_height'],$imgPath);
		return true;
	}

}
?>