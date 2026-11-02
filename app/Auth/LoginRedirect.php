<?php

declare(strict_types=1);

namespace App\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Anonymous visitors who are denied go to the ImpressCMS login page, with
 * the original URL as return target (design L11).
 *
 * url() is what `redirectGuestsTo()` hands Laravel's `auth` middleware,
 * which computes a target even when the response comes from elsewhere.
 * for() builds the response itself: Laravel's own guest redirect stores
 * `url.intended` in the session, here ImpressCMS' $_SESSION.
 */
final class LoginRedirect
{
    public static function url(Request $request): string
    {
        return "{$request->getBasePath()}/user.php?xoops_redirect=" . rawurlencode($request->getRequestUri());
    }

    public static function for(Request $request): RedirectResponse
    {
        return new RedirectResponse(self::url($request));
    }
}
