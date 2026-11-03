<?php

declare(strict_types=1);

namespace App\Admin;

use App\LegacyBridge\LegacyResponse;
use Closure;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Runs legacy rendering code inside a Laravel request and turns what it
 * produced into a Response, as the legacy bridge does for whole scripts: the
 * output it echoed, the status code and the header lines it sent with
 * header() and setcookie() (design L29).
 *
 * The header lines come from headers_list() after the code ran, so they follow
 * PHP's replace semantics as on the legacy page (a later header('Cache-Control:
 * …') replaces an earlier one; Set-Cookie lines add up). They are then removed
 * from PHP and sent verbatim by the response, so each goes out once. Buffers
 * the code left open are drained and the buffer level is restored, also when
 * it throws.
 */
final class LegacyOutput
{
    /**
     * @param Closure(): void $render
     */
    public function capture(Closure $render): Response
    {
        $level = ob_get_level();
        ob_start();

        try {
            $render();
            while (ob_get_level() > $level + 1) {
                ob_end_flush();
            }

            $content = ob_get_level() > $level ? (string) ob_get_clean() : '';
        } catch (Throwable $e) {
            while (ob_get_level() > $level) {
                ob_end_clean();
            }

            throw $e;
        }

        $status = http_response_code();
        $response = LegacyResponse::fromLegacyOutput($content, is_int($status) && $status >= 100 ? $status : 200, headers_list());
        if (! headers_sent()) {
            header_remove();
            http_response_code(200);
        }

        return $response;
    }
}
