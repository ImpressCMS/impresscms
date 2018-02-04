<?php
/**
 *
 * Class To load plugins for modules.
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	ICMS\Plugins
 * @since	1.2
 * @author	ImpressCMS
 * @author	Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 */
class icms_plugins_Object {

    /**
     * Plugin information (storied as array)
     *
     * @var array 
     */
	public $_infoArray = array();

	/**
	 * Constructor
	 * 
	 * @param array $array	    data
	 */
	public function __construct($array) {
		$this->_infoArray = $array;
	}

	/**
	 * Gets info about item
	 * 
	 * @param string $item	    Item name
	 * 
	 * @return mixed
	 */
	public function getItemInfo($item) {
		if (isset($this->_infoArray['items'][$item])) {
			return $this->_infoArray['items'][$item];
		} else {
			return false;
		}
	}

	/**
	 * Gets items list
	 * 
	 * @return array
	 */
	public function getItemList() {
		$ret = array();
		foreach ($this->_infoArray['items'] as $k=>$v) {
			$ret[$k] = $v['caption'];
		}
		return $ret;
	}

	/**
	 * Get items
	 * 
	 * @return mixed
	 */
	public function getItem() {
		$ret = false;
		foreach($this->_infoArray['items'] as $k => $v) {
			$search_str = str_replace('%u', '', $v['url']);
			if (strpos($_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'], $search_str) > 0) {
				$ret = $k;
				break;
			}
		}
		return $ret;
	}

	/**
	 * Get item from request
	 * 
	 * @param string $item	    Item name
	 * 
	 * @return mixed
	 */
	public function getItemIdForItem($item) {
		return $_REQUEST[$this->_infoArray['items'][$item]['request']];
	}
}


