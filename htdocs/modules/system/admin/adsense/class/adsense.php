<?php
/**
 * ImpressCMS Adsenses
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		Administration
 * @since		1.2
 * @author		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version		$Id:$
 */

defined("ICMS_ROOT_PATH") or die("ImpressCMS root path not defined");

class SystemAdsense extends icms_ipf_Object {
	public $content = FALSE;

	public function __construct(&$handler) {
		parent::__construct($handler);

		$this->quickInitVar('adsenseid', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('description', XOBJ_DTYPE_TXTAREA, true, _CO_ICMS_ADSENSE_DESCRIPTION, _CO_ICMS_ADSENSE_DESCRIPTION_DSC);
		$this->quickInitVar('client_id', XOBJ_DTYPE_TXTBOX, true, _CO_ICMS_ADSENSE_CLIENT_ID, _CO_ICMS_ADSENSE_CLIENT_ID_DSC);
		$this->quickInitVar('slot', XOBJ_DTYPE_TXTBOX, TRUE, _CO_ICMS_ADSENSE_SLOT, _CO_ICMS_ADSENSE_SLOT_DSC);
		$this->quickInitVar('tag', XOBJ_DTYPE_TXTBOX, false, _CO_ICMS_ADSENSE_TAG, _CO_ICMS_ADSENSE_TAG_DSC);
		$this->quickInitVar('format', XOBJ_DTYPE_TXTBOX, true, _CO_ICMS_ADSENSE_FORMAT, _CO_ICMS_ADSENSE_FORMAT_DSC);
		$this->quickInitVar('color_border', XOBJ_DTYPE_TXTBOX, true, _CO_ICMS_ADSENSE_COLOR_BORDER, _CO_ICMS_ADSENSE_COLOR_BORDER_DSC);
		$this->quickInitVar('color_background', XOBJ_DTYPE_TXTBOX, true, _CO_ICMS_ADSENSE_COLOR_BACKGROUND, _CO_ICMS_ADSENSE_COLOR_BORDER_DSC);
		$this->quickInitVar('color_link', XOBJ_DTYPE_TXTBOX, true, _CO_ICMS_ADSENSE_COLOR_LINK, _CO_ICMS_ADSENSE_COLOR_LINK_DSC);
		$this->quickInitVar('color_url', XOBJ_DTYPE_TXTBOX, true, _CO_ICMS_ADSENSE_COLOR_URL, _CO_ICMS_ADSENSE_COLOR_URL_DSC);
		$this->quickInitVar('color_text', XOBJ_DTYPE_TXTBOX, true, _CO_ICMS_ADSENSE_COLOR_TEXT, _CO_ICMS_ADSENSE_COLOR_TEXT_DSC);
		$this->quickInitVar('style', XOBJ_DTYPE_TXTAREA, false, _CO_ICMS_ADSENSE_STYLE, _CO_ICMS_ADSENSE_STYLE_DSC);

		$this->setControl('format', array('handler' => 'adsense','method' => 'getFormats'));
		$this->setControl('color_border', 'color');
		$this->setControl('color_background', 'color');
		$this->setControl('color_link', 'color');
		$this->setControl('color_url', 'color');
		$this->setControl('color_text', 'color');
	}

	public function getVar($key, $format = 's') {
		if ($format == 's' && in_array($key, array("color_border", "color_background", "color_link", "color_url", "color_text"))) {
			return call_user_func(array($this, $key));
		}
		return parent::getVar($key, $format);
	}

	public function color_border() {
		$value = $this->getVar("color_border", "n");
		if ($value == "") return;
		return "#" . $value;
	}

	public function color_background() {
		$value = $this->getVar("color_background", "n");
		if ($value == "") return;
		return "#" . $value;
	}

	public function color_link() {
		$value = $this->getVar("color_link", "n");
		if ($value == "") return;
		return "#" . $value;
	}

	public function color_url() {
		$value = $this->getVar("color_url", "n");
		if ($value == "") return;
		return "#" . $value;
	}

	public function color_text() {
		$value = $this->getVar("color_text", "n");
		if ($value == "") return;
		return "#" . $value;
	}

	public function render() {
		if ($this->getVar('style', 'n') != '') {
			$ret = '<div style="' . $this->getVar('style', 'n') . '">';
		} else {
			$ret = '<div>';
		}

		$ret .= '<script type="text/javascript">';
		$ret .= 'google_ad_client = "' . $this->getVar('client_id', 'n') . '";';
		$ret .= 'google_ad_slot = "' . $this->getVar("slot", "n") . '";';
		$ret .= 'google_ad_width = ' . $this->handler->adFormats[$this->getVar('format', 'n')]['width'] . ';';
		$ret .= 'google_ad_height = ' . $this->handler->adFormats[$this->getVar('format', 'n')]['height'] . ';';
		$ret .= 'google_ad_format = "' . $this->getVar('format', 'n') . '";';
		$ret .= 'google_ad_type = "text";';
		$ret .= 'google_ad_channel ="";';
		$ret .= 'google_color_border = "' . $this->getVar('color_border', 'n') . '";';
		$ret .= 'google_color_bg = "' . $this->getVar('color_background', 'n') . '";';
		$ret .= 'google_color_link = "' . $this->getVar('color_link', 'n') . '";';
		$ret .= 'google_color_url = "' . $this->getVar('color_url', 'n') . '";';
		$ret .= 'google_color_text = "' . $this->getVar('color_text', 'n') . '";';
		$ret .= '</script>';
		$ret .= '<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js">';
		$ret .= '</script>';
		$ret .= '</div>';

		return $ret;
	}

	public function getXoopsCode() {
		$ret = '[adsense]' . $this->getVar('tag', 'n') . '[/adsense]';
		return $ret;
	}

	public function getCloneLink() {
		$ret = '<a href="' . ICMS_URL . '/modules/system/admin.php?fct=adsense&amp;op=clone&amp;adsenseid=' . $this->id() . '"><img src="' . ICMS_IMAGES_SET_URL . '/actions/editcopy.png" style="vertical-align: middle;" alt="' . _CO_ICMS_CUSTOMTAG_CLONE . '" title="' . _CO_ICMS_CUSTOMTAG_CLONE . '" /></a>';
		return $ret;
	}

	public function emptyString($var) {
		return (strlen($var) > 0);
	}

	public function generateTag() {
		$title = rawurlencode(strtolower($this->getVar('description', 'e')));
		$title = icms_core_DataFilter::icms_substr($title, 0, 10, '');

		$pattern = array ("/%09/", "/%20/", "/%21/", "/%22/", "/%23/", "/%25/", "/%26/", "/%27/", "/%28/", "/%29/", "/%2C/", "/%2F/", "/%3A/", "/%3B/", "/%3C/", "/%3D/", "/%3E/", "/%3F/", "/%40/", "/%5B/", "/%5C/", "/%5D/", "/%5E/", "/%7B/", "/%7C/", "/%7D/", "/%7E/", "/\./" );
		$rep_pat = array ("-", "-", "-", "-", "-", "-100", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-at-", "-", "-", "-", "-", "-", "-", "-", "-", "-" );
		$title = preg_replace($pattern, $rep_pat, $title);


		$rep_pat = array ("-", "e", "e", "e", "e", "c", "a", "a", "a", "i", "i", "u", "u", "u", "o", "o" );
		$title = preg_replace($pattern, $rep_pat, $title);

		$tableau = explode("-", $title);
		$tableau = array_filter($tableau, array($this, "emptyString"));
		$title = implode("-", $tableau);

		$title = $title . time ();
		$title = md5($title);
		return $title;
	}

	public function getAdsenseName() {
		$ret = $this->getVar('description');
		return $ret;
	}
}

class SystemAdsenseHandler extends icms_ipf_Handler {
	public $adFormats = array();
	private $_adFormatsList = array();
	private $_objects = FALSE;

	public function __construct(&$db) {
		parent::__construct($db, 'adsense', 'adsenseid', 'tag', 'description', 'system');
		$this->buildFormats();
	}

	private function buildFormats() {
		$this->adFormats['728x90_as']['caption'] = '728 X 90 Leaderboard';
		$this->adFormats['728x90_as']['width'] = 728;
		$this->adFormats['728x90_as']['height'] = 90;
		$this->_adFormatsList['728x90_as'] = $this->adFormats['728x90_as']['caption'];

		$this->adFormats['468x60_as']['caption'] = '468 X 60 Banner';
		$this->adFormats['468x60_as']['width'] = 468;
		$this->adFormats['468x60_as']['height'] = 60;
		$this->_adFormatsList['468x60_as'] = $this->adFormats['468x60_as']['caption'];

		$this->adFormats['234x60_as']['caption'] = '234 X 60 Half Banner';
		$this->adFormats['234x60_as']['width'] = 234;
		$this->adFormats['234x60_as']['height'] = 60;
		$this->_adFormatsList['234x60_as'] = $this->adFormats['234x60_as']['caption'];

		$this->adFormats['120x600_as']['caption'] = '120 X 600 Skyscraper';
		$this->adFormats['120x600_as']['width'] = 120;
		$this->adFormats['120x600_as']['height'] = 600;
		$this->_adFormatsList['120x600_as'] = $this->adFormats['120x600_as']['caption'];

		$this->adFormats['160x600_as']['caption'] = '160 X 600 Wide Skyscraper';
		$this->adFormats['160x600_as']['width'] = 160;
		$this->adFormats['160x600_as']['height'] = 600;
		$this->_adFormatsList['160x600_as'] = $this->adFormats['160x600_as']['caption'];

		$this->adFormats['120x240_as']['caption'] = '120 X 240 Vertical Banner';
		$this->adFormats['120x240_as']['width'] = 120;
		$this->adFormats['120x240_as']['height'] = 240;
		$this->_adFormatsList['120x240_as'] = $this->adFormats['120x240_as']['caption'];

		$this->adFormats['336x280_as']['caption'] = '336 X 280 Large Rectangle';
		$this->adFormats['336x280_as']['width'] = 136;
		$this->adFormats['336x280_as']['height'] = 280;
		$this->_adFormatsList['336x280_as'] = $this->adFormats['336x280_as']['caption'];

		$this->adFormats['300x250_as']['caption'] = '300 X 250 Medium Rectangle';
		$this->adFormats['300x250_as']['width'] = 300;
		$this->adFormats['300x250_as']['height'] = 250;
		$this->_adFormatsList['300x250_as'] = $this->adFormats['300x250_as']['caption'];

		$this->adFormats['250x250_as']['caption'] = '250 X 250 Square';
		$this->adFormats['250x250_as']['width'] = 250;
		$this->adFormats['250x250_as']['height'] = 250;
		$this->_adFormatsList['250x250_as'] = $this->adFormats['250x250_as']['caption'];

		$this->adFormats['200x200_as']['caption'] = '200 X 200 Small Square';
		$this->adFormats['200x200_as']['width'] = 200;
		$this->adFormats['200x200_as']['height'] = 200;
		$this->_adFormatsList['200x200_as'] = $this->adFormats['200x200_as']['caption'];

		$this->adFormats['180x150_as']['caption'] = '180 X 150 Small Rectangle';
		$this->adFormats['180x150_as']['width'] = 180;
		$this->adFormats['180x150_as']['height'] = 150;
		$this->_adFormatsList['180x150_as'] = $this->adFormats['180x150_as']['caption'];

		$this->adFormats['125x125_as']['caption'] = '125 X 125 Button';
		$this->adFormats['125x125_as']['width'] = 125;
		$this->adFormats['125x125_as']['height'] = 125;
		$this->_adFormatsList['125x125_as'] = $this->adFormats['125x125_as']['caption'];
	}

	public function getFormats() {
		return $this->_adFormatsList;
	}

	protected function beforeSave(&$obj) {
		if ($obj->getVar('tag') == '') {
			$obj->setVar('tag', $title  = $obj->generateTag());
		}
		$obj->setVar("color_border", str_replace("#", "", $obj->getVar("color_border")));
		$obj->setVar("color_background", str_replace("#", "", $obj->getVar("color_background")));
		$obj->setVar("color_link", str_replace("#", "", $obj->getVar("color_link")));
		$obj->setVar("color_url", str_replace("#", "", $obj->getVar("color_url")));
		$obj->setVar("color_text", str_replace("#", "", $obj->getVar("color_text")));

		return true;
	}

	public function getAdsensesByTag() {
		if (!$this->_objects) {
			$adsensesObj = $this->getObjects(null, true);
			$ret = array();
			foreach ($adsensesObj as $adsenseObj) {
				$ret[$adsenseObj->getVar('tag')] = $adsenseObj;
			}
			$this->_objects = $ret;
		}
		return $this->_objects;
	}
}