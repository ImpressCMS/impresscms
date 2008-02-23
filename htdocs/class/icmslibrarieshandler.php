<?
if (!defined('XOOPS_ROOT_PATH')) {
	die("XOOPS root path not defined");
}

include_once(XOOPS_ROOT_PATH . '/class/xoopslists.php');

/**
* IcmsLibrariiesHandler
*
* Class handling third party libraries within ImpressCMS
*
* @copyright	The ImpressCMS Project http://www.impresscms.org/
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @package		libraries
* @since		1.1
* @author		marcan <marcan@impresscms.org>
* @version		$Id$
*/
class IcmsLibrariesHandler {

	/**
	 * @var array $_librariesArray array containing a list of all available thirs partu libraries
	 */
	var $_librariesArray=array();

	/**
	 * Constructor
     *
     * @return	void
	 */
	function IcmsLibrariesHandler() {
		$librariesArray = XoopsLists::getDirListAsArray(ICMS_LIBRARIES_PATH);
		foreach ($librariesArray as $library) {
			$library_boot_file = $this->getLibraryBootFilePath($library);
			if (file_exists($library_boot_file)) {
				include_once($library_boot_file);
				$this->_librariesArray[] = $library;
			}
		}
	}

	/**
	 * Access the only instance of this class
     *
     * @static
     * @staticvar   object
     *
     * @return	object
     *
     */
	function &getInstance()
	{
		static $instance;
		if (!isset($instance)) {
			$instance = new IcmsLibrariesHandler();
		}
		return $instance;
	}

	/**
	 * Triggers a specific event on all the libraries
     *
     * @$event string name of the event to trigger
     * @$array mixed container to pass any arguments to be used by the library
     *
     * @return	TRUE if successful, FALSE if not
     */
	function triggerEvent($event, $array=false) {
		foreach($this->_librariesArray as $library) {
			$functionName = $this->getFunctionName($event, $library);
			if (function_exists($functionName)) {
				$ret = $functionName($array);
			}
		}
	}

	/**
	 * Construct the path of the boot file a specified library
     *
     * @$library string name of the library
     *
     * @return	string path of the boot file of the specified library
     *
     */
	function getLibraryBootFilePath($library) {
		$ret = ICMS_LIBRARIES_ROOT_PATH . '/' . $library . '/icms.library.' . $library . '.php';
		return $ret;
	}

	/**
	 * Construct the name of the function which would be call on a specific event for a specific library
     *
     * @$event string name of the event
     * @$library string name of the library
     *
     * @return	string name of the function
     *
     */
	function getFunctionName($event, $library) {
		$ret = 'icmsLibrary' . ucfirst($library) . '_' . $event;
		return $ret;
	}

}

?>