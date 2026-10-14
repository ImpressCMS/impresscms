<?php

/*
 * ImpressCMS front controller (Symfony host).
 *
 * Every request enters here. The kernel handles it; Symfony routes respond
 * directly, everything else is legacy ImpressCMS. Legacy scripts are executed
 * by the `require` below, at file scope, because they rely on their top-level
 * variables being globals (design D5). Variables of this file are prefixed with
 * `__icms_` to stay out of the way of legacy globals.
 */

declare(strict_types=1);

use App\Kernel;
use App\Runtime\HostRuntime;

// One autoloader for the whole process: ImpressCMS must not load its own.
define('ICMS_HOST_AUTOLOADED', true);

// php -S with this file as router reports the requested path as SCRIPT_NAME.
if (PHP_SAPI === 'cli-server' && isset($_SERVER['DOCUMENT_ROOT'])) {
    $__icms_root = rtrim(str_replace('\\', '/', (string) $_SERVER['DOCUMENT_ROOT']), '/');
    $__icms_self = str_replace('\\', '/', __FILE__);
    if (str_starts_with($__icms_self, $__icms_root . '/')) {
        $_SERVER['SCRIPT_NAME'] = $_SERVER['PHP_SELF'] = substr($__icms_self, strlen($__icms_root));
        $_SERVER['SCRIPT_FILENAME'] = __FILE__;
    }
    unset($__icms_root, $__icms_self);
}

require dirname(__DIR__) . '/vendor/autoload.php';

$__icms_runtime = new HostRuntime(['project_dir' => dirname(__DIR__)] + ($_SERVER['APP_RUNTIME_OPTIONS'] ?? []));
[$__icms_app, $__icms_args] = $__icms_runtime
    ->getResolver(static fn (array $context): Kernel => new Kernel((string) $context['APP_ENV'], (bool) $context['APP_DEBUG']))
    ->resolve();
$__icms_runner = $__icms_runtime->getRunner($__icms_app(...$__icms_args));
unset($__icms_runtime, $__icms_app, $__icms_args);

$__icms_status = $__icms_runner->run();

if (null !== $__icms_execution = $__icms_runner->pendingLegacyExecution()) {
    try {
        require $__icms_execution->script();
        $__icms_status = $__icms_runner->completeLegacy($__icms_execution);
    } catch (\Throwable $__icms_exception) {
        $__icms_status = $__icms_runner->failLegacy($__icms_execution, $__icms_exception);
    }
}

exit($__icms_status);
