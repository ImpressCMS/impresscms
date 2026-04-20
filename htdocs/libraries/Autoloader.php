<?php
/**
 * ImpressCMS legacy autoloader bridge.
 *
 * Bridges the legacy PSR-0 style icms_* class names to their PSR-4 Icms\*
 * equivalents and provides a robust fallback so that:
 *
 *   1. icms_ipf_Handler          → Icms\Ipf\Handler
 *   2. icms_core_DataFilter      → Icms\Core\DataFilter
 *   3. Unrefactored legacy files still load from their original lowercase
 *      libraries/icms/foo/Bar.php path.
 *
 * Loaded automatically via the "autoload.files" entry in composer.json so it
 * is registered on every request regardless of whether vendor/ lives in the
 * web root or in the trust path.
 *
 * @copyright   The ImpressCMS Project https://www.impresscms.org/
 * @license     https://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU GPL v2
 * @package     icms
 */

declare(strict_types=1);

if (!function_exists('icms_legacy_autoloader_register')) {
    /**
     * Register the legacy → PSR-4 autoloader bridge. Idempotent.
     */
    function icms_legacy_autoloader_register(): void
    {
        static $registered = false;
        if ($registered) {
            return;
        }
        $registered = true;

        $librariesDir = __DIR__;
        $icmsDir = $librariesDir . DIRECTORY_SEPARATOR . 'icms';

        // Legacy name → modern PSR-4 class map for identifiers that were
        // renamed (not just moved) during the refactor, e.g. when the modern
        // class name is not a direct PascalCase transform of the legacy one.
        $renameMap = [
            'icms_core_Object' => 'Icms\\Core\\Entity',
        ];

        spl_autoload_register(
            static function (string $class) use ($librariesDir, $icmsDir, $renameMap): void {
                if (isset($renameMap[$class])) {
                    $target = $renameMap[$class];
                    if (class_exists($target, true) || interface_exists($target, true) || trait_exists($target, true)) {
                        if (
                            !class_exists($class, false)
                            && !interface_exists($class, false)
                            && !trait_exists($class, false)
                        ) {
                            class_alias($target, $class);
                        }
                    }
                    return;
                }
                // Classmap: bare "icms" abstract base class → libraries/icms.php
                if ($class === 'icms') {
                    $file = $librariesDir . DIRECTORY_SEPARATOR . 'icms.php';
                    if (is_file($file)) {
                        require_once $file;
                    }
                    return;
                }

                // Legacy PSR-0 style names (icms_xxx_yyy) get routed to the
                // modern Icms\Xxx\Yyy namespace when possible. If the modern
                // class file exists, loading it will register the legacy
                // alias via class_alias() at the foot of the file.
                if (strncmp($class, 'icms_', 5) === 0) {
                    $parts = array_map('ucfirst', explode('_', substr($class, 5)));
                    $psr4Class = 'Icms\\' . implode('\\', $parts);
                    $psr4File = $icmsDir . DIRECTORY_SEPARATOR
                        . str_replace('\\', DIRECTORY_SEPARATOR, substr($psr4Class, 5))
                        . '.php';
                    if (is_file($psr4File)) {
                        require_once $psr4File;
                        if (
                            !class_exists($class, false)
                            && !interface_exists($class, false)
                            && !trait_exists($class, false)
                            && (
                                class_exists($psr4Class, false)
                                || interface_exists($psr4Class, false)
                                || trait_exists($psr4Class, false)
                            )
                        ) {
                            class_alias($psr4Class, $class);
                        }
                        return;
                    }
                    // Fallback: legacy lowercase file layout used before the
                    // PSR-4 migration. Kept for safety during the transition.
                    $legacyFile = $librariesDir . DIRECTORY_SEPARATOR
                        . str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
                    if (is_file($legacyFile)) {
                        require_once $legacyFile;
                    }
                    return;
                }

                // PSR-4: Icms\Ipf\Handler → libraries/icms/Ipf/Handler.php
                if (strncmp($class, 'Icms\\', 5) === 0) {
                    $file = $icmsDir . DIRECTORY_SEPARATOR
                        . str_replace('\\', DIRECTORY_SEPARATOR, substr($class, 5))
                        . '.php';
                    if (is_file($file)) {
                        require_once $file;
                    }
                }
            },
            true,
            true,
        );
    }
}

icms_legacy_autoloader_register();
