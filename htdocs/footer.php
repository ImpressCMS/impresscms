<?php

defined('ICMS_ROOT_PATH') || die('ICMS root path not defined');

global $icmsResponse;
($icmsResponse instanceof \icms_response_Text) || die('There are no unused response (Maybe you are trying to include footer.php twice?');

$_SESSION['ad_sess_regen'] = FALSE;
if (isset($_SESSION['sess_regen']) && $_SESSION['sess_regen']) {
	icms::$session->sessionOpen(TRUE);
	$_SESSION['sess_regen'] = FALSE;
} else {
	icms::$session->sessionOpen();
}

// ################# Preload Trigger beforeFooter ##############
\icms::$preload->triggerEvent('beforeFooter');

\icms::$logger->stopTime('Module display');

$icmsResponse->render();

\icms::$logger->stopTime();
