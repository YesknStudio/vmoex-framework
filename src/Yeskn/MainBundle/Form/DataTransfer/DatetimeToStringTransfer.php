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
    private $withTime;

    public function __construct($withTime)
    {
        $this->withTime = $withTime;
    }

    /**
     * 把格式化类型转成模板类型
     *
     * @param \DateTime $value
     *
     * @return string
     */
    public function transform($value)
    {
        return $value->format($this->withTime
            ? 'Y-m-d H:i:s'
            : 'Y-m-d'
        );
    }

    /**
     * 把显示类型转成格式化类型
     *
     * @param string $value
     * @return \DateTime
     */
    public function reverseTransform($value)
    {
        return new \DateTime($value);
    }
}
