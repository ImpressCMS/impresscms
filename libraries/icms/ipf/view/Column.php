<?php
/**
 * icms_ipf_Object Table Listing
 *
 * Contains the classes responsible for displaying a highly configurable and features rich listing of IcmseristableObject objects
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since	1.1
 * @author	marcan <marcan@impresscms.org>
 */

/**
 * icms_ipf_view_Column class
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	ICMS\IPF\View
 * @since	1.1
 * @author	marcan <marcan@impresscms.org>
 */
class icms_ipf_view_Column {

	/**
	 * Keyname
	 *
	 * @var string
	 */
	public $_keyname = '';

		/**
		 * Align of text in column
		 *
		 * @var string
		 */
	public $_align = _GLOBAL_LEFT;

		/**
		 * Width
		 *
		 * @var bool|string|int
		 */
	public $_width = false;

		/**
		 * Custom method that formats value
		 *
		 * @var callable|null
		 */
	public $_customMethodForValue;

		/**
		 * Extra params
		 *
		 * @var string
		 */
	public $_extraParams = '';

		/**
		 * Column can be sortable?
		 *
		 * @var bool
		 */
	public $_sortable = true;

		/**
		 * Custom caption for column
		 * Uf empty it tried to autodetect from property
		 *
		 * @var string
		 */
	public $_customCaption = '';

	/**
	 * Constructor
	 *
	 * @param unknown_type $keyname
	 * @param str $align
	 * @param unknown_type $width
	 * @param unknown_type $customMethodForValue
	 * @param unknown_type $param
	 * @param unknown_type $customCaption
	 * @param unknown_type $sortable
	 */
	public function __construct($keyname, $align = _GLOBAL_LEFT, $width = false, $customMethodForValue = false, $param = false, $customCaption = false, $sortable = true) {
		$this->_keyname = $keyname;
		$this->_align = $align;
		$this->_width = $width;
		$this->_customMethodForValue = $customMethodForValue;
		$this->_sortable = $sortable;
		$this->_param = $param;
		$this->_customCaption = $customCaption;
	}

	/**
	 * Accessor for keyname
	 */
	public function getKeyName() {
		return $this->_keyname;
	}

	/**
	 * Accessor for align
	 */
	public function getAlign() {
		return $this->_align;
	}

	/**
	 * Accessor
	 */
	public function isSortable() {
		return $this->_sortable;
	}

	/**
	 * Accessor for width
	 */
	public function getWidth() {
		if ($this->_width) {
			$ret = $this->_width;
		} else {
			$ret = '';
		}
		return $ret;
	}

	/**
	 * Accessor for custom caption
	 */
	public function getCustomCaption() {
		return $this->_customCaption;
	}

}

