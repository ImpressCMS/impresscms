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
	 * Name of the latest version
	 * @public $latest_version_name string
	 */
	public $latest_version_name;

	/*
	 * Name of installed version
	 * @private $installed_version_name string
	 */
	public $installed_version_name;

	/*
	 * Number of the latest build
	 * @public $latest_build integer
	 */
	public $latest_build;

	/*
	 * Status of the latest build
	 *
	 * 1  = Alpha
	 * 2  = Beta
	 * 3  = RC
	 * 10 = Final
	 *
	 * @public $latest_status integer
	 */
	public $latest_status;

	/*
	 * URL of the latest release
	 * @public $latest_url string
	 */
	public $latest_url;

	/*
	 * Changelog of the latest release
	 * @public $latest_changelog string
	 */
	public $latest_changelog;

	/**
	 * Constructor
	 *
	 * @return	void
	 *
	 */
	public function __construct() {
		$this->installed_version_name = ICMS_VERSION_NAME;
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
	 * Get the latest version name
	 *
	 * @return	string
	 */
	public function getLatestVersionName() {
		return $this->latest_version_name;
	}

	/**
	 * Get the installed version name
	 *
	 * @return	string
	 */
	public function getInstalledVersionName() {
		return $this->installed_version_name;
	}

	/**
	 * Get the latest build number
	 *
	 * @return	int
	 */
	public function getLatestBuild() {
		return $this->latest_build;
	}

	/**
	 * Get the latest version status
	 *
	 * @return	int
	 */
	public function getLatestStatus() {
		return $this->latest_status;
	}

	/**
	 * Get the latest version URL
	 *
	 * @return	string
	 */
	public function getLatestUrl() {
		return $this->latest_url;
	}

	/**
	 * Get the latest changelog
	 *
	 * @return	string
	 */
	public function getLatestChangelog() {
		return $this->latest_changelog;
	}
}
