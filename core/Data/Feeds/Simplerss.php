<?php
namespace ImpressCMS\Core\Data\Feeds;

use SimplePie;

/**
 * Class handling RSS feeds, using SimplePie class
 *
 * SimplePie is a very fast and easy-to-use class, written in PHP, that puts the 'simple' back into 'really simple syndication'.
 * Flexible enough to suit beginners and veterans alike, SimplePie is focused on speed, ease of use, compatibility and
 * standards compliance.
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since	1.2
 * @author	malanciault <marcan@impresscms.org)
 * @package	ICMS\Feeds
 */
class Simplerss extends SimplePie {

	/**
	 * The Simplerss class contains feed level data and options
	 *
	 * There are two ways that you can create a new Simplerss object. The first
	 * is by passing a feed URL as a parameter to the Simplerss constructor
	 * (as well as optionally setting the cache expiry - The cache location is automatically set
	 * as ICMS_CACHE_PATH). This will initialise the whole feed with all of the default settings, and you
	 * can begin accessing methods and properties immediately.
	 *
	 * The second way is to create the Simplerss object with no parameters
	 * at all. This will enable you to set configuration options. After setting
	 * them, you must initialise the feed using $feed->init(). At that point the
	 * object's methods and properties will be available to you.
	 *
	 * @access public
	 * @param string $feed_url This is the URL you want to parse.
	 * @param int $cache_duration This is the number of seconds that you want to store the cache file for.
	 */
	public function __construct($feed_url = null, $cache_duration = null)
	{
		/* SimplePie 1.3+ does not accept arguments in the constructor */
		parent::__construct();

		$this->set_cache_location(ICMS_CACHE_PATH);
		$this->set_curl_options(
			$this->getDefaultCurlOptions()
		);

		if ($cache_duration !== null) {
			$this->set_cache_duration($cache_duration);
			$this->enable_cache(true);
		}

		// Only init the script if we're passed a feed URL
		if ($feed_url !== null) {
			$this->set_feed_url($feed_url);
			$this->init();
		}
	}

	/**
	 * Get default CURL options
	 *
	 * @return array<string, mixed>
	 */
	protected function getDefaultCurlOptions(): array
	{
		$options = [
			CURLOPT_REFERER => ICMS_URL,
			CURLOPT_USERAGENT => null,
		];

		if (env('LOGGING_ENABLED', false)) {
			$options[CURLOPT_VERBOSE] = true;
			$options[CURLOPT_STDERR] = fopen(ICMS_LOGGING_PATH . '/curl.log', 'w+');
		}

		return $options;
	}
}
