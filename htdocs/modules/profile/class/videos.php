<?php

/**
* Classes responsible for managing profile videos objects
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

class ProfileVideos extends IcmsPersistableSeoObject {

	/**
	 * Constructor
	 *
	 * @param object $handler ProfilePostHandler object
	 */
	public function __construct(& $handler) {
		global $icmsConfig;

		$this->IcmsPersistableObject($handler);

		$this->quickInitVar('video_id', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('uid_owner', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('video_desc', XOBJ_DTYPE_TXTBOX, true);
		$this->quickInitVar('youtube_code', XOBJ_DTYPE_TXTBOX, true);
		$this->quickInitVar('creation_time', XOBJ_DTYPE_LTIME, false);
		$this->quickInitVar('main_video', XOBJ_DTYPE_TXTBOX, false);
		$this->initCommonVar('counter', false);
		$this->initCommonVar('dohtml', false, true);
		$this->initCommonVar('dobr', false, true);
		$this->initCommonVar('doimage', false, true);
		$this->initCommonVar('dosmiley', false, true);
		$this->initCommonVar('doxcode', false, true);

		$this->setControl('uid_owner', 'user');
		//$this->hideFieldFromForm('main_video');


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
	function getVideoToDisplay() {
		$ret = '<object width="320" height="265"><param name="movie" value="http://www.youtube.com/v/' . $this->getVar ( 'youtube_code' ) . '&hl='._LANGCODE.'&fs=1&color1=0x3a3a3a&color2=0x999999"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="http://www.youtube.com/v/' . $this->getVar ( 'youtube_code' ) . '&hl='._LANGCODE.'&fs=1&color1=0x3a3a3a&color2=0x999999" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="320" height="265"></embed></object>';
		return $ret;
	}
	
	function getVideoSender() {
		return icms_getLinkedUnameFromId($this->getVar('uid_owner', 'e'));
	}

	/**
	 * Check to see wether the current user can edit or delete this video
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
	 * Check to see wether the current user can view this video
	 *
	 * @return bool true if he can, false if not
	 */
	function userCanView() {
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
	 * @return array of video info
	 */
	function toArray() {
		$ret = parent :: toArray();
		$ret['creation_time'] = formatTimestamp($this->getVar('creation_time', 'e'), 'm');
		$ret['video_content'] = $this->getProfileVideo();
		$ret['video_title'] = $this->getVar('title','e');
		$ret['editItemLink'] = $this->getEditItemLink(false, true, true);
		$ret['deleteItemLink'] = $this->getDeleteItemLink(false, true, true);
		$ret['userCanEditAndDelete'] = $this->userCanEditAndDelete();
		$ret['userCanView'] = $this->userCanView();
		$ret['video_senderid'] = $this->getVar('uid_owner','e');
		$ret['video_sender_link'] = $this->getVideoSender();
		return $ret;
	}
}
class ProfileVideosHandler extends IcmsPersistableObjectHandler {

	/**
	 * Constructor
	 */
	public function __construct(& $db) {
		$this->IcmsPersistableObjectHandler($db, 'videos', 'video_id', 'video_desc', '', 'profile');
	}

	/**
	* delete profile_video matching a set of conditions
	* 
	* @param object $criteria {@link CriteriaElement} 
	* @return bool FALSE if deletion failed
	*/
	function deleteAll($criteria = null)
	{
		$sql = 'DELETE FROM '.$this->table;
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
		}
		if (!$result = $this->db->query($sql)) {
			return false;
		}
		return true;
	}
	/**
	 * Assign Video Content to Template
	 * @param int $NbVideos the number of videos this user have
	 * @param array of objects 
	 * @return void
	 */	
	function assignVideoContent($nbVideos, $videos)	{
		if ($nbVideos==0){
			return false;
		} else {
			/**
     * Lets populate an array with the dati from the videos
     */  
			$i = 0;
			foreach ($videos as $video){
				$videos_array[$i]['url']      = $video->getVar("youtube_code","s");
				$videos_array[$i]['desc']     = $video->getVar("video_desc","s");
				$videos_array[$i]['id']  	  = $video->getVar("video_id","s");
				
				$i++;
			}
		   return $videos_array;
		}
    }

}
?>