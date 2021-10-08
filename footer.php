<?php
/**
 * This file is used as footer for all places where content is generated not in object way
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @package     ImpressCMS/Core
 *
 * @todo        Remove this file in the future
 */

$_SESSION['ad_sess_regen'] = false;
/**
 * @var \Aura\Session\Session $session
 */
$session = \icms::$session;
if (isset($_SESSION['sess_regen']) && $_SESSION['sess_regen']) {
	$session->start();
	$session->regenerateId();
} else {
	$session->resume();
}

// ################# Preload Trigger beforeFooter ##############
\icms::$preload->triggerEvent('beforeFooter');

\icms::$logger->stopTime('Module display');

global $xoopsOption;

$response = $xoopsOption['response']->getBody();

\icms::$logger->stopTime();

echo $response;
