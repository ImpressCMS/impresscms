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
class icms_core_Versioncheckergithub extends icms_core_Versionchecker implements icms_core_VersioncheckerInterface
{

	/*
	 * URL of the GitHub API containing version information
	 * @public $version_url string
	 */
	public $version_url = "https://api.github.com/repos/ImpressCMS/impresscms/releases/latest";
    public $installed = array();
    public $latest = array();


	/**
	 * Constructor
	 *
	 * @return    void
	 */
	public function __construct()
	{
		parent::__construct();

		// Initialize arrays with default values
		$this->latest = array(
			'version_name' => '',
			'version' => '',
			'build' => 0,
			'status' => 0,
			'url' => '',
			'changelog' => '',
			'title' => '',
			'description' => '',
			'link' => ''
		);

		$this->installed = array(
			'version_name' => ICMS_VERSION_NAME,
			'version' => ICMS_VERSION_NAME,
			'build' => defined('ICMS_VERSION_BUILD') ? ICMS_VERSION_BUILD : 0,
			'status' => defined('ICMS_VERSION_STATUS') ? ICMS_VERSION_STATUS : 10
		);
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
	public function check(): bool
    {
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
			//$this->syncLegacyProperties();
		} else {
			$this->errors[] = _AM_VERSION_CHECK_RSSDATA_EMPTY;
			return false;
		}

		// Use the hasUpdate method for consistent version comparison
		return $this->hasUpdate();
	}



	private function get_latest($owner = 'ImpressCMS', $repo = 'impresscms') : array
	{
		$url= $this->version_url;
		//$url = "https://api.github.com/repos/$owner/$repo/releases";

		// Call the GitHub API
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'PHP');
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		$response = curl_exec($ch);

		$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if ($response === FALSE || $http_code !== 200) {
		    $error = curl_error($ch);
		    curl_close($ch);
		    $this->errors[] = "GitHub API error: " . ($error ?: "HTTP $http_code");
		    return array();
		}
		curl_close($ch);

		// Decode the JSON response
		$releases = json_decode($response, true);

		// Return releases or empty array if decode failed
		return is_array($releases) ? $releases : array();
	}
	public function getLatestVersionNumber() : string {
		$versionName = $this->getLatestVersionName();
		if (empty($versionName)) {
			return '';
		}
		// Remove 'v' prefix if present (e.g., "v2.0.1" -> "2.0.1")
		return trim(ltrim($versionName, 'v'));
	}

	public function getInstalledVersionNumber() : string {
		$versionName = $this->getInstalledVersionName();
		if (empty($versionName)) {
			return '';
		}
		// Remove "ImpressCMS " prefix if present (e.g., "ImpressCMS 2.0.1" -> "2.0.1")
		return trim(str_replace('ImpressCMS ', '', $versionName));
	}

    public function getLatestVersionName() : string {
		return isset($this->latest['version_name']) ? $this->latest['version_name'] : '';
	}

    public function getInstalledVersionName() : string {
		return isset($this->installed['version_name']) ? $this->installed['version_name'] : '';
	}

	/**
	 * Get the complete installed version information array
	 *
	 * @return	array
	 */
	public function getInstalled() {
		return $this->installed;
	}

	/**
	 * Get the latest build number
	 *
	 * @return	int
	 */
	public function getLatestBuild() {
		return isset($this->latest['build']) ? (int)$this->latest['build'] : 0;
	}

	/**
	 * Get the latest version status
	 *
	 * @return	int
	 */
	public function getLatestStatus() {
		return isset($this->latest['status']) ? (int)$this->latest['status'] : 0;
	}

	/**
	 * Get the latest version URL
	 *
	 * @return	string
	 */
	public function getLatestUrl() {
		return isset($this->latest['url']) ? $this->latest['url'] : '';
	}

	/**
	 * Get the latest changelog
	 *
	 * @return	string
	 */
	public function getLatestChangelog() {
		return isset($this->latest['changelog']) ? $this->latest['changelog'] : '';
	}

	/**
	 * Get the complete latest version information array
	 *
	 * @return	array
	 */
	public function getLatest() {
		return $this->latest;
	}

	/**
	 * @inheritDoc
	 */
	public function hasUpdate(): bool
	{
		$latestVersion = $this->getLatestVersionNumber();
		$installedVersion = $this->getInstalledVersionNumber();

		if (empty($latestVersion) || empty($installedVersion)) {
			return false;
		}

		return version_compare($latestVersion, $installedVersion, '>');
	}
	public function hasLatest(): bool
	{
		$latestVersion = $this->getLatestVersionNumber();
		$installedVersion = $this->getInstalledVersionNumber();

		if (empty($latestVersion) || empty($installedVersion)) {
			return false;
		}

		return version_compare($latestVersion, $installedVersion, '=');
	}
}
