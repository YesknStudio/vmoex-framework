<?php

/**
 * This file is part of project wpcraft.
 *
 * Author: Jake
 * Create: 2018-09-15 19:29:30
 */

namespace Yeskn\MainBundle\Form\DataTransfer;

use Symfony\Component\Form\DataTransformerInterface;

class DatetimeToStringTransfer implements DataTransformerInterface
{
    /**
     * @param \DateTime|null $value
     *
     * @return string
     */
    public function transform($value)
    {
        if (is_string($value)) {
            return new \DateTime('Y-m-d');
        }

        return $value;
    }

    /**
     * @param mixed $value
     * @return \DateTime|mixed
     */
    public function reverseTransform($value)
    {
        if (is_object($value)) {
            return $value;
        }

        return new \DateTime($value);
    }
}