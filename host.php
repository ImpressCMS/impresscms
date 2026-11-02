<?php

/*
 * ImpressCMS front controller (Laravel host), in the trust path.
 *
 * The web root's index.php hands every request it receives to this file, with
 * ICMS_LEGACY_ROOT (the web root) and ICMS_TRUST_PATH in $_SERVER. The kernel
 * handles the request; Laravel routes respond directly, everything else is
 * legacy ImpressCMS. Legacy scripts are executed by the `require` below, at
 * file scope, because they rely on their top-level variables being globals
 * (design L5). index.php requires this file at its own file scope, so that is
 * the global scope. Variables of this file are prefixed with `__icms_` to stay
 * out of the way of legacy globals.
 */

declare(strict_types=1);

use App\Runtime\HostRunner;
use App\Runtime\PhpEnvironment;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// one autoloader for the whole process: ImpressCMS must not load its own
define('ICMS_HOST_AUTOLOADED', true);

// php -S with the web root's index.php as router reports the requested path as SCRIPT_NAME
if (PHP_SAPI === 'cli-server' && isset($_SERVER['DOCUMENT_ROOT'])) {
    $__icms_docroot = rtrim(str_replace('\\', '/', (string) $_SERVER['DOCUMENT_ROOT']), '/');
    $__icms_legacy_root = rtrim(str_replace('\\', '/', (string) ($_SERVER['ICMS_LEGACY_ROOT'] ?? __DIR__ . '/htdocs')), '/');
    if ($__icms_legacy_root === $__icms_docroot || str_starts_with($__icms_legacy_root, "{$__icms_docroot}/")) {
        $_SERVER['SCRIPT_NAME'] = $_SERVER['PHP_SELF'] = substr($__icms_legacy_root, strlen($__icms_docroot)) . '/index.php';
        $_SERVER['SCRIPT_FILENAME'] = "{$__icms_legacy_root}/index.php";
    }
    unset($__icms_docroot, $__icms_legacy_root);
}

require __DIR__ . '/vendor/autoload.php';

PhpEnvironment::capture();

// bootstrap/app.php registers the Icms\ library from the web root (design L21)
$__icms_runner = new HostRunner(require_once __DIR__ . '/bootstrap/app.php');

if (null !== $__icms_execution = $__icms_runner->handle(Request::capture())) {
    try {
        require $__icms_execution->script();
        $__icms_runner->completeLegacy($__icms_execution);
    } catch (\Throwable $__icms_exception) {
        $__icms_runner->failLegacy($__icms_execution, $__icms_exception);
    }
}
