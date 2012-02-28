<?php
/**
 * ImpressCMS Adsenses
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		Administration
 * @subpackage	AdSense
 * @since		1.2
 * @author		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version		$Id$
 */

defined("ICMS_ROOT_PATH") or die("ImpressCMS root path not defined");

/**
 * AdSense object - Google AdSense 
 *  
 * @package		Administration
 * @subpackage	AdSense
 */
class mod_system_Adsense extends icms_ipf_Object {
	public $content = FALSE;

	/**
	 * Constructor
	 * 
	 * @param object $handler
	 */
	public function __construct(&$handler) {
		parent::__construct($handler);

		$this->quickInitVar('adsenseid', XOBJ_DTYPE_INT, TRUE);
		$this->quickInitVar('description', XOBJ_DTYPE_TXTAREA, TRUE, _CO_ICMS_ADSENSE_DESCRIPTION, _CO_ICMS_ADSENSE_DESCRIPTION_DSC);
		$this->quickInitVar('client_id', XOBJ_DTYPE_TXTBOX, TRUE, _CO_ICMS_ADSENSE_CLIENT_ID, _CO_ICMS_ADSENSE_CLIENT_ID_DSC);
		$this->quickInitVar('slot', XOBJ_DTYPE_TXTBOX, TRUE, _CO_ICMS_ADSENSE_SLOT, _CO_ICMS_ADSENSE_SLOT_DSC);
		$this->quickInitVar('tag', XOBJ_DTYPE_TXTBOX, FALSE, _CO_ICMS_ADSENSE_TAG, _CO_ICMS_ADSENSE_TAG_DSC);
		$this->quickInitVar('format', XOBJ_DTYPE_TXTBOX, TRUE, _CO_ICMS_ADSENSE_FORMAT, _CO_ICMS_ADSENSE_FORMAT_DSC);
		$this->quickInitVar('color_border', XOBJ_DTYPE_TXTBOX, TRUE, _CO_ICMS_ADSENSE_COLOR_BORDER, _CO_ICMS_ADSENSE_COLOR_BORDER_DSC);
		$this->quickInitVar('color_background', XOBJ_DTYPE_TXTBOX, TRUE, _CO_ICMS_ADSENSE_COLOR_BACKGROUND, _CO_ICMS_ADSENSE_COLOR_BORDER_DSC);
		$this->quickInitVar('color_link', XOBJ_DTYPE_TXTBOX, TRUE, _CO_ICMS_ADSENSE_COLOR_LINK, _CO_ICMS_ADSENSE_COLOR_LINK_DSC);
		$this->quickInitVar('color_url', XOBJ_DTYPE_TXTBOX, TRUE, _CO_ICMS_ADSENSE_COLOR_URL, _CO_ICMS_ADSENSE_COLOR_URL_DSC);
		$this->quickInitVar('color_text', XOBJ_DTYPE_TXTBOX, TRUE, _CO_ICMS_ADSENSE_COLOR_TEXT, _CO_ICMS_ADSENSE_COLOR_TEXT_DSC);
		$this->quickInitVar('style', XOBJ_DTYPE_TXTAREA, FALSE, _CO_ICMS_ADSENSE_STYLE, _CO_ICMS_ADSENSE_STYLE_DSC);

		$this->setControl('format', array('method' => 'getFormats'));
		$this->setControl('color_border', 'color');
		$this->setControl('color_background', 'color');
		$this->setControl('color_link', 'color');
		$this->setControl('color_url', 'color');
		$this->setControl('color_text', 'color');
	}

	/**
	 * Override accessor for several properties
	 * 
	 * @see htdocs/libraries/icms/ipf/icms_ipf_Object::getVar()
	 */
	public function getVar($key, $format = 's') {
		if ($format == 's' && in_array($key, array("color_border", "color_background", "color_link", "color_url", "color_text"))) {
			return call_user_func(array($this, $key));
		}
		return parent::getVar($key, $format);
	}

	/**
	 * Custom accessor for the color_border property
	 * @return	string
	 */
	public function color_border() {
		$value = $this->getVar("color_border", "n");
		if ($value == "") return;
		return "#" . $value;
	}

	/**
	 * Custom accessor for the color_background property
	 * @return	string
	 */
	public function color_background() {
		$value = $this->getVar("color_background", "n");
		if ($value == "") return;
		return "#" . $value;
	}

	/**
	 * Custom accessor for the color_link property
	 * @return	string
	 */
	public function color_link() {
		$value = $this->getVar("color_link", "n");
		if ($value == "") return;
		return "#" . $value;
	}

	/**
	 * Custom accessor for the color_url property
	 * @return	string
	 */
	public function color_url() {
		$value = $this->getVar("color_url", "n");
		if ($value == "") return;
		return "#" . $value;
	}

	/**
	 * Custom accessor for the color_text property
	 * @return	string
	 */
	public function color_text() {
		$value = $this->getVar("color_text", "n");
		if ($value == "") return;
		return "#" . $value;
	}

	/**
	 * Generate the script to insert the AdSense units in the theme
	 * @return	string
	 */
	public function render() {
		if ($this->getVar('style', 'n') != '') {
			$ret = '<div style="' . $this->getVar('style', 'n') . '">';
		} else {
			$ret = '<div>';
		}

		$ret .= '<script type="text/javascript">'
			. 'google_ad_client = "' . $this->getVar('client_id', 'n') . '";'
			. 'google_ad_slot = "' . $this->getVar("slot", "n") . '";'
			. 'google_ad_width = ' . $this->handler->adFormats[$this->getVar('format', 'n')]['width'] . ';'
			. 'google_ad_height = ' . $this->handler->adFormats[$this->getVar('format', 'n')]['height'] . ';'
			. 'google_ad_format = "' . $this->getVar('format', 'n') . '";'
			. 'google_ad_type = "text";'
			. 'google_ad_channel ="";'
			. 'google_color_border = "' . $this->getVar('color_border', 'n') . '";'
			. 'google_color_bg = "' . $this->getVar('color_background', 'n') . '";'
			. 'google_color_link = "' . $this->getVar('color_link', 'n') . '";'
			. 'google_color_url = "' . $this->getVar('color_url', 'n') . '";'
			. 'google_color_text = "' . $this->getVar('color_text', 'n') . '";'
			. '</script>'
			. '<script type="text/javascript" src="http://pagead2.googlesyndication.com/pagead/show_ads.js">'
			. '</script>'
			. '</div>';

		return $ret;
	}

	/**
	 * Generate the custom tag string for an AdSense unit
	 * @return	string
	 */
	public function getXoopsCode() {
		$ret = '[adsense]' . $this->getVar('tag', 'n') . '[/adsense]';
		return $ret;
	}

	/**
	 * Generate the link HTML to clone a unit
	 * @return	string 
	 */
	public function getCloneLink() {
		$ret = '<a href="' . ICMS_MODULES_URL . '/system/admin.php?fct=adsense&amp;op=clone&amp;adsenseid=' 
			. $this->id() . '"><img src="' . ICMS_IMAGES_SET_URL . '/actions/editcopy.png" style="vertical-align: middle;" alt="' 
			. _CO_ICMS_CUSTOMTAG_CLONE . '" title="' . _CO_ICMS_CUSTOMTAG_CLONE . '" /></a>';
		return $ret;
	}

	/**
	 * Determine if a string is empty
	 * @param 	string $var
	 * @return	boolean
	 */
	public function emptyString($var) {
		return (strlen($var) > 0);
	}

	/**
	 * Generate a unique tag for an AdSense unit
	 * @return	string
	 */
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

		$title = $title . time();
		$title = md5($title);
		return $title;
	}

}
