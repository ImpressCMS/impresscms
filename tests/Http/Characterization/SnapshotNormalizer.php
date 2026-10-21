<?php

declare(strict_types=1);

namespace App\Tests\Http\Characterization;

use App\Tests\Support\TestSite;
use Symfony\Component\BrowserKit\Response;

/**
 * Turns an HTTP response into a stable text transcript: status, selected
 * headers and the body, with per-request noise masked (host and port,
 * session ids, security tokens, timestamps, ETags).
 */
final class SnapshotNormalizer
{
    /** Headers whose values are part of the snapshot. */
    private const HEADERS = ['content-type', 'location', 'cache-control', 'pragma', 'x-frame-options'];

    /**
     * @param bool $stripTokens remove the ImpressCMS security token field
     *                          (XOOPS_TOKEN_REQUEST) from the body, to compare a
     *                          form with a legacy form that had none
     */
    public function __construct(
        private readonly string $baseUri,
        private readonly bool $stripTokens = false,
    ) {
    }

    public function transcript(string $request, Response $response, bool $hashBody = false): string
    {
        $lines = [$request, 'status: ' . $response->getStatusCode()];

        $headers = array_change_key_case($response->getHeaders(), CASE_LOWER);
        foreach (self::HEADERS as $name) {
            foreach ((array) ($headers[$name] ?? []) as $value) {
                $lines[] = $name . ': ' . $this->text((string) $value);
            }
        }
        foreach ((array) ($headers['set-cookie'] ?? []) as $cookie) {
            $lines[] = 'set-cookie: ' . $this->cookie((string) $cookie);
        }

        $body = $response->getContent();
        if ($this->stripTokens) {
            $body = (string) preg_replace('#[ \t]*<input type=(["\'])hidden\1 name=\1XOOPS_TOKEN_REQUEST\1[^>]*>\R?#', '', $body);
        }
        $lines[] = '';
        $lines[] = $hashBody
            ? 'sha1: ' . sha1($body) . ' (' . strlen($body) . ' bytes)'
            : $this->text($body);

        return implode("\n", $lines) . "\n";
    }

    /**
     * Cookie name and attributes; the value is masked, attribute order and
     * case are normalised, and expiry dates are reduced to "set".
     */
    private function cookie(string $header): string
    {
        $parts = array_map('trim', explode(';', $header));
        [$name] = explode('=', (string) array_shift($parts), 2);

        $attributes = [];
        foreach ($parts as $part) {
            if ($part === '') {
                continue;
            }
            [$key, $value] = array_pad(explode('=', $part, 2), 2, null);
            $key = strtolower($key);
            $attributes[$key] = match ($key) {
                'expires', 'max-age' => $key,
                'domain' => 'domain=' . $this->text((string) $value),
                default => $value === null ? $key : $key . '=' . $value,
            };
        }
        ksort($attributes);

        return $name . '=*; ' . implode('; ', $attributes);
    }

    private function text(string $text): string
    {
        $host = (string) parse_url($this->baseUri, PHP_URL_HOST);
        $port = (string) parse_url($this->baseUri, PHP_URL_PORT);

        $rules = [
            // the test server listens on a random port
            '#' . preg_quote($host . ':' . $port, '#') . '#' => '{HOST}',
            '#(?<![\w.])' . preg_quote($host, '#') . '(?![\w.:])#' => '{HOSTNAME}',
            // session ids
            '#\b(ICMSSESSION|icms_session|PHPSESSID)=[A-Za-z0-9,-]+#' => '$1={SESSION}',
            // security tokens and other md5/sha1 values
            '#\b[0-9a-f]{40}\b#' => '{SHA1}',
            '#\b[0-9a-f]{32}\b#' => '{MD5}',
            // unix timestamps (2001..2286), also as cache busters
            '#\b1[0-9]{9}\b#' => '{TIMESTAMP}',
            // dates and times as rendered by ImpressCMS
            '#\b\d{4}[/-]\d{1,2}[/-]\d{1,2}\b#' => '{DATE}',
            '#\b\d{1,2}[/-]\d{1,2}[/-]\d{4}\b#' => '{DATE}',
            '#\b\d{1,2}:\d{2}(:\d{2})?\b#' => '{TIME}',
            '#\b(Mon|Tue|Wed|Thu|Fri|Sat|Sun), \d{2} \w{3} \d{4}\b#' => '{HTTPDATE}',
            // query and page generation statistics
            '#\b\d+\.\d+ (seconds|secs?)\b#' => '{DURATION}',
        ];

        // file system paths (legacy error pages name files) differ per checkout and OS
        $root = TestSite::repoRoot();
        $text = str_replace([$root, str_replace('/', '\\', $root)], '{ROOT}', $text);
        $text = (string) preg_replace_callback(
            '#\{ROOT\}[\\\\/][\w.\\\\/-]*#',
            static fn (array $m): string => str_replace('\\', '/', $m[0]),
            $text,
        );

        $text = (string) preg_replace(array_keys($rules), array_values($rules), $text);

        return rtrim(str_replace("\r\n", "\n", $text));
    }
}
