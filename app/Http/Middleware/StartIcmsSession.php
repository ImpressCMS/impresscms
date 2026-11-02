<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Replaces Laravel's StartSession in the `icms` group (design L10): starts
 * the ImpressCMS session through the `icms` store and puts it on the
 * request. It sets no cookie of its own, because PHP sends ImpressCMS'
 * session cookie, and saves nothing itself, because PHP writes the session at
 * shutdown through the ImpressCMS handler.
 */
final class StartIcmsSession
{
    public function __construct(private readonly Session $session)
    {
    }

    /**
     * @param Closure(Request): Response $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $this->session->start();
        $request->setLaravelSession($this->session);

        $response = $next($request);

        if ($request->isMethod('GET') && ! $request->ajax() && ! $request->prefetch()) {
            $this->session->setPreviousUrl($request->fullUrl());
        }

        $this->session->save();

        return $response;
    }
}
