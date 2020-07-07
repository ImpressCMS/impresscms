<?php
/**
 * ImpressCMS Userrank Handler
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since	1.2
 * @author	Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 */

namespace ImpressCMS\Core\Member;

/**
 * Handler for the user ranks object
 *
 * @package	ICMS\Member\Rank
 */
class UserRankHandler extends \ImpressCMS\Core\IPF\Handler {

	/** */
	public $objects = false;

	/**
	 * Create a new instance of the handler
	 *
	 * @param object $db
	 */
	public function __construct($db) {
		global $icmsConfigUser;

		icms_loadLanguageFile('system', 'common');
		icms_loadLanguageFile('system', 'userrank', true);

		parent::__construct($db, 'member_rank', 'rank_id', 'rank_title', '', 'icms', 'ranks', true);
		$this->enableUpload(array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png'), $icmsConfigUser['rank_maxsize'], $icmsConfigUser['rank_width'], $icmsConfigUser['rank_height']);
	}

	/**
	 *
	 *
	 * @param	int 	$rank_id
	 * @param	int 	$posts
	 * @return	array
	 */
	public function getRank($rank_id = 0, $posts = 0) {
		$rank_id = (int) $rank_id;
		$posts = (int) $posts;

		$criteria = new \ImpressCMS\Core\Database\Criteria\CriteriaCompo();
		if ($rank_id != 0) {
			$criteria->add(new \ImpressCMS\Core\Database\Criteria\CriteriaItem("rank_id", $rank_id));
		} else {
			$criteria->add(new \ImpressCMS\Core\Database\Criteria\CriteriaItem("rank_min", $posts, "<="));
			$criteria->add(new \ImpressCMS\Core\Database\Criteria\CriteriaItem("rank_max", $posts, ">="));
			$criteria->add(new \ImpressCMS\Core\Database\Criteria\CriteriaItem("rank_special", "0"));
		}

		$ranks = $this->getObjects($criteria);
		if (count($ranks) != 1) {
			$rank = array(
				"id" => 0,
				"title" => "",
				"image" => ICMS_UPLOAD_URL . "blank.gif");
		} else {
			$rank = array(
				"id" => $rank_id,
				"title" => $ranks[0]->getVar("rank_title"),
				"image" => $this->getImageUrl() . $ranks[0]->getVar("rank_image"));
		}

		return $rank;
	}

	/**
	 * Relocate images for ranks from previous location
	 * @return	bool
	 */
	public function MoveAllRanksImagesToProperPath() {
		$sql = "SELECT rank_image FROM " . $this->table;
		$Query = $this->query($sql, false);
		foreach ($Query as $qpart) {
			$file_orig = ICMS_UPLOAD_PATH . "/" . $qpart["rank_image"];
			if (file_exists($file_orig)) {
				\ImpressCMS\Core\Filesystem::copyRecursive($file_orig, $this->getImagePath() . $qpart["rank_image"]);
			}
		}

		return true;
	}
}
