<?php

/**
 * This file is part of project vmoex-framework.
 *
 * Author: Jake
 * Create: 2019-03-26 21:45:14
 */

namespace Yeskn\Support;

use Symfony\Component\HttpFoundation\ParameterBag as HttpParameterBag;

class ParameterBag extends HttpParameterBag
{
    public function __get($name)
    {
        return $this->get($name);
    }

    public function __set($name, $value)
    {
        $this->set($name, $value);
    }
}
