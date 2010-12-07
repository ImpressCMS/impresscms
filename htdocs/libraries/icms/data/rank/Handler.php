<?php
/**
 * Handler for the user rank object
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		http://www.fsf.org/copyleft/gpl.html GNU public license
 * @category	ICMS
 * @package		Data
 * @subpackage	Rank
 * @version		SVN: $Id$
 */
/**
 * Handler for the user rank object
 *
 * @category	ICMS
 * @package		Data
 * @subpackage	Rank
 */
class icms_data_rank_Handler extends icms_core_ObjectHandler {

	/**
	 * Constructor
	 * @param   object  $db reference to the DB class object
	 **/
	public function __construct(&$db) {
		parent::__construct($db);
	}

	/**
	 * Create a new rank
	 *
	 * @param   bool  $isNew is it a new rank?
	 * @return  object	reference to the (@link XoopsRank) object
	 **/
	public function &create($isNew = TRUE) {
		$obj = new icms_data_rank_Object();
		if ($isNew === TRUE) {
			$obj->setNew();
		}
		return $obj;
	}

	/**
	 * Gets the rank from the database
	 *
	 * @param   int  $id
	 * @return  object
	 **/
	public function &get($id = 0) {
		$object =& $this->create(FALSE);
		$sql = "SELECT * FROM " . $this->db->prefix('ranks') . " WHERE rank_id = '" . $this->db->quoteString($id) . "'";
		if (!$result = $this->db->query($sql)) {
			$ret = NULL;
			return $ret;
		}
		while ($row = $this->db->fetchArray($result)) {
			$object->assignVars($row);
		}

		return $object;
	}

	/**
	 * Gets list of ranks
	 *
	 * @param   object  $criteria Criteria (@link icms_db_criteria_Compo) to match when getting the ranks
	 * @param   string  $limit How many ranks to get
	 * @param   string  $start Where to start with getting the ranks (for pagination)
	 * @return  array
	 **/
	public function getList($criteria = NULL, $limit = 0, $start = 0) {
		$ret = array();
		if ($criteria == NULL) {
			$criteria = new icms_db_criteria_Compo();
		}

		$sql = 'SELECT rank_id, rank_title FROM ' . $this->db->prefix('ranks');
		if (isset($criteria) && is_subclass_of($criteria, 'icms_db_criteria_Element')) {
			$sql .= ' ' . $criteria->renderWhere();
			if ($criteria->getSort() != '') {
				$sql .= ' ORDER BY ' . $criteria->getSort() . ' ' . $criteria->getOrder();
			}
			$limit = $criteria->getLimit();
			$start = $criteria->getStart();
		}

		$result = $this->db->query($sql, $limit, $start);
		if (!$result) {
			return $ret;
		}

		$myts =& icms_core_Textsanitizer::getInstance();
		while ($myrow = $this->db->fetchArray($result)) {
			$ret[$myrow["rank_id"]] = $myts->htmlSpecialChars($myrow["rank_title"]);
		}
		return $ret;
	}
	
	public function insert(&$object) {
		
	}

	public function delete(&$object) {
		
	}
}
