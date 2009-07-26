<?php

/**
* Classes responsible for managing profile friendship objects
*
* @copyright	GNU General Public License (GPL)
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.3
* @author		Jan Pedersen, Marcello Brandao, Sina Asghari, Gustavo Pilla <contact@impresscms.org>
* @package		profile
* @version		$Id$
*/

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

// including the IcmsPersistabelSeoObject
include_once ICMS_ROOT_PATH . '/kernel/icmspersistableobject.php';
include_once(ICMS_ROOT_PATH . '/modules/profile/include/functions.php');

define('PROFILE_FRIENDSHIP_STATUS_PENDING', 1);
define('PROFILE_FRIENDSHIP_STATUS_ACQUAINTANCE', 2);
define('PROFILE_FRIENDSHIP_STATUS_ACCEPTED', 3);
define('PROFILE_FRIENDSHIP_STATUS_REJECTED', 4);

class ProfileFriendship extends IcmsPersistableObject {

	/**
	 * Constructor
	 *
	 * @param object $handler ProfilePostHandler object
	 */
	public function __construct(& $handler) {
		global $icmsConfig;

		$this->IcmsPersistableObject($handler);

		$this->quickInitVar('friendship_id', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('friend1_uid', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('friend2_uid', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('creation_time', XOBJ_DTYPE_LTIME, false);
		$this->quickInitVar('situation', XOBJ_DTYPE_INT, true, false, false, PROFILE_FRIENDSHIP_STATUS_PENDING);
		$this->setControl('friend1_uid', 'user');
		$this->hideFieldFromForm('friend2_uid');
		$this->hideFieldFromForm('creation_time');
		$this->setControl('situation', array (
			'itemHandler' => 'friendship',
			'method' => 'getFriendship_statusArray',
			'module' => 'profile'
		));
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
	
	function getFriend() {
		global $icmsUser;
		$uid = isset($_REQUEST['uid'])?intval($_REQUEST['uid']):$icmsUser->uid();
		$friend = ($uid==$friendshipObj->getVar('friend2_uid'))?$friendshipObj->getVar('friend1_uid'):$friendshipObj->getVar('friend2_uid');
		return icms_getLinkedUnameFromId($friend);
	}

	function getAvatar() {
		global $icmsUser;
		$uid = isset($_REQUEST['uid'])?intval($_REQUEST['uid']):$icmsUser->uid();
		$friend = ($uid==$friendshipObj->getVar('friend2_uid'))?$friendshipObj->getVar('friend1_uid'):$friendshipObj->getVar('friend2_uid');
		$member_handler =& xoops_gethandler('member');
		$processUser =& $member_handler->getUser($friend);
		return $processUser->gravatar();
	}

	/**
	 * Check to see wether the current user can edit or delete this friendship
	 *
	 * @return bool true if he can, false if not
	 */
	function userCanEditAndDelete() {
		global $icmsUser, $profile_isAdmin;
		if (!is_object($icmsUser)) {
			return false;
		}
		if($this->getVar('friend2_uid', 'e') == $icmsUser->uid()){
			return true;
		}
		return false;
	}

	/**
	 * Overridding IcmsPersistable::toArray() method to add a few info
	 *
	 * @return array of friendship info
	 */
	function toArray() {
		$ret = parent :: toArray();
		$ret['creation_time'] = formatTimestamp($this->getVar('creation_time', 'e'), 'm');
		$ret['friendship_avatar'] = $this->getAvatar();
		$ret['friendship_content'] = $this->getFriend();
		$ret['editItemLink'] = $this->getEditItemLink(false, true, true);
		$ret['deleteItemLink'] = $this->getDeleteItemLink(false, true, true);
		$ret['userCanEditAndDelete'] = $this->userCanEditAndDelete();
		$ret['friendship_senderid'] = $this->getVar('uid_owner','e');
		$ret['friendship_sender_link'] = $this->getPictureSender();
		return $ret;
	}
}
class ProfileFriendshipHandler extends IcmsPersistableObjectHandler {


	/**
	 * @var array of status
	 */
	var $_friendship_statusArray = array ();
	/**
	 * Constructor
	 */
	public function __construct(& $db) {
		$this->IcmsPersistableObjectHandler($db, 'friendship', 'friendship_id', 'friend1_uid', '', 'profile');
	}

	/**
	 * Create the criteria that will be used by getFriendship and getFriendshipCount
	 *
	 * @param int $start to which record to start
	 * @param int $limit limit of friendships to return
	 * @param int $friend1_uid if specifid, only the friendship of this user will be returned
	 * @return CriteriaCompo $criteria
	 */
	function getFriendshipCriteria($start = 0, $limit = 0, $friend1_uid) {
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
		$criteria->add(new Criteria('friend1_uid', $friend1_uid), 'OR');
		$criteria->add(new Criteria('friend2_uid', $friend1_uid));
		return $criteria;
	}

	/**
	 * Get friendships as array, ordered by creation_time DESC
	 *
	 * @param int $start to which record to start
	 * @param int $limit max friendships to display
	 * @param int $friend1_uid only the friendship of this user will be returned
	 * @param int $friend2_uid if specifid, the friendship of these two users will be returned.
	 * @return array of friendships
	 */
	function getFriendship($start = 0, $limit = 0, $friend1_uid) {
		$criteria = $this->getFriendshipCriteria($start, $limit, $friend1_uid);
		$ret = $this->getObjects($criteria, true, false);
		return $ret;
	}

	/**
	 * Retreive the possible status of a friendship object
	 *
	 * @return array of status
	 */
	function getFriendship_statusArray() {
		if (!$this->_friendship_statusArray) {
			$this->_friendship_statusArray[PROFILE_FRIENDSHIP_STATUS_PENDING] = _CO_PROFILE_FRIENDSHIP_STATUS_PENDING;
			$this->_friendship_statusArray[PROFILE_FRIENDSHIP_STATUS_ACQUAINTANCE] = _CO_PROFILE_FRIENDSHIP_STATUS_ACQUAINTANCE;
			$this->_friendship_statusArray[PROFILE_FRIENDSHIP_STATUS_ACCEPTED] = _CO_PROFILE_FRIENDSHIP_STATUS_ACCEPTED;
			$this->_friendship_statusArray[PROFILE_FRIENDSHIP_STATUS_REJECTED] = _CO_PROFILE_FRIENDSHIP_STATUS_REJECTED;
		}
		return $this->_friendship_statusArray;
	
	}
	
	/**
	 * Check wether the current user can submit a new friendship or not
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
	* Get the averages of each evaluation hot trusty etc...
	* 
	* @param int $user_uid
	* @return array $vetor with averages
	*/
	
	function getMoyennes($user_uid){
	
	global $icmsUser;
	
	$vetor = array();
	$vetor['mediahot']=0;
	$vetor['mediatrust']=0;	
	$vetor['mediacool']=0;		
	$vetor['sumfan']=0;
	
	//Calculating avg(hot)	
	$sql ="SELECT friend2_uid, Avg(hot) AS mediahot FROM ".$this->db->prefix('profile_friendship');
	$sql .=" WHERE  (hot>0) GROUP BY friend2_uid HAVING (friend2_uid=".$user_uid.") ";
	$result = $this->db->query($sql);
	while ($myrow = $this->db->fetchArray($result)) {
		$vetor['mediahot']= $myrow['mediahot']*16;
	}
	
	//Calculating avg(trust)
	$sql ="SELECT friend2_uid, Avg(trust) AS mediatrust FROM ".$this->db->prefix('profile_friendship');
	$sql .=" WHERE  (trust>0) GROUP BY friend2_uid HAVING (friend2_uid=".$user_uid.") ";
	$result = $this->db->query($sql);
	while ($myrow = $this->db->fetchArray($result)) {
		$vetor['mediatrust']= $myrow['mediatrust']*16;
	}
	//Calculating avg(cool)
	$sql  = "SELECT friend2_uid, Avg(cool) AS mediacool FROM ".$this->db->prefix('profile_friendship');
	$sql .= " WHERE  (cool>0) GROUP BY friend2_uid HAVING (friend2_uid=".$user_uid.") ";
	$result = $this->db->query($sql);
	while ($myrow = $this->db->fetchArray($result)) {
		$vetor['mediacool']= $myrow['mediacool']*16;
	}	

	//Calculating sum(fans)
	$sql ="SELECT friend2_uid, Sum(fan) AS sumfan FROM ".$this->db->prefix('profile_friendship');
	$sql .=" GROUP BY friend2_uid HAVING (friend2_uid=".$user_uid.") ";
	$result = $this->db->query($sql);
	while ($myrow = $this->db->fetchArray($result)) {
		$vetor['sumfan']= $myrow['sumfan'];
	}
	
	return $vetor;
	}

	/**
	 * Retreive the friendship_id of users
	 *
	 * @return amount
	 */
	function getFriendshipIdPerUser($uid1, $uid2){
		$sql = 'SELECT friendship_id FROM '.$this->table.' WHERE ';
		$sql .= '((friend1_uid="'.$uid1.'" AND friend2_uid="'.$uid2.'") OR (friend1_uid="'.$uid2.'" AND friend2_uid="'.$uid1.'"))';
		$ret = $this->query($sql, false);
		return $ret[0]['friendship_id'];
	}


	/**
	 * Retreive the friendship_id of users
	 *
	 * @return array of amounts
	 */
	function getFriendshipIdsWaiting($uid){
		$array = array();
		$sql = 'SELECT friendship_id FROM '.$this->table.' WHERE ';
		$sql .= '(friend2_uid="'.$uid.'" AND situation = 1)';
		$ret = $this->query($sql, false);
		if(count($ret)>0){
			for ($i = 0; $i < count($ret); $i++) {
			$array[] = $ret[$i]['friendship_id'];
			}
		}
		return $array;
	}

}
?>