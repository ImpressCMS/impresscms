<?php

/*
 * ImpressCMS front controller (Symfony host), in the trust path.
 *
 * The web root's index.php hands every request it receives to this file, with
 * ICMS_LEGACY_ROOT (the web root) and ICMS_TRUST_PATH in $_SERVER. The kernel
 * handles the request; Symfony routes respond directly, everything else is
 * legacy ImpressCMS. Legacy scripts are executed by the `require` below, at
 * file scope, because they rely on their top-level variables being globals.
 * index.php requires this file at its own file scope, so that is the global
 * scope. Variables of this file are prefixed with `__icms_` to stay out of the
 * way of legacy globals.
 */

declare(strict_types=1);

use App\Kernel;
use App\Runtime\HostRuntime;

// One autoloader for the whole process: ImpressCMS must not load its own.
define('ICMS_HOST_AUTOLOADED', true);

$__icms_legacy_root = rtrim(str_replace('\\', '/', (string) ($_SERVER['ICMS_LEGACY_ROOT'] ?? __DIR__ . '/htdocs')), '/');

// php -S with index.php as router reports the requested path as SCRIPT_NAME.
if (PHP_SAPI === 'cli-server' && isset($_SERVER['DOCUMENT_ROOT'])) {
    $__icms_docroot = rtrim(str_replace('\\', '/', (string) $_SERVER['DOCUMENT_ROOT']), '/');
    if ($__icms_legacy_root === $__icms_docroot || str_starts_with($__icms_legacy_root, $__icms_docroot . '/')) {
        $_SERVER['SCRIPT_NAME'] = $_SERVER['PHP_SELF'] = substr($__icms_legacy_root, strlen($__icms_docroot)) . '/index.php';
        $_SERVER['SCRIPT_FILENAME'] = $__icms_legacy_root . '/index.php';
    }
    unset($__icms_docroot);
}

require __DIR__ . '/vendor/autoload.php';
// The Icms\ library, its legacy icms_* names and the icms class, from the web root
// (which a test fixture may not have).
if (is_file($__icms_legacy_root . '/libraries/Autoloader.php')) {
    require_once $__icms_legacy_root . '/libraries/Autoloader.php';
}
unset($__icms_legacy_root);

$__icms_runtime = new HostRuntime(['project_dir' => __DIR__] + ($_SERVER['APP_RUNTIME_OPTIONS'] ?? []));
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
