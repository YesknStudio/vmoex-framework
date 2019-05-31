<?php

/**
 * This file is part of project vmoex-framework.
 *
 * Author: Jake
 * Create: 2019-05-31 14:31:55
 */

namespace Yeskn\Support\Traits;

trait OptionsTrait
{
    /**
     * @param $key
     * @return mixed
     * @throws \LogicException
     */
    public function getOption($key)
    {
        $one = $this->getDoctrine()->getRepository('YesknMainBundle:Options')->findOneBy(['name' => $key]);
        return $one ? $one->getValue() : '';
    }
}
