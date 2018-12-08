<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-10-13 10:07:07
 */

namespace Yeskn\Support;

class Validator
{
    public static function isEmail($string)
    {
        return filter_var($string, FILTER_VALIDATE_EMAIL);
    }
}
