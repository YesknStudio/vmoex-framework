<?php

/**
 * This file is part of project vmoex-framework.
 *
 * Author: Jake
 * Create: 2019-04-01 20:54:09
 */

namespace Yeskn\MainBundle\Form\DataTransfer;

use Symfony\Component\Form\DataTransformerInterface;

class DatetimeRangeToStringTransformer implements DataTransformerInterface
{
    public function transform($value)
    {
        return implode(' - ', $value ?: []);
    }

    public function reverseTransform($value)
    {
        return $value ? explode(' - ', $value) : null;
    }
}
