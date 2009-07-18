<?php

/**
* Classes responsible for managing profile audio objects
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
include_once ICMS_ROOT_PATH . '/kernel/icmspersistableseoobject.php';
include_once(ICMS_ROOT_PATH . '/modules/profile/include/functions.php');
include_once(ICMS_ROOT_PATH . '/class/uploader.php');

class ProfileAudio extends IcmsPersistableSeoObject {

	/**
	 * Constructor
	 *
	 * @param object $handler ProfilePostHandler object
	 */
	public function __construct(& $handler) {
		global $icmsConfig;

		$this->IcmsPersistableObject($handler);

		$this->quickInitVar('audio_id', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('title', XOBJ_DTYPE_TXTBOX, true);
		$this->quickInitVar('author', XOBJ_DTYPE_TXTBOX, false);
		$this->quickInitVar('url', XOBJ_DTYPE_TXTBOX, true);
		$this->quickInitVar('uid_owner', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('data_creation', XOBJ_DTYPE_TXTBOX, true);
		$this->quickInitVar('data_update', XOBJ_DTYPE_TXTBOX, false);
		$this->initCommonVar('counter');
		$this->initCommonVar('dohtml');
		$this->initCommonVar('dobr');
		$this->initCommonVar('doimage');
		$this->initCommonVar('dosmiley');
		$this->initCommonVar('docxode');
		$this->setControl('url', 'upload');

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

	public function load($id){
		$this->$this->handler->getObject($id);
	}


	/**
	 * Get All Blocks
	 *
	 * @since XOOPS
	 * 
	 * @param unknown_type $rettype
	 * @param unknown_type $side
	 * @param unknown_type $visible
	 * @param unknown_type $orderby
	 * @param unknown_type $isactive
	 * @return unknown
	 * 
	 * @deprecated 
	 */
	public function getAllprofile_audios ($criteria=array(), $asobject=false, $sort="audio_id", $order="ASC", $limit=0, $start=0){
		return $this->handler->getAllprofile_audios ($criteria, $asobject, $sort, $order, $limit, $start);
	}
	
}
class ProfileAudioHandler extends IcmsPersistableObjectHandler {

	/**
	 * Constructor
	 */
	public function __construct(& $db) {
		global $icmsModuleConfig;
		$this->IcmsPersistableObjectHandler($db, 'audio', 'audio_id', '', '', 'profile');
		$this->setUploaderConfig(false, array("audio/mp3" , "audio/x-mp3", "audio/mpeg"), $icmsModuleConfig['maxfilesize']);
	}
	function getAllprofile_audios($criteria=array(), $asobject=false, $sort="audio_id", $order="ASC", $limit=0, $start=0)
	{
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
			$sql = "SELECT audio_id FROM ".$this->table." $where_query ORDER BY $sort $order";
			$result = $this->db->query($sql,$limit,$start);
			while ( $myrow = $this->db->fetchArray($result) ) {
				$ret[] = $myrow['profile_audio_id'];
			}
		} else {
			$sql = "SELECT * FROM ".$this->table." $where_query ORDER BY $sort $order";
			$result = $this->db->query($sql,$limit,$start);
			while ( $myrow = $this->db->fetchArray($result) ) {
				$ret[] = $this->get($myrow);
			}
		}
		return $ret;
	}


	public function &getObjects($id, $as_object = true, $debug=false, $criteria=false) {
		$obj = parent::get($id, $as_object, $debug, $criteria);
		return $obj;
	}
	

	/**
	* retrieve a profile_audio
	* 
	* @param int $id of the profile_audio
	* @return mixed reference to the {@link profile_audio} object, FALSE if failed
	*/
	function &get($id)	{
    	$get = $this->getObjects($id, false);
    	return $get;
	}
	/**
	 * Save an Audio Object
	 *
	 * Overwrited Method
	 * 
	 * @param unknown_type $obj
	 * @param unknown_type $force
	 * @param unknown_type $checkObject
	 * @param unknown_type $debug
	 * @return unknown
	 */
	public function insert(& $obj, $force = false, $checkObject = true, $debug=false){
		$new = $obj->isNew();
		if(!$new){
			$sql = sprintf("DELETE FROM %s WHERE audio_id = '%u'", $this->table, intval($obj->getVar('audio_id')));
			$this->db->query($sql);
		}
		$status = parent::insert( $obj, $force, $checkObject, $debug );
		return $status; 
		
	}
	
	/**
	* count profile_audios matching a condition
	* 
	* @param object $criteria {@link CriteriaElement} to match
	* @return int count of profile_audios
	*/
	function getCount($criteria = null)
	{
		$sql = 'SELECT COUNT(*) FROM '.$this->table;
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
		$sql = 'DELETE FROM '.$this->table;
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
		}
		if (!$result = $this->db->query($sql)) {
			return false;
		}
		return true;
	}

}
?>