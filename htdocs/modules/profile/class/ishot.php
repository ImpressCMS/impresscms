<?php

/**
* Classes responsible for managing profile ishot objects
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

class ProfileIshot extends IcmsPersistableObject {

	/**
	 * Constructor
	 *
	 * @param object $handler ProfilePostHandler object
	 */
	public function __construct(& $handler) {
		global $icmsConfig;

		$this->IcmsPersistableObject($handler);

		$this->quickInitVar('ishot_id', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('uid_voter', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('uid_vote', XOBJ_DTYPE_TXTBOX, true);
		$this->quickInitVar('ishot', XOBJ_DTYPE_INT, false);
		$this->quickInitVar('time', XOBJ_DTYPE_TXTBOX, true);

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
class ProfileIshotHandler extends IcmsPersistableObjectHandler {

	/**
	 * Constructor
	 */
	public function __construct(& $db) {
		$this->IcmsPersistableObjectHandler($db, 'ishot', 'ishot_id', '', '', 'profile');
	}
	function getHottest($criteria = null)
	{


		$sql = 'SELECT DISTINCTROW uname, user_avatar, uid_voted, COUNT(cod_ishot) AS qtd FROM '.$this->db->prefix('profile_ishot').', '.$this->db->prefix('users');
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
		}
		//attention here this is kind of a hack
		$sql .= " AND uid = uid_voted";
		if ($criteria->getGroupby() != '') {
			$sql .= $criteria->getGroupby();
		}
		if ($criteria->getSort() != '') {
			$sql .= ' ORDER BY '.$criteria->getSort().' '.$criteria->getOrder();
		}
		$limit = $criteria->getLimit();
		$start = $criteria->getStart();
		
		$result = $this->db->query($sql, $limit, $start);
		$vetor = array();
		$i=0;
		while ($myrow = $this->db->fetchArray($result)) {
			
			$vetor[$i]['qtd']= $myrow['qtd'];
			$vetor[$i]['uid_voted']= $myrow['uid_voted'];
			$vetor[$i]['uname']= $myrow['uname'];
			$vetor[$i]['user_avatar']= $myrow['user_avatar'];
			$i++;
		}
		
		
		return $vetor;
	} 

function getHotFriends($criteria = null, $id_as_key = false)
	{
		$ret = array();
		$limit = $start = 0;
		$sql = 'SELECT uname, user_avatar, uid_voted FROM '.$this->db->prefix('profile_ishot').', '.$this->db->prefix('users');;
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
		//attention here this is kind of a hack
		$sql .= " AND uid = uid_voted AND ishot=1" ;
		if ($criteria->getSort() != '') {
			$sql .= ' ORDER BY '.$criteria->getSort().' '.$criteria->getOrder();
		}
		$limit = $criteria->getLimit();
		$start = $criteria->getStart();
		
		$result = $this->db->query($sql, $limit, $start);
		$vetor = array();
		$i=0;
		while ($myrow = $this->db->fetchArray($result)) {
			
			$vetor[$i]['uid_voted']= $myrow['uid_voted'];
			$vetor[$i]['uname']= $myrow['uname'];
			$vetor[$i]['user_avatar']= $myrow['user_avatar'];
			$i++;
		}
		
		
		return $vetor;

		}
	}
}
?>