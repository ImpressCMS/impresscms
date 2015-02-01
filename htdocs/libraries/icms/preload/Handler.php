<?php
/**
 * ICMS Preload Handler
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license		LICENSE.txt
 * @category	ICMS
 * @package		Preload
 * @since		1.1
 * @author		marcan <marcan@impresscms.org>
 * @author	    Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 * @version		SVN: $Id: Handler.php 12368 2013-11-17 04:04:28Z skenow $
 */

defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');

/**
 * icms_preload_Handler
 *
 * Class handling preload events automatically detect from the files in ICMS_PRELOAD_PATH
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @category	ICMS
 * @package		Preload
 * @since		1.1
 * @author		marcan <marcan@impresscms.org>
 */
class icms_preload_Handler {

	/**
	 * @var array $_preloadFilesArray array containing a list of all preload files in ICMS_PRELOAD_PATH
	 */
	private $_preloadFilesArray = array();

	/**
	 * @var array $_preloadEventsArray array containing a list of all events for all preload file, indexed by event name and sorted by order of execution
	 */
	private $_preloadEventsArray = array();

	/**
	 * Constructor
	 *
	 * Determine the preloads by scanning the preloads directory and the preloads directory for each module specified
	 * Preloads in the system preloads directory will execute for all requests. Preloads in a module's preload directory
	 * will only load for that module's requests
	 *
	 * @return	void
	 */
	public function __construct() {
		$preloadFilesArray = str_replace('.php', '', icms_core_Filesystem::getFileList(ICMS_PRELOAD_PATH, '', array('php')));
		foreach ($preloadFilesArray as $filename) {
			// exclude index.html
			if (!in_array($this->getClassName($filename), get_declared_classes())) {
				$this->_preloadFilesArray[] = $filename;
				$this->addPreloadEvents($filename);
			}
		}

		// add ondemand preload
		global $icmsOnDemandPreload;
		if (isset($icmsOnDemandPreload) && count($icmsOnDemandPreload) > 0) {
			foreach ($icmsOnDemandPreload as $onDemandPreload) {
				$this->_preloadFilesArray[] = $onDemandPreload['filename'];
				$this->addPreloadEvents($onDemandPreload['filename'], $onDemandPreload['module']);
			}
		}
	}

	/**
	 * Add the events defined in filename
	 *
	 * To be attached to the preload events, methods in each preload class must be prefixed with 'event'
	 * and have a defined event (like 'beforeFilterHTMLinput'). A fully qualified method would look
	 * like 'eventBeforeFilterHTMLinput'
	 *
	 * @todo	implement an order parameter, to enable prioritizing responses to an event
	 *
	 * @param string $filename
	 */
	public function addPreloadEvents($filename, $module = false) {
		if ($module) {
			$filepath = ICMS_ROOT_PATH . "/modules/$module/preload/$filename.php";
		} else {
			$filepath = ICMS_PRELOAD_PATH . "/$filename.php";
		}
		include_once $filepath;

		$classname = $this->getClassName($filename);

		if (in_array($classname, get_declared_classes())) {
			$preloadItem = new $classname();

			$class_methods = get_class_methods($classname);
			foreach ($class_methods as $method) {
				if (strpos($method, 'event') === 0) {
					$preload_event = strtolower(str_replace('event', '', $method));

					$callback = array( $preloadItem, $method );
					icms_Event::attach('icms', $preload_event, $callback);
					/*
					$preload_event_weight_define_name = strtoupper($classname) . '_' . strtoupper($preload_event);
					if (defined($preload_event_weight_define_name)) {
						$preload_event_weight = constant($preload_event_weight_define_name);
						$this->_preloadEventsArray[$preload_event][$preload_event_weight] = $preload_event_array;
					} else {
						$this->_preloadEventsArray[$preload_event][] = $preload_event_array;
					}*/
				}
			}
		}
	}

	/**
	 * Access the only instance of this class
	 *
	 * @static
	 * @staticvar   object
	 * @return	object
	 *
	 */
	static public function &getInstance() {
		static $instance;
		if (!isset($instance)) {
			$instance = new icms_preload_Handler();
		}
		return $instance;
	}

	/**
	 * Triggers a specific event on all the libraries
	 *
	 * Here are the currently supported events:
	 * - startCoreBoot	 triggered at the  start of the core booting process (start  of include/common.php)
	 * - finishCoreBoot	 triggered at the end of the core booting process (end of include/common.php)
	 * - adminHeader	 triggered when calling icms_cp_header() and is used to output content in the head section of the admin side
	 * - beforeFooter	 triggered when include/footer.php is called, at the beginning of the file
	 * - startOutputInit	 triggered when starting to output the content, in include/header.php after instantiation of $xoopsTpl
	 * - adminBeforeFooter	 triggered before the footer is loaded in the admin control panel
	 * - beforeFilterTextareaInput	 triggered before text from a textarea is processed to save to the database (@see icms_core_DataFilter)
	 * - afterFilterTextareaInput	 triggered after text from a textarea is processed to save to the database (@see icms_core_DataFilter)
	 * - beforeFilterTextareaDisplay	 triggered before text from a textarea is processed to display (@see icms_core_DataFilter)
	 * - afterFilterTextareaDisplay	 triggered after text from a textarea is processed to display (@see icms_core_DataFilter)
	 * - beforeFilterHTMLinput	 triggered before text from a textarea is processed as HTML to save to the database (@see icms_core_DataFilter)
	 * - afterFilterHTMLinput	 triggered after text from a textarea is processed as HTML to save to the database (@see icms_core_DataFilter)
	 * - beforeFilterHTMLdisplay	 triggered before text from a textarea is processed as HTML to display (@see icms_core_DataFilter)
	 * - afterFilterHTMLdisplay	 triggered after text from a textarea is processed as HTML to display (@see icms_core_DataFilter)
	 * - beforeDisplayTarea	 triggered before before text from a textarea is processed to display (@see icms_core_Textsanitizer)
	 * - afterDisplayTarea	 triggered after text from a textarea is processed to display (@see icms_core_Textsanitizer)
	 * - beforePreviewTarea	 triggered before text from a textarea is processed for preview (@see icms_core_Textsanitizer)
	 * - afterPreviewTarea	 triggered after text from a textarea is processed for preview (@see icms_core_Textsanitizer)
	 * - savingSystemAdminPreferencesItem	 triggered before saving preferences in the admin control panel (modules/system/preferences/main.php)
	 * - afterSaveSystemAdminPreferencesItems	triggered after  saving preferences in the admin control panel (modules/system/preferences/main.php)
	 *
	 * @param $event string name of the event to trigger
	 * @param $array mixed container to pass any arguments to be used by the library
	 *
	 * @return	TRUE if successful, FALSE if not
	 */
	public function triggerEvent($event, $array = array()) {
		$event = strtolower($event);
		icms_Event::trigger('icms', $event, null, $array);
	}

	/**
	 * Construct the name of the class based on the filename
	 *
	 * All preloads will be discovered if the class name is the
	 * file name without the extension (uppercase the first letter) prefixed with 'IcmsPreload'
	 * For example, file name = protectEmail.php -> class name = IcmsPreloadProtectEmail
	 *
	 * @todo	switch to use the PSR-0 naming convention (plugin_preload_{filename})
	 *
	 * @param	$filename string filename where the class is located
	 * @return	string name of the class
	 */
	public function getClassName($filename) {
		return 'IcmsPreload' . ucfirst(str_replace('.php', '', $filename));
	}
}
