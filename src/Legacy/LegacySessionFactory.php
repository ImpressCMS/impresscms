<?php

declare(strict_types=1);

namespace App\Legacy;

use Symfony\Component\DependencyInjection\Attribute\AsDecorator;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionFactoryInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpFoundation\Session\Storage\SessionStorageFactoryInterface;

/**
 * ImpressCMS owns the session (design D10): before Symfony touches it, the
 * legacy services boot, so Icms\Core\Session sets the session name, cookie
 * parameters and database save handler and starts the session. Symfony's
 * php_bridge storage then works on that very session, with attributes that
 * are the $_SESSION entries legacy code uses (LegacySessionAttributeBag).
 */
#[AsDecorator('session.factory')]
final class LegacySessionFactory implements SessionFactoryInterface
{
    public function __construct(
        private readonly LegacyRuntime $runtime,
        private readonly RequestStack $requestStack,
        #[Autowire(service: 'session.storage.factory')]
        private readonly SessionStorageFactoryInterface $storageFactory,
        #[Autowire(service: 'session_listener')]
        private readonly ?object $sessionListener = null,
    ) {
    }

    public function createSession(): SessionInterface
    {
        $this->runtime->bootTo('services');

        $usageReporter = $this->sessionListener !== null && method_exists($this->sessionListener, 'onSessionUsage')
            ? $this->sessionListener->onSessionUsage(...)
            : null;

        return new Session(
            $this->storageFactory->createStorage($this->requestStack->getMainRequest()),
            new LegacySessionAttributeBag(),
            null,
            $usageReporter,
        );
    }
}
