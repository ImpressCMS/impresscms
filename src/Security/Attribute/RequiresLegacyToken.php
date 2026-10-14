<?php

declare(strict_types=1);

namespace App\Security\Attribute;

/**
 * POST requests to this controller must carry a valid ImpressCMS security
 * token (icms::$security->check(), field XOOPS_TOKEN_REQUEST), as issued by
 * icms::$security->getTokenHTML() on a legacy or Symfony page. A token is
 * accepted once.
 */
#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::TARGET_METHOD)]
final class RequiresLegacyToken
{
}
