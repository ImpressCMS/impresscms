<?php

declare(strict_types=1);

namespace App\Legacy\Attribute;

use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * Injects the handler of a module class into a controller argument or an
 * autowired service constructor (App\Legacy\ModuleHandlerLocator):
 *
 *     public function __invoke(#[IcmsModuleHandler('system', 'mimetype')] \Icms\Ipf\Handler $mimetypes)
 *
 * It is the instance icms_getModuleHandler('mimetype', 'system') returns in
 * the same request. Controller arguments are resolved after the kernel.controller
 * listeners, so after the admin context (#[SystemAdminFunction]) has booted
 * ImpressCMS. Like #[IcmsHandler], it keeps the container compiler from loading
 * the legacy class of the parameter type.
 */
#[\Attribute(\Attribute::TARGET_PARAMETER)]
final class IcmsModuleHandler extends Autowire
{
    public function __construct(public readonly string $dirname, public readonly string $name)
    {
        parent::__construct(expression: sprintf(
            "service('icms.module_handlers').get('%s', '%s')",
            addcslashes($dirname, "'\\"),
            addcslashes($name, "'\\"),
        ));
    }
}
