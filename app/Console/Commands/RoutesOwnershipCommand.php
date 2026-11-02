<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Routing\RouteOwnership;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

/**
 * Shows which application owns a URL: a Laravel route, a reserved prefix, a
 * 405, or a legacy script and how the bridge resolves it.
 */
final class RoutesOwnershipCommand extends Command
{
    protected $signature = 'icms:routes:ownership
        {url : path with query, for example "/modules/system/admin.php?fct=users"}
        {--method=GET : HTTP method}';

    protected $description = 'Show whether Laravel or legacy ImpressCMS handles a URL';

    public function handle(RouteOwnership $ownership): int
    {
        $request = Request::create((string) $this->argument('url'), strtoupper((string) $this->option('method')));
        $result = $ownership->describe($request);

        $this->table(['Owner', 'Route', 'Detail'], [[$result['owner'], $result['route'] ?? '-', $result['detail']]]);

        return self::SUCCESS;
    }
}
