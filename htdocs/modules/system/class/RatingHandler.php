<?php
/**
 * ImpressCMS Ratings
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		System
 * @subpackage	Ratings
 * @since		1.2
 * @author		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version		SVN: $Id: RatingHandler.php 11586 2012-02-19 05:31:18Z skenow $
 */

/**
 * Handler for the ratings object
 * @package		System
 * @subpackage	Ratings
 */
class mod_system_RatingHandler extends icms_ipf_Handler {

	public $_rateOptions = array();
	public $_moduleList = FALSE;
	public $pluginsObject;

	/**
	 * Constructor for the ratings handler
	 * 
	 * @param object $db
	 */
	public function __construct($db) {
		parent::__construct($db, 'rating', 'ratingid', 'rate', '', 'system');
		$this->generalSQL = 'SELECT * FROM ' . $this->table . ' AS ' . $this->_itemname . ' INNER JOIN ' . $this->db->prefix('users') . ' AS user ON ' . $this->_itemname . '.uid=user.uid';

		$this->_rateOptions[1] = 1;
		$this->_rateOptions[2] = 2;
		$this->_rateOptions[3] = 3;
		$this->_rateOptions[4] = 4;
		$this->_rateOptions[5] = 5;

		$this->pluginsObject = new icms_plugins_Handler();
	}

	/**
	 * Retrieve a list of modules enabling ratings
	 * @return	array
	 */
	public function getModuleList() {
		if (!$this->_moduleList) {
			$moduleArray = $this->pluginsObject->getPluginsArray('rating');
			$this->_moduleList[0] = _CO_ICMS_MAKE_SELECTION;
			foreach ($moduleArray as $k=>$v) {
				$this->_moduleList[$k] = $v;
			}
		}
		return $this->_moduleList;
	}

	/**
	 * Accessor for the rate property
	 * @return	array	Rating options
	 */
	public function getRateList() {
		return $this->_rateOptions;
	}

	/**
	 * Get the average rating for an item
	 * 
	 * @param int $itemid
	 * @param str $dirname
	 * @param str $item
	 * @return	int|array	0 if there is no rating; an array containing the average and the total ratings for the item
	 */
	public function getRatingAverageByItemId($itemid, $dirname, $item) {
		$itemid = (int) $itemid;
		$sql = "SELECT AVG(rate), COUNT(ratingid) FROM " . $this->table . " WHERE itemid=$itemid AND dirname='$dirname' AND item='$item' GROUP BY itemid";
		$result = $this->db->query($sql);
		if (!$result) {
			return 0;
		}
		list($average, $sum) = $this->db->fetchRow($result);
		$ret['average'] = isset($average) ? $average : 0;
		$ret['sum'] = isset($sum) ? $sum : 0;
		return $ret;
	}
	
	/**
	 * Determine if a user has already rated an item
	 * 
	 * @param	str	$item
	 * @param	int	$itemid
	 * @param	str	$dirname
	 * @param	int	$uid
	 * @return	bool|array
	 */
	public function already_rated($item, $itemid, $dirname, $uid) {

		$criteria = new icms_db_criteria_Compo();
		$criteria->add(new icms_db_criteria_Item('item', $item));
		$criteria->add(new icms_db_criteria_Item('itemid', (int) $itemid));
		$criteria->add(new icms_db_criteria_Item('dirname', $dirname));
		$criteria->add(new icms_db_criteria_Item('user.uid', (int) $uid));

		$ret = $this->getObjects($criteria);

		if (!$ret) {
			return FALSE;
		} else {
			return $ret[0];
		}
	}
}
