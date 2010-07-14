<?php
/**
 * ImpressCMS Bootstrap event handler
 *
 * @copyright	The ImpressCMS Project http://www.impresscms.org/
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package		libraries
 * @since		1.3
 * @author		marcan <marcan@impresscms.org>
 * @version		$Id: adsense.php 19775 2010-07-11 18:54:25Z malanciault $
 */

/**
 * Preload items and events for AdSense
 * @since 1.2
 *
 */
class IcmsPreloadBootstrap extends icms_preload_Item {

	/**
	 * Called after the kernel initializes a service
	 * @return	void
	 */
	function eventLoadService($params) {
		list($name, $service) = $params;
		switch ($name) {
			case "logger":
				$service->startTime('ICMS');
				$service->startTime('ICMS Boot');
				break;
		}
	}

}

