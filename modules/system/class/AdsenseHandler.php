<?php
/**
 * ImpressCMS Adsenses
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since	1.2
 * @author	Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 */

/**
 * Handler for AdSense object
 *
 * @package ImpressCMS\Modules\System\Class\Adsense
 */
class mod_system_AdsenseHandler extends icms_ipf_Handler {
	public $adFormats = array();
	private $_adFormatsList = array();
	private $_objects = FALSE;

	/**
	 * Constructor
	 * @param object $db	Database object
	 */
	public function __construct(&$db) {
		parent::__construct($db, 'adsense', 'adsenseid', 'tag', 'description', 'system');
		$this->buildFormats();
	}

	/**
	 * Create a list of formats for the AdSense units
	 */
	private function buildFormats() {
		$this->adFormats['728x90_as'] = array(
			'caption' => '728 X 90 Leaderboard',
			'width' => 728,
			'height' => 90,
		);
		$this->_adFormatsList['728x90_as'] = $this->adFormats['728x90_as']['caption'];

		$this->adFormats['468x60_as'] = array(
			'caption'  =>'468 X 60 Banner',
			'width' => 468,
			'height' => 60,
		);
		$this->_adFormatsList['468x60_as'] = $this->adFormats['468x60_as']['caption'];

		$this->adFormats['234x60_as'] = array(
			'caption'  =>'234 X 60 Half Banner',
			'width' => 234,
			'height' => 60,
		);
		$this->_adFormatsList['234x60_as'] = $this->adFormats['234x60_as']['caption'];

		$this->adFormats['120x600_as'] = array(
			'caption'  =>'120 X 600 Skyscraper',
			'width' => 120,
			'height' => 600,
		);
		$this->_adFormatsList['120x600_as'] = $this->adFormats['120x600_as']['caption'];

		$this->adFormats['160x600_as'] = array(
			'caption'  =>'160 X 600 Wide Skyscraper',
			'width' => 160,
			'height' => 600,
		);
		$this->_adFormatsList['160x600_as'] = $this->adFormats['160x600_as']['caption'];

		$this->adFormats['120x240_as'] = array(
			'caption'  =>'120 X 240 Vertical Banner',
			'width' => 120,
			'height' => 240,
		);
		$this->_adFormatsList['120x240_as'] = $this->adFormats['120x240_as']['caption'];

		$this->adFormats['336x280_as'] = array(
			'caption'  =>'336 X 280 Large Rectangle',
			'width' => 136,
			'height' => 280,
		);
		$this->_adFormatsList['336x280_as'] = $this->adFormats['336x280_as']['caption'];

		$this->adFormats['300x250_as'] = array(
			'caption'  =>'300 X 250 Medium Rectangle',
			'width' => 300,
			'height' => 250,
		);
		$this->_adFormatsList['300x250_as'] = $this->adFormats['300x250_as']['caption'];

		$this->adFormats['250x250_as'] = array(
			'caption'  =>'250 X 250 Square',
			'width' => 250,
			'height' => 250,
		);
		$this->_adFormatsList['250x250_as'] = $this->adFormats['250x250_as']['caption'];

		$this->adFormats['200x200_as'] = array(
			'caption'  =>'200 X 200 Small Square',
			'width' => 200,
			'height' => 200,
		);
		$this->_adFormatsList['200x200_as'] = $this->adFormats['200x200_as']['caption'];

		$this->adFormats['180x150_as'] = array(
			'caption'  =>'180 X 150 Small Rectangle',
			'width' => 180,
			'height' => 150,
		);
		$this->_adFormatsList['180x150_as'] = $this->adFormats['180x150_as']['caption'];

		$this->adFormats['125x125_as'] = array(
			'caption'  =>'125 X 125 Button',
			'width' => 125,
			'height' => 125,
		);
		$this->_adFormatsList['125x125_as'] = $this->adFormats['125x125_as']['caption'];
	}

	/**
	 * Accessor for the list of formats
	 * @return	array
	 */
	public function getFormats() {
		return $this->_adFormatsList;
	}

	/**
	 * Action to take before the AdSense object is saved
	 * @param	object 	$obj
	 * @return	boolean
	 */
	protected function beforeSave(&$obj) {
		if ($obj->getVar('tag') == '') {
			$obj->setVar('tag', $title  = $obj->generateTag());
		}
		$obj->setVar("color_border", str_replace("#", "", $obj->getVar("color_border")));
		$obj->setVar("color_background", str_replace("#", "", $obj->getVar("color_background")));
		$obj->setVar("color_link", str_replace("#", "", $obj->getVar("color_link")));
		$obj->setVar("color_url", str_replace("#", "", $obj->getVar("color_url")));
		$obj->setVar("color_text", str_replace("#", "", $obj->getVar("color_text")));

		return TRUE;
	}

	/**
	 * Retrieve an AdSense unit by its tag
	 * @return	array	Object array
	 */
	public function getAdsensesByTag() {
		if (!$this->_objects) {
			$adsensesObj = $this->getObjects(NULL, TRUE);
			$ret = array();
			foreach ($adsensesObj as $adsenseObj) {
				$ret[$adsenseObj->getVar('tag')] = $adsenseObj;
			}
			$this->_objects = $ret;
		}
		return $this->_objects;
	}
}
