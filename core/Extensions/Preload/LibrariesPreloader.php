<?php
/**
 * Handles all functions related to 3rd party libraries within ImpressCMS
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	LICENSE.txt
 * @since	1.1
 * @author	marcan <marcan@impresscms.org>
 * @author	modified by UnderDog <underdog@impresscms.org>
 */

namespace ImpressCMS\Core\Extensions\Preload;

use ImpressCMS\Core\File\Filesystem;

/**
 * LibrariesPreloader
 *
 * Class handling third party libraries within ImpressCMS
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	ICMS\Preload
 * @since	1.1
 * @author	marcan <marcan@impresscms.org>
 *
 * @deprecated since 2.0 - use composer instead!
 */
class LibrariesPreloader {
	/**
	 * @public array $_librariesArray array containing a list of all available third party libraries
	 */
	public $_librariesArray = array();

	/**
	 * Constructor
	 *
	 * @return	void
	 */
	public function __construct() {
		$librariesArray = Filesystem::getDirList(ICMS_LIBRARIES_PATH);
		foreach ($librariesArray as $library) {
			$library_boot_file = $this->getLibraryBootFilePath($library);
			if (file_exists($library_boot_file)) {
				include_once $library_boot_file;
				$this->_librariesArray[] = $library;
			}
		}
	}

	/**
	 * Access the only instance of this class
	 *
	 *
	 * @return	self
	 */
	public static function &getInstance() {
		static $instance;
		if (!isset($instance)) {
			$instance = new self();
		}
		return $instance;
	}

	/**
	 * Triggers a specific event on all the libraries
	 *
	 * Here are the currently supported events:
	 * - finishCoreBoot : this event is triggered at the end of the core booting process (end of include/common.php)
	 * - adminHeader : this event is triggered when calling icms_cp_header() and is used to output content in the head section of the admin side
	 * - beforeFooter : this event is triggered when include/footer.php is called, at the begining of the file
	 * - startOutputInit : this event is triggered when starting to output the content, in include/header.php after instantiation of $xoopsTpl
	 *
	 * @param $event string name of the event to trigger
	 * @param $array mixed container to pass any arguments to be used by the library
	 * @return void
	 */
	public function triggerEvent($event, $array = false) {
		foreach ($this->_librariesArray as $library) {
			$functionName = $this->getFunctionName($event, $library);
			if (function_exists($functionName)) {
				$ret = $functionName($array);
			}
		}
	}

	/**
	 * Construct the path of the boot file a specified library
	 *
	 * @param $library string name of the library
	 * @return	string path of the boot file of the specified library
	 */
	public function getLibraryBootFilePath($library) {
		return ICMS_LIBRARIES_PATH.'/'.$library.'/icms.library.'.$library.'.php';
	}

	/**
	 * Construct the name of the function which would be call on a specific event for a specific library
	 *
	 * @param $event string name of the event
	 * @param $library string name of the library
	 * @return	string name of the function
	 */
	public function getFunctionName($event, $library) {
		return 'icmsLibrary'.ucfirst($library).'_'.$event;
	}
}

