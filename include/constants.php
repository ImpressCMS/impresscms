<?php

use ImpressCMS\Core\Data\BootConfig;

/**
 * @var BootConfig $bootConfig
 */
$bootConfig = icms::getInstance()->get(BootConfig::class);

if (!$bootConfig->isEnvDataExists()) {
	return;
}

/**
 * @deprecated Using directly constants now is deprecated. Will be removed this in the future.
 */
foreach ($bootConfig->toArray() as $name => $value) {
	define('ICMS_' . strtoupper($name), $value);
}

/**#@+
 * Constants
 */
define('XOOPS_SIDEBLOCK_LEFT', 1);
define('XOOPS_SIDEBLOCK_RIGHT', 2);
define('XOOPS_SIDEBLOCK_BOTH', -2);
define('XOOPS_CENTERBLOCK_LEFT', 3);
define('XOOPS_CENTERBLOCK_RIGHT', 5);
define('XOOPS_CENTERBLOCK_CENTER', 4);
define('XOOPS_CENTERBLOCK_ALL', -6);
define('XOOPS_CENTERBLOCK_BOTTOMLEFT', 6);
define('XOOPS_CENTERBLOCK_BOTTOMRIGHT', 8);
define('XOOPS_CENTERBLOCK_BOTTOM', 7);

define('XOOPS_BLOCK_INVISIBLE', 0);
define('XOOPS_BLOCK_VISIBLE', 1);
define('XOOPS_MATCH_START', 0);
define('XOOPS_MATCH_END', 1);
define('XOOPS_MATCH_EQUAL', 2);
define('XOOPS_MATCH_CONTAIN', 3);