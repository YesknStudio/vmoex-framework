<?php

/**
 * This file is part of project yeskn-studio/vmoex-framework.
 *
 * Author: Jake
 * Create: 2018-09-16 10:51:20
 */

namespace Yeskn\Support\Http;

class ApiFail extends ApiResponse
{
    public function __construct($message = '操作失败', $detail = null)
    {
        parent::__construct(0, $detail, $message);
    }
}
