<?php

declare(strict_types=1);

namespace App\Tests\Support;

use PHPUnit\Event\TestSuite\Started;
use PHPUnit\Event\TestSuite\StartedSubscriber;
use PHPUnit\Runner\Extension\Extension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;

/**
 * Loads htdocs/tests/environment.php when the Library suite starts.
 *
 * The library environment defines ImpressCMS path constants and a no-op
 * icms_loadLanguageFile(). Defining them globally would break the suites
 * that boot the real legacy runtime, so they are only loaded on demand.
 */
final class LibraryEnvironmentExtension implements Extension
{
    public function bootstrap(Configuration $configuration, Facade $facade, ParameterCollection $parameters): void
    {
        $facade->registerSubscriber(new class implements StartedSubscriber {
            public function notify(Started $event): void
            {
                if ($event->testSuite()->name() === 'Library') {
                    require_once dirname(__DIR__, 2) . '/htdocs/tests/environment.php';
                }
            }
        });
    }
}
