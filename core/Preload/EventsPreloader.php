<?php
/**
 * ICMS Preload Handler
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @license	LICENSE.txt
 * @since	1.1
 * @author	marcan <marcan@impresscms.org>
 * @author	Sina Asghari (aka stranger) <pesian_stranger@users.sourceforge.net>
 */

namespace ImpressCMS\Core\Preload;

use ImpressCMS\Core\Event;

/**
 * icms_preload_Handler
 *
 * Class handling preload events automatically detect from the files in ICMS_PRELOAD_PATH
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license	http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package	ICMS\Preload
 * @since	1.1
 * @author	marcan <marcan@impresscms.org>
 */
class EventsPreloader {

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
		foreach (\icms::getInstance()->get('preload') as $preloadClass) {
			$this->registerEvents($preloadClass);
		}
	}

	/**
	 * Add the events defined in filename
	 *
	 * To be attached to the preload events, methods in each preload class must be prefixed with 'event'
	 * and have a defined event (like 'beforeFilterHTMLinput'). A fully qualified method would look
	 * like 'eventBeforeFilterHTMLinput'
	 *
	 * @param string $filename Filename for where to add Preload events
	 * @param bool $module Module name
	 *
	 * @todo    implement an order parameter, to enable prioritizing responses to an event
	 *
	 */
	public function addPreloadEvents($filename, $module = false)
	{
		if ($module) {
			$filepath = ICMS_ROOT_PATH . "/modules/$module/preload/$filename.php";
		} else {
			$filepath = ICMS_PRELOAD_PATH . "/$filename.php";
		}
		include_once $filepath;

		$classname = $this->getClassName($filename);

		if (in_array($classname, get_declared_classes(), true)) {
			$this->registerEvents(
				new $classname()
			);
		}
	}

	/**
	 * Register preload events
	 *
	 * @param object $instance Preload instance
	 */
	protected function registerEvents($instance)
	{
		$class_methods = get_class_methods($instance);
		foreach ($class_methods as $method) {
			if (strpos($method, 'event') === 0) {
				$preload_event = strtolower(str_replace('event', '', $method));

				Event::attach('icms', $preload_event, [$instance, $method]);
			}
		}
	}

	/**
	 * Access the only instance of this class
	 *
	 * @static
	 * @staticvar   object
	 * @return    object
	 *
	 */
	static public function &getInstance()
	{
		static $instance;
		if (!isset($instance)) {
			$instance = new EventsPreloader();
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
	 * - beforeFilterTextareaInput	 triggered before text from a textarea is processed to save to the database (@see \ImpressCMS\Core\DataFilter)
	 * - afterFilterTextareaInput	 triggered after text from a textarea is processed to save to the database (@see \ImpressCMS\Core\DataFilter)
	 * - beforeFilterTextareaDisplay	 triggered before text from a textarea is processed to display (@see \ImpressCMS\Core\DataFilter)
	 * - afterFilterTextareaDisplay	 triggered after text from a textarea is processed to display (@see \ImpressCMS\Core\DataFilter)
	 * - beforeFilterHTMLinput	 triggered before text from a textarea is processed as HTML to save to the database (@see \ImpressCMS\Core\DataFilter)
	 * - afterFilterHTMLinput	 triggered after text from a textarea is processed as HTML to save to the database (@see \ImpressCMS\Core\DataFilter)
	 * - beforeFilterHTMLdisplay	 triggered before text from a textarea is processed as HTML to display (@see \ImpressCMS\Core\DataFilter)
	 * - afterFilterHTMLdisplay	 triggered after text from a textarea is processed as HTML to display (@see \ImpressCMS\Core\DataFilter)
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
		Event::trigger('icms', $event, null, $array);
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

