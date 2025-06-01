<?php
/**
 * Abstract base class for version checkers
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * Abstract base class for version checkers
 *
 * Provides common functionality for checking if the ImpressCMS install is up to date
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Core
 * @subpackage	VersionChecker
 * @since		1.0
 * @author		marcan <marcan@impresscms.org>
 * @version		$Id: Versionchecker.php 11603 2012-02-26 08:45:50Z fiammy $
 */
abstract class icms_core_Versionchecker implements icms_core_VersioncheckerInterface {

	/*
	 * errors
	 * @public $errors array
	 */
	public $errors = array();

	/*
	 * Time before fetching the version information again and store it in cache
	 * @public $cache_time integer
	 * @todo set this to a day at least or make it configurable in System Admin > Preferences
	 */
	public $cache_time=1;

	/*
	 * Installed version information array
	 * @public $installed array
	 */
	public array $installed = array(
		'version_name' => null,
		'build' => null,
		'status' => null
	);

	/*
	 * Latest version information array
	 * @public $latest array
	 */
	public array $latest = array(
		'version_name' => null,
		'build' => null,
		'status' => null,
		'url' => null,
		'changelog' => null
	);

	/*
	 * Legacy properties for backward compatibility
	 * @deprecated Use $installed and $latest arrays instead
	 */
	public $installed_version_name;
	public $latest_version_name;
	public $latest_build;
	public $latest_status;
	public $latest_url;
	public $latest_changelog;
	/**
	 * @var array|null[]
	 */

	/**
	 * Constructor
	 *
	 * @return	void
	 */
	public function __construct() {
		// Initialize installed version information
		$this->installed['version_name'] = ICMS_VERSION_NAME;
		$this->installed['build'] = defined('ICMS_VERSION_BUILD') ? ICMS_VERSION_BUILD : null;
		$this->installed['status'] = defined('ICMS_VERSION_STATUS') ? ICMS_VERSION_STATUS : null;

		// Sync legacy property for backward compatibility
		$this->installed_version_name = $this->installed['version_name'];
	}

	/**
	 * Access the only instance of this class
	 *
	 * @static
	 * @staticvar object
	 *
	 * @return	object
	 */
	abstract static public function &getInstance();

	/**
	 * Check for a newer version of ImpressCMS
	 *
	 * @return	bool	TRUE if there is an update, FALSE if no update OR errors occurred
	 */
	abstract public function check();

	/**
	 * @return bool TRUE if there is an update, FALSE if no update
	 */
	abstract public function hasUpdate(): bool;
	/**
	 * Gets all the error messages
	 *
	 * @param	bool	$ashtml	return as html?
	 * @return	mixed
	 */
	public function getErrors($ashtml = true) {
		if (!$ashtml) {
			return $this->errors;
		} else {
			$ret = '';
			if (count($this->errors) > 0) {
				foreach ($this->errors as $error) {
					$ret .= $error.'<br />';
				}
			}
			return $ret;
		}
	}

	/**
	 * Synchronize legacy properties with the installed and latest arrays
	 * This ensures backward compatibility
	 */
	protected function syncLegacyProperties() {
		// Sync installed legacy property
		$this->installed_version_name = $this->installed['version_name'];

		// Sync latest legacy properties
		$this->latest_version_name = $this->latest['version_name'];
		$this->latest_build = $this->latest['build'];
		$this->latest_status = $this->latest['status'];
		$this->latest_url = $this->latest['url'];
		$this->latest_changelog = $this->latest['changelog'];
	}

	/**
	 * Get the latest version name
	 *
	 * @return	string
	 */
	public function getLatestVersionName() {
		return $this->latest['version_name'];
	}

	/**
	 * Get the installed version name
	 *
	 * @return	string
	 */
	public function getInstalledVersionName() {
		return $this->installed['version_name'];
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
		return $this->latest['build'];
	}

	/**
	 * Get the latest version status
	 *
	 * @return	int
	 */
	public function getLatestStatus() {
		return $this->latest['status'];
	}

	/**
	 * Get the latest version URL
	 *
	 * @return	string
	 */
	public function getLatestUrl() {
		return $this->latest['url'];
	}

	/**
	 * Get the latest changelog
	 *
	 * @return	string
	 */
	public function getLatestChangelog() {
		return $this->latest['changelog'];
	}

	/**
	 * Get the latest version number
	 * @return string
	 */
	public function getLatestVersionNumber() : string {
		return trim(substr($this->getLatestVersionName(), 1));
	}

	/**
	 * @inheritDoc
	 */
	public function getInstalledVersionNumber() : string {
		return trim(substr($this->getInstalledVersionName(), 10));
	}
	/**
	 * @inheritDoc
	 */
	public function getLatest() {
		return $this->latest;
	}
}
