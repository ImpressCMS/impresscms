<?php

declare(strict_types=1);

namespace App\Legacy\Attributes;

use Attribute;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Container\ContextualAttribute;

/**
 * Injects an ImpressCMS handler, the instance icms::handler() returns:
 *
 *     public function __invoke(#[IcmsHandler('icms_member')] Handler $members)
 *
 * A Laravel contextual attribute (design L8). The container resolves it
 * before it looks at the parameter's class, so the legacy class file, which
 * exits unless ImpressCMS is booted, is not loaded early.
 */
#[Attribute(Attribute::TARGET_PARAMETER)]
final class IcmsHandler implements ContextualAttribute
{
    public function __construct(public readonly string $name)
    {
    }

    public static function resolve(self $attribute, Container $container): object
    {
        return $container->make('icms.handlers')->get($attribute->name);
    }
}
