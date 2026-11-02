<?php

declare(strict_types=1);

namespace App\Testing;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

/**
 * Listeners that exist only in the `test` environment; registered by
 * HostServiceProvider.
 */
final class TestServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        Event::listen('icms.icms.loadService-db', [LoadServiceDbRecorder::class, 'handle']);
    }
}
