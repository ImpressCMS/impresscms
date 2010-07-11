<?php
/**
 * ImpressCMS Adsenses
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Administration
 * @subpackage	Adsense
 * @since		1.2
 * @author		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version		SVN: $Id$
 */

defined("ICMS_ROOT_PATH") or die("ImpressCMS root path not defined");
/**
 * Handler for the Adsense object
 *
 *
 * @category	ICMS
 * @package		Administration
 * @subpackage	Adsense
 *
 */
class mod_system_AdsenseHandler extends icms_ipf_Handler {

	public $adFormats;
	public $adFormatsList;
	public $objects = false;

	public function __construct($db) {
		parent::__construct($db, 'adsense', 'adsenseid', 'tag', 'description', 'system');
		$this->adFormats = array();
		$this->adFormatsList = array();

		$this->adFormats['728x90_as']['caption'] = '728 X 90 Leaderboard';
		$this->adFormats['728x90_as']['width'] = 728;
		$this->adFormats['728x90_as']['height'] = 90;
		$this->adFormatsList['728x90_as'] = $this->adFormats['728x90_as']['caption'];

		$this->adFormats['468x60_as']['caption'] = '468 X 60 Banner';
		$this->adFormats['468x60_as']['width'] = 468;
		$this->adFormats['468x60_as']['height'] = 60;
		$this->adFormatsList['468x60_as'] = $this->adFormats['468x60_as']['caption'];

		$this->adFormats['234x60_as']['caption'] = '234 X 60 Half Banner';
		$this->adFormats['234x60_as']['width'] = 234;
		$this->adFormats['234x60_as']['height'] = 60;
		$this->adFormatsList['234x60_as'] = $this->adFormats['234x60_as']['caption'];

		$this->adFormats['120x600_as']['caption'] = '120 X 600 Skyscraper';
		$this->adFormats['120x600_as']['width'] = 120;
		$this->adFormats['120x600_as']['height'] = 600;
		$this->adFormatsList['120x600_as'] = $this->adFormats['120x600_as']['caption'];

		$this->adFormats['160x600_as']['caption'] = '160 X 600 Wide Skyscraper';
		$this->adFormats['160x600_as']['width'] = 160;
		$this->adFormats['160x600_as']['height'] = 600;
		$this->adFormatsList['160x600_as'] = $this->adFormats['160x600_as']['caption'];

		$this->adFormats['120x240_as']['caption'] = '120 X 240 Vertical Banner';
		$this->adFormats['120x240_as']['width'] = 120;
		$this->adFormats['120x240_as']['height'] = 240;
		$this->adFormatsList['120x240_as'] = $this->adFormats['120x240_as']['caption'];

		$this->adFormats['336x280_as']['caption'] = '336 X 280 Large Rectangle';
		$this->adFormats['336x280_as']['width'] = 136;
		$this->adFormats['336x280_as']['height'] = 280;
		$this->adFormatsList['336x280_as'] = $this->adFormats['336x280_as']['caption'];

		$this->adFormats['300x250_as']['caption'] = '300 X 250 Medium Rectangle';
		$this->adFormats['300x250_as']['width'] = 300;
		$this->adFormats['300x250_as']['height'] = 250;
		$this->adFormatsList['300x250_as'] = $this->adFormats['300x250_as']['caption'];

		$this->adFormats['250x250_as']['caption'] = '250 X 250 Square';
		$this->adFormats['250x250_as']['width'] = 250;
		$this->adFormats['250x250_as']['height'] = 250;
		$this->adFormatsList['250x250_as'] = $this->adFormats['250x250_as']['caption'];

		$this->adFormats['200x200_as']['caption'] = '200 X 200 Small Square';
		$this->adFormats['200x200_as']['width'] = 200;
		$this->adFormats['200x200_as']['height'] = 200;
		$this->adFormatsList['200x200_as'] = $this->adFormats['200x200_as']['caption'];

		$this->adFormats['180x150_as']['caption'] = '180 X 150 Small Rectangle';
		$this->adFormats['180x150_as']['width'] = 180;
		$this->adFormats['180x150_as']['height'] = 150;
		$this->adFormatsList['180x150_as'] = $this->adFormats['180x150_as']['caption'];

		$this->adFormats['125x125_as']['caption'] = '125 X 125 Button';
		$this->adFormats['125x125_as']['width'] = 125;
		$this->adFormats['125x125_as']['height'] = 125;
		$this->adFormatsList['125x125_as'] = $this->adFormats['125x125_as']['caption'];
		//$this->addPermission('view', _CO_ICMS_CUSTOMTAG_PERMISSION_VIEW, _CO_ICMS_CUSTOMTAG_PERMISSION_VIEW_DSC);
	}

	/**
	 * Get a list of ad sizes
	 */
	public function getFormats() {
		return $this->adFormatsList;
	}

	/**
	 * Action to take before saving the object
	 *
	 * @param $obj
	 */
	public function beforeSave(&$obj) {
		if ($obj->getVar('tag') == '') {
			$obj->setVar('tag', $title  = $obj->generateTag());
		}

		return true;
	}

	/**
	 * Retrieve a list of ads by a tag value
	 */
	public function getAdsensesByTag() {
		if (!$this->objects) {
			$adsensesObj = $this->getObjects(null, true);
			$ret = array();
			foreach ($adsensesObj as $adsenseObj) {
				$ret[$adsenseObj->getVar('tag')] = $adsenseObj;
			}
			$this->objects = $ret;
		}
		return $this->objects;
	}
}

