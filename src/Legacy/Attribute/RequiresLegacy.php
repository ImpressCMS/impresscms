<?php

declare(strict_types=1);

namespace App\Legacy\Attribute;

/**
 * Boots ImpressCMS (headless) up to a stage before the controller runs.
 *
 *     #[RequiresLegacy(stage: 'language')]  // configuration and language files
 *     #[RequiresLegacy]                     // everything, including the module
 *
 * On a controller class it applies to every action.
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
final class RequiresLegacy
{
    public function __construct(public readonly string $stage = 'online')
    {
    }
}
