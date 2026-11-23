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

		if (!empty($github_data) && is_array($github_data)) {
		    $latest_release = $github_data;
			// Populate the latest array with the new structure
			$this->latest['version_name'] = !empty($latest_release['name']) ? $latest_release['name'] : $latest_release['tag_name'];
			$this->latest['build'] = $latest_release['id'];
			$this->latest['status'] = 10; // Only final releases are selected by get_latest()
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



	private function get_latest($owner = 'ImpressCMS', $repo = 'impresscms')
	{
		// Fetch the list of releases (not /releases/latest, which is based on creation date
		// and could return an older branch's patch release as "latest")
		$url = "https://api.github.com/repos/$owner/$repo/releases?per_page=100";

		// Call the GitHub API
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_USERAGENT, 'ImpressCMS-VersionChecker/2.0');
	    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
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
	    $release = json_decode($response, true);

	    if (json_last_error() !== JSON_ERROR_NONE) {
	        $this->errors[] = "JSON decode error: " . json_last_error_msg();
	        return array();
	    }

		if (!is_array($release)) {
			return array();
		}

		return $this->selectHighestFinalRelease($release);
	}

	/**
	 * Select the release with the highest version number, ignoring drafts and pre-releases
	 *
	 * @param array $releases Releases as returned by the GitHub API
	 * @return array The selected release or an empty array if there is none
	 */
	private function selectHighestFinalRelease(array $releases): array
	{
		$best = array();
		$bestVersion = '';
		foreach ($releases as $release) {
			if (!is_array($release) || !empty($release['draft']) || !empty($release['prerelease'])) {
				continue;
			}
			$version = $this->extractVersionNumber((string)($release['tag_name'] ?? ''));
			// skip tags without a numeric version
			if (!preg_match('/^\d/', $version)) {
				continue;
			}
			if ($bestVersion === '' || version_compare($version, $bestVersion, '>')) {
				$best = $release;
				$bestVersion = $version;
			}
		}
		return $best;
	}
	public function getLatestVersionNumber() : string {
		$versionName = $this->getLatestVersionName();
		if (empty($versionName)) {
			return '';
		}
		return $this->extractVersionNumber($versionName);
	}

	public function getInstalledVersionNumber() : string {
		$versionName = $this->getInstalledVersionName();
		if (empty($versionName)) {
			return '';
		}
		return $this->extractVersionNumber($versionName);
	}

	/**
	 * Extract a normalised version number from a version name, so it can be passed to version_compare()
	 *
	 * Handles optional prefixes ("ImpressCMS ", "v"), build metadata ("+build.5") and suffixes:
	 *  - stable markers (final, stable, release, ga) are dropped
	 *  - alpha/a, beta/b, rc, dev are kept (lowercase, full name) with optional number
	 *  - patch/pl/p are normalised to "pl"
	 *
	 * Examples:
	 *  "ImpressCMS 2.0.4 Final" -> "2.0.4"
	 *  "v2.0.4"                 -> "2.0.4"
	 *  "ImpressCMS 2.0.5 Alpha" -> "2.0.5-alpha"
	 *  "v2.1.0-RC.2"            -> "2.1.0-rc2"
	 *  "2.0.5b1"                -> "2.0.5-beta1"
	 *  "2.0.4-patch1+build.7"   -> "2.0.4-pl1"
	 *
	 * @param string $name
	 * @return string
	 */
	private function extractVersionNumber(string $name): string {
		$pattern = '/(\d+(?:\.\d+)*)'
			. '(?:[-_.\s]*(alpha|beta|patch|final|stable|release|rc|dev|pl|ga|a|b|p)(?![a-z])[-_.\s]*(\d*))?/i';
		if (!preg_match($pattern, $name, $m)) {
			return trim($name);
		}

		$version = $m[1];
		if (empty($m[2])) {
			return $version;
		}

		$map = array(
			'a' => 'alpha', 'alpha' => 'alpha',
			'b' => 'beta', 'beta' => 'beta',
			'rc' => 'rc', 'dev' => 'dev',
			'p' => 'pl', 'pl' => 'pl', 'patch' => 'pl',
			'final' => '', 'stable' => '', 'release' => '', 'ga' => '',
		);
		$suffix = $map[strtolower($m[2])];
		if ($suffix === '') {
			return $version;
		}
		return $version . '-' . $suffix . $m[3];
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

	    return version_compare($latestVersion, $installedVersion, ">");
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
