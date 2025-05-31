<?php
/**
 * RSS-based version checker implementation
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Core
 * @subpackage	VersionChecker
 * @since		2.0
 * @author		marcan <marcan@impresscms.org>
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * RSS-based version checker implementation
 *
 * This class implements version checking using RSS feeds
 */
class icms_core_Versionchecker_RSS extends icms_core_Versionchecker {

	/**
	 * URL of the XML containing version information
	 * @var string
	 */
	public $version_xml = "http://www.impresscms.org/impresscms_version_branch13.xml";

	/**
	 * Check for a newer version of ImpressCMS using RSS feed
	 *
	 * @return	bool	TRUE if there is an update, FALSE if no update OR errors occurred
	 */
	public function check() {
		// Create a new instance of the SimplePie object
		$feed = new icms_feeds_Simplerss();
		$feed->set_feed_url($this->version_xml);
		$feed->set_cache_duration(0);
		$feed->set_autodiscovery_level(SIMPLEPIE_LOCATOR_NONE);
		$feed->init();
		$feed->handle_content_type();

		if (!$feed->error) {
			$versionInfo['title'] = $feed->get_title();
			$versionInfo['link'] = $feed->get_link();
			$versionInfo['image_url'] = $feed->get_image_url();
			$versionInfo['image_title'] = $feed->get_image_title();
			$versionInfo['image_link'] = $feed->get_image_link();
			$feed_item = $feed->get_item(0);
			$versionInfo['description'] = $feed_item->get_description();
			$versionInfo['permalink'] = $feed_item->get_permalink();
			$versionInfo['title'] = $feed_item->get_title();
			$versionInfo['content'] = $feed_item->get_content();
			$guidArray = $feed_item->get_item_tags('', 'guid');
			$versionInfo['guid'] = $guidArray[0]['data'];
		} else {
			$this->errors[] = _AM_VERSION_CHECK_RSSDATA_EMPTY;
			return false;
		}

		// Populate the latest array with the new structure
		$this->latest['version_name'] = $versionInfo['title'];
		$this->latest['changelog'] = $versionInfo['description'];
		$build_info = explode('|', $versionInfo['guid']);
		$this->latest['build'] = $build_info[0];
		$this->latest['status'] = $build_info[1];
		$this->latest['url'] = $versionInfo['link'];

		// Sync legacy properties for backward compatibility
		$this->syncLegacyProperties();

		if ($this->latest['build'] > ICMS_VERSION_BUILD) {
			// There is an update available
			return true;
		}
		return false;
	}

	/**
	 * Access the only instance of this class
	 *
	 * @static
	 * @staticvar object
	 *
	 * @return	object
	 */
	static public function &getInstance() {
		static $instance;
		if (!isset($instance)) {
			$instance = new self();
		}
		return $instance;
	}
}

/**
 * Backward compatibility alias
 *
 * @deprecated Use icms_core_Versionchecker_RSS instead
 */
if (!class_exists('icms_core_Versionchecker_Original')) {
	class_alias('icms_core_Versionchecker_RSS', 'icms_core_Versionchecker_Original');
}
