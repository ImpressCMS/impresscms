<?php

defined('ICMS_ROOT_PATH') or die('ImpressCMS root path not defined');

\icms::$logger->stopTime('Module init');
\icms::$logger->startTime('ICMS output init');

global $xoopsOption;
$xoopsOption['theme_use_smarty'] = 1;

\icms::$response = new \icms_response_HTML($xoopsOption);

\icms::$logger->stopTime('ICMS output init');
\icms::$logger->startTime('Module display');
