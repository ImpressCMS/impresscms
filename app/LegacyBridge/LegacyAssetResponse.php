<?php

declare(strict_types=1);

namespace App\LegacyBridge;

use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * A static file under the legacy root, with ETag and Last-Modified for
 * conditional requests but, like a web server serving the file, without an
 * automatic Cache-Control header.
 */
final class LegacyAssetResponse extends BinaryFileResponse
{
    use SendsHeadersVerbatim;
}
