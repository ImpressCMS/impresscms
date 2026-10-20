<?php

declare(strict_types=1);

namespace App\Admin\Attribute;

/**
 * Marks a controller as a function of the system module's administration,
 * `modules/system/admin.php?fct=<fct>` (design D3):
 *
 *     #[SystemAdminFunction('mimetype')]
 *
 * Before the controller runs, App\Admin\SystemAdminContext sets ImpressCMS up
 * as admin.php does (admin mode, the full boot, cp_functions.php, the system
 * constants and language files) and applies its access rules. A visitor who
 * may not use the function gets the legacy denial instead of the controller.
 * It replaces #[RequiresLegacy] for such controllers.
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
final class SystemAdminFunction
{
    public function __construct(public readonly string $fct)
    {
    }
}
