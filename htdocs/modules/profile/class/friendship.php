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
		$this->quickInitVar('situation', XOBJ_DTYPE_INT, true, false, false, PROFILE_FRIENDSHIP_STATUS_PENDING);
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
	 * Retreive the possible status of a friendship object
	 *
	 * @return array of status
	 */
	function getConfig_statusArray() {
		if (!$this->_friendship_statusArray) {
			$this->_friendship_statusArray[PROFILE_FRIENDSHIP_STATUS_PENDING] = _CO_PROFILE_FRIENDSHIP_STATUS_PENDING;
			$this->_friendship_statusArray[PROFILE_FRIENDSHIP_STATUS_ACQUAINTANCE] = _CO_PROFILE_FRIENDSHIP_STATUS_ACQUAINTANCE;
			$this->_friendship_statusArray[PROFILE_FRIENDSHIP_STATUS_ACCEPTED] = _CO_PROFILE_FRIENDSHIP_STATUS_ACCEPTED;
			$this->_friendship_statusArray[PROFILE_FRIENDSHIP_STATUS_REJECTED] = _CO_PROFILE_FRIENDSHIP_STATUS_REJECTED;
		}
		return $this->_friendship_statusArray;
	
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
	 * @return array of amounts
	 */
	function getFriendshipIdPerUser($uid1, $uid2){
		$sql = 'SELECT friendship_id FROM '.$this->table.' WHERE ((friend1_uid="'.$uid1.'" AND friend2_uid="'.$uid2.'") OR (friend1_uid="'.$uid2.'" AND friend2_uid="'.$uid1.'"))';
		$ret = $this->query($sql, false);
		return $ret;
	}

}
?>