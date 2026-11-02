<?php

/*
 * ImpressCMS front controller (Laravel host).
 *
 * Every request enters here. The kernel handles it; Laravel routes respond
 * directly, everything else is legacy ImpressCMS. Legacy scripts are executed
 * by the `require` below, at file scope, because they rely on their top-level
 * variables being globals (design L5). Variables of this file are prefixed
 * with `__icms_` to stay out of the way of legacy globals.
 */

declare(strict_types=1);

use App\Runtime\HostRunner;
use App\Runtime\PhpEnvironment;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// one autoloader for the whole process: ImpressCMS must not load its own
define('ICMS_HOST_AUTOLOADED', true);

// php -S with this file as router reports the requested path as SCRIPT_NAME
if (PHP_SAPI === 'cli-server' && isset($_SERVER['DOCUMENT_ROOT'])) {
    $__icms_root = rtrim(str_replace('\\', '/', (string) $_SERVER['DOCUMENT_ROOT']), '/');
    $__icms_self = str_replace('\\', '/', __FILE__);
    if (str_starts_with($__icms_self, "{$__icms_root}/")) {
        $_SERVER['SCRIPT_NAME'] = $_SERVER['PHP_SELF'] = substr($__icms_self, strlen($__icms_root));
        $_SERVER['SCRIPT_FILENAME'] = __FILE__;
    }
    unset($__icms_root, $__icms_self);
}

require dirname(__DIR__) . '/vendor/autoload.php';

PhpEnvironment::capture();

$__icms_runner = new HostRunner(require_once dirname(__DIR__) . '/bootstrap/app.php');

if (null !== $__icms_execution = $__icms_runner->handle(Request::capture())) {
    try {
        require $__icms_execution->script();
        $__icms_runner->completeLegacy($__icms_execution);
    } catch (\Throwable $__icms_exception) {
        $__icms_runner->failLegacy($__icms_execution, $__icms_exception);
    }
}
