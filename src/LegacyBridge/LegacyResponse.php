<?php

declare(strict_types=1);

namespace App\LegacyBridge;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * The response a legacy script produced: its output, status code and headers,
 * sent unchanged.
 */
final class LegacyResponse extends Response
{
    use SendsHeadersVerbatim;

    /**
     * @param list<string> $headerLines headers_list() of the script
     */
    public static function fromLegacyOutput(string $content, int $status, array $headerLines): self
    {
        $response = new self($content, $status);
        foreach ($headerLines as $line) {
            [$name, $value] = array_pad(explode(':', $line, 2), 2, '');
            $name = trim($name);
            if ($name === '') {
                continue;
            }
            if (strcasecmp($name, 'Set-Cookie') === 0) {
                $response->headers->setCookie(Cookie::fromString(trim($value)));
            } else {
                $response->headers->set($name, trim($value), false);
            }
        }
        $response->preserveHeaders(array_values($headerLines));

        return $response;
    }

    /**
     * The headers are final: unlike Response::prepare() no Content-Type, charset
     * or cache headers are added or rewritten.
     */
    public function prepare(Request $request): static
    {
        if ('HTTP/1.0' !== $request->server->get('SERVER_PROTOCOL')) {
            $this->setProtocolVersion('1.1');
        }
        if ($request->isMethod('HEAD')) {
            $this->setContent(null);
        }

        return $this;
    }
}
