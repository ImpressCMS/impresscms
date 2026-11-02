<?php

declare(strict_types=1);

namespace App\LegacyBridge;

/**
 * Sends the headers of a legacy response exactly as legacy code produced
 * them. HttpFoundation, which Laravel's responses build on, would normalise
 * them: re-order Cache-Control directives, add must-revalidate next to
 * Expires, reformat cookies, and always add Cache-Control and Date.
 *
 * preserveHeaders() records the header bag as the baseline. At send time
 * headers that are still unchanged go out as the original raw lines; headers
 * that the host added or changed afterwards go out the HttpFoundation way.
 *
 * @mixin \Symfony\Component\HttpFoundation\Response
 */
trait SendsHeadersVerbatim
{
    /** @var list<string> */
    private array $verbatimLines = [];

    /** @var array<string, list<string|null>> lower-case name => values at baseline */
    private array $baselineHeaders = [];

    /** @var array<string, true> lower-case names that have raw lines */
    private array $verbatimNames = [];

    /** @var list<string> */
    private array $baselineCookies = [];

    /**
     * @param list<string> $rawLines header lines as legacy code sent them ("Name: value")
     */
    public function preserveHeaders(array $rawLines = []): void
    {
        $this->verbatimLines = $rawLines;
        $this->verbatimNames = [];
        foreach ($rawLines as $line) {
            $this->verbatimNames[self::headerName($line)] = true;
        }

        $this->baselineHeaders = array_change_key_case($this->headers->allPreserveCaseWithoutCookies(), CASE_LOWER);
        $this->baselineCookies = array_map('strval', $this->headers->getCookies());
    }

    public function sendHeaders(?int $statusCode = null): static
    {
        if (headers_sent()) {
            return $this;
        }

        $statusCode ??= $this->statusCode;
        $current = $this->headers->allPreserveCaseWithoutCookies();
        $currentByKey = array_change_key_case($current, CASE_LOWER);
        $unchanged = fn (string $key): bool => array_key_exists($key, $this->baselineHeaders)
            && ($currentByKey[$key] ?? null) === $this->baselineHeaders[$key];

        foreach ($current as $name => $values) {
            $key = strtolower($name);
            if ($unchanged($key) && (isset($this->verbatimNames[$key]) || $key === 'cache-control' || $key === 'date')) {
                continue;
            }

            $replace = strcasecmp($name, 'Content-Type') === 0;
            foreach ($values as $value) {
                header("{$name}: {$value}", $replace, $statusCode);
            }
        }

        foreach ($this->verbatimLines as $line) {
            $key = self::headerName($line);
            if ($key === 'set-cookie' || $unchanged($key)) {
                header($line, false, $statusCode);
            }
        }

        foreach ($this->headers->getCookies() as $cookie) {
            if (! in_array((string) $cookie, $this->baselineCookies, true)) {
                header("Set-Cookie: {$cookie}", false, $statusCode);
            }
        }

        header("HTTP/{$this->version} {$statusCode} {$this->statusText}", true, $statusCode);

        return $this;
    }

    private static function headerName(string $line): string
    {
        return strtolower(trim((string) strstr("{$line}:", ':', true)));
    }
}
