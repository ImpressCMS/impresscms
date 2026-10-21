<?php

/*
 * php -S router for a web root without the index.php preamble (the bridge
 * fixtures in tests/Fixtures/legacy-root): hands every request to the host,
 * with the web root from ICMS_LEGACY_ROOT, as the preamble would.
 */

$_SERVER['ICMS_LEGACY_ROOT'] = (string) getenv('ICMS_LEGACY_ROOT');

// The fixtures have no libraries/; the host still needs the Icms\ library.
require_once dirname(__DIR__, 2) . '/htdocs/libraries/Autoloader.php';

require dirname(__DIR__, 2) . '/host.php';
