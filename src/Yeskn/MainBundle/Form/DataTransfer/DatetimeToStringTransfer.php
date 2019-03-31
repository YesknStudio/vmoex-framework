<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-15 19:29:30
 */

namespace Yeskn\MainBundle\Form\DataTransfer;

use Symfony\Component\Form\DataTransformerInterface;

class DatetimeToStringTransfer implements DataTransformerInterface
{
    /**
     * @param mixed $value
     *
     * @return string
     */
    public function transform($value)
    {
        if (is_string($value)) {
            return new \DateTime($value);
        }

        return $value;
    }

    /**
     * @param mixed $value
     * @return \DateTime
     */
    public function reverseTransform($value)
    {
        if (is_object($value)) {
            return $value;
        }

        return new \DateTime($value);
    }
}
