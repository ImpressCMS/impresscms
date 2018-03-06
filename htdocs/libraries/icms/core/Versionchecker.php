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
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	ICMS\Core
 * @since	1.0
 * @author	marcan <marcan@impresscms.org>
 */
class icms_core_Versionchecker {

	/**
	 * Errors
	 *
	 * @var array
	 */
	public $errors = array();

	/**
	 * Name of the latest version
	 *
	 * @var string
	 */
	public $latest_version_name = '';

	/**
	 * Name of installed version
	 *
	 * @var $installed_version_name string
	 */
	public $installed_version_name = ICMS_VERSION_NAME;

	/**
	 * URL of the latest release
	 *
	 * @var string
	 */
	public $latest_url = '';

	/**
	 * Changelog of the latest release
	 *
	 * @var string
	 */
	public $latest_changelog = '';

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

	/**
	 * Check for a newer version of ImpressCMS
	 *
	 * @return	TRUE if there is an update, FALSE if no update OR errors occured
	 */
	public function check() {

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_URL, 'https://api.github.com/repos/ImpressCMS/impresscms/releases/latest');
		$result = curl_exec($ch);
		if (!$result) {
			$this->errors[] = curl_error($ch);
			return false;
		}
		curl_close($ch);

		$data = json_decode($result, true);

		$this->latest_version_name = $data['name'];
		$this->latest_changelog = $data['body'];

		if (substr($data['tag_name'], 0, 1) == 'v') {
			$data['tag_name'] = substr($data['tag_name'], 1);
		}

		if (version_compare(ICMS_VERSION, $data['tag_name'])) {
			// There is an update available
			$this->latest_url = $data['url'];
			return true;
		}
		return false;
	}

	/**
	 * Gets all the error messages
	 *
	 * @param	$ashtml	bool	return as html?
	 *
	 * @return	mixed
	 */
	public function getErrors($ashtml=true) {
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
}
