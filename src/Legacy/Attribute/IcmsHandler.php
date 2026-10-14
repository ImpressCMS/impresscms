<?php

declare(strict_types=1);

namespace App\Legacy\Attribute;

use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * Injects an ImpressCMS handler into a controller argument or an autowired
 * service constructor:
 *
 *     public function __invoke(#[IcmsHandler('icms_member')] \Icms\Member\Handler $members)
 *
 * The name is anything App\Legacy\HandlerLocator accepts. Being an Autowire
 * attribute, it also keeps the container compiler from loading the legacy
 * class of the parameter type (legacy class files exit unless ImpressCMS
 * constants are defined); type other legacy arguments with
 * #[Autowire(service: 'icms.security')] and the like for the same reason.
 */
#[\Attribute(\Attribute::TARGET_PARAMETER)]
final class IcmsHandler extends Autowire
{
    public function __construct(public readonly string $name)
    {
        parent::__construct(expression: sprintf("service('icms.handlers').get('%s')", addcslashes($name, "'\\")));
    }
}
