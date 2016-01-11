<?php
/**
 * This file is used as footer for all places where content is generated not in object way
 *
 * @copyright	http://www.impresscms.org/ The ImpressCMS Project
 * @package     ImpressCMS/Core
 *
 * @todo        Remove this file in the future
 */

(\icms::$response instanceof \icms_response_Text) || die('There are no unused response (Maybe you are trying to include footer.php twice?');

$_SESSION['ad_sess_regen'] = FALSE;
if (isset($_SESSION['sess_regen']) && $_SESSION['sess_regen']) {
	\icms::$session->sessionOpen(TRUE);
	$_SESSION['sess_regen'] = FALSE;
} else {
	\icms::$session->sessionOpen();
}

// ################# Preload Trigger beforeFooter ##############
\icms::$preload->triggerEvent('beforeFooter');

\icms::$logger->stopTime('Module display');

\icms::$response->render();
\icms::$response = null;

\icms::$logger->stopTime();
