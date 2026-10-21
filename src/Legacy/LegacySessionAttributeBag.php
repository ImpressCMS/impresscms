<?php

declare(strict_types=1);

namespace App\Legacy;

use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;

/**
 * Session attributes that are the ImpressCMS session itself: Symfony's
 * $session->get('xoopsUserId') reads $_SESSION['xoopsUserId'], and what Symfony
 * sets is visible to legacy code under the same key. (Symfony's own bag keeps
 * its attributes in a separate `_sf2_attributes` entry.)
 */
final class LegacySessionAttributeBag extends AttributeBag
{
    /**
     * @param array<string, mixed> $attributes the bag's own entry, not used
     */
    public function initialize(array &$attributes): void
    {
        if (!isset($_SESSION)) {
            $_SESSION = [];
        }
        $this->attributes = &$_SESSION;
    }
}
