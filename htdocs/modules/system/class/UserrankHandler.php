<?php
/**
 * ImpressCMS Userrank Handler
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.2
 * @author		Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 */

defined("ICMS_ROOT_PATH") or die ("ImpressCMS root path not defined");

icms_loadLanguageFile("system", "common");
icms_loadLanguageFile("system", "userrank", TRUE);

/**
 * Handler for the user ranks object
 *
 * @package		System
 * @subpackage	Users
 */
class mod_system_UserrankHandler extends icms_ipf_Handler {
	
	/** */
	public $objects = FALSE;

	/**
	 * Create a new instance of the handler
	 *
	 * @param object $db
	 */
	public function __construct($db) {
		global $icmsConfigUser;
		parent::__construct($db, "userrank", "rank_id", "rank_title", "", "system");
		$this->table = $this->db->prefix("ranks");
		$this->enableUpload(array("image/gif", "image/jpeg", "image/pjpeg", "image/x-png", "image/png"), $icmsConfigUser["rank_maxsize"], $icmsConfigUser["rank_width"], $icmsConfigUser["rank_height"]);
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

		$criteria = new icms_db_criteria_Compo();
		if ($rank_id != 0) {
			$criteria->add(new icms_db_criteria_Item("rank_id", $rank_id));
		} else {
			$criteria->add(new icms_db_criteria_Item("rank_min", $posts, "<="));
			$criteria->add(new icms_db_criteria_Item("rank_max", $posts, ">="));
			$criteria->add(new icms_db_criteria_Item("rank_special", "0"));
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
		$Query = $this->query($sql, FALSE);
		foreach ($Query as $qitem) {
			$values[] = $qitem["rank_image"];
		}

		foreach ($values as $value) {
			if (file_exists(ICMS_UPLOAD_PATH . "/" . $value)) {
				icms_core_Filesystem::copyRecursive(ICMS_UPLOAD_PATH . "/" . $value, $this->getImagePath() . $value);
			}
		}

		return TRUE;
	}
}
