<?php

declare(strict_types=1);

namespace App\Security;

use App\Legacy\LegacyRuntime;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

/**
 * The user logged in to ImpressCMS is the authenticated user (design D11).
 *
 * Runs on every request of the (lazy) firewall that needs the
 * user: boots legacy up to the module stage and wraps icms::$user. Login,
 * logout, autologin and session expiry stay in ImpressCMS, so they take effect
 * for Symfony routes on the next request. Anonymous visitors stay
 * unauthenticated; when a route denies them, they are sent to the ImpressCMS
 * login page with the original URL as return target.
 */
final class IcmsSessionAuthenticator extends AbstractAuthenticator implements AuthenticationEntryPointInterface
{
    public function __construct(
        private readonly LegacyRuntime $runtime,
        private readonly TokenStorageInterface $tokenStorage,
    ) {
    }

    /**
     * Lazy (null): authenticate only when something asks for the user, so that
     * legacy pages and routes without access rules never boot ImpressCMS here
     * (a legacy page must boot it itself, interactively). Then always: the
     * ImpressCMS session decides, not a token kept by Symfony.
     */
    public function supports(Request $request): ?bool
    {
        return null;
    }

    public function authenticate(Request $request): Passport
    {
        $this->runtime->bootTo('module');
        $user = \icms::$user;
        if (!is_object($user)) {
            throw new CustomUserMessageAuthenticationException('Not logged in to ImpressCMS.');
        }

        $icmsUser = IcmsUser::fromLegacy($user);

        return new SelfValidatingPassport(new UserBadge($icmsUser->getUserIdentifier(), static fn (): IcmsUser => $icmsUser));
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        // anonymous (never logged in, logged out or expired in ImpressCMS): carry on
        // unauthenticated, also if a token was restored from the session
        $this->tokenStorage->setToken(null);

        return null;
    }

    public function start(Request $request, ?AuthenticationException $authException = null): Response
    {
        return new RedirectResponse($request->getBasePath() . '/user.php?xoops_redirect=' . rawurlencode($request->getRequestUri()));
    }
}
