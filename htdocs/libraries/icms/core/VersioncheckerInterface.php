<?php
/**
 * Interface for version checker implementations
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Core
 * @subpackage	VersionChecker
 * @since		2.0
 * @author		ImpressCMS Core Team
 */

defined('ICMS_ROOT_PATH') or die("ImpressCMS root path not defined");

/**
 * Interface for version checker implementations
 *
 * Defines the contract that all version checker implementations must follow
 */
interface icms_core_VersioncheckerInterface {

	/**
	 * Check for a newer version
	 *
	 * @return	bool	TRUE if there is an update, FALSE if no update OR errors occurred
	 */
	public function check();

	/**
	 * Gets all the error messages
	 *
	 * @param	bool	$ashtml	return as html?
	 * @return	mixed
	 */
	public function getErrors($ashtml = true);

	/**
	 * Get the latest version name
	 *
	 * @return	string
	 */
	public function getLatestVersionName();

	/**
	 * Get the installed version name
	 *
	 * @return	string
	 */
	public function getInstalledVersionName();

	/**
	 * Get the latest build number
	 *
	 * @return	int
	 */
	public function getLatestBuild();

	/**
	 * Get the latest version status
	 *
	 * @return	int
	 */
	public function getLatestStatus();

	/**
	 * Get the latest version URL
	 *
	 * @return	string
	 */
	public function getLatestUrl();

	/**
	 * Get the latest changelog
	 *
	 * @return	string
	 */
	public function getLatestChangelog();
}
