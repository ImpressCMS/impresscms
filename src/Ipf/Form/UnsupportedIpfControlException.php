<?php

declare(strict_types=1);

namespace App\Ipf\Form;

/**
 * An IPF variable uses a data type or control that metadata-driven forms do
 * not support (yet). Such entities keep their legacy pages.
 */
final class UnsupportedIpfControlException extends \LogicException
{
    public function __construct(public readonly string $variable, public readonly string $control)
    {
        parent::__construct(sprintf('The IPF variable "%s" uses the control "%s", which metadata-driven forms do not support.', $variable, $control));
    }
}
