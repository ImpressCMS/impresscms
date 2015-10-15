<?php
/**
 * ImpressCMS Userranks
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		System
 * @subpackage	Users
 * @since		1.2
 * @author		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 */

defined("ICMS_ROOT_PATH") or die ("ImpressCMS root path not defined");

icms_loadLanguageFile("system", "common");
icms_loadLanguageFile("system", "userrank", TRUE);

/**
 * Ranks to assign members
 * 
 * @property int    $rank_id       Rank ID
 * @property string $rank_title    Title
 * @property int    $rank_min      Min required items count
 * @property int    $rank_max      Max required items count
 * @property int    $rank_special  Is this rank special?
 * @property string $rank_image    Image
 */
class icms_member_rank_Object extends icms_ipf_Object {

	/** */
	public $content = FALSE;

	/**
	 * Create a new instance of the userrank object
	 * 
	 * @param object $handler
	 */
	public function __construct(&$handler) {
		$this->initVar("rank_id", self::DTYPE_INTEGER, 0, TRUE, 5);
		$this->initVar("rank_title", self::DTYPE_STRING, '', TRUE, 50, null, null, _CO_ICMS_USERRANK_RANK_TITLE, _CO_ICMS_USERRANK_RANK_TITLE_DSC);
		$this->initVar("rank_min", self::DTYPE_INTEGER, 0, TRUE, 8, null, null, _CO_ICMS_USERRANK_RANK_MIN, _CO_ICMS_USERRANK_RANK_MIN_DSC);
		$this->initVar("rank_max", self::DTYPE_INTEGER, 0, TRUE, 8, null, null, _CO_ICMS_USERRANK_RANK_MAX, _CO_ICMS_USERRANK_RANK_MAX_DSC);
		$this->initVar("rank_special", self::DTYPE_INTEGER, 0, TRUE, 1, null, null, _CO_ICMS_USERRANK_RANK_SPECIAL, _CO_ICMS_USERRANK_RANK_SPECIAL_DSC);
		$this->initVar("rank_image", self::DTYPE_STRING, '', TRUE, 255, null, null, _CO_ICMS_USERRANK_RANK_IMAGE, _CO_ICMS_USERRANK_RANK_IMAGE_DSC);
                
                parent::__construct($handler);

		$this->setControl("rank_special", "yesno");
		$this->setControl("rank_image", "image");
	}

	/**
	 * (non-PHPdoc)
	 * @see htdocs/libraries/icms/ipf/icms_ipf_Object::getVar()
	 */
	public function getVar($key, $format = "s") {
		if ($format == "s" && in_array($key, array())) {
			return call_user_func(array($this, $key));
		}
		return parent::getVar($key, $format);
	}

	/**
	 * Create a link for cloning the object
	 * @return	str
	 */
	public function getCloneLink() {
		$ret = '<a href="' . ICMS_MODULES_URL . '/system/admin.php?fct=userrank&amp;op=clone&amp;rank_id=' . $this->id() . '"><img src="' . ICMS_IMAGES_SET_URL . '/actions/editcopy.png" style="vertical-align: middle;" alt="' . _CO_ICMS_CUSTOMTAG_CLONE . '" title="' . _CO_ICMS_CUSTOMTAG_CLONE . '" /></a>';
		return $ret;
	}

	/**
	 * Create a link to the image for the rank
	 * @return	str
	 */
	public function getRankPicture() {
		$ret = '<img src="' . $this->handler->getImageUrl() . $this->getVar("rank_image") . '" />';
		return $ret;
	}

	/**
	 * Accessor for the rank_title property
	 * @return	str
	 */
	public function getRankTitle() {
		$ret = $this->getVar("rank_title");
		return $ret;
	}
}
