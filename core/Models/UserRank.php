<?php
/**
 * ImpressCMS Userranks
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since	1.2
 * @author	Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 */

namespace ImpressCMS\Core\Models;

/**
 * Ranks to assign members
 *
 * @package	ICMS\Member\Rank
 *
 * @property int    $rank_id       Rank ID
 * @property string $rank_title    Title
 * @property int    $rank_min      Min required items count
 * @property int    $rank_max      Max required items count
 * @property int    $rank_special  Is this rank special?
 * @property string $rank_image    Image
 */
class UserRank extends AbstractExtendedModel {

	/** */
	public $content = false;

	/**
	 * @inheritDoc
	 */
	public function __construct(&$handler, array $data = []) {
		icms_loadLanguageFile('system', 'common');
		icms_loadLanguageFile('system', 'userrank', true);

		$this->initVar('rank_id', self::DTYPE_INTEGER, 0, true, 5);
		$this->initVar('rank_title', self::DTYPE_STRING, '', true, 50, null, null, _CO_ICMS_USERRANK_RANK_TITLE, _CO_ICMS_USERRANK_RANK_TITLE_DSC);
		$this->initVar('rank_min', self::DTYPE_INTEGER, 0, true, 8, null, null, _CO_ICMS_USERRANK_RANK_MIN, _CO_ICMS_USERRANK_RANK_MIN_DSC);
		$this->initVar('rank_max', self::DTYPE_INTEGER, 0, true, 8, null, null, _CO_ICMS_USERRANK_RANK_MAX, _CO_ICMS_USERRANK_RANK_MAX_DSC);
		$this->initVar('rank_special', self::DTYPE_INTEGER, 0, true, 1, null, null, _CO_ICMS_USERRANK_RANK_SPECIAL, _CO_ICMS_USERRANK_RANK_SPECIAL_DSC);
		$this->initVar('rank_image', self::DTYPE_STRING, '', true, 255, null, null, _CO_ICMS_USERRANK_RANK_IMAGE, _CO_ICMS_USERRANK_RANK_IMAGE_DSC);

		parent::__construct($handler, $data);

		$this->setControl('rank_special', 'yesno');
		$this->setControl('rank_image', 'image');
	}

	/**
	 * Create a link for cloning the object
	 * @return	string
	 */
	public function getCloneLink() {
		return '<a href="' . ICMS_MODULES_URL . '/system/admin.php?fct=userrank&amp;op=clone&amp;rank_id=' . $this->id() . '"><img src="' . ICMS_IMAGES_SET_URL . '/actions/editcopy.png" style="vertical-align: middle;" alt="' . _CO_ICMS_CUSTOMTAG_CLONE . '" title="' . _CO_ICMS_CUSTOMTAG_CLONE . '" /></a>';
	}

	/**
	 * Create a link to the image for the rank
	 * @return string
	 */
	public function getRankPicture() {
		return '<img src="' . $this->handler->getImageUrl() . $this->getVar('rank_image') . '" />';
	}

	/**
	 * Accessor for the rank_title property
	 * @return	string
	 */
	public function getRankTitle() {
		return $this->getVar('rank_title');
	}
}
