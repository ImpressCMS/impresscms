<?php

declare(strict_types=1);

namespace App\Ipf\Form;

use LogicException;

/**
 * An IPF variable uses a data type or control that metadata-driven forms do
 * not support (yet). Such entities keep their legacy pages.
 */
final class UnsupportedIpfControlException extends LogicException
{
    public function __construct(
        public readonly string $variable,
        public readonly string $control,
    ) {
        parent::__construct("The IPF variable \"{$variable}\" uses the control \"{$control}\", which metadata-driven forms do not support.");
    }
}
