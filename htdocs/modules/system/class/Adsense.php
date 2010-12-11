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
 *
 * @category	ICMS
 * @package		Administration
 * @subpackage	Adsense
 *
 */
class mod_system_Adsense extends icms_ipf_Object {

	/**
	 *
	 * @var unknown_type
	 */
	public $content = false;

	public function __construct(&$handler) {
		parent::__construct($handler);

		$this->quickInitVar('adsenseid', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('description', XOBJ_DTYPE_TXTAREA, true, _CO_ICMS_ADSENSE_DESCRIPTION, _CO_ICMS_ADSENSE_DESCRIPTION_DSC);
		$this->quickInitVar('client_id', XOBJ_DTYPE_TXTBOX, true, _CO_ICMS_ADSENSE_CLIENT_ID, _CO_ICMS_ADSENSE_CLIENT_ID_DSC);
		$this->quickInitVar('tag', XOBJ_DTYPE_TXTBOX, false, _CO_ICMS_ADSENSE_TAG, _CO_ICMS_ADSENSE_TAG_DSC);
		$this->quickInitVar('format', XOBJ_DTYPE_TXTBOX, true, _CO_ICMS_ADSENSE_FORMAT, _CO_ICMS_ADSENSE_FORMAT_DSC);
		$this->quickInitVar('border_color', XOBJ_DTYPE_TXTBOX, true, _CO_ICMS_ADSENSE_BORDER_COLOR, _CO_ICMS_ADSENSE_BORDER_COLOR_DSC);
		$this->quickInitVar('background_color', XOBJ_DTYPE_TXTBOX, true, _CO_ICMS_ADSENSE_BACKGROUND_COLOR, _CO_ICMS_ADSENSE_BORDER_COLOR_DSC);
		$this->quickInitVar('link_color', XOBJ_DTYPE_TXTBOX, true, _CO_ICMS_ADSENSE_LINK_COLOR, _CO_ICMS_ADSENSE_LINK_COLOR_DSC);
		$this->quickInitVar('url_color', XOBJ_DTYPE_TXTBOX, true, _CO_ICMS_ADSENSE_URL_COLOR, _CO_ICMS_ADSENSE_URL_COLOR_DSC);
		$this->quickInitVar('text_color', XOBJ_DTYPE_TXTBOX, true, _CO_ICMS_ADSENSE_TEXT_COLOR, _CO_ICMS_ADSENSE_TEXT_COLOR_DSC);
		$this->quickInitVar('style', XOBJ_DTYPE_TXTAREA, false, _CO_ICMS_ADSENSE_STYLE, _CO_ICMS_ADSENSE_STYLE_DSC);

		$this->setControl('format', array('handler' => 'adsense','method' => 'getFormats'));
		$this->setControl('border_color', array('name' => 'text','size' => 6,'maxlength' => 6));
		$this->setControl('background_color', array('name' => 'text','size' => 6,'maxlength' => 6));
		$this->setControl('link_color', array('name' => 'text','size' => 6,'maxlength' => 6));
		$this->setControl('url_color', array('name' => 'text','size' => 6,'maxlength' => 6));
		$this->setControl('text_color', array('name' => 'text','size' => 6,'maxlength' => 6));
	}


	/**
	 *
	 * @param unknown_type $key
	 * @param str $format
	 */
	public function getVar($key, $format = 's') {
		if ($format == 's' && in_array($key, array())) {
			return call_user_func(array($this, $key));
		}
		return parent::getVar($key, $format);
	}

	/**
	 *
	 */
	public function render() {
		global $icms_adsense_handler;
		if ($this->getVar('style', 'n') != '') {
			$ret = '<div style="' . $this->getVar('style', 'n') . '">';
		} else {
			$ret = '<div>';
		}

		$ret .= '<script type="text/javascript"><!--
			google_ad_client = "' . $this->getVar('client_id', 'n') . '";
			google_ad_width = ' . $icms_adsense_handler->adFormats[$this->getVar('format', 'n')]['width'] . ';
			google_ad_height = ' . $icms_adsense_handler->adFormats[$this->getVar('format', 'n')]['height'] . ';
			google_ad_format = "' . $this->getVar('format', 'n') . '";
			google_ad_type = "text";
			google_ad_channel ="";
			google_color_border = "' . $this->getVar('border_color', 'n') . '";
			google_color_bg = "' . $this->getVar('background_color', 'n') . '";
			google_color_link = "' . $this->getVar('link_color', 'n') . '";
			google_color_url = "' . $this->getVar('url_color', 'n') . '";
			google_color_text = "' . $this->getVar('text_color', 'n') . '";
			//--></script>
			<script type="text/javascript"
			  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
			</script>
			</div>';
		return $ret;
	}

	/**
	 *
	 */
	public function getXoopsCode() {
		$ret = '[adsense]' . $this->getVar('tag', 'n') . '[/adsense]';
		return $ret;
	}

	/**
	 *
	 */
	public function getCloneLink() {
		$ret = '<a href="' . ICMS_URL . '/modules/system/admin.php?fct=adsense&amp;op=clone&amp;adsenseid='
			. $this->id() . '"><img src="' . ICMS_IMAGES_SET_URL . '/actions/editcopy.png" style="vertical-align: middle;" alt="'
			. _CO_ICMS_CUSTOMTAG_CLONE . '" title="' . _CO_ICMS_CUSTOMTAG_CLONE . '" /></a>';
		return $ret;
	}

	/**
	 *
	 * @param str $var
	 */
	public function emptyString($var) {
		return (strlen($var) > 0);
	}

	/***
	 *
	 */
	public function generateTag() {
		$title = rawurlencode(strtolower($this->getVar('description', 'e')));
		$title = icms_core_DataFilter::icms_substr($title, 0, 10, '');
		// Transformation des ponctuations
		//				 Tab	 Space	  !		"		#		%		&		'		(		)		,		/		:		;		<		=		>		?		@		[		\		]		^		{		|		}		~	   .
		$pattern = array("/%09/", "/%20/", "/%21/", "/%22/", "/%23/", "/%25/", "/%26/", "/%27/", "/%28/", "/%29/", "/%2C/", "/%2F/", "/%3A/", "/%3B/", "/%3C/", "/%3D/", "/%3E/", "/%3F/", "/%40/", "/%5B/", "/%5C/", "/%5D/", "/%5E/", "/%7B/", "/%7C/", "/%7D/", "/%7E/", "/\./");
		$rep_pat = array("-", "-", "-", "-", "-", "-100", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-at-", "-", "-", "-", "-", "-", "-", "-", "-", "-");
		$title = preg_replace($pattern, $rep_pat, $title);

		// Transformation des caract�res accentu�s
		//				  �		�		�		�		�		�		�		�		�		�		�		�		�		�		�		�		$pattern = array ("/%B0/", "/%E8/", "/%E9/", "/%EA/", "/%EB/", "/%E7/", "/%E0/", "/%E2/", "/%E4/", "/%EE/", "/%EF/", "/%F9/", "/%FC/", "/%FB/", "/%F4/", "/%F6/" );
		$rep_pat = array("-", "e", "e", "e", "e", "c", "a", "a", "a", "i", "i", "u", "u", "u", "o", "o");
		$title = preg_replace($pattern, $rep_pat, $title);

		$tableau = explode("-", $title); // Transforme la chaine de caract�res en tableau
		$tableau = array_filter($tableau, array($this, "emptyString")); // Supprime les chaines vides du tableau
		$title = implode("-", $tableau); // Transforme un tableau en chaine de caract�res s�par� par un tiret

		$title = $title . time();
		$title = md5($title);
		return $title;
	}

	/**
	 *
	 */
	public function getAdsenseName() {
		$ret = $this->getVar('description');
		return $ret;
	}
}
