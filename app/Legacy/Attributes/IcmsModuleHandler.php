<?php

declare(strict_types=1);

namespace App\Legacy\Attributes;

use App\Legacy\ModuleHandlerLocator;
use Attribute;
use Illuminate\Contracts\Container\Container;
use Illuminate\Contracts\Container\ContextualAttribute;

/**
 * Injects a module's handler, the instance icms_getModuleHandler() gives
 * legacy code (design L34):
 *
 *     public function __invoke(#[IcmsModuleHandler('system', 'mimetype')] Handler $mimetypes)
 */
#[Attribute(Attribute::TARGET_PARAMETER)]
final class IcmsModuleHandler implements ContextualAttribute
{
    public function __construct(
        public readonly string $dirname,
        public readonly string $name,
    ) {
    }

    public static function resolve(self $attribute, Container $container): object
    {
        return $container->make(ModuleHandlerLocator::class)->get($attribute->dirname, $attribute->name);
    }
}
