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
 * @version		SVN: $Id$
 */

/**
 * Rating object
 * @package		System
 * @subpackage	Ratings
 */
class mod_system_Rating extends icms_ipf_Object {

	/** */
	public $_modulePlugin = FALSE;

	/**
	 * Constructor for the ratings object
	 * @param object $handler
	 */
	public function __construct(&$handler) {
		parent::__construct($handler);

		$this->quickInitVar('ratingid', XOBJ_DTYPE_INT, TRUE);
		$this->quickInitVar('dirname', XOBJ_DTYPE_TXTBOX, TRUE, _CO_ICMS_RATING_DIRNAME);
		$this->quickInitVar('item', XOBJ_DTYPE_TXTBOX, TRUE, _CO_ICMS_RATING_ITEM);
		$this->quickInitVar('itemid', XOBJ_DTYPE_INT, TRUE, _CO_ICMS_RATING_ITEMID);
		$this->quickInitVar('uid', XOBJ_DTYPE_INT, TRUE, _CO_ICMS_RATING_UID);
		$this->quickInitVar('date', XOBJ_DTYPE_LTIME, TRUE, _CO_ICMS_RATING_DATE);
		$this->quickInitVar('rate', XOBJ_DTYPE_INT, TRUE, _CO_ICMS_RATING_RATE);

		$this->initNonPersistableVar('name', XOBJ_DTYPE_TXTBOX, 'user', _CO_ICMS_RATING_NAME);
		$this->setControl('dirname', array('method' => 'getModuleList', 'onSelect' => 'submit'));
		$this->setControl('item', array('object' => &$this, 'method' => 'getItemList'));
		$this->setControl('uid', 'user');
		$this->setControl('rate', array('method' => 'getRateList'));
	}

	/**
	 * Custom accessors for properties
	 *
	 * @param	string $key
	 * @param	string $format
	 * @return	mixed
	 */
	public function getVar($key, $format = 's') {
		if ($format == 's' && in_array($key, array())) {
			return call_user_func(array($this, $key));
		}
		return parent::getVar($key, $format);
	}

	/**
	 * Retrieve the username associated with a rating
	 * @return	string
	 */
	public function name() {
		return icms_member_user_Handler::getUserLink($this->getVar('uid', 'e'), TRUE, array());
	}

	/**
	 * Accessor for the dirname property
	 * @return	string
	 */
	public function dirname() {
		$moduleArray = $this->handler->getModuleList();
		return $moduleArray[$this->getVar('dirname', 'n')];
	}

	/**
	 * Enter description here ...
	 * @return
	 */
	public function getItemList() {
		$plugin = $this->getModulePlugin();
		return $plugin->getItemList();
	}

	/**
	 * Retrieve the value of the rating as a link
	 * @return	string
	 */
	public function getItemValue() {
		$moduleUrl = ICMS_MODULES_URL . '/' . $this->getVar('dirname', 'n') . '/';
		$plugin = $this->getModulePlugin();
		$pluginItemInfo = $plugin->getItemInfo($this->getVar('item'));
		if (!$pluginItemInfo) {
			return '';
		}
		$itemPath = sprintf($pluginItemInfo['url'], $this->getVar('itemid'));
		$ret = '<a href="' . $moduleUrl . $itemPath . '">' . $pluginItemInfo['caption'] . '</a>';
		return $ret;
	}

	/**
	 * Accessor for the rate property
	 * @return	int
	 */
	public function getRateValue() {
		return $this->getVar('rate');
	}

	/**
	 * Create a link to the user profile associated with the rating
	 *
	 * @return	string
	 * @see	icms_member_user_Handler::getUserLink
	 */
	public function getUnameValue() {
		return icms_member_user_Handler::getUserLink($this->getVar('uid'));
	}

	/**
	 * Enter description here ...
	 */
	public function getModulePlugin() {
		if (!$this->_modulePlugin) {
			$this->_modulePlugin = $this->handler->pluginsObject->getPlugin('rating', $this->getVar('dirname', 'n'));
		}
		return $this->_modulePlugin;
	}
}