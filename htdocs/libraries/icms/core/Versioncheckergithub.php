<?php
/**
 * Class used to determine if the core, or modules, need to be updated
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * IcmsVersionChecker
 *
 * Class used to check if the ImpressCMS install is up to date
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Core
 * @subpackage	VersionCheckergithub
 * @since		2.0
 * @author		fiammybe <david.j@impresscms.org>
 * @todo		turn this into a generic way of testing for new updates from github, also for themes, modules, ...
 */
class icms_core_Versioncheckergithub extends icms_core_Versionchecker
{

	/*
	 * URL of the GitHub API containing version information
	 * @public $version_url string
	 */
	public $version_url = "https://api.github.com/repos/ImpressCMS/impresscms/releases/latest";



	/**
	 * Constructor
	 *
	 * @return    void
	 */
	public function __construct()
	{
		parent::__construct();

		// Add GitHub-specific fields to the installed array
		$this->installed['version'] = ICMS_VERSION_NAME; // GitHub-specific field for tag comparison
	}

	/**
	 * Access the only instance of this class
	 *
	 * @static
	 * @staticvar object
	 *
	 * @return    object
	 *
	 */
	static public function &getInstance()
	{
		static $instance;
		if (!isset($instance)) {
			$instance = new self();
		}
		return $instance;
	}

	/**
	 * Check for a newer version of ImpressCMS using GitHub API
	 *
	 * @return	bool	TRUE if there is an update, FALSE if no update OR errors occurred
	 */
	public function check() {
		// get the release data from the github API
		$github_data = $this->get_latest('ImpressCMS', 'impresscms');

		if (!empty($github_data) && is_array($github_data) && isset($github_data[0])) {
			$latest_release = $github_data[0];

			// Populate the latest array with the new structure
			$this->latest['version_name'] = $latest_release['name'];
			$this->latest['build'] = $latest_release['id'];
			$this->latest['status'] = 10; // Assume final for GitHub releases
			$this->latest['url'] = isset($latest_release['assets'][0]) ? $latest_release['assets'][0]['browser_download_url'] : $latest_release['html_url'];
			$this->latest['changelog'] = $latest_release['body'];

			// Keep GitHub-specific data in separate arrays for backward compatibility
			$this->latest['title'] = $latest_release['name'];
			$this->latest['version'] = $latest_release['tag_name'];
			$this->latest['description'] = $latest_release['body'];
			$this->latest['link'] = $this->latest['url'];

			// Sync legacy properties for backward compatibility
			$this->syncLegacyProperties();
		} else {
			$this->errors[] = _AM_VERSION_CHECK_RSSDATA_EMPTY;
			return false;
		}

		if (version_compare(substr(ICMS_VERSION_NAME, 10), substr($this->latest['version'], 1), 'lt')) {
			// There is an update available
			return true;
		}
		return false;
	}



	private function get_latest($owner = 'ImpressCMS', $repo = 'impresscms') : array
	{
		$url = "https://api.github.com/repos/$owner/$repo/releases";

		// Create a stream context
		$options = [
			'http' => [
				'method' => 'GET',
				'header' => [
					'User-Agent: PHP'
				]
			]
		];

		$context = stream_context_create($options);

		// Call the GitHub API
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'PHP');
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		$response = curl_exec($ch);

		if ($response === FALSE || curl_getinfo($ch, CURLINFO_HTTP_CODE) !== 200) {
		    curl_close($ch);
		    return array();
		}
		curl_close($ch);

		// Decode the JSON response
		$releases = json_decode($response, true);

		// Return releases or empty array if decode failed
		return is_array($releases) ? $releases : array();
	}
	public function getLatestVersionNumber() : string {
		return trim(substr($this->getLatestVersionName(), 1));
	}
	public function getInstalledVersionNumber() : string {
		return trim(substr($this->getInstalledVersionName(), 10));
	}
	/**
	 * @inheritDoc
	 */
	public function hasUpdate(): bool
	{
		return version_compare($this->getLatestVersionNumber(),$this->getInstalledVersionNumber(), '>');
	}
}
