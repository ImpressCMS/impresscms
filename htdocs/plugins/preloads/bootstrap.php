<?php

/**
 * ImpressCMS Bootstrap event handler
 *
 * @copyright The ImpressCMS Project http://www.impresscms.org/
 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @package libraries
 * @since 1.3
 * @author marcan <marcan@impresscms.org>
 * @version $Id:$
 */
class icms_BootstrapEventHandler {

	static public function setup() {
		icms_Event::attach('icms', 'loadService', array(__CLASS__, 'loadService'));
		icms_Event::attach('icms', 'finishcoreboot', array(__CLASS__, 'finishCoreBoot'));
		icms_Event::attach('icms', '*', array(__CLASS__, 'backwardCompatibility'));
	}

	/**
	 * Called after the kernel initializes a service in icms::loadService
	 *
	 * @return void
	 */
	static public function loadService($params, $event) {
		switch ($params['name']) {
			case "logger":
				$params['service']->startTime('ICMS');
				$params['service']->startTime('ICMS Boot');
				break;
		}
	}

	static public function finishCoreBoot() {
		icms::$logger->stopTime('ICMS Boot');
		icms::$logger->startTime('Module init');
	}

	/**
	 * Create variables necessary for XOOPS / ICMS < 1.4 BC
	 *
	 * @param array $params
	 * @param icms_Event $event
	 */
	static public function backwardCompatibility($params, $event) {
		return true;
	}
}

icms_BootstrapEventHandler::setup();
